<?php if($title): //显示页面标题+关注人头像 ?>
<div id="big_right">
    <div id="plugin_title"><?=$title ?></div>
<?php foreach($focus as $f): ?>
<div class="a_user_avatar">
    <a href="<?= URL::site('user_home?id='.$f['user_id']) ?>">
    <?= View::factory('inc/user/avatar', array('id' => $f['user_id'], 'size'=>48,'sex'=>$f['FUser']['sex'])) ?></a>
    <a href="<?= URL::site('user_home?id='.$f['user_id']) ?>"><?= $f['FUser']['realname'] ?></a>
</div>
<?php endforeach; ?>
</div>

<?php else: //仅显示头像列表 ?>
<?php foreach($focus as $f): ?>
<div class="a_user_avatar">
    <a href="<?= URL::site('user_home?id='.$f['user_id']) ?>">
    <?= View::factory('inc/user/avatar', array('id' => $f['user_id'], 'size'=>48,'sex'=>$f['FUser']['sex'])) ?></a>
    <a href="<?= URL::site('user_home?id='.$f['user_id']) ?>"><?= $f['FUser']['realname'] ?></a>
</div>
<?php endforeach; ?>
<?php endif ?>

