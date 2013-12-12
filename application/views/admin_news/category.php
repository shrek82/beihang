<!-- admin_news/category:_body -->
<style type="text/css">
    #news_category_form{ padding: 10px; background: #fcfcfc; border: 1px solid #eee; }
</style>

<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" style="margin-top:9px " >
    <tr >
        <td height="29" class="td_title" colspan="4" >分类管理</td>
    </tr>
    <tr class="td_title">
        <td width="5%" style="text-align:center">id</td>
        <td width="50%" >分类名称</td>
        <td width="7%" style="text-align:center">新闻总数</td>
        <td width="7%"  style="text-align:center">删除</td>
    </tr>
    <?php if (count($category) > 0): ?>
        <?php foreach ($category as $c): ?>
            <tr id="c_<?= $c['id'] ?>">
                <td style="text-align:center"><?= $c['id'] ?></td>
                <td height="25" style="padding:0px 10px" >
                    <a href="javascript:getCategoryForm(<?= $c['id'] ?>)" title="点击修改"><?= $c['name'] ?></a>
                </td>
                <td style="text-align:center"><?= $c['news_count'] ?></td>
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

<table class="admin_table" border="0" cellpadding="0" cellspacing="1" width="100%">
    <tr>

        <td>    <div id="get_category_form"></div>
            <script type="text/javascript">
                var category = new ajaxForm('news_category_form',{
                    redirect: '<?= $_URL ?>'
                });
                function getCategoryForm(id){
                    if(!is_defined(id)){
                        id=0;
                    }
                    $('#get_category_form').load('<?= URL::site('admin_news/getCategoryForm?category_id=') ?>'+id);
                }
                getCategoryForm();
            </script></td>
    </tr>
</table>

<script type="text/javascript">
    function del(cid){
        new Facebox({
            title: '删除确认！',
            message: '确定要删除此分类吗？<br>注意删除分类将同时删除分类下所有新闻内容！',
            icon:'question',
            ok: function(){
                new Request({
                    url: '<?= URL::site('admin_news/delcategory?cid=') ?>'+cid,
                    type: 'post',
                    success: function(){
                        candyDel('c_'+cid);
                    }
                }).send();
            }
        }).show();
    }

    function category_send(){
        new ajaxForm('news_category_form',{textSending: '发送中',textError: '重试',textSuccess: '修改成功',callback:function(id){
                window.location.reload();
            }}).send();
    }
</script>