<!-- admin_bbs/focusForm:_body -->

<form action="" method="post" enctype="multipart/form-data">
<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" >
<tr >
<td height="29" class="td_title" colspan="2" ><b>论坛幻灯片管理</b>
</td>
</tr>

	<tr>
	    <td class="field">话题标题：</td>
	    <td><?=$bbs_focus['title']?></td>
	</tr>
	<tr>
	    <td class="field" valign="top" >使用话题内容图片:</td>
	    <td>
		<?php if($img_path):?>
                <?php foreach ($img_path as $key=>$value):?>
		<table class="admin_table">
		    <tr>
			<td style="border-width:0"><input type="radio" name="img_path" value="<?=$value?>" id="img<?=$key?>"/></td>
			<td style="border-width:0"><label for="img<?=$key?>"><img src="<?=$value?>" /></label></td>
		    </tr>
		</table>
		<br>
		<?php endforeach;?>
		<?php else:?>
		<span class="nodata">暂时还没有图片</span>
		<?php endif;?>
	    </td>
	</tr>
	<tr>
	    <td class="field">使用上传图片：</td>
	    <td><input type="file" name="file"  /><span style="color:#999"></span></td>
	</tr>

	<tr>
	    <td class="field"></td>
	    <td>

		<input type="submit" value="保存修改" name="button"  class="button_blue"/>

	    </td>
	</tr>


    </tbody>
</table><br>

       <input type="hidden" name="id" value="<?= $bbs_focus['id'] ?>" />

        <?php if($err): ?>
        <div class="notice"><?= $err; ?></div>
        <?php endif; ?>
</form>