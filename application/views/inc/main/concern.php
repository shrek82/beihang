<?php
// 最受关注的校友
$concern = Doctrine_Query::create()
		->select('u.id,u.realname, (SELECT COUNT(um.user) FROM UserMark um WHERE u.id = um.user) AS umcount')
		->from('User u')
		->orderBy('umcount DESC')
		->limit(12)
		->useResultCache(true, 3600, 'user_concern')
		->fetchArray();
?>
<div id="concern_top">
<?php foreach ($concern as $c): ?>
    <div class="ubox">
	<a href="<?= URL::site('user_home?id='.$c['id']) ?>">
<?= View::factory('inc/user/avatar', array('id' => $c['id'], 'size' => 48,'sex'=>$c['sex'])) ?>
	</a>
	
        <a href="<?= URL::site('user_home?id='.$c['id']) ?>"><?= $c['realname'] ?></a>
	</div>
<?php endforeach ?>
</div>

<div class='clear'></div>

