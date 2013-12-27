<!-- people/newsView:_body -->
<style type="text/css">
    .news_content{font-size:14px; line-height: 1.8em; margin: 20px 10px}
    .news_content p{ margin-top: 25px}
/*comments*/
#comments_list .one_cmt_right{float:left; margin-left:8px; width:580px; text-align: left;}
#comment_form_box .comment_form{float:left; width:580px;}
#cmt_content{width:600px; height:70px}
</style>
<div id="main_left" style="background-color:#F8FCFF; " >

    <h2 style="text-align:center" ><?= $news['title'] ?></h2>
    <div class="news_info" style="text-align:center; margin: 5px 10px; color: #999" >发布：<?= $news['create_at']; ?><?php if(trim($news['author_name'])):?>&nbsp;&nbsp;作者：<?=$news['author_name']?><?php endif;?></div>
    <div class=" dotted_line" style="margin:0px 15px"></div>
    <div class="news_content" id="content" >
	<?= $news['content'] ?>
	    </div>
    
	    <div class=" dotted_line" style="margin:0px 15px"></div>
            
      <div id="scrollToComment" style="height:5px"></div>
    <?php if ($news['is_comment']): ?>
        <p class="comments_title">评论</p>
        <div style="padding:10px">
            <!--回复及评论 -->
            <?= View::factory('inc/comment/newform', array('params' => array('news_id' => $news['id']))) ?>
            <!--//回复及评论 -->
        </div>
    <?php endif; ?>


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