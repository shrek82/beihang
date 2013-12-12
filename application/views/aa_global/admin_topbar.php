<div id="top_tool">
    <div class="cur_here"><a href="/"><?=$_CONFIG->base['sitename']?></a> &gt; <a href="/aa/branch">校友分会</a> &gt; <a href="/aa_home?id=<?=$_ID?>"><?= $_AA['name'] ?></a>  &gt;  分会管理</div>
    <div class="user_nav"><?= View::factory('global/user_tool'); ?></div>
</div>

<div id="hp_top" style="margin-bottom: 0">
    <div id="hp_name">
        <span class="hp_logo"><img src="/static/homepage/images/zju_logo.png" ></span>
        <span class="hp_title">校友会管理</span>
        <span class="sign_members"></span>
    </div>
    <div id="hp_nav">
        <ul class="menu"><li><a href="/aa_home?id=<?= $_ID ?>" ><<返回</a></li>
            <li><a href="/aa_admin?id=<?= $_ID ?>" <?= $_C . '/' . $_A == 'aa_admin/index' ? 'class="cur"' : '' ?>>审核</a></li>
            <li><a href="/aa_admin_base?id=<?= $_ID ?>"  <?= $_C == 'aa_admin_base' ? 'class="cur"' : '' ?>>介绍</a></li>
            <li><a href="/aa_admin_news?id=<?= $_ID ?>" <?= $_C == 'aa_admin_news' ? 'class="cur"' : '' ?>>新闻</a></li>
            <li><a href="/aa_admin_event?id=<?= $_ID ?>" <?= $_C == 'aa_admin_event' ? 'class="cur"' : '' ?>>活动</a></li>
            <li><a href="/aa_admin_bbs?id=<?= $_ID ?>" <?= $_C == 'aa_admin_bbs' ? 'class="cur"' : '' ?>>话题</a></li>
            <li><a href="/aa_admin_member?id=<?= $_ID ?>" <?= $_C == 'aa_admin_member' ? 'class="cur"' : '' ?>>成员</a></li>
            <li><a href="/aa_admin_club?id=<?= $_ID ?>" <?= $_C == 'aa_admin_club' ? 'class="cur"' : '' ?>>俱乐部</a></li>
            <li><a href="/aa_admin_album?id=<?= $_ID ?>" <?= $_C == 'aa_admin_album' ? 'class="cur"' : '' ?>>相册</a></li>
            <li><a href="/aa_admin_theme/show?id=<?= $_ID ?>" <?= $_C== 'aa_admin_theme' ? 'class="cur"' : '' ?>>显示</a></li>
        </ul>
        <div class="clear"></div>
    </div>
</div>