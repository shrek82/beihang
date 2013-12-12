<!-- classroom_home/members:_body -->
<div id="main_left" style="width: 680px;">
    <?php if (!$members): ?>
        <p class="nodata">暂时还没有成员。</p>
    <?php else: ?>
        <?php foreach ($members as $i => $m): ?>
            <div class="ubox" style="width:75px;height:90px">
                <div style="width:50px;margin:5px">
                    <a href="<?= URL::site('user_home?id=' . $m['user_id']) ?>" title="点击访问主页" target="_blank"><?= View::factory('inc/user/avatar', array('id' => $m['user_id'], 'size' => 48, 'sex' => $m['User']['sex'])) ?></a>
                </div>
                <div style="color:#999">
                    <a href="<?= URL::site('user_home?id=' . $m['user_id']) ?>" title="点击访问主页" target="_blank"><?= $m['User']['realname'] ?></a><br />
                    <?php
                        echo Date::span_str(strtotime($m['visit_at']));
                        echo '前访问';
                    ?>
                </div>
            </div>

        <?php endforeach; ?>
    <?php endif; ?>
    <?= $pager ?>
    <div class="clear"></div>
</div>
<div id="main_right">
    <p class="column_tt">管理员</p>
    <div class="aa_block">
        <?php foreach ($managers as $v): ?>
            <div class="ubox">
                <a href="<?= URL::site('user_home?id=' . $v['user_id']) ?>" title="点击进入主页" target="_blank"><?= View::factory('inc/user/avatar', array('id' => $v['user_id'], 'size' => 48, 'sex' => $v['sex'])) ?></a>
                <p class="face_name" ><a href="<?= URL::site('user_home?id=' . $v['user_id']) ?>"><?= $v['realname'] ?></a><br>
                    <span><?= Date::span_str(strtotime($v['visit_at']) + 1) ?>前</span></p>
            </div>
        <?php endforeach; ?>
        <div class='clear'></div>
    </div>
</div>
<div class="clear"></div>