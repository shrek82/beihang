<?php
//版本：1.0
?>
<?php if(isset($topbar_links)): ?>
<div id="user_topbar" class="span-19 last">
    <?php  foreach($topbar_links as $link=>$name): ?>
    <a href="<?= URL::site($link) ?>" class="<?= $_URI == $link ? 'cur':'' ?>"><?= $name ?></a>
    <?php endforeach; ?>
</div>
<?php endif; ?>