<?php
//版本：1.0
?>
<?php if ($users): ?>
    <div>
    <?php foreach ($users AS $u): ?>
    <?php
	$user = array();
	$user['id'] = isset($u['User']['id']) ? $u['User']['id'] : $u['id'];
	$user['sex'] = isset($u['User']['sex']) ? $u['User']['sex'] : '';
	$user['realname'] = isset($u['User']['realname']) ? $u['User']['realname'] : $u['id'];
	$user['online'] = isset($u['User']['online']) ? $u['User']['online'] : $u['online'];
    ?>
        <div class="new_user_avatar">
    	<div class="face<?=$user['online']?'_online':'';?>"><a href="<?= URL::site('user_home?id=' . $user['id']) ?>" style="font-size:12px"><img src="<?= Model_User::avatar($user['id'], 48, $user['sex']) ?>"></a></div>
    	<div class="name"><a href="<?= URL::site('user_home?id=' . $user['id']) ?>" ><?= $user['realname'] ?></a></div>
        </div>
    <?php endforeach; ?>
        <div class="clear"></div>
    </div>
<?php endif; ?>