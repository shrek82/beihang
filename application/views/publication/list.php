<!-- publication/list:_body -->
<div id="sidebar_left">
<p class="sidebar_title"><?=$publication['issue']?></p>
    <div class="sidebar_box" style="text-align: center">
<img src="<?=$publication['cover']?>"  width="150" height="203"/><br>

<br>
<?php if($publication['pdf'] && is_file($_SERVER['DOCUMENT_ROOT'].$publication['pdf'])):?>
<a href="/<?=$publication['pdf']?>">下载PDF阅读文件</a>
<?php else:?>
<span style="color:#999">暂无PDF文件</span>
<?php endif;?>

<br>
<br>
<br>
    </div>
</div>


<div id="main_right">
 <p><img src="/static/images/publication_title.gif"></p><br>
    <div id="list">
<?php foreach($columns AS $c):?>
	<p class="title"><?=$c['name']?></p>
	<ul>
	    <?php foreach($c['article'] AS $a):?>
	    <li><a href="<?=URL::site('publication/article?id='.$a['id'])?>" target="_blank"><?=$a['title']?></a></li>
	    <?php endforeach;?>
	</ul>
<?php endforeach;?>
    </div>
</div>

<div class="clear"></div>