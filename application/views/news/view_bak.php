<!--left -->
<script type="text/javascript">
<!--
var  flag=false;
function  DrawImage(ImgD){
     var  image=new  Image();
     image.src=ImgD.src;
     if(image.width>0  &&  image.height>0){
       flag=true;
       if(image.width/image.height>=  600/750){
         if(image.width>600){
         ImgD.width=600;
         ImgD.height=(image.height*600)/image.width;
         }else{
         ImgD.width=image.width;
         ImgD.height=image.height;
         }

         }
       else{
         if(image.height>750){
         ImgD.height=750;
         ImgD.width=(image.width*750)/image.height;
         }else{
         ImgD.width=image.width;
         ImgD.height=image.height;
         }

         }
       }
}
//-->
</script>
<div id="main_left" style="background-color:#F8FCFF; " >
    <h1 style="<?=$news['title_color']?'color:'.$news['title_color'].';':''?><?=$news['font_size']?'font-size:'.$news['font_size'].'pt;':''?>"><?= $news['title'] ?></h1>
    <div class="news_info">发布：<?= $news['create_at']; ?>&nbsp;&nbsp;&nbsp;&nbsp;来源：<?php if($aa_info):?><a href="<?=URL::site('aa_home?id='.$aa_info['id'])?>" target="_blank"><?=$aa_info['name']?></a><?php else:?><a href="/aa/">校友总会</a><?php endif;?>&nbsp;&nbsp;&nbsp;&nbsp;<?=$news['author_name']?'作者：'.$news['author_name'].'&nbsp;&nbsp;&nbsp;&nbsp;':'';?>点击：<?= $news['hit'] ?></div>
    <div class=" dotted_line" style="margin:0px 15px"></div>
    <div class="news_content" id="content">
	<?php if (!$news['is_release']): ?>
    	<div class="notice">该新闻尚未发布，只有管理员跟发布人可见。</div>
	<?php endif; ?>

	<?php if ($news['is_draft']): ?>
		<div class="notice">该新闻为预览效果，目前还是草稿，别人无法浏览。</div>
	<?php endif; ?>

 <?php
 //设置大图自动缩小js
$content=$news['content'];
//替换原杭州校友会图片附件路径
$content = preg_replace('/src="\/upload\/([^\"]+)"/i', 'src="http://v2.zjuhz.com/upload/$1"', $content);
$content=preg_replace('/(<img[^>]+src\s*=\s*"?\/static\/upload\/attached\/([^>"\s]+)"?[^>]*>)/im', '<a href="/static/upload/attached/$2?v=1" class="colorboxPic" style="cursor:url(/static/big.cur),pointer">$1</a>',$content);
$content=preg_replace('/_bmiddle\.(jpg|gif|jpeg|png)\?v=1/i', '.$1',$content);
echo $content;
?>

	    </div>
	    <div class=" dotted_line" style="margin:0px 15px"></div>
	    <div class="tool_link"><a href="javascript:copyhttp()" id="copyhttp">复制网址</a> <a href="javascript:window.print()">打印本页</a></div>
	    <div class=" dotted_line" style="margin:0px 15px"></div>
	    <p class="related_title">相关新闻</p>
	    <div id="related">
	<?php if (count($relate) == 0): ?>
	            <span class="nodata">暂时还没有相关新闻</span>
	<?php else: ?>
			<ul>
	    <?php foreach ($relate as $r): ?>
			    <li><a href="<?= URL::site('news/view?id=' . $r['id']) ?>"><?= Text::limit_chars($r['title'], 30, '...') ?></a><span><?= date('Y-n-d', strtotime($r['create_at'])); ?></span></li>
	    <?php endforeach; ?>
			</ul>
	<?php endif; ?>
		        </div>
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
			    <div id="view_right" >
			        <!-- 热门点击-->
			        <p class="sidebar_title2" ><span style="color:#c00">推荐</span>新闻</p>
			        <div class="sidebar_box2">
	<?php if (!$dig_news): ?>
			    	<p class="nodata">暂无推荐新闻</p>
	<?php endif; ?>
			    	<ul class="con_small_list" >
	    <?php foreach ($dig_news as $n): ?>
			    	    <li><a href="<?= URL::site('news/view?id=' . $n['id']) ?>" title="<?= $n['title'] ?>" ><?= Text::limit_chars($n['title'], 13, '..') ?></a></li>
	    <?php endforeach; ?>
	</ul>
    </div>
    <!-- //热门点击-->

</div>
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
