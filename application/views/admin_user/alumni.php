<!-- admin_user/alumni:_body -->
<style type="text/css">
    #content_table td{ height: 24px}
    #content_table .field{ text-align: right; padding: 0px 4px; width: 80px}
</style>
<?php if ($err): ?>
    <div class="notice"><?= $err; ?></div>
<?php endif; ?>

    <form action="<?=URL::site('admin_user/alumniSub')?>" method="post" id="alumni_form">
        <table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" >
            <tr>
                <td height="29" class="td_title" colspan="6"><b>档案管理</b></td>
            </tr>
            <tr>
                <td class="field" style="width:150px">源数据：</td>
                <td>
                <?= $total_alumni ?>条
            </td>
        </tr>
        <tr>
            <td class="field">处理进度：</td>
            <td>本<span id="span_page"><?=$total_alumni?'1':'0';?></span>批，共<?=$total_page?>批 &nbsp;<span id="loading"></span></td>
        </tr>
        <tr>
            <td class="field">操作类型：</td>
            <td>
                <select name="action_type" id="action_type" onchange="document.getElementById('typeselect').value=this.value;">
                    <option value="update">更新档案</option>
                    <option value="add">添加档案</option>
                </select>
            </td>
        </tr>
        <tr>
            <td class="field">操作说明：</td>
            <td>1、导入操作，在完成所有导入后将自动删除源数据，以免重复导入。<br>
            2、更新操作，没有的原档案将自动添加该档案。<br>
            3、为不影响服务器，导入或更新将自动分次执行，在此过程中，请勿点击该页其他链接。</td>
        </tr>
        <tr>
            <td colspan="2" style="text-align:center;padding:10px">
<input type="hidden" name="total_alumni" value="<?=$total_alumni?>">
<input type="hidden" value="update" name="type" id="typeselect">
<input type="hidden" name="page" value="<?=$total_alumni?'1':'0';?>"  id="page" style="width:50px" >
<input type="hidden" name="total_page" value="<?=$total_page?>" style="width:50px">

                <input type="button" id="submit_button" value="确定" name="button" class="button_blue"  onclick="alumni()"  />
                <input type="button" value="取消" onclick="window.history.back()" class="button_gray">
            </td>
        </tr>
    </table>
</form>

<script type="text/javascript">
    function alumni(){
        $('#loading').html('<img src="/static/images/user/loading6.gif" />');
        document.getElementById('action_type').disabled='true';
        var alumni_form = new ajaxForm('alumni_form', {
            callback:function(page){
                if(page>0){
                    $('#page').attr('value', page);
                    $('#span_page').html(page);
                    setTimeout(function(){alumni();},2000);
                }
                else{
                    $('#loading').html('<img src="/static/images/li_ok.gif" />操作完成！');
                    document.getElementById('action_type').disabled='false';
                }
            }});
        alumni_form.send();
    }
</script>