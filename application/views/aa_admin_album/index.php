<!-- aa_admin_album/index:_body -->
<style type="text/css">
    .album_box{ margin:10px 0 0 10px; float:left; text-align:center;width:150px;height:140px;overflow:hidden }
    .album_box .photo{ height:100px;background: url(/static/images/user/album_bg.gif) no-repeat right bottom; padding:4px 6px 10px 6px;border-top:1px solid #E8E8E8; border-left:1px solid #E8E8E8;margin-bottom:5px;}
</style>
<div id="admin950">
    <div style="padding: 5px 20px">
        <form action="/aa_admin_album?id=<?= $_ID ?>" style="text-align: right" method="POST">
            <input type="text" name="q" value="<?= $q ?>" class="input_text" size="30"/>
            <input type="submit" value="搜索" class="button_blue" />
        </form>
    </div>

    <div class="album_index" >
        <?php if (!$albums): ?>
            <div class="nodata">暂时还没有相册。</div>
        <?php else: ?>
            <?php foreach ($albums as $ab): ?>
                <div class="album_box" id="album_<?= $ab['id'] ?>">
                    <div class="photo" >
                        <?php
                        $img_path = $ab['img_path'] ? '/' . $ab['img_path'] : '/static/images/default_album.gif';
                        $url = URL::site('album/picIndex') . URL::query(array('id' => $ab['id'], 'wpg' => true));
                        ?>
                        <a href="<?= $url ?>" title="浏览<?=$ab['name']?>" target="_blank"><img  src="<?= $img_path ?>" style="height:100px"/></a>
                    </div>
                    <a href="javascript:;" onclick="editAlbum(<?=$ab['id']?>)" title="点击修改" ><?=  Text::limit_chars($ab['name'],3,'...')?>编辑</a><span style="color:#999">(<?= $ab['pic_num'] ? $ab['pic_num'] : 0; ?>张)</span> <a href="javascript:del(<?= $ab['id'] ?>)" title="删除相册" style="color:#f00">×</a>
                </div>
            <?php endforeach; ?>
            <div class="clear"></div>
        <?php endif; ?>
    </div>

    <div><?= $pager ?></div>
</div>

<script type="text/javascript">
    function del(cid){
        new candyConfirm({
            message: '确定要删除此相册吗？注意删除后将不能再恢复。',
            url:'<?= URL::site('aa_admin_album/del?id=' . $_ID . '&album_id=') ?>'+cid,
            removeDom:'album_'+cid
        }).open();
    }

    function editAlbum(cid) {
        new Facebox({
            title: '编辑相册',
            width:'400px',
            url:'<?= URL::site('aa_admin_album/editForm?id=' . $_ID . '&album_id=') ?>'+cid,
            ok: function(){
                //提交表单
                new ajaxForm('alubm_form', {
                    callback:function(data){
                        //window.location.reload();
                    }
                }).send();
            }
        }).show();
    }
</script>