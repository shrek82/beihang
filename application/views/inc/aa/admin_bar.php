<!-- inc/aa/admin_bar:_body_left -->
<?php

    $admin_links = array(
        'aa_admin' => '审核',
	'aa_admin_base' => '基础',
        'aa_admin_news' => '新闻',
        'aa_admin_bbs' => '论坛',
        'aa_admin_event' => '活动',
        'aa_admin_member' => '成员',
        'aa_admin_album' => '相册',
        'aa_admin_club' => '俱乐部',
    );

    if( ! $_MEMBER) exit;

    $permissions = explode(' ', $_MEMBER['permissions']);

?>
<div id="aa_admin_top">

<div><span style="font-size:22px; color: #333"><?= $aa['name'] ?></span>
	<span style="color:#666;padding:0 10px">管理中心</span>
</div>

<div id="aa_admin_nav">
    <a href="<?= URL::site('aa_home?id='.$_ID) ?>"><<返回</a>
    <?php
        foreach($admin_links as $uri => $key):
        if(in_array($key, $permissions)):
    ?>
    <a href="<?= URL::site($uri.'?id='.$_ID) ?>"
       class="<?= $uri == $_C ? 'cur':'' ?>"><?= $key ?></a>
    <?php endif; endforeach; ?>
    <div class="clear"></div>
</div>

</div>
<div class="clear"></div>