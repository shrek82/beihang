<div id="admin950">
    <style type="text/css">
        .album_box{ margin:10px 0 0 10px; float:left; text-align:center;width:150px;height:140px;overflow:hidden }
        .album_box .photo{ height:100px;background: url(/static/images/user/album_bg.gif) no-repeat right bottom; padding:4px 6px 10px 6px;border-top:1px solid #E8E8E8; border-left:1px solid #E8E8E8;margin-bottom:5px;}
    </style>

    <div class="album_index" >
        <?php if (!$albums): ?>
            <div class="nodata" style="height:200px">暂时还没有任何照片，<a href="javascript:;" onclick="createAlbum()">我来发布第一张照片！</a></div>
            <script type="text/javascript">
                function createAlbum(id){
                    new Request({
                        url: '/classroom_home/album?id=<?= $_ID ?>',
                        type: 'post',
                        data:'new_album=1',
                        success:function(aid){
                            window.location.href='/album/uploadPic?id='+aid+'&enc=<?= base64_encode(date('d')) ?>';
                        }
                    }).send();
                }
            </script>
        <?php else: ?>
            <?php foreach ($albums as $ab): ?>
                <div class="album_box" >
                    <div class="photo" >
                        <?php
                        $img_path = $ab['img_path'] ? '/' . $ab['img_path'] : '/static/images/default_album.gif';
                        $url = URL::site('album/picIndex') . URL::query(array('id' => $ab['id'], 'wpg' => true));
                        ?>
                        <a href="<?= $url ?>"><img  src="<?= $img_path ?>" style="height:100px"/></a>
                    </div>
                    <a href="<?= $url ?>" title="<?= $ab['name'] ?>"><?= Text::limit_chars($ab['name'], 7, '..') ?></a><span style="color:#999">(<?= $ab['pic_num'] ? $ab['pic_num'] : 0; ?>张)</span>
                </div>
            <?php endforeach; ?>
            <div class="clear"></div>
        <?php endif; ?>
    </div>
</div>
