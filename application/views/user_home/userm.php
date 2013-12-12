<?php if($title): //显示页面标题+关注人头像 ?>
<div id="big_right">
    <div id="plugin_title"><?=$title ?></div>
<?php foreach($mark as $m): ?>
<div class="a_user_avatar">
    <a href="<?= URL::site('user_home?id='.$m['user']) ?>">
    <?= View::factory('inc/user/avatar', array('id' => $m['user'], 'size'=>48,'sex'=>$m['MUser']['sex'])) ?></a>
    <a href="<?= URL::site('user_home?id='.$m['user']) ?>"><?= $m['MUser']['realname'] ?></a>
</div>
<?php endforeach; ?>
</div>

<?php else: //仅显示头像列表 ?>
<?php foreach($mark as $m): ?>
<div class="a_user_avatar">
    <a href="<?= URL::site('user_home?id='.$m['user']) ?>">
    <?= View::factory('inc/user/avatar', array('id' => $m['user'], 'size'=>48,'sex'=>$m['MUser']['sex'])) ?></a>
    <a href="<?= URL::site('user_home?id='.$m['user']) ?>"><?= $m['MUser']['realname'] ?></a>
</div>
<?php endforeach; ?>
<?php endif ?>

