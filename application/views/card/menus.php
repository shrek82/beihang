<?php
    $menus=  Model_content::cardMenu();
 ?>
<?php if(!$menus):?>
<p class="nodata">暂无内容</p>
<?php endif;?>
<ul class="con_small_list" >
<?php foreach($menus as $m): ?>
 <?php if($m['redirect']):?>
<li><a href="<?= $m['redirect'] ?>" <?=$id==$m['id']?'style="font-weight:bold"':'' ?>><?= Text::limit_chars($m['title'],13, '..') ?></a></li>
    <?php else:?>
<li><a href="<?= URL::site('card?id='.$m['id']) ?>" <?=$id==$m['id']?'style="font-weight:bold"':'' ?>><?= Text::limit_chars($m['title'],13, '..') ?></a></li>
    <?php endif;?>

<?php endforeach; ?>
</ul>

