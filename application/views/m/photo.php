<!-- m/photo:_body -->

<?php if($img_path):?>
<img src="<?=$img_path?>">
<?php endif;?>
<form method="POST" enctype="multipart/form-data" action="">
名称：<input type="text" name="name" id=""><br>
选择照片：<input type="file" name="file" value="" /><br><input type="submit" value="上传" />
</form>