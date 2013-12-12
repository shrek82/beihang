<?php
//版本：1.0
?>
<style type="text/css">
#user_leftbar{ padding: 10px 0; line-height: 200%;
               text-align: center; font-size: 1.0em;
               color: #ccc; }
#user_leftbar a{ text-decoration: none;  }
#user_leftbar a.cur{ color: #000; }
</style>

<div id="user_leftbar" class="span-4">
    <?php if($_C.'/'.$_A != 'user_home/index'): ?>
        <?php if($_MASTER === FALSE): ?>
        <div>
            <a href="<?= URL::site('user_home?id='.$_ID) ?>">TA的主页</a> |
            <a href="<?= URL::site('user_home?id='.$_UID) ?>">我的主页</a>
        </div>
        <?php else: ?>
        <div><a href="<?= URL::site('user_home?id='.$_ID) ?>">返回主页</a></div>
        <?php endif; ?>
    <?php endif; ?>

    <?= View::factory('inc/user/avatar', array('id'=>$_ID,'size'=>128)) ?>

    <div id="user_home_panel">
        <?php if($_MASTER === FALSE): ?>
        <?= View::factory('inc/user/home_guest') ?>
        <?php else: ?>
        <?= View::factory('inc/user/home_master') ?>
        <?php endif; ?>
    </div>

    <!-- 加入的各种组织 -->
    <?= View::factory('inc/user/qlink'); ?>
</div>