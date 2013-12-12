<!-- club_admin_album/category:_body -->
<div id="admin950" style="padding-bottom:150px">
        <?php if ($album): ?>
            <form id="album_form" method="post" action="<?= URL::site('club_admin_album/category?id=' . $_ID . '&album_id=' . $album['id']) ?>">
                相册名称：<input type="text" name="name" value="<?= $album['name'] ?>" size="40" class="input_text" />
                <input type="button" value="保存修改"  class="button_blue" onclick="new ajaxForm('album_form',{redirect:'/club_admin_album/club?id=<?=$_ID?>'}).send()"/>
            </form>
        <?php else: ?>
            <form id="album_form" method="post" action="<?= URL::site('club_admin_album/category?id=' . $_ID) ?>">
                相册名称：<input type="text" name="name" value="" size="40" class="input_text" /> 
                <input type="button"  value="确认"  class="button_blue"  onclick="new ajaxForm('album_form',{redirect:'/club_admin_album/club?id=<?=$_ID?>'}).send()"/>
            </form>
        <?php endif; ?>
</div>