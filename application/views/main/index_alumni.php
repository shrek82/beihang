<p class="sidebar_title" style="border-width:1px 0">最受关注校友</p>

<div id="stars_alumni">
    <?php foreach ($concern as $c): ?>
        <div class="alumni_face">
    	<a href="<?= URL::site('user_home?id=' . $c['id']) ?>" target="'_blank"><?= View::factory('inc/user/avatar', array('id' => $c['id'], 'size' => 48,'sex'=>$c['sex'])) ?></a>
    	<a href="<?= URL::site('user_home?id=' . $c['id']) ?>" target="'_blank"><?= $c['realname'] ?></a>
        </div>
    <?php endforeach ?>
        <div class="clear"></div>
        <p class="more" style="margin:5px 0 0 10px;"><a href="<?= URL::site('alumni') ?>">>>更多</a></p>

</div>