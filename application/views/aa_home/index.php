<!-- aa_home/index:_body -->
<div id="main_left" style="width: 680px;">

   <?php if ($_THEME['banner_limit'] > 0): ?>
        <!-- banner -->
        <div id="banner_pics">
            <?php $view['banner'] = $banner; ?>
            <?= View::factory('aa_home/banner', $view); ?>
        </div>
    <?php endif; ?>

    <div id="hot_link">
        <?php if ($_ID == 1 && $near_event_tags): ?>
            <span style="color:#666">近期活动：</span>
            <?php foreach ($near_event_tags AS $t): ?><a href="/aa_home/event?id=<?= $_ID ?>&tag=<?= urlencode($t['name']) ?>" title="<?= $t['num'] ? '近期' . $t['num'] . '场活动' : null; ?>"><?= $t['name'] ?><?= $t['num'] ? '(' . $t['num'] . ')' : null; ?></a>&nbsp;&nbsp;<?php endforeach; ?>
            <a href='/aa_home/event?id=<?= $_ID ?>'>更多...</a>
        <?php elseif ($_ID == 1): ?>
            <br><span style="color:#666">热门活动：</span>
            <?php if ($host_event_tags): ?>
                <?php foreach ($host_event_tags AS $t): ?><a href="/aa_home/event?id=<?= $_ID ?>&tag=<?= urlencode($t['name']) ?>" title="<?= isset($t['startnum']) && $t['startnum'] ? '近期' . $t['startnum'] . '场活动' : null; ?>"><?= $t['name'] ?><?= isset($t['startnum']) && $t['startnum'] ? '(' . $t['startnum'] . ')' : null; ?></a><?php endforeach; ?>
                <a href='/aa_home/event?id=<?= $_ID ?>'>更多...</a>
            <?php else: ?>
                <span class="nodata">暂时还没有活动标签</span>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <?php if ($_THEME['allow_post_weibo']): ?>
        <script type="text/javascript">
            var postedJumpUrl = '/weibo?id=<?= $_ID ?>';
        </script>
        <?= View::factory('weibo/postForm'); ?>
    <?php endif; ?>

    <!--weibo -->
    <?php if ($_THEME['weibo_limit'] > 0 AND count($weibo) > 3): ?>
        <div class="big_title">
            <p class="name">大家都在说什么</p><p class="tool">
                <a href="<?= URL::site('weibo') . URL::query() ?>" class="link2">更多</a></p></div>
        <div class="scrollWeibo link2" id="scrollWeibo">
            <ul id="slider" class="slider">
                <?= View::factory('weibo/scroll_content_list', array('weibo' => $weibo)); ?>
            </ul>
        </div>
        <script type="text/javascript">readyScript.newthing=scrollnewthing;</script>
        <!--//weibo -->
    <?php endif; ?>

    <!--news -->
    <?php if ($_THEME['news_limit'] > 0): ?>
        <div class="big_title">
            <p class="name">新闻动态</p><p class="tool"><a href="<?= URL::site('/user_news/form?aa=' . $_ID) ?>" class="postlink link2">投稿</a> | <?php if (($_MEMBER && $_MEMBER['manager'])): ?><a href="<?= URL::site('aa_admin_news') . URL::query() ?>" class=" link2">管理</a><?php else: ?><a href="<?= URL::site('aa_home/news') . URL::query() ?>" class="link2">更多</a><?php endif; ?></p></div>
        <?php if (!$news): ?>
            <div class="nodata">暂时还没有新闻。</div>
        <?php endif; ?>
        <ul class="con_list2">
            <?php foreach ($news as $n): ?>
                <li style="padding:2px 0"><?= $n['category_name'] ?>&nbsp;<a href="<?= URL::site('aa_home/newsDetail?id=' . $_ID . '&nid=' . $n['id']) ?>" target="_blank"  <?= $n['is_fixed'] ? 'style="color:#f30"' : ''; ?>><?= Text::limit_chars($n['title'], 42, '...'); ?></a>
                    <?php if ($n['is_fixed']): ?>&nbsp;&nbsp;<font style="color:#f60"><img src="/static/images/is_top.gif" title="置顶新闻"></font><?php endif; ?>
                    <span class="date"><?= Date::span_str(strtotime($n['create_at'])) ?>前&nbsp;</span>
                </li>
            <?php endforeach; ?>
        </ul>
        <!--//news -->
    <?php endif; ?>
    <?php if ($_THEME['event_limit'] > 0): ?>
        <!--event -->
        <div class="big_title">
            <p class="name">近期活动</p>
            <p class="tool"><a href="<?= URL::site('/event/form?aa=' . $_ID) ?>" class="postlink link2">发起</a> | <?php if (($_MEMBER && $_MEMBER['manager'])): ?><a href="<?= URL::site('aa_admin_event') . URL::query() ?>" class="link2">管理</a><?php else: ?><a href="<?= URL::site('aa_home/event') . URL::query() ?>" class="link2">更多</a><?php endif; ?></div>
        <?php if (!$event): ?>
            <div class="nodata">暂时还没有活动信息。</div>
        <?php endif; ?>

        <ul class="con_list2">
            <? $display_event = $_THEME['event_limit']; ?>
            <?php foreach ($event as $key => $e): ?>
                <? if ($key <= $display_event) break; ?>
                <li style="padding:2px 0">
                    <?= $e['type'] ? $e['type'] : '无分类'; ?>&nbsp;<a href="<?= Db_Event::getLink($e['id'], $e['aa_id'], $e['club_id']) ?>"  target="_blank" <?= $e['is_fixed'] ? 'style="color:#f30"' : ''; ?>><?= Text::limit_chars($e['title'], 35, '..') ?></a><?php if ($e['sign_num'] > 0): ?>&nbsp;&nbsp;(已有<font style="color:#1F9800"><?= $e['sign_num'] ?></font>人参与)<?php endif; ?>
                    <span class="date">
                        <?php if (time() >= strtotime($e['start']) AND time() <= strtotime($e['finish'])): ?>
                            进行中
                        <?php elseif (time() <= strtotime($e['start'])): ?>
                            <?= Date::span_str(strtotime($e['start'])) ?>后
                        <?php else: ?>
                            <?= Date::span_str(strtotime($e['start'])) ?>前
                        <?php endif; ?>&nbsp;
                    </span>
                </li>
            <?php endforeach; ?>
        </ul>
        <!--//event -->
    <?php endif; ?>

    <?php if ($_THEME['bbsunit_limit'] > 0): ?>
        <!--bbs -->
        <div class="big_title"><p class="name">最新话题</p>
            <p class="tool"><a href="<?= URL::site('bbs/unitForm?aa_id=' . $_ID) ?>" class="postlink link2">发帖</a> |  <?php if (($_MEMBER && $_MEMBER['manager'])): ?><a href="<?= URL::site('bbs/list?aid=' . $_ID) ?>" class="link2">管理</a><?php else: ?><a href="<?= URL::site('bbs/list?aid=' . $_ID) ?>" class="link2">更多</a><?php endif; ?></p></div>

        <?php if (!$units): ?>
            <div class="nodata">暂时还没有话题。</div>
        <?php else: ?>

            <table width="100%" class="hp_table" id="bbs_table" cellspacing="0" cellpadding="0">
                <tr>
                    <th style="text-align:center;width:20px">&nbsp;&nbsp;</th>
                    <th style="text-align:left;width:350px;">标题</th>
                    <th style="text-align:center;">作者</th>
                    <th style="text-align:center;">回复/点击</th>
                    <th style="text-align:center;">最后评论</th>
                </tr>
                <?php foreach ($units as $un): ?>
                    <tr>
                        <td style="height: 34px;">
                            <?php if ($un['is_fixed']): ?>
                                <a href="<?= URL::site('bbs/viewPost?id=' . $un['id']) ?>" target="_blank" title="新窗口打开 置顶话题"><img src="/static/ico/is_fixed.gif"  /></a>
                            <?php elseif (strtotime(date('Y-m-d H:i:s')) - strtotime($un['comment_at']) <= 86400 OR strtotime(date('Y-m-d H:i:s')) - strtotime($un['create_at']) <= 86400): ?>
                                <a href="<?= URL::site('bbs/viewPost?id=' . $un['id']) ?>" target="_blank" title="新窗口打开  新帖或新回复"><img src="/static/ico/folder_new.gif"  border="0" align="absmiddle"  /></a>
                            <?php else: ?>
                                <a href="<?= URL::site('bbs/viewPost?id=' . $un['id']) ?>" target="_blank" title="新窗口打开"><img src="/static/ico/folder_common.gif" /></a>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?= URL::site('bbs/viewPost?id=' . $un['id']) ?>" style="<?= $un['is_fixed'] ? 'color:#f30' : ''; ?>" title="<?= $un['title'] ?>" target="_blank">
                                <?= Text::limit_chars($un['title'], 25, '...') ?>
                            </a>
                            <?php if ($un['is_good']): ?>&nbsp;<img src="/static/ico/recommend_1.gif"  border="0"  title="推荐帖子"/><?php endif; ?>
                            <?php if ($un['reply_num'] > 10): ?>&nbsp;<img src="/static/ico/hot_1.gif" title="热门话题"><?php endif; ?>

                        </td>
                        <td style="text-align:center;"><a href="<?= URL::site('user_home?id=' . $un['user_id']) ?>" title="浏览该主页" target="_blank"><?= $un['realname'] ?></a>
                            <br><span style="color:#999"><?= date('Y-n-d', strtotime($un['create_at'])); ?></span></td>
                        <td style="text-align:center;">
                            <font style="color:#006600"><?= $un['reply_num'] ?></font>&nbsp;<font style="color:#999">/</font>&nbsp;<?= $un['hit'] ?></span>
                        </td>
                        <td style="text-align:center;color:#666">&nbsp;&nbsp;<?= $un['comment_at'] ? Date::span_str(strtotime($un['comment_at'])) : Date::span_str(strtotime($un['create_at'])) ?>前</td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
        <!--//bbs -->
    <?php endif; ?>


    <!--photo -->
    <div class="big_title"><p class="name">最近更新相册</p>
        <p class="tool"><a href="/aa_home/album?id=<?= $_ID ?>">更多</a></p>
    </div>

    <?php if (!$albums): ?>
        <div class="nodata">暂时还没有照片。</div>
    <?php else: ?>
        <div class="photo_list">
            <?php foreach ($albums as $a): ?>
                <div class="pbox">
                    <a href="<?= URL::site('album/picIndex?id=' . $a['id']) ?>" target="_blank" title="<?= $a['name'] ?>"><img src="<?= URL::base() . $a['img_path'] ?>" style="height:90px" /></a>
                    <br /><a href="<?= URL::site('album/picIndex?id=' . $a['id']) ?>" title="<?= $a['name'] ?>"><?= Text::limit_chars($a['name'], 10, '..') ?></a>
                </div>
            <?php endforeach; ?><div class="clear"></div>
        </div>
    <?php endif; ?>

    <!--//photo -->

</div>

<div id="main_right">
    <?= View::factory('weibo/userbox'); ?>
    <p class="column_tt">本会介绍</p>
    <div class="aa_block">
        <ul class="aa_conlist">
            <li><a href="<?= URL::site('aa_home/info?id=' . $_ID) ?>"><?= $_AA['name'] ?>简介</a></li>
            <?php foreach ($infos as $inf): ?>
                <li><a href="<?= URL::site('aa_home/info?id=' . $_ID . '&info_id=' . $inf['id']) ?>"><?= Text::limit_chars($inf['title'], 13, '..') ?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>
        <p class="column_tt">最近访问</p>
        <div class="aa_block">
            <?php foreach ($visit_members as $v): ?>
                <div class="ubox">
                    <a href="<?= URL::site('user_home?id=' . $v['user_id']) ?>" title="点击进入主页" target="_blank"><?= View::factory('inc/user/avatar', array('id' => $v['user_id'], 'size' => 48, 'sex' => $v['sex'])) ?></a>
                    <p class="face_name"><a href="<?= URL::site('user_home?id=' . $v['user_id']) ?>"><?= $v['realname'] ?></a><br>
                        <span><?= Date::span_str(strtotime($v['visit_at']) + 1) ?>前</span></p>
                </div>
            <?php endforeach; ?>
            <div class='clear'></div>
        </div>

    <!--link -->
    <?php if (@count($logo) > 0): ?>
        <p class="column_tt">赞助及鸣谢</p>
        <div style="padding:10px; ">
            <?php foreach ($logo as $lg): ?>
                <div style=" float:left;width:92px;border:1px solid #eee; margin-right: 5px; margin-bottom: 5px">
                    <a href="<?= $lg['url'] ?>" title="<?= $lg['title'] ?>" target="_blank"><img src="<?= $lg['filename'] ?>" alt="<?= $lg['title'] ?>" style="width:92px;height:40px"/>
                    </a>
                </div>
            <?php endforeach; ?>
            <div class="clear"></div>
        </div>
    <?php endif; ?>
    <!--contacts -->
    <p class="column_tt">联系我们</p>
    <div class="aa_block">
        <?php if (!$_AA['tel']): ?>
            <p class="nodata" style="padding:0px 5px">暂无信息</p>
        <?php endif; ?>
        <ul class="home_contacts">
            <?php
            $fields = array('contacts' => '联系人：', 'tel' => '电话：', 'fax' => '传真：', 'email' => '邮件：', 'website' => '网站：', 'address' => '地址：');
            foreach ($fields as $k => $v):
                if ($_AA[$k]):
                    ?>
                    <li><?= $v ?><?= $_AA[$k] ?></li>
                    <?php
                endif;
            endforeach;
            ?>
        </ul>
    </div>
    <!--//contacts -->
</div><!--//main_right -->
<div class="clear"></div>