<!-- classroom_admin/bar:_body_right -->
<?php $bar_links = array(
    'classroom_admin' => '审批加入',
    'classroom_admin/base' => '班级设置',
    'classroom_admin/members' => '成员管理',
    'classroom_admin/album' => '相册管理',
); ?>
 <div id="aa_admin_action" style="width:650px">
<?php foreach($bar_links as $uri=>$name): ?>
<a href="<?= URL::site($uri.'?id='.$_CLASSROOM['id'])?>" class="<?= ($_URI==$uri)?'cur':'' ?>"><?= $name ?></a>
<?php endforeach; ?>
    <div class="clear"></div>
</div>