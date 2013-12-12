<!-- club_home/album:_body -->

<style type="text/css">
    .album_box{ margin:10px 0 0 10px; float:left; text-align:center;width:150px;height:140px;overflow:hidden }
    .album_box .photo{ height:100px;background: url(/static/images/user/album_bg.gif) no-repeat right bottom; padding:4px 6px 10px 6px;border-top:1px solid #E8E8E8; border-left:1px solid #E8E8E8;margin-bottom:5px;}
</style>

<div id="main_left">
    <div style="padding: 5px 20px">
        <form action="/club_home/album?id=<?=$_ID?>" style="text-align: right" method="POST">
            <input type="text" name="q" value="<?= $q ?>" class="input_text" size="20"/>
            <input type="submit" value="搜索" class="button_blue" />
        </form>
    </div>

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
                        <a href="<?= $url ?>"><img  src="<?= $img_path ?>" style="height:100px"/></a>
                    </div>
                    <a href="<?= $url ?>" title="<?= $ab['name'] ?>"><?= Text::limit_chars($ab['name'], 7, '..') ?></a><span style="color:#999">(<?= $ab['pic_num'] ? $ab['pic_num'] : 0; ?>张)</span>
                </div>
            <?php endforeach; ?>
            <div class="clear"></div>
        <?php endif; ?>
    </div>

    <div><?= $pager ?></div>

</div>
<div id="main_right">
    <p class="column_tt">相册分类</p>
    <div class="aa_block">
        <ul class="aa_conlist">

            <li><a href="/club_home/album?id=<?= $_ID ?>&list=new" <?= $list == 'new' ? 'class="cur"' : ''; ?>>最近上传</a></li>
            <li><a href="/club_home/album?id=<?= $_ID ?>&list=event" <?= $list == 'event' ? 'class="cur"' : ''; ?>>活动相册</a></li>
            <li><a href="/club_home/album?id=<?= $_ID ?>&list=all" <?= $list == 'all' ? 'class="cur"' : ''; ?>>所有相册</a></li>
        </ul>
    </div>
</div>
