<p class="sidebar_title">新闻分类</p>
<div class="sidebar_box">
    <ul class="sidebar_menus">
	<?php foreach ($category as $c): ?>
    	<li><a href="<?= URL::site('news/list?aa_id=0&cid=' . $c['id']) ?>" ><?= Text::limit_chars($c['name'], 13, '..') ?></a></li>
	<?php endforeach ?>
    </ul>
</div>