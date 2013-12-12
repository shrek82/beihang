<div>
<div id="main_left" >
    <h1><?=$notice['title'] ?></h1>
    <div class="news_info">发布日期：<?=$notice['post_at']?></div>
    <div class=" dotted_line" style="margin:0px 15px"></div>
<div class="news_content" id="content">
<?=$notice['content'] ?>
</div>
</div>
    <div id="sidebar_right">
	<div class="sidebar_title">其他公告</div>
	<div class="sidebar_box">
        <?php if(count($more_notices) == 0): ?>
        <span class="nodata">暂时还没有公告</span>
        <?php else: ?>
    <ul>
        <?php foreach($more_notices as $n): ?>
        <li><a href="<?= URL::site('news/notice?id='.$n['id']) ?>"><?= $n['title'] ?></a></li>
        <?php endforeach; ?>
    </ul>
        <?php endif; ?>
	</div>
    </div>
</div>