<!--left -->
<div id="main_left" style="background-color:#F8FCFF; " >
    <div class="path">您的位置：校友捐赠 > <?=$content['catName']?></div>
    <h1><?= $content['title'] ?></h1>
    <div class="con_info">发布：<?= $content['create_at']; ?></div>
    <div class=" dotted_line" style="margin:0px 15px"></div>
    <div class="content" id="content"><?= $content['content'] ?></div>
</div>
<!--//left -->

<!--right -->
<div id="view_right" >
    <!-- 热门点击-->
    <p class="sidebar_title2" >其他<?=$content['catName']?></p>
    <div class="sidebar_box2">
	<?php if (!$more): ?>
    	<p class="nodata">暂无推荐新闻</p>
	<?php endif; ?>
    	<ul class="con_small_list" >
	    <?php foreach ($more as $m): ?>
    	    <li><a href="<?= URL::site('donate/view?id=' . $m['id']) ?>" title="<?= $m['title'] ?>" ><?= Text::limit_chars($m['title'], 13, '..') ?></a></li>
	    <?php endforeach; ?>
	</ul>
    </div>
    <!-- //热门点击-->

</div>
<!--//right -->

<div class="clear"></div>