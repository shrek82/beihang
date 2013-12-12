<div style="margin:0px 0px 15px 5px;font-family: verdana;color:#999">
    <?php if ($aa_id > 0): ?><a href="<?= URL::site('aa_home?id=' . $aa_id) ?>" title="返回校友会主页"><?= $aa_info['sname'] ?>校友会</a><?php else: ?><a href="<?= URL::site('aa') ?>" title="返回校友总会会主页">校友总会</a><?php endif; ?> &gt; <a href="<?= URL::site('bbs/list?aid=' . $aa_id) ?>">交流园地</a> <?php if ($bbs_info): ?><em>&gt;</em> <a href="<?= URL::site('bbs?aa_id=' . $aa_id . '&b=' . $bbs_info['id']) ?>"><?= $bbs_info['name'] ?></a><?php endif; ?>:
</div>

<?php
$subject = Kohana::config('bbs.subject');
$params = http_build_query($params, '', '&');
?>

<div class="blue_tab">
    <ul style="left:10px">
        <li ><a href="<?= URL::site('bbs/list') ?>" class="<?= !isset($_GET['aid']) ? 'cur' : '' ?>">全部校友会</a></li>
        <li ><a href="<?= URL::site('bbs/list?aid=0') ?>"  class="<?= isset($_GET['aid']) && $aa_id == 0 ? 'cur' : ''; ?>">校友总会</a></li>
        <?php if (isset($signed_aa)): ?>
            <?php foreach ($signed_aa as $a): ?>
                <li ><a class="<?= $aa_id == $a['aa_id'] ? 'cur' : '' ?>" href="<?= URL::site('bbs/list?aid=' . $a['aa_id']) ?>" ><?= $a['sname'] ?>校友会</a></li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
</div>

<!-- 当前校友会下属版块 -->
<div id="bbs_links" >
    <?php if ($aa_id === '0' OR $aa_id > 0): ?>
        <!-- 总会和地方校友会 -->
        <span style="color:#333">筛选：</span>
        <?php if ($aa_all_bbs): ?>
            <a href="<?= URL::site('bbs/list?aid=' . $aa_id); ?>" class="<?= (empty($bbs_id) && empty($club_id)) ? 'cur' : ''; ?>">全部主题</a>&nbsp;&nbsp;|&nbsp;&nbsp;
            <?php foreach ($aa_all_bbs as $key => $b): ?>
                <? $quick_bbs_id = $key == 0 ? $b['id'] : $quick_bbs_id; ?>
                <?php if ($b['club_id'] == $club_id): ?>
                    <a href="<?= URL::site('bbs/list?aid=' . $aa_id . '&cid=' . $b['club_id'] . '&bid=' . $b['id']) ?>" class="<?= ($b['id'] == $bbs_id || $b['club_id'] == $club_id) ? 'cur' : ''; ?>"><?= $b['club_name'] ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;
                <?php elseif (!$b['club_id']): ?>
                    <a href="<?= URL::site('bbs/list?aid=' . $aa_id . '&bid=' . $b['id']) ?>" class="<?= ($b['id'] == $bbs_id || $b['club_id'] == $club_id) ? 'cur' : ''; ?>"><?= $b['name'] ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;
                <?php endif; ?>
            <?php endforeach; ?>
            <? $quick_bbs_id = $bbs_id ? $bbs_id : $quick_bbs_id; ?>
        <?php else: ?>
            <span style="color:#999">暂时还没有版块</span>

        <?php endif; ?>

        <?php if ($aa_id > 0): ?>
            <a href="/bbs/list?aid=<?= $aa_id ?>&show=event" style="color:#f30">校友活动</a>
        <?php endif; ?>
        <!-- //总会地方校友会 -->
    <?php else: ?>
        <!-- 默认公共交流区 -->
        <span style="color:#333">筛选：</span>
        <a href="<?= URL::site('bbs/list') ?>" style="color:#333;font-weight: bold">全部主题</a>&nbsp;&nbsp;|&nbsp;&nbsp;
        <?php if ($_UID): ?>
            <a href="<?= URL::site('bbs/list?show=mytopic') ?>" style="color:#3366CC;font-weight: bold" title="我发布的话题">我的主题</a>&nbsp;&nbsp;|&nbsp;&nbsp;
            <a href="<?= URL::site('bbs/list?show=mycomment') ?>" style="color:#6C39D8;font-weight: bold" title="我回复过的话题">我回复过的</a>&nbsp;&nbsp;|&nbsp;&nbsp;
        <?php endif; ?>
        <a href="<?= URL::site('bbs/list?show=today') ?>" style="color:#01478F;font-weight: bold" title="今日新发布主题">今日</a>&nbsp;&nbsp;|&nbsp;&nbsp;
        <a href="<?= URL::site('bbs/list?show=week') ?>" style="color:#2C6408;font-weight: bold" title="一周内发布的主题">一周内</a>&nbsp;&nbsp;|&nbsp;&nbsp;
        <a href="<?= URL::site('bbs/list?show=good') ?>" style="color:#f60;font-weight: bold" title="各地推荐主题">推荐</a>&nbsp;&nbsp;|&nbsp;&nbsp;
        <a href="<?= URL::site('bbs/list?show=event') ?>" style="color:#f30;font-weight: bold" title="各地活动主题">活动</a>&nbsp;&nbsp;|&nbsp;&nbsp;

    <?php endif; ?>
    <!-- //公共交流区 -->
</div>
<!--end-->

<div style="margin:15px 0;height: 30px">
    <div style="width:800px;float: left;right:2px;"><?= $pager ?></div>
    <div style="width:100px;float: right;text-align: right;padding-right: 20px"><input type="button" class="unit_post" onclick="window.location.href = '<?= URL::site('bbs/unitForm?aa_id=' . $aa_id . '&club_id=' . @$club_id . '&b=' . $bbs_id) ?>'" value="发布新帖"></div>
</div>

<table width="100%" id="bbs_table" border="0"  cellpadding="0" cellspacing="0" style="margin-top:10px;">
    <thead>
        <tr>
            <th style="text-align:left;padding:0px 10px;width:470px"><span><?= $fixed_units ? '置顶话题' : '最新话题'; ?></span></th>
            <th style="width:100px; text-align: center"><span>作者</span></th>
            <th style="width:100px;text-align: center"><span>评论 / 点击</span></th>
            <th style="width:100px;text-align: center"><span>最后回复</span></th>
        </tr>
    </thead>
    <tbody>
        <?php if ($fixed_units): ?>
            <?php foreach ($fixed_units as $i => $u): ?>
                <tr <?= $i % 2 == 1 ? 'style="background-color:#F9F9F9"' : null ?>>
                    <td style="font-size: 1.1em; color:#999; text-align: left;"> &nbsp;
                        <span style="vertical-align: 5px">
                            <a href="<?= URL::site('bbs/view' . $u['type'] . '?id=' . $u['id']) ?>" target="_blank"><img src="/static/images/pin_3.gif" border="0" align="absmiddle"  title="新窗口打开 置顶帖子" /></a></span>&nbsp;
                        <span class="bbs_aa_info">
                            <?php if (!isset($_GET['aid'])): ?>
                                <?php if ($u['aa_id']): ?>
                                    [<a href="<?= URL::site('bbs/list?aid=' . $u['aa_id']) ?>" title="浏览<?= $u['aa_id'] ? $u['aa_sname'] . '校友会' : '公共园地'; ?>所有主题" class="aa_link"><?= isset($u['aa_id']) ? $u['aa_sname'] : '公共'; ?></a>]
                                <?php else: ?>
                                    [<a href="<?= URL::site('bbs/list?aid=0') ?>" title="浏览所有公共主题"  class="aa_link">公共</a>]
                                <?php endif; ?>
                            <?php else: ?>[<a href="/bbs/list?sid=<?= $u['subject'] ?>&<?= $params ?>" style="color:#888888"><?= isset($subject[$u['subject']]) ? $subject[$u['subject']] : '话题'; ?></a>]<?php endif; ?>
                        </span>

                        <a href="<?= URL::site('bbs/view' . $u['type'] . '?id=' . $u['id']) ?>" class="title" <? if ($u['is_fixed'] OR $u['is_good']): ?>style="font-weight:bold;color:<?= $u['title_color'] ? $u['title_color'] : '#f30'; ?>"<?php else: ?> <?php if (!empty($u['title_color'])): ?>style="font-weight:bold;color:<?= $u['title_color'] ?>"<?php endif; ?><?php endif; ?>   ><?= Text::limit_chars($u['title'], 30, '...') ?></a>
                        <?= $u['is_pic'] ? '&nbsp;<font><img src="/static/ico/image_s.gif" title="包含图片" class="middle"></font>' : ''; ?>
                        <?php if (isset($u['Vote'])): ?>
                            <img src="/static/ico/vote.gif"  border="0" class="middle" title="投票帖子"/>
                        <?php endif; ?>
                        <?php if ($u['reply_num'] >= 15): ?>
                            <img src="/static/ico/hot_1.gif"  border="0" class="middle" title="热门帖子"/>
                        <?php endif; ?>
                        <?php if ($u['is_good']): ?>
                            <img src="/static/ico/recommend_1.gif"  border="0" class="middle" title="推荐帖子"/>
                        <?php endif; ?>
                        <span class="commmet">
                            <?= commentPage($u['id'], $u['reply_num']); ?>
                        </span>
                    </td>
                    <td class="center"><a href="<?= URL::site('user_home?id=' . $u['user_id']) ?>" ><?= $u['realname'] ?></a><br><span style="color:#777;font-size: 11px"><?= date('m-d H:i', strtotime($u['create_at'])); ?></span></td>
                    <td class="center"><span style="color:green"><?= $u['reply_num'] ?></span>/<?= $u['hit'] ?></td>
                    <td class="center" style="color:#777;font-size: 11px">
                        <?php if ($u['new_replyid']): ?>
                            <a href="<?= URL::site('user_home?id=' . $u['new_replyid']) ?>" ><?= $u['replyname'] ?></a><br>
                            <?= Date::ueTime($u['comment_at']); ?>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>

        <?php if ($fixed_units): ?>
            <tr>
                <th colspan="4" style="text-align:left;padding-left: 12px;border-top-width: 0"><span>最新话题</span></th>
            </tr>
        <?php endif; ?>
        <?php foreach ($units as $i => $u): ?>
            <tr style="<?= $i % 2 == 1 ? 'background-color:#F9F9F9' : '' ?>">
                <td style="font-size: 1.1em; color:#999; text-align: left;"> &nbsp;
                    <span style="vertical-align: 5px">
                        <a href="<?= URL::site('bbs/view' . $u['type'] . '?id=' . $u['id']) ?>" target="_blank"><?php if (strtotime(date('Y-m-d H:i:s')) - strtotime($u['comment_at']) <= 86400 OR strtotime(date('Y-m-d H:i:s')) - strtotime($u['create_at']) <= 86400): ?><img src="/static/ico/folder_new.gif"  border="0" align="absmiddle"  title="新窗口打开  新帖或有新回复"/><?php else: ?><img src="/static/images/topicnew.gif"  border="0" align="absmiddle"  title="新窗口打开"/><?php endif; ?></a></span>&nbsp;
                    <span class="bbs_aa_info">
                        <?php if (!isset($_GET['aid'])): ?>
                            <?php if ($u['aa_id']): ?>
                                [<a href="<?= URL::site('bbs/list?aid=' . $u['aa_id']) ?>" title="浏览<?= $u['aa_id'] ? $u['aa_sname'] . '校友会' : '公共园地'; ?>所有主题" class="aa_link"><?= isset($u['aa_id']) ? $u['aa_sname'] : '公共'; ?></a>]
                            <?php else: ?>
                                [<a href="<?= URL::site('bbs/list?aid=0') ?>" title="浏览所有公共主题"  class="aa_link">公共</a>]
                            <?php endif; ?>
                        <?php else: ?>[<a href="/bbs/list?sid=<?= $u['subject'] ?>&<?= $params ?>" style="color:#888888"><?= isset($subject[$u['subject']]) ? $subject[$u['subject']] : '话题'; ?></a>]<?php endif; ?>
                    </span>

                    <a href="<?= URL::site('bbs/view' . $u['type'] . '?id=' . $u['id']) ?>" class="title" <? if ($u['is_fixed'] OR $u['is_good']): ?>style="font-weight:bold;color:<?= $u['title_color'] ? $u['title_color'] : '#f30'; ?>"<?php else: ?> <?php if (!empty($u['title_color'])): ?>style="font-weight:bold;color:<?= $u['title_color'] ?>"<?php endif; ?><?php endif; ?>   ><?= Text::limit_chars($u['title'], 30, '...') ?></a>
                    <?= $u['is_pic'] ? '&nbsp;<font><img src="/static/ico/image_s.gif" title="包含图片" class="middle"></font>' : ''; ?>
                    <?php if (isset($u['Vote'])): ?>
                        <img src="/static/ico/vote.gif"  border="0" class="middle" title="投票帖子"/>
                    <?php endif; ?>
                    <?php if ($u['reply_num'] >= 15): ?>
                        <img src="/static/ico/hot_1.gif"  border="0" class="middle" title="热门帖子"/>
                    <?php endif; ?>
                    <?php if ($u['is_good']): ?>
                        <img src="/static/ico/recommend_1.gif"  border="0" class="middle" title="推荐帖子"/>
                    <?php endif; ?>
                    <span class="commmet">
                        <?= commentPage($u['id'], $u['reply_num']); ?>
                    </span>

                </td>
                <td class="center"><a href="<?= URL::site('user_home?id=' . $u['user_id']) ?>" ><?= $u['realname'] ?></a><br><span style="color:#777;font-size: 11px"><?= date('m-d H:i', strtotime($u['create_at'])); ?></span></td>
                <td class="center"><span style="color:green"><?= $u['reply_num'] ?></span>/<?= $u['hit'] ?></td>
                <td class="center" style="color:#777;font-size: 11px">
                    <?php if ($u['new_replyid']): ?>
                        <a href="<?= URL::site('user_home?id=' . $u['new_replyid']) ?>" ><?= $u['replyname'] ?></a><br>
                        <?= Date::ueTime($u['comment_at']); ?>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>

<?php if(!count($units)):?>
    <tr>
        <td colspan="4" style="height:50px;color: #666;">
            暂时还没有话题
        </td>
    </tr>
    <?php endif;?>

    </tbody>
</table>

<?php if (count($units) > 10): ?>
    <div style="text-align:right; padding-right:5px; margin:25px 20px 0px 0;">
        <input type="button" class="unit_post" onclick="window.location.href = '<?= URL::site('bbs/unitForm?aa_id=' . $aa_id . '&club_id=' . @$club_id . '&b=' . $bbs_id) ?>'" value="发布新帖">
    </div>
<?php endif; ?>

<div style="margin: -55px 10px 0 0;height:60px;padding:0px"><?= $pager ?></div>
<?php if ($_UID): ?>
    <div style="border:1px solid #DFECF5; clear: both">
        <p style="background:#E7F1FD;padding:5px 10px;font-size:12px; color: #333;font-weight: bold;margin-top:1px;margin-left: 1px">快速发帖：</p>
        <form method="post" action="<?= URL::site('bbs/unitPost') ?>" id="bbs_unit_form" style="margin:6px 15px">
            <input type="hidden" name="bbs_id" value="<?= isset($quick_bbs_id) ? $quick_bbs_id : 2; ?>">
            <input type="hidden" name="hidden" value="">
            <input type="hidden" name="id" value="">
            <input type="hidden" name="quick" value="yes">
            <table >
                <tr>
                    <td style="width:60px; text-align: right">标题：</td>
                    <td><input type="text" name="title" style="width:600px;font-size:14px; height:22px; padding: 2px 4px" class="input_text" /></td>
                </tr>
                <tr>
                    <td valign="top" style="width:60px; text-align: right">内容：</td>
                    <td><textarea name="content" id="content" style="width:700px;height:100px;font-size:14px;line-height: 1.6em" class="input_text"></textarea></td>
                </tr>
                <tr>
                    <td ></td>
                    <td><input type="button" id="submit_button"   value="发布帖子"   onclick="unitSave()" class="button_blue" /></td>
                </tr>
            </table>
        </form>
    </div>
    <script type="text/javascript">
        function unitSave() {
            var unit_form = new ajaxForm('bbs_unit_form', {callback: function(id) {
                    location.href = '<?= URL::site('bbs/viewPost?id=') ?>' + id
                }});
            unit_form.send();
        }
    </script>
<?php endif; ?>
<?php

function commentPage($id, $reply_num) {
    $pages = ceil($reply_num / 20);
    if ($pages >= 15) {
        $links = '...';
        for ($i = 1; $i <= 15; $i++) {
            $links.='<a href="' . URL::site('bbs/viewPost?id=' . $id . '&page=' . $i) . '#comment">' . $i . '</a>';
        }
        $links.='...<a href="' . URL::site('bbs/viewPost?id=' . $id . '&page=' . $pages) . '#comment">' . $pages . '</a>';
    } elseif ($pages >= 2) {
        $links = '...';
        for ($i = 1; $i <= $pages; $i++) {
            $links.='<a href="' . URL::site('bbs/viewPost?id=' . $id . '&page=' . $i) . '#comment">' . $i . '</a>';
        }
    } else {
        return false;
    }
    return $links;
}
?>
<script type="text/javascript">
    function gotopage(goto_page) {
        var url = document.getElementById('goto_url').value;
        var page = document.getElementById(goto_page).value;
        if (page > 0) {
            window.location.href = url + 'page=' + page;
        }
        else {
            document.getElementById(goto_page).focus();
            alert('请在输入页码后跳转，谢谢!');
        }
    }

    (function() {
        $('table tbody tr').hover(function() {
            $(this).addClass("highlight");
        }, function() {
            $(this).removeClass("highlight");
        });
    })();
</script>


