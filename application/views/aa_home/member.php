<!-- aa_home/member:_body -->
<div id="main_left">
    <div style="padding: 5px 20px">
        <form action="/aa_home/member?id=<?=$_ID?>" style="text-align: right" method="POST">
            <input type="text" name="q" value="<?= $q ?>" onclick="this.value=''"  class="input_text" size="20"/>
            <input type="submit" value="搜索" class="button_blue" />
            <?php if ($q): ?>
                <input type="button" onclick="location.href='<?= URL::query(array('q' => null)) ?>'" value="全部" class="button_blue" />
            <?php endif; ?>
        </form>
    </div>

    <?php if (count($members) == 0): ?>
        <p class="nodata">暂时还没有成员。</p>
    <?php else: ?>
        <?php foreach ($members as $m): ?>
            <div class="ubox" style="width:75px;height:90px">
                <div style="width:50px;margin:5px">
                    <a href="<?= URL::site('user_home?id=' . $m['user_id']) ?>" title="点击访问主页" target="_blank"><?= View::factory('inc/user/avatar', array('id' => $m['user_id'], 'size' => 48, 'sex' => $m['User']['sex'])) ?></a>
                </div>
                <div>
                    <a href="<?= URL::site('user_home?id=' . $m['user_id']) ?>" title="点击访问主页" target="_blank"><?= $m['User']['realname'] ?></a><br />

                    <?php
                    if ($orderby == 'join') {
                        echo Date::span_str(strtotime($m['join_at']));
                        echo '前加入';
                    }
                    if ($orderby == 'visit') {
                        echo Date::span_str(strtotime($m['visit_at']));
                        echo '前访问';
                    }
                    ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
    <div class="clear"></div>
    <?= $pager ?>
</div>
<div id="main_right">

    <div class="column_tt">管理员</div>
    <div class="aa_block">
        <?php foreach ($manager as $v): ?>
            <div class="ubox">
                <a href="<?= URL::site('user_home?id=' . $v['user_id']) ?>" title="点击进入主页" target="_blank"><?= View::factory('inc/user/avatar', array('id' => $v['user_id'], 'size' => 48, 'sex' => $v['sex']))
        ?></a>
                <a href="<?= URL::site('user_home?id=' . $v['user_id']) ?>"><?= $v['realname'] ?></a>
            </div>
        <?php endforeach; ?>
        <div class='clear'></div>
    </div>

    <div class="column_tt">最新加入</div>
    <div class="aa_block" style="padding:10px 0">
        <?php if (!$joinmember): ?>
            <p class="nodata" style="padding:0px 5px">暂无加入</p>
        <?php endif; ?>

        <?php foreach ($joinmember as $m): ?>
            <div class="ubox" style="margin-right:10px">
                <a href="<?= URL::site('user_home?id=' . $m['user_id']) ?>"><?= View::factory('inc/user/avatar', array('id' => $m['user_id'], 'size' => 48, 'sex' => $m['sex']))
        ?></a>
                <p class="face_name"><a href="<?= URL::site('user_home?id=' . $m['user_id']) ?>"><?= $m['realname'] ?></a><br>
                    <span><?= Date::span_str(strtotime($m['join_at']) + 1) ?>前</span></p>
            </div>
        <?php endforeach; ?>
        <div class='clear'></div>
    </div>

</div>
<div class="clear"></div>
