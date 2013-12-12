<!-- aa_home/index:_body -->
<div id="main_left" style="width: 680px;">

    <?php if ($_THEME['banner_limit'] > 0): ?>
        <!-- banner -->
        <div id="banner_pics">
            <?php $view['banner'] = $banner; ?>
            <?= View::factory('aa_home/banner', $view); ?>
        </div>
    <?php endif; ?>

    <!--photo -->
    <div class="big_title"><p class="name">班级介绍</p></div>
    <div>
        <?php if (!$_CLASSROOM['intro']): ?>
            <span class="nodata">暂时还没有介绍。</span>
        <? else: ?>
            <?= $_CLASSROOM['intro'] ?>
        <?php endif; ?>

    </div>

    <!--bbs -->
    <div class="big_title"><p class="name">最新话题</p>
        <p class="tool"><a href="<?= URL::site('classroom_home/bbsPost?id=' . $_ID) ?>" class="postlink link2">发帖</a></p></div>

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
                            <a href="<?= URL::site('classroom_home/bbsUnit?id=' . $_CLASSROOM['id'] . '&unit_id=' . $un['id']) ?>" target="_blank" title="新窗口打开  新帖或新回复"><img src="/static/ico/folder_new.gif"  border="0" align="absmiddle"  /></a>
                        <?php else: ?>
                            <a href="<?= URL::site('classroom_home/bbsUnit?id=' . $_CLASSROOM['id'] . '&unit_id=' . $un['id']) ?>" target="_blank" title="新窗口打开"><img src="/static/ico/folder_common.gif" /></a>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="<?= URL::site('classroom_home/bbsUnit?id=' . $_CLASSROOM['id'] . '&unit_id=' . $un['id']) ?>" style="<?= $un['is_fixed'] ? 'color:#f30' : ''; ?>" title="<?= $un['title'] ?>" target="_blank">
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



    <!--photo -->
    <div class="big_title"><p class="name">最近同学照片</p>
        <p class="tool"><a href="/classroom_home/album?id=<?= $_ID ?>">更多</a></p>
    </div>

    <?php if (!$photos): ?>
        <div class="nodata">暂时还没有上传任何照片，<a href="<?= URL::site('user_album') ?>">现在就上传照片</a></div>
    <?php endif; ?>
    <div class="photo_list">
        <?php foreach ($photos as $key => $p): ?>
            <div class="pbox">
                <a href="<?= URL::site('album/picIndex?id=' . $p['album_id']) ?>" target="_blank"><img src="<?= URL::base() . $p['img_path'] ?>"  style="height:90px" /></a>
                <br />
                <a href="<?= URL::site('user_home?id=' . $p['user_id']) ?>"><?= $p['realname'] ?></a>

                <span style="color:#999"><?= Date::span_str(strtotime($p['upload_at'])) ?>前</span>
            </div>
            <?php if (($key + 1) % 4 == 0): ?>
                <div class="clear"></div>
            <?php endif; ?>
        <?php endforeach; ?><div class="clear"></div>
    </div>
    <!--//photo -->

</div>

<div id="main_right">

<?= View::factory('classroom_home/userbox'); ?>

    <?php if ($_CLASSROOM['notice']): ?>
        <p class="column_tt">班级公告</p>
        <div style="padding:0px 15px"><?= $_CLASSROOM['notice'] ?></div>
    <?php endif; ?>

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

    <?php if(count($managers)>0):?>
    <p class="column_tt">管理员</p>
    <div class="aa_block">
        <?php foreach ($managers as $v): ?>
            <div class="ubox">
                <a href="<?= URL::site('user_home?id=' . $v['user_id']) ?>" title="点击进入主页" target="_blank"><?= View::factory('inc/user/avatar', array('id' => $v['user_id'], 'size' => 48, 'sex' => $v['sex'])) ?></a>
                <p class="face_name"><a href="<?= URL::site('user_home?id=' . $v['user_id']) ?>"><?= $v['realname'] ?></a><br>
                    <span><?= Date::span_str(strtotime($v['visit_at']) + 1) ?>前</span></p>
            </div>
        <?php endforeach; ?>
        <div class='clear'></div>
    </div>
    <?php endif;?>

</div><!--//main_right -->
<div class="clear"></div>

