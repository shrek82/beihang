<!--left -->
<style type="text/css">
    .content_body{ line-height:1.8em; padding: 20px;}
    .content_body p{ margin-bottom: 15px}
    .content_body img{ padding: 4px; border: 1px solid #eee;margin-bottom: 15px}
    .content_title{text-align: left; font-size: 18px; font-weight: bold;height: 30px; line-height: 30px; padding: 15px; background: url(/static/images/dotted_line_bg2.gif) repeat-x bottom}
</style>

<!--//left -->

<!--right -->
<div id="main_left">
    <div class="content_title"><?=$content['title']?></div>
    <div class="content_body">
	<?=$content['content']?>
    </div>

</div>
 <!--//right -->

 <div id="sidebar_right"  >
<p class="sidebar_title" >北航龙卡</p>
<div class="sidebar_box">
<?php include_once 'menus.php'; ?>
</div>
 </div>

 <div class="clear"></div>