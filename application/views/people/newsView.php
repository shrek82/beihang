<!-- people/newsView:_body -->
<style type="text/css">
    .news_content{font-size:14px; line-height: 1.8em; margin: 20px 10px}
    .news_content p{ margin-top: 25px}
</style>
<div id="main_left" style="background-color:#F8FCFF; " >
    <h2 style="text-align:center" ><?= $news['title'] ?></h2>
    <div class="news_info" style="text-align:center; margin: 5px 10px; color: #999" >发布：<?= $news['create_at']; ?><?php if(trim($news['author_name'])):?>&nbsp;&nbsp;作者：<?=$news['author_name']?><?php endif;?></div>
    <div class=" dotted_line" style="margin:0px 15px"></div>
    <div class="news_content" id="content" >
	<?= $news['content'] ?>
	    </div>
	    <div class=" dotted_line" style="margin:0px 15px"></div>

</div>
			    <!--//left -->

<!--right -->
<?php
include 'sidebar.php';
?>
<!--//right -->

<div class="clear"></div>
<script type="text/javascript">
    function copyhttp(){
	var clipBoardContent="";
	clipBoardContent+=this.location.href;
	window.clipboardData.setData("Text",clipBoardContent);
	$('copyhttp').set('html','网址已经复制');
    }
</script>