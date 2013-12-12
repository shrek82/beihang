<!-- user_album/index:_body -->
<div id="big_right">
    <div id="plugin_title">相册</div>

    <?php if ($_MASTER): ?>
        <form action="<?= $_URL ?>" method="post" id="album_form">
            <input type="text" name="name" value="" placeholder="新相册名称" class="input_text" size="40" />
            <input type="button"  id="submit_button" onclick="save()" value="创建" class="input_button" />
        </form>

        <script type="text/javascript">
            function save(){
                new ajaxForm('album_form',{textSending: '发送中',textError: '重试',textSuccess: '发送成功',callback:function(id){
                        window.location.reload();
                    }}).send();
            }
        </script>

    <?php endif; ?>


    <style type="text/css">
        .album_box{ margin:10px 0 0 10px; float:left; text-align:center;width:150px;height:140px;overflow:hidden }
        .album_box .photo{ height:100px;background: url(/static/images/user/album_bg.gif) no-repeat right bottom; padding:4px 6px 10px 6px;border-top:1px solid #E8E8E8; border-left:1px solid #E8E8E8;margin-bottom:5px;}
    </style>


    <div class="album_index" >
        <?php if (!$albums): ?>
            <div class="nodata">暂时还没有相册。</div>
        <?php else: ?>
            <?php foreach ($albums as $ab): ?>
                <div class="album_box" >
                    <div class="photo" >
                        <?php
                        $img_path = $ab['img_path'] ? '/' . $ab['img_path'] : '/static/images/default_album.gif';
                        $url = URL::site('album/picIndex') . URL::query(array('id' => $ab['id'], 'wpg' => true));
                        ?>
                        <a href="<?= $url ?>"><img  src="<?= $img_path ?>" style="height:90px;border-width: 0"/></a>
                    </div>
                    <a href="<?= $url ?>" title="<?= $ab['name'] ?>"><?= Text::limit_chars($ab['name'], 7, '..') ?></a><span style="color:#999">(<?= $ab['pic_num'] ? $ab['pic_num'] : 0; ?>张)</span>
                </div>
            <?php endforeach; ?>
            <div class="clear"></div>
        <?php endif; ?>
    </div>
</div>