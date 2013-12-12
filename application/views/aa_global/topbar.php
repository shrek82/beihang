<div id="top_tool">
    <div class="cur_here"><a href="/"><?=$_CONFIG->base['sitename']?></a> &gt; <a href="/aa/branch">校友分会</a> &gt; <?= $_AA['name'] ?></div>
    <div class="user_nav"><?= View::factory('global/user_tool'); ?></div>
</div>

<div id="hp_top" >
    <div id="hp_name">
        <span class="hp_logo"><img src="/static/homepage/images/zju_logo.png" ></span>
        <span class="hp_title"><a href="/aa_home?id=<?= $_ID ?>"><?= $_AA['name'] ?></a></span>
        <span class="sign_members"><?= $_AA['mcount'] ? '已有' . $_AA['mcount'] . '人' : '暂时还没有人加入'; ?></span>
    </div>
    <div id="hp_nav">
        <ul class="menu" id="menus">
            <li><a href="/aa_home?id=<?= $_ID ?>" <?= $_C . '/' . $_A == 'aa_home/index' ? 'class="cur"' : '' ?>>首页</a></li>
            <li><a href="/weibo?id=<?= $_ID ?>" <?= $_C == 'weibo' ? 'class="cur"' : '' ?>>新鲜事</a></li>
            <li><a href="/aa_home/event?id=<?= $_ID ?>" <?= ($_A == 'event' OR $_A == 'eventview') ? 'class="cur"' : '' ?>>活动</a></li>
            <li><a href="/aa_home/news?id=<?= $_ID ?>"  <?= $_C . '/' . $_A == 'aa_home/news' || $_C . '/' . $_A == 'aa_home/newsDetail' ? 'class="cur"' : '' ?>>新闻</a></li>
            <li><a href="/aa_home/club?id=<?= $_ID ?>" <?= $_C . '/' . $_A == 'aa_home/club' ? 'class="cur"' : '' ?>>俱乐部</a></li>
            <li><a href="/aa_home/album?id=<?= $_ID ?>" <?= $_C . '/' . $_A == 'aa_home/album' ? 'class="cur"' : '' ?>>相册</a></li>
            <li><a href="/aa_home/member?id=<?= $_ID ?>" <?= $_C . '/' . $_A == 'aa_home/member' ? 'class="cur"' : '' ?>>成员</a></li>
            <li><a href="<?= URL::site('bbs/list?aid=' . $_ID) ?>" >论坛</a></li>
            <?php if (($_MEMBER && $_MEMBER['manager']) OR $_ROLE == '管理员'): ?>
                <li><a href="<?= URL::site('aa_admin/index?id=' . $_ID) ?>"  <?= $_C == 'aa_admin' ? 'class="cur"' : '' ?>>管理</a></li>
            <?php endif; ?>
        </ul>
        <?php
        $for = 'event';
        switch ($_A) {
            case "news":
                $for = 'news';
                break;
            case "event":
                $for = 'event';
                break;
            case 'bbs':
                $for = 'bbs';
                break;
        }
        ?>
        <form method="get" action="<?= URL::site('search') ?>" style="margin:0">
            <div id="top_search">
                <ul>
                    <li><input type="text"  name="q" class="search_input"  value="新闻、话题、活动" onclick="if(this.value=='新闻、话题、活动'){this.value='';}" onblur="if(this.value==''){this.value='新闻、话题、活动';}"/><input type="hidden" name="for" value="<?= $for ?>"  ></li>
                    <li><input type="submit"  class="search_button"  value="" style="*margin-top:1px"/></li>
                </ul>
            </div>
        </form>
        <div class="clear"></div>
    </div>
</div>
