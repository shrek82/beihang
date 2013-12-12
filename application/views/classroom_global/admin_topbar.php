<div id="top_tool">
    <div class="cur_here"><a href="/"><?=$_CONFIG->base['sitename']?></a> &gt; <a href="/classroom">班级录</a> &gt; <a href="/classroom_home?id=<?=$_ID?>"><?= $_CLASSROOM['name'] ?></a>  &gt;  班级管理</div>
    <div class="user_nav"><?= View::factory('global/user_tool'); ?></div>
</div>

<div id="hp_top" style="margin-bottom: 0">
    <div id="hp_name">
        <span class="hp_logo"><img src="/static/homepage/images/zju_logo.png" ></span>
        <span class="hp_title"><?= $_CLASSROOM['name'] ?> - 管理后台</span>
        <span class="sign_members"></span>
    </div>
    <div id="hp_nav">
        <ul class="menu"><li><a href="/classroom_home?id=<?= $_ID ?>" ><<返回</a></li>
            <li><a href="/classroom_admin?id=<?= $_ID ?>" <?= $_A == 'index' ? 'class="cur"' : '' ?>>审核</a></li>
            <li><a href="/classroom_admin/base?id=<?= $_ID ?>"  <?= $_A == 'base' ? 'class="cur"' : '' ?>>介绍</a></li>
            <li><a href="/classroom_admin/members?id=<?= $_ID ?>" <?= $_A == 'members' ? 'class="cur"' : '' ?>>成员</a></li>
            <li><a href="/classroom_admin/album?id=<?= $_ID ?>" <?= $_A == 'album' ? 'class="cur"' : '' ?>>相册</a></li>
            <li><a href="/classroom_admin_theme/show?id=<?= $_ID ?>" <?= $_C== 'classroom_admin_theme' ? 'class="cur"' : '' ?>>显示</a></li>
        </ul>
        <div class="clear"></div>
    </div>
</div>