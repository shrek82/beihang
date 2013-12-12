<div id="admin950">

   <?php if (count($all_album) > 0): ?>
        <?php foreach ($all_album as $b): ?>
            <a href="<?= URL::site('classroom_admin/album?id=' . $_CLASSROOM['id'] . '&album_id=' . $b['id']) ?>" title="点击修改"><?= $b['name'] ?></a>(<?=$b['pic_num']?$b['pic_num']:'0';?>张)&nbsp;&nbsp;<span style="color:#ccc">|</span>&nbsp;&nbsp;
        <?php endforeach; ?>
    <?php else: ?>
        <p class="nodata">暂时还没有创建任何相册!</p>
    <?php endif; ?>
    <br>
    <br>

    <?php if ($album): ?>
        <form id="album_form" method="post" action="<?= URL::site('classroom_admin/album?id=' . $_CLASSROOM['id'] . '&album_id=' . $album['id']) ?>">
            相册名称：<br><input type="text" name="name" value="<?= $album['name'] ?>" size="40" class="input_text" /><br>
            <br><input type="submit" value="保存修改"  class="button_blue" /><input type="button"  value="删除"  class="button_gray" onclick="window.location.href='<?= URL::site('classroom_admin/album?id=' . $_CLASSROOM['id'] . '&album_id=' . $album['id'] . '&del=yes') ?>'" />
        </form>
    <?php else: ?>
        <form id="album_form" method="post" action="<?= URL::site('classroom_admin/album?id=' . $_CLASSROOM['id']) ?>">
            相册名称：<br><input type="text" name="name" value="" size="50" class="input_text" /><br>
            <br><input type="submit"  value="创建"  class="button_blue" />
        </form>
    <?php endif; ?>

</div>