<div id="main_left">
    <div id="banners">
        <?php $view['banner'] = $banner; ?>
        <?= View::factory('aa_home/banner', $view); ?>
    </div>

    <?= View::factory('weibo/postForm'); ?>

    <?php
    if (isset($user_info)) {
        if ($user_info['id'] == $_UID) {
            $titleLabel = '我';
        } else {
            $titleLabel = $user_info['realname'];
        }
    } else {
        $titleLabel = '';
    }
    ?>
    <div class="blue_tab2" style="margin-top:10px">
        <ul id="weiboUlNav">
            <?php if (!$uid): ?>
                <li><a href="javascript:getLast('original')" <?= empty($v) || $v == 'original' ? 'class="cur"' : ''; ?> onfocus="this.blur()"> 原创的</a></li>
                <li><a href="javascript:getLast('event')" <?= $v == 'event' ? 'class="cur"' : ''; ?> onfocus="this.blur()"> 活动相关</a></li>
                <li><a href="javascript:getLast('aa')" <?= $v == 'aa' ? 'class="cur"' : ''; ?> onfocus="this.blur()">组织的</a></li>
                <li><a href="javascript:getLast('mark')" <?= $v == 'alumnus' ? 'class="cur"' : ''; ?> onfocus="this.blur()">我关注的</a></li>
            <?php else: ?>
                <?php if ($uid): ?>
                    <li><a href="" class="cur"><?= $titleLabel ?>发布的</a></li>
                <?php elseif ($v == 'atme'): ?>
                    <li><a href="" class="cur">提到我的</a></li>
                <?php elseif ($v == 'mark'): ?>
                    <li><a href="" class="cur">我关注的</a></li>
                <?php endif; ?>
            <?php endif; ?>
        </ul>
    </div>

    <!-- weibo list -->
    <div id="weibo_content_list" >
        <div id="top_Loading" style="padding:10px;color: #94B6E4;display: none"><span style="vertical-align: middle "><img src="/static/homepage/images/loading.gif" ></span> 加载中...</div>
        <div id="checkNewNum" style="display:none"></div>
        <div id="ajaxLoadList" class="link2">
            <?php include 'full_content_list.php'; ?></div>
    </div>
    <div id="bottom_loading"></div>
    <input type="hidden" value="all" id="curView">

    <?php if (count($weibo) > 0): ?>
        <?php if ($uid OR $v OR $page > 4): ?>
            <?= $pager ?>
        <?php elseif ($total_page > $page): ?>
            <div style=" text-align: center; margin:5px 0" id="bottom_getmore">
                <input type="hidden" value="<?= $page ?>" id="nowpage">
                <input type="hidden" value="<?= $total_page ?>" id="total_page">
                <input type="button" onclick="homegetmore()" class="moreload" title="浏览更早的">
            </div>
        <?php else: ?>
        <?php endif; ?>
    <?php endif; ?>

    <!-- //weibo list -->
</div>
<!-- //main_left -->

<!-- main_right -->
<div id="main_right">
    <?php include 'userbox.php'; ?>

    <div id="right_user_menu" class="link2">
        <ul>
            <li <?= empty($uid) && empty($v) && empty($topic) ? 'class="cur"' : ''; ?>><a href="/weibo?id=<?= $_ID ?>">最近发布</a><p class="dottle_line"></p></li>
            <?php if ($_UID): ?>
                <li <?= $uid == $_UID ? 'class="cur"' : ''; ?>><a href="/weibo?id=<?= $_ID ?>&uid=<?= $_UID ?>">我发布的</a><p class="dottle_line"></p></li>
                <li <?= $v == 'atme' ? 'class="cur"' : ''; ?>><a href="/weibo?id=<?= $_ID ?>&v=atme" >@提到我的</a><p class="dottle_line"></p></li>
                <li <?= $v == 'cmt' ? 'class="cur"' : ''; ?>><a href="/weibo?id=<?= $_ID ?>&v=cmt">我评论过的</a><p class="dottle_line"></p></li>
            <?php endif; ?>
        </ul>
    </div>

    <p class="column_tt">热门话题</p>
    <div class="aa_block">
        <?php if ($hot_topics): ?>
            <ul class="aa_conlist link2">
                <?php foreach ($hot_topics AS $t): ?>
                    <li><a href="/weibo?id=<?= $_ID ?>&topic=<?= urlencode($t['topic']) ?>"><?= $t['topic'] ?></a>&nbsp;<span style="color:#999">(<?= $t['num'] ?>)</span></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <div class="nodata">暂时还没有话题</div>
        <?php endif; ?>
    </div>
</div>
<!-- //main_right -->

<div class="clear"></div>

<script language="javascript">
    var $navLi=$("#weiboUlNav li");
    $(document).ready(function(){
        $navLi.click(function(){
            $navLi.children("a").removeClass('cur');
            $(this).children("a").addClass('cur');
        });
    });
    function resetUINav(){
        $navLi.children("a").removeClass('cur');
        $navLi.first().children("a").addClass('cur');

    }
    //自动检查最新
    //window.setTimeout("checkLatestNum()",10000);
</script>