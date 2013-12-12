<!-- 专业班级导航 -->
<?php $bar_links = array(
     'classroom_home' => '<<返回班级首页',
    'classroom_admin' => '审批加入',
    'classroom_admin/base' => '班级设置',
    'classroom_admin/members' => '成员管理',
    'classroom_admin/album' => '相册管理',
); ?>

<div id="aa_top">

        <div><span style="font-size:22px; color: #333;font-family:'Microsoft YaHei','Microsoft JhengHei'"><?= $_CLASSROOM['speciality'] ?></span>
            <span style="color:#666;padding:0 10px"><?= $_CLASSROOM['institute'] ? ' -- ' . $_CLASSROOM['institute'] . '&nbsp;&nbsp;' : '' ?><?= $_CLASSROOM['start_year'] ?>级</span>
            <?php if (!$_CLASSROOM['verify']): ?>
                <span style="color:#f60">尚未通过审核</span>
            <?php endif; ?>
            </div>

<div id="aa_admin_nav">
<?php foreach($bar_links as $uri=>$name): ?>
<a href="<?= URL::site($uri.'?id='.$_CLASSROOM['id'])?>" class="<?= ($_URI==$uri)?'cur':'' ?>"><?= $name ?></a>
<?php endforeach; ?>
<div class="clear"></div>
</div>

<?php if(isset($action_url)):?>
<div id="aa_admin_action" style="margin:0;padding:0px 10px">
    <?php foreach($action_url as $url=>$name):?>
    <a href="<?=$url?>"><?=$name?></a>
    <?php endforeach;?>
    <div class="clear"></div>
</div>
<?php endif;?>
<div class="clear"></div>
 </div>

