<!-- publication/index:_body -->
<div id="main_left">
    <p><img src="/static/images/publication_title.gif"></p>
    <div>
	<ul class="con_list a14">
	    <?php foreach($report AS $r):?>
	    <li>
		<a href="<?=URL::site('publication/reportView?id='.$r['id'])?>" target="_blank"><?=$r['title']?></a>
		<span><?=$r['create_at']?></span>
	    </li>
	    <?php endforeach;?>
	</ul>
	<div class="clear"></div>
	<div><?=$pager?></div>
    </div>
</div>
<div id="sidebar_right">
<?php include 'sidebar.php';?>
</div>
<div class="clear"></div>