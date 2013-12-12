<!-- club_global/topbar:_body_top -->
<div id="top_tool">
    <div class="cur_here"><a href="/"><?=$_CONFIG->base['sitename']?></a> > <a href="/aa_home?id=<?= $_CLUB['aa_id'] ?>"><?= $_CLUB['aa_name'] ?></a> > <a href="/aa_home/club?id=<?= $_CLUB['aa_id'] ?>">俱乐部</a>:</div>
    <div class="user_nav"><?= View::factory('global/user_tool'); ?></div>
</div>
<div id="hp_top" >
    <div id="hp_name">
        <span class="hp_logo"><img src="/static/homepage/images/zju_logo.png" ></span>
        <span class="hp_title"><a href="/club_home?id=<?= $_ID ?>"><?= $_CLUB['name'] ?></a></span>
        <span class="sign_members"><?= $_CLUB['mcount'] ? '已有' . $_CLUB['mcount'] . '人' : '暂时还没有人加入'; ?></span>
    </div>
    <div id="hp_nav">
        <ul class="menu">
            <li><a href="/club_home?id=<?= $_ID ?>" <?= $_C . '/' . $_A == 'club_home/index' ? 'class="cur"' : '' ?>>首页</a></li>
            <?php if ($_THEME['news_limit'] > 0): ?><li><a href="/club_home/news?id=<?= $_ID ?>"  <?= $_C . '/' . $_A == 'club_home/news' || $_C . '/' . $_A == 'club_home/newsDetail' ? 'class="cur"' : '' ?>>新闻</a></li><?php endif;?>
            <li><a href="/club_home/event?id=<?= $_ID ?>" <?= ($_A == 'event' OR $_A=='eventview') ? 'class="cur"' : '' ?>>活动</a></li>
            <li><a href="/club_home/album?id=<?= $_ID ?>" <?= $_C . '/' . $_A == 'club_home/album' ? 'class="cur"' : '' ?>>相册</a></li>
            <li><a href="/club_home/member?id=<?= $_ID ?>" <?= $_C . '/' . $_A == 'club_home/member' ? 'class="cur"' : '' ?>>成员</a></li>
            <li><a href="<?= URL::site('bbs/list?aid=' . $_CLUB['aa_id']) ?>&cid=<?= $_ID ?>" >论坛</a></li>
            <li><a href="/club_home/info?id=<?= $_ID ?>" <?= $_C . '/' . $_A == 'club_home/info' ? 'class="cur"' : '' ?>>介绍</a></li>
            <?php if ($_IS_MANAGER): ?>
                <li><a href="<?= URL::site('club_admin/index?id=' . $_ID) ?>"  <?= $_C == 'club_admin' ? 'class="cur"' : '' ?>>管理</a></li>
            <?php endif; ?>
        </ul>
        <div class="clear"></div>
    </div>
</div>