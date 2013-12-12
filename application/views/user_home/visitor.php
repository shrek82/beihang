<!-- user_home/visitor:_body -->
<?php if($title): //显示页面标题+访客头像 ?>

<div id="big_right">
    <div id="plugin_title"><?=$title ?></div>
<?php foreach($visitor as $v): ?>
<div class="a_user_avatar">
    <a href="<?= URL::site('user_home?id='.$v['visitor_id']) ?>" >
    <?= View::factory('inc/user/avatar', array('id' => $v['visitor_id'], 'size'=>48,'sex'=>$v['User']['sex'])) ?></a>
    <a href="<?= URL::site('user_home?id='.$v['visitor_id']) ?>"><?= $v['User']['realname'] ?></a><br />
    <span style="color: #999"><?= Date::span_str((strtotime($v['visit_at'])-100)) ?>前</span>
</div>
<?php endforeach; ?>
</div>

<?php else: //只显示访客头像 ?>

<?php foreach($visitor as $v): ?>
<div class="a_user_avatar">
    <a href="<?= URL::site('user_home?id='.$v['visitor_id']) ?>" >
    <?= View::factory('inc/user/avatar', array('id' => $v['visitor_id'], 'size'=>48,'sex'=>$v['User']['sex'])) ?></a>
    <a href="<?= URL::site('user_home?id='.$v['visitor_id']) ?>"><?= $v['User']['realname'] ?></a><br />
    <span style="color: #999"><?= Date::span_str((strtotime($v['visit_at'])-100)) ?>前</span>
</div>
<?php endforeach; ?>

<?php endif ?>
