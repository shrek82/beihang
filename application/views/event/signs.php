<?php if (count($signs) == 0): ?>
    <span class="nodata">暂无记录。</span>
<?php else: ?>

    <?php foreach ($signs AS $s): ?>
        <div id="sign_user_<?= $s['id'] ?>" class="new_user_avatar" style="float: left; margin:0 6px 5px 0; height: 80px ">
            <?php if (!$s['is_anonymous']): ?>
                <div class="face<?= $s['online'] ? '_online' : ''; ?>"><a href="<?= URL::site('user_home?id=' . $s['User']['id']) ?>" style="font-size:12px"><img src="<?= Model_User::avatar($s['User']['id'], 48, $s['User']['sex']) ?>"></a></div>
                <div class="name"><a href="<?= URL::site('user_home?id=' . $s['User']['id']) ?>" title="报名<?= $s['num'] ?>人"><?= $s['User']['realname'] ?></a></div>
            <?php else: ?>
                <div class="face<?= $s['online'] ? '_online' : ''; ?>"><img src="<?= Model_User::avatar(0, 48, $s['User']['sex']) ?>" title="猜猜我是谁"></div>
                <div class="name" style="color:#999">匿名</div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>

    <div class="clear"></div>
<?php endif; ?>