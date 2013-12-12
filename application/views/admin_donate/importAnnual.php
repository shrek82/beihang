<!-- admin_donate/importAnnual:_body -->
<style type="text/css">
#content_table td{ height: 24px}
#content_table .field{ text-align: right; padding: 0px 4px; width: 80px}
</style>
<form action="" method="post" enctype="multipart/form-data">
<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" >
<tr>
<td height="29" class="td_title" colspan="6"><b>导入年度统计</b></td>
</tr>

	<tr>
	    <td class="field">Excel文件：</td>
	    <td style="padding:15px"><input type="file" name="file"   />&nbsp;&nbsp;<span style="color:#999">格式：xls，所有导入数据默认已支付，格式请参考范例</span></td>
	</tr>

	<tr>
	    <td class="field">Excel范例：</td>
	    <td><a href="/static/donate.xls">点击另存为</a></td>
	</tr>
	<tr>
	    <td class="field"></td>
	    <td class="center" style="padding:20px">

		<input type="submit" value="确定导入" name="button" class="button_blue" />
		<input type="button" value="取消" onclick="window.history.back()" class="button_gray">

	    </td>
	</tr>
</table><br>

        <?php if($err): ?>
        <div class="notice"><?= $err; ?></div>
        <?php endif; ?>
</form>