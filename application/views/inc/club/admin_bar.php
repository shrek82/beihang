<!-- inc/club/admin_bar:_body_left -->
<?php

    $admin_links = array(
        'club_admin_base' => '基础',
	       'club_admin' => '申请',
        'club_admin_bbs' => '论坛',
        //'club_admin_event' => '活动',
        //'club_admin_member' => '成员',
        'club_admin_album' => '相册',
    );

?>
<div id="aa_admin_top">

<div><span style="font-size:22px; color: #333"><?= $_CLUB['name'] ?></span>
	<span style="color:#666;padding:0 10px">管理中心</span>
</div>

<div id="aa_admin_nav">
    <?php if($_ID):?>
    <a href="<?= URL::site('club_home?id='.$_ID.'&club_id='.$_ID) ?>"><<返回</a>
    <?php else:?>
    <a href="<?= URL::site('aa') ?>"><<校友总会</a>
    <?php endif;?>
    
 <?php foreach($admin_links as $uri => $key): ?>
    <a href="<?= URL::site($uri.'?id='.$_ID.'&club_id='.$_ID) ?>" class="<?= $uri == $_C ? 'cur':'' ?>"><?= $key ?></a>
     <?php endforeach; ?>
    <div class="clear"></div>
</div>

</div>
