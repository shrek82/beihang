<!-- club_global/admin_topbar:_body_top -->
<div id="top_tool">
    <div class="cur_here"><a href="/"><?=$_CONFIG->base['sitename']?></a> > <a href="/aa_home?id=<?= $_CLUB['aa_id'] ?>"><?= $_CLUB['aa_name'] ?></a> > <a href="/aa_home/club?id=<?= $_CLUB['aa_id'] ?>">俱乐部</a>:</div>
    <div class="user_nav"><?= View::factory('global/user_tool'); ?></div>
</div>

<div id="hp_top" style="margin-bottom: 0">
    <div id="hp_name">
        <span class="hp_logo"><img src="/static/homepage/images/zju_logo.png" ></span>
        <span class="hp_title"><a href="/club_home?id=<?= $_ID ?>"><?= $_CLUB['name'] ?></a></span>
        <span class="sign_members"><?= $_CLUB['mcount'] ? '已有' . $_CLUB['mcount'] . '人' : '暂时还没有人加入'; ?></span>
    </div>
    <div id="hp_nav">
        <ul class="menu">
            <li><a href="/club_home?id=<?= $_ID ?>" <?= $_C . '/' . $_A == 'club_home/index' ? 'class="cur"' : '' ?>>&LT;返回</a></li>
            <li><a href="/club_admin?id=<?= $_ID ?>" <?= $_C . '/' . $_A == 'club_admin/index' ? 'class="cur"' : '' ?>>审核</a></li>
            <li><a href="/club_admin_base/index?id=<?= $_ID ?>" <?= $_C == 'club_admin_base' ? 'class="cur"' : '' ?>>介绍</a></li>
            <li><a href="/club_admin_news/index?id=<?= $_ID ?>" <?= $_C == 'club_admin_news' ? 'class="cur"' : '' ?>>新闻</a></li>
            <li><a href="/club_admin_event/index?id=<?= $_ID ?>" <?= $_C  == 'club_admin_event' ? 'class="cur"' : '' ?>>活动</a></li>
            <li><a href="/club_admin_bbs/index?id=<?= $_ID ?>" <?= $_C == 'club_admin_bbs' ? 'class="cur"' : '' ?>>话题</a></li>
            <li><a href="/club_admin_member/index?id=<?= $_ID ?>" <?= $_C  == 'club_admin_member' ? 'class="cur"' : '' ?>>成员</a></li>
            <li><a href="/club_admin_album/index?id=<?= $_ID ?>" <?= $_C  == 'club_admin_album' ? 'class="cur"' : '' ?>>相册</a></li>
            <li><a href="/club_admin_theme/show?id=<?= $_ID ?>" <?= $_C == 'club_admin_theme' ? 'class="cur"' : '' ?>>显示</a></li>
        </ul>
        <div class="clear"></div>
    </div>
</div>