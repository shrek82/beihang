<style type="text/css">
    .cur{ font-weight: bold; color: #1F558E}
</style>
<div id="aa_admin_action">
<?php foreach($actions as $uri => $action): ?>
<a href="<?= URL::site($uri.'?id='.$_ID) ?>" class="<?= $uri == $_C.'/'.$_A ? 'cur':'' ?>"><?= $action ?></a>&nbsp;
<?php endforeach; ?>
</div>