<!--left -->
<style type="text/css">
    .content_body{ line-height:1.8em; padding: 20px;}
    .content_body p{ margin-bottom: 15px}
    .content_body img{ padding: 4px; border: 1px solid #eee;margin-bottom: 15px}
    .content_title{text-align: left; font-size: 18px; font-weight: bold;height: 30px; line-height: 30px; padding: 15px; background: url(/static/images/dotted_line_bg2.gif) repeat-x bottom}
</style>
<!--//left -->

<!--right -->
<div width="100%">
    <div class="content_title">北航龙卡管理员登陆</div>

    <form action="<?=URL::site('card/login')?>" method="POST">
    <table border="0" cellspacing="0" cellpadding="0" style="margin:20px 0">
	<tbody>
	    <tr>
		<td>用户名：</td>
		<td><input name="name" type="text" class="input_text" style="width:150px" value=""></td>
	    </tr>
	    <tr>
		<td>密&nbsp;&nbsp;码：</td>
		<td><input name="password" type="password" class="input_text" style="width:150px" value=""></td>
	    </tr>

	    <tr>
		<td></td>
		<td><input type="submit" value="登陆" class="button_blue"></td>
	    </tr>
	</tbody>
    </table>
                <?php if($err): ?><br>
                <div class="notice"><?= $err; ?></div>
                <?php endif; ?>
</form>
</div>
 <!--//right -->
 <div class="clear"></div>