<!-- club_home/index:_body -->

<div id="aa_home_left">

        <!-- club event list -->
        <?php if ($event): ?>
                <div class="aa_home_title">
                        <span class="title_name">近期活动...</span>
                        <span class="more_link">
                                <img src="<?= URL::base() . 'static/images/+.gif' ?>" />
                                <a href="<?= URL::site('/event/form?aa=' . $_ID . '&club=' . $_CLUB_ID) ?>" >发起活动</a> |
                                <a href="<?= URL::site('club_home/event') . URL::query() ?>">更多....</a>
                        </span>
                </div>



                <ul class="con_list">
                        <?php foreach ($event as $e): ?>
                                <li>
                                        &nbsp;&nbsp;<a href="<?= URL::site('event/view?id=' . $e['id']) ?>"><?= $e['title'] ?></a>
                                        &nbsp;&nbsp;(已有<font style="color:#1F9800"><?= $e['sign_num'] ?></font>人参与)
                                        <span class="date">
                                                <?php if (time() >= strtotime($e['start']) AND time() <= strtotime($e['finish'])): ?>
                                                        进行中
                                                <?php elseif (time() <= strtotime($e['start'])): ?>
                                                        <?= Date::span_str(strtotime($e['start'])) ?>后
                                                <?php else: ?>
                                                        <?= Date::span_str(strtotime($e['start'])) ?>前
                                                <?php endif; ?>
                                        </span>
                                </li>
                        <?php endforeach; ?>
                </ul>
        <?php endif; ?>
        <!-- //club event list -->


        <!-- bbs unit list -->
        <div class="aa_home_title">
                <span class="title_name">最新话题...</span>
                <span class="more_link">
                        <img src="<?= URL::base() . 'static/images/+.gif' ?>" />
                        <a href="<?= URL::site('bbs/unitForm?club_id=' . $_CLUB_ID) ?>">发布新帖</a> |
                        <a href="<?= URL::site('club_home/bbs') . URL::query() ?>">更多....</a>
                </span>
        </div>

        <?php if (!$units): ?>
                <div class="nodata">暂时还没有话题。</div>
        <?php else: ?>

                <table width="100%" class="aa_table" id="bbs_table" cellspacing="0" cellpadding="0">
                        <tr>
                                <th style="text-align:center;width:20px">&nbsp;&nbsp;</th>
                                <th style="text-align:left;width:350px;">标题</th>
                                <th style="text-align:center;">作者</th>
                                <th style="text-align:center;">回复/点击</th>
                                <th style="text-align:center;">最后评论</th>
                        </tr>
                        <?php foreach ($units as $un): ?>
                                <tr>
                                        <td style="height: 35px;">
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
                                        <td style="text-align:center;color:#666"><?= $un['comment_at'] ? Date::span_str(strtotime($un['comment_at'])) : Date::span_str(strtotime($un['create_at'])) ?>前</td>
                                </tr>
                        <?php endforeach; ?>
                </table>
        <?php endif; ?>

        <!-- //bbs unit list -->

        <!-- club event list -->
        <?php if (!$event): ?>
                <div class="aa_home_title">
                        <span class="title_name">近期活动...</span>
                        <span class="more_link">
                                <img src="<?= URL::base() . 'static/images/+.gif' ?>" />
                                <a href="<?= URL::site('/event/form?aa=' . $_ID . '&club=' . $_CLUB_ID) ?>" >发起活动</a> |
                                <a href="<?= URL::site('club_home/event') . URL::query() ?>">更多....</a>
                        </span>
                </div>


                <div class="nodata">暂时还没有活动信息。</div>
        <?php endif; ?>


        <!-- //club event list -->

        <!-- club photo -->
        <div class="aa_home_title">
                <span class="title_name">俱乐部相册...</span>
                <span class="more_link"><a href="<?= URL::site('club_home/album') . URL::query() ?>">更多....</a>
                </span>
        </div>

        <?php if (!$photo): ?>
                <div class="nodata">暂时还没有照片。</div>
        <?php endif; ?>
        <div class="photo_list">
                <?php foreach ($photo as $p): ?>
                        <div class="pbox">
                                <a href="<?= URL::site('album/picView?id=' . $p['id']) ?>"><img src="<?= URL::base() . $p['img_path'] ?>"  style="width:130px"/></a>
                                <br />
                                <a href="<?= URL::site('album/picView?id=' . $p['id']) ?>"><?= Text::limit_chars($p['Album']['name'] , 10, '..') ?></a>
                        </div>
                <?php endforeach; ?><div class="clear"></div>
        </div>
        <!-- //club photo -->


</div>

<div id="aa_home_right">
        <!-- manager -->
        <p class="column_tt">管理员...</p>
        <div class="aa_block">
                <?php if (!$manager): ?>
                        <p class="nodata">暂无管理员</p>
                <?php endif; ?>
                <?php foreach ($manager as $m): ?>
                        <div class="ubox">
                                <a href="<?= URL::site('user_home?id=' . $m['user_id']) ?>"><?= View::factory('inc/user/avatar', array('id' => $m['user_id'], 'size' => 48,'sex'=>$m['sex'])) ?></a>
                                <a href="<?= URL::site('user_home?id=' . $m['user_id']) ?>"><?= $m['realname'] ?></a>
                        </div>
                <?php endforeach; ?>
                <div class='clear'></div>
        </div>

        <!-- members join -->
        <p class="column_tt">最近加入的...</p>
        <div class="aa_block">
                <?php if (!$member): ?><p class="nodata">暂无校友</p><?php endif; ?>
                <?php foreach ($member as $m): ?>
                        <div class="ubox">
                                <a href="<?= URL::site('user_home?id=' . $m['user_id']) ?>"><?= View::factory('inc/user/avatar', array('id' => $m['user_id'], 'size' => 48,'sex'=>$m['sex'])) ?></a>
                                <a href="<?= URL::site('user_home?id=' . $m['user_id']) ?>"><?= $m['realname'] ?></a>
                                <br>
                                <span><?= Date::span_str(strtotime($m['join_at']) + 1) ?>前</span>
                        </div>
<?php endforeach; ?>
                <div class='clear'></div>
        </div>
        <!-- member visit -->
        <p class="column_tt">最近访问的...</p>
        <div class="aa_block">
<?php if (!$visitor): ?><p class="nodata">暂无记录</p><?php endif; ?>
                <?php foreach ($visitor as $v): ?>
                        <div class="ubox"><a href="<?= URL::site('user_home?id=' . $v['user_id']) ?>">
                        <?= View::factory('inc/user/avatar', array('id' => $v['user_id'], 'size' => 48,'sex'=>$v['sex'])) ?></a>
                                <a href="<?= URL::site('user_home?id=' . $v['user_id']) ?>"><?= $v['realname'] ?></a><br>
                                <span><?= Date::span_str(strtotime($v['visit_at']) + 1) ?>前</span>
                        </div>
<?php endforeach; ?>
                <div class='clear'></div>
        </div>
</div>