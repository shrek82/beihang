<!-- alumni/index:_body -->
<style type="text/css">
    .bar{ font-size: 12px; height: 25px; line-height: 25px;border-bottom: 1px solid #E5EFFC}
    #user_online{ padding: 10px 0}
    #user_online a{ margin-right: 10px}
</style>
<div id="main_left">
    <h4>北航校友</h4>
    <!-- 寻找校友 -->
    <div  style="padding: 7px; background: #f5f5f5; border: 1px solid #eee; margin-top: 20px">
        <form action="" method="get">

            姓名
            <input type="text" name="name" class="input_text"  size="30" value="<?=Arr::get($_GET,'name') ?>"/>
            <input type="submit" value="寻找校友" class="button_blue" />
        </form>
    </div>

    <!-- 校友列表 -->
    <div class="user_list">
        <?php if(isset($query)): ?>
        <div style="padding:10px 0;color:#666"><?= $query; ?></div>
        <?php endif; ?>

        <?php if(count($users) == 0): ?>
        <p class="nodata" style="padding:15px 0">很抱歉，没有找到符合您条件的校友！</p>
        <?php endif; ?>

        <div class="clear"></div>

        <?php foreach($users as $u): ?>
        <div class="user_box">
            <a href="<?= URL::site('user_home?id='.$u['id']) ?>">
            <?= View::factory('inc/user/avatar', array('id' => $u['id'], 'size'=>48)) ?>
            <?= $u['realname'] ?></a><br>
	    <span style="color:#999"><?=$u['city']?$u['city']:'未知' ?></span>
        </div>
        <?php endforeach; ?>
	 <div class="clear"></div>
        <?= $pager ?>
    </div>

    <!-- 在线校友 -->
    <div class="bar">目前在线校友： </div>
    <div id="user_online">
        <?php foreach($online as $u): ?>
        <a href="<?= URL::site('user_home?id='.$u['id']) ?>"><?= $u['realname'] ?></a>
        <?php endforeach; ?>
    </div>
</div>

<div id="sidebar_right">

    <p class="sidebar_title" >最近加入校友</p>
    <div class="sidebar_box">
    <?php foreach($regs as $i => $u): ?>
    <a href="<?= URL::site('user_home?id='.$u['id']) ?>" style="padding: 2px 5px;" target="_blank"><?= $u['realname'] ?></a>
    <?= $i%3==2 ? '<br />' : '' ?>
    <?php endforeach ?>
    <div class="clear"></div>
    </div>


</div>