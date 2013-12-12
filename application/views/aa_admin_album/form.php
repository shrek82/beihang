<form id="album_form" method="post" action="<?= URL::site('aa_admin_album/form').URL::query(array('album_id'=>$album['id'])) ?>">
<input type="text" name="name" value="<?= $album['name'] ?>" size="40" class="input_text"/>
<input type="button" id="submit_button" onclick="new ajaxForm('album_form', {callback:function(){location.reload()}}).send()" value="确认" class="button_blue" />
</form>