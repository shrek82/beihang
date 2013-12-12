<style type="text/css">

#content_table td{ height: 24px}
#content_table .field{ text-align: right; padding: 0px 4px; width: 80px}
</style>
<form action="" method="post" enctype="multipart/form-data">
<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" >
<tr>
<td height="29" class="td_title" colspan="6"><b>文章管理</b></td>
</tr>
<tr>
	    <td class="field">所属期刊：<?=$pub_id?></td>
	    <td>
		<select name="pub_id">
		    <?php foreach ($publication AS $p):?>
		    <option value="<?=$p['id']?>" <?php if($pub_id==$p['id']):?>selected<?php endif;?>><?=$p['name']?>-<?=$p['issue']?></option>
		    <?php endforeach;?>
		</select>
	    </td>
	</tr>
	<tr>
	    <td class="field">TXT文件：</td>
	    <td style="padding:15px"><input type="file" name="file"   />&nbsp;&nbsp;<span style="color:#999"></span></td>
	</tr>
	<tr>
        <td class="field"></td>
	<td style="color:#999"> 温馨提示：为避免文字编码错误，建议导入txt文件使用英文字符，如：alumni2012-3.txt</td>
	</tr>
	<tr>
	    <td class="center" style="padding:20px" colspan="2">

		<input type="submit" value="确定导入" name="button" class="button_blue" />
		<input type="button" value="取消" onclick="window.history.back()" class="button_gray">
	    </td>
	</tr>

</table><br>

        <?php if($err): ?>
        <div class="notice"><?= $err; ?></div>
        <?php endif; ?>
</form>
