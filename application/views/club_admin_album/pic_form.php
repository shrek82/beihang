<div style="margin-top:20px">
<form id="pic_form" method="post"
      action="<?= URL::site('club_admin_album/picForm').
                  URL::query(array('pic_id' => $pic['id'])) ?>">
    名称：<input type="text" name="name" value="<?= $pic['name'] ?>" size="50"  class="input_text"/>
    <textarea name="intro" style="height:80px; width: 90%; margin-bottom: 5px" class="input_text"><?= $pic['intro'] ?></textarea><br>
    <input type="button" onclick="new ajaxForm('pic_form').send()" value="确认" class="button_blue" />
    <input type="button" onclick="del(<?=$pic['id']?>)" value="删除"  class="button_gray" />
</form>
</div>