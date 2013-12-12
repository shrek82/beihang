<p> <a href="<?=  Db_Event::getLink($event['id'],$event['aa_id'],$event['club_id'])?>" style="color:#c00;font-size: 14px"> <<  返回<?=$event['title']?></a></p>
<h2>设置活动参加方式或分类：</h2>


<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" style="margin-top:9px " >

    <tr class="td_title" style=" background: #eee; height:25px;">
        <td width="5%" style="text-align:center">序号</td>
        <td width="50%" >分类名称</td>
        <td width="10%" style="text-align:center">下载报名名单</td>
        <td width="20%"  style="text-align:center">删除</td>
    </tr>
    <?php if (count($categorys) > 0): ?>
        <?php foreach ($categorys as $c): ?>
            <tr id="c_<?= $c['id'] ?>">
                <td style="text-align:center"><?= $c['id'] ?></td>
                <td height="25" style="padding:0px 10px" >
                    <input type="text" class="input_text" style="width: 450px" value="<?= $c['name'] ?>" onblur="mchange(<?= $c['id'] ?>)" id="category_input_<?= $c['id'] ?>">
                </td>
                <td style="text-align:center; color: green"><?= $c['sign_num']?$c['sign_num']:'0';?>人</td>
                <td style="text-align:center"><a href="javascript:del(<?= $c['id'] ?>)">删除</a></td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td height="25" style="padding:0px 10px" colspan="4" >
                <span class="nodata">没有任何分类信息。</span>
            </td>
        </tr>
    <?php endif; ?>
</table>


<div style=" color: #666;margin:15px 15px" id="addnewsigncat">添加新分类：
    <form action="/event/signCategorys?event_id=<?= $event_id ?>" method="POST" id="signcategory_form">
        <input type="text" class="input_text" style="width: 450px" name="name">
        <input type="button" onclick="signFormSub(<?= $event_id ?>)" value="添加" class="button_blue" id="submit_button"><br>
    </form>
</div>



<script type="text/javascript">
    //添加新报名分类
    function signFormSub(event_id){
        new ajaxForm('signcategory_form',{textSending: '发送中',textError: '重试',textSuccess: '发送成功',callback:function(id){
                window.location.reload();
            }}).send();
    }

    function mchange(category_id){
        new Request({
            type: 'post',
            url: '<?= URL::site('event/signCategorys?event_id='.$event_id.'&category_id=') ?>'+category_id,
            data: 'name='+$('#category_input_'+category_id).val()
        }).send();
    }

    function del(category_id){
        new candyConfirm({
            message:'确定要删除该内容吗？',
            url:'<?= URL::site('event/signCategorys?event_id='.$event_id.'&del=1&category_id=') ?>'+category_id,
            removeDom:'c_'+category_id
        }).open();
    }
</script>
