<div style="padding:10px 20px;">

	<div style="font-size:14px;">
		<a href="<?=URL::site('album/picIndex?id='.$id)?>">&lt;&lt;返回相册</a>
	</div>
    <h2>批量编辑照片</h2>
    <form method="post" action="<?=URL::site('album/picEdit?id='.$id)?>">
    <?php foreach($photo AS $pic):?>
    <?php $link = URL::site('album/picView?id='.$pic['id']) ?>
    <table border="0" cellspacing="0" cellpadding="0">
	<tr>
	    <td valign="top" width="180"><a href="<?= $link ?>" title="点击预览" target="_blank"><img src="<?= URL::base().$pic['img_path'] ?>"  class="img"/></a></td>
	    <td>
		<table border="0" cellspacing="0" cellpadding="0">
		    <tr>
			<td >名称：</td>
			<td>
			    <input type="hidden" name="pic_id[]" value="<?=$pic['id']?>">
			    <input type="text" name="name[]" value="<?= $pic['name'] ?>" class="input_text" style="width:300px;" onclick="this.select()"/></td>
		    </tr>
		    <tr>
			<td valign="top">描述：</td>
			<td valign="top"><textarea name="intro[]" style="width:500px; height:60px" class="input_text"><?= $pic['intro'] ?></textarea></td>
		    </tr>
		</table>
	    </td>
	</tr>
    </table>
    <p class="dotted_line" style="margin:15px 0"></p>
    <?php endforeach;?>
    <p style="text-align:center"><input type="submit"  value="保存修改"  class="button_blue"/></p>
    </form>
</div>