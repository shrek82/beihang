<!--left -->
<style type="text/css">
    .content_body{ line-height:1.8em; padding: 20px;}
    .content_body p{ margin-bottom: 15px}
    .content_body img{ padding: 4px; border: 1px solid #eee;margin-bottom: 15px}
    .content_title{text-align: left; font-size: 18px; font-weight: bold;height: 30px; line-height: 30px; padding: 15px; background: url(/static/images/dotted_line_bg2.gif) repeat-x bottom}
</style>
<div id="main_left">
    <div class="content_title">信用卡申请表</div>
   <form action="" method="post" >
    <table border="0" cellspacing="0" cellpadding="0" style="margin:20px auto">
	<tbody>
	    <tr>
		<td colspan="2">
		  <img alt="" src="/static/upload/editor/images/card.jpg" style="width: 532px; height: 250px; " />
		</td>
	    </tr>
	    <tr>
		<td style="text-align:center"><input type="radio" name="card_type" value="金卡" checked="checked" id="jinka" /><label for="jinka">金卡</label></td>
		<td style="text-align:center"><input type="radio" name="card_type" value="普卡" id="puka" /><label for="puka">普卡</label></td>
	    </tr>
	    	<tr>
		    <td style="text-align:left" colspan="2" height="20"></td>
	    </tr>
	    	<tr>
		    <td style="text-align:left" colspan="2">姓名：<input type="text" class="input_text" style="width:200px" name="realname" value="<?=$realname?>"></td>
	    </tr>
	    	<tr>
		    <td style="text-align:left" colspan="2">手机：<input type="text" class="input_text" style="width:200px" name="mobile" value="<?=$mobile?>"></td>
	    </tr>
	    	<tr>
		    <td style="text-align:left" colspan="2"><input type="submit" class="button_blue" value="确定提交" style="margin-left:35px"></td>
	    </tr>

	    <tr>
		<td colspan="2">

		<?php if($success):?><br>
		    <div class="success"><?= $success; ?></div>
		 <?php endif;?>
                <?php if($err): ?><br>
                <div class="notice"><?= $err; ?></div>
                <?php endif; ?></td>
	    </tr>
	</tbody>
    </table>
</form>

</div>
<!--//left -->

<!--right -->


<div id="sidebar_right"  >
<p class="sidebar_title" >北航龙卡</p>
<div class="sidebar_box">
<?php include_once 'menus.php'; ?>
</div>
 </div>
 <!--//right -->
 <div class="clear"></div>