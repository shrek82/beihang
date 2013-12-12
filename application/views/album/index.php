<style type="text/css">
        .album_box{ margin:10px 0 0 10px; float:left; text-align:center; width:165px; height:150px;overflow: hidden;border-width:0}
        .album_box .photo{background: url(<?= URL::base(); ?>static/images/user/album_bg.gif) no-repeat right bottom; padding:4px 6px 10px 6px;border-top:1px solid #E8E8E8; border-left:1px solid #E8E8E8;margin-bottom:5px;}
</style>

<div class="album_index" >
        <?php if (!$album): ?>
                <div class="nodata">暂时还没有相册。</div>
        <?php else: ?>
                <?php foreach ($album as $ab): ?>
                        <div class="album_box">
                                <div class="photo" >
                                        <?php
                                        $img = $ab['snapshot'] ? URL::base() . $ab['snapshot'] :
                                                URL::base() . 'static/images/default_album.gif';

                                        $url = URL::site('album/picIndex') .
                                                URL::query(array('id' => $ab['id'], 'wpg' => true));
                                        ?>
                                        <a href="<?= $url ?>">                         
                                                <img width="150" height="113" src="<?= $img ?>" border="0" />
                                        </a>
                                </div>
                                <a href="<?= $url ?>" title="<?= $ab['name'] ?>"><?= Text::limit_chars($ab['name'], 7, '..') ?></a><span style="color:#999">(<?= $ab['total_pics'] ?>张)</span>
                        </div>
                <?php endforeach; ?>
                <div class="clear"></div>
        <?php endif; ?>
</div>
