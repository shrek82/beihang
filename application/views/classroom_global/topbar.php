<div id="top_tool">
    <div class="cur_here"><a href="/"><?=$_CONFIG->base['sitename']?></a> &gt; <a href="/classroom">班级录</a> &gt; <?= $_CLASSROOM['name'] ?></div>
    <div class="user_nav"><?= View::factory('global/user_tool'); ?></div>
</div>

<div id="hp_top" >
    <div id="hp_name">
        <span class="hp_logo"><img src="/static/homepage/images/zju_logo.png" ></span>
        <span class="hp_title"><a href="/classroom_home?id=<?= $_ID ?>"><?= $_CLASSROOM['name'] ?></a></span>
        <span class="sign_members"><?= $_CLASSROOM['mcount'] ? '已有' . $_CLASSROOM['mcount'] . '人' : '暂时还没有人加入'; ?></span>
    </div>
    <div id="hp_nav">
        <ul class="menu" id="menus">
            <li><a href="/classroom_home?id=<?= $_ID ?>" <?= $_C . '/' . $_A == 'classroom_home/index' ? 'class="cur"' : '' ?>>首页</a></li>
            <li><a href="/classroom_home/bbs?id=<?= $_ID ?>" <?=$_A == 'bbs'||$_A == 'bbsPost'||$_A == 'bbsUnit' ? 'class="cur"' : '' ?>>话题</a></li>
            <li><a href="/classroom_home/guestbook?id=<?= $_ID ?>" <?= $_A == 'guestbook' ? 'class="cur"' : '' ?>>留言板</a></li>
            <li><a href="/classroom_home/members?id=<?= $_ID ?>" <?= $_A == 'members' ? 'class="cur"' : '' ?>>成员</a></li>
            <li><a href="/classroom_home/addressbook?id=<?= $_ID ?>" <?= $_A == 'addressbook' ? 'class="cur"' : '' ?>>通讯录</a></li>
            <li><a href="/classroom_home/album?id=<?= $_ID ?>" <?= $_A == 'album' ? 'class="cur"' : '' ?>>相册</a></li>
            <?php if ($_IS_MANAGER== '管理员'): ?>
                <li><a href="<?= URL::site('classroom_admin/index?id=' . $_CLASSROOM['id']) ?>"  <?= $_C == 'classroom_admin' ? 'class="cur"' : '' ?>>管理</a></li>
            <?php endif; ?>
        </ul>
        <div class="clear"></div>
    </div>
</div>
