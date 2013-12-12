<?php
$binding = Doctrine_Query::create()
        ->select('id,service,screen_name,domain,uid')
        ->from('WeiboBinding')
        ->where('user_id=?', $_ID)
        ->fetchArray();
?>

<div id="user_home_qlink" class="candyCorner">
<?php if (count($binding) == 0): ?>
                <?php if ($_MASTER): ?>
        <img src="/static/logo/sina/16x16.png" width="16" height="16" style=" vertical-align: middle" />&nbsp;<a href="/user_info/binding" style="color:#999">立即绑定</a>
                <?php else:?>
        尚未绑定微博。
                <?php endif; ?> 
        <?php else: ?>
                <?php foreach ($binding as $b): ?>
                        <img src="/static/logo/<?= $b['service'] ?>/16x16.png" width="16" height="16" style=" vertical-align: middle" />&nbsp;
                        <a href="http://weibo.com/u/<?= $b['uid'] ?>" style="color:#333" target="_blank"><?= $b['screen_name'] ? $b['screen_name'] : '进入微博'; ?></a>
                        <br>
        <?php endforeach; ?>
        <?php endif; ?>


</div>