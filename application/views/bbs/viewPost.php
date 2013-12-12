<?php
//热心度
$user_point = $unit['User']['point'];
$div_point = Common_Point::divPoint();
$user_temp = Common_Point::getTemp($div_point, $user_point);
?>
<div style="color:#666;margin:5px 0">
    您的位置：
    <?php
    if ($unit['Bbs']['aa_id'] > 0):
        $bbs_url = '/bbs/list?f=' . $unit['Bbs']['aa_id'] . '&b=' . $unit['Bbs']['id'];
        ?>
        <a href="<?= URL::site('aa_home?id=' . $unit['Bbs']['aa_id']) ?>"><?= $unit['Bbs']['Aa']['sname'] ?>校友会</a> &raquo;
        <a href="<?= URL::site('bbs/list?aid=' . $unit['Bbs']['aa_id']) ?>">交流园地</a> &raquo;

    <?php elseif ($unit['Bbs']['aa_id'] == 0): ?>
        <a href="<?= URL::site('aa') ?>">校友总会</a> &raquo;
        <a href="<?= URL::site('bbs/list?aid=0') ?>">交流园地</a> &raquo;
    <?php endif; ?>

    <?php
    if ($unit['Bbs']['club_id'] > 0):
        $bbs_url = 'club_home/bbs/list?aid=' . $unit['Bbs']['aa_id'] . '&cid=' . $unit['Bbs']['club_id'];
        ?>
        <a href="<?= URL::site('club_home?id=' . $unit['Bbs']['club_id']) ?>" target="_blank"><?= $unit['Bbs']['Club']['name'] ?></a> &raquo;
    <?php endif; ?>

    <?php
    if ($unit['Bbs']['aa_id'] > 0) {
        $bbs_url = 'bbs/list?aid=' . $unit['Bbs']['aa_id'] . '&bid=' . $unit['bbs_id'];
    } else {
        $bbs_url = 'bbs/list?aid=0&bid=' . $unit['bbs_id'];
    }
    ?>
    <?php if (!$unit['Bbs']['club_id']): ?>
        <a href="<?= URL::site($bbs_url) ?>"><?= $unit['Bbs']['name'] ?></a>
    <?php endif; ?>
</div>

<!--//当前位置 -->

<!--发布新帖及回复按钮 -->
<div class="bbs_post_reply_bar">
    <?php if(!$_SETTING['close_bbs_comment']) :?>
    <input type="button" class="comment_post" value="发布回帖" onclick="scrollTo('comment_form', 300)">&nbsp;&nbsp;&nbsp;&nbsp;
    <?php endif;?>
    <?php if(!$_SETTING['prohibit_posting']) :?>
    <input type="button" class="unit_post" onclick="window.location.href = '<?= URL::site('bbs/unitForm') . URL::query(array('aa_id' => $unit['Bbs']['aa_id'], 'club_id' => $unit['Bbs']['club_id'], 'b' => $unit['Bbs']['id'], 'id' => null)) ?>'" value="发布新帖">
    <?php endif;?>
</div>
<!--//发布新帖及回复按钮 -->

<!--主题 -->
<table border="0" cellspacing="0" cellpadding="0" class="unit_table" >
    <tr>
        <td colspan="2" class="topic_topbar"><?php if ($unit['event_id']): ?>活动<?php elseif ($vote): ?>投票<?php else: ?>主题<?php endif; ?>：<?= $unit['title'] ?></td>
    </tr>
    <tr>
        <td valign="top" class="unit_left_bg" rowspan="2">
            <div class="left_user_face">
                <img src="/static/images/online<?= $unit['online'] ? '1' : '0'; ?>.gif" style="vertical-align:middle" title="当前<?= $unit['online'] ? '在线' : '不在线'; ?>">
                <a href="<?= URL::site('user_home?id=' . $unit['user_id']) ?>" class="commentor"><?= $unit['User']['realname'] ?></a><br>
                <a href="<?= URL::site('user_home?id=' . $unit['user_id']) ?>" target="_blank">
                    <img src="<?= Model_User::avatar($unit['user_id'], 128, $unit['User']['sex']) . '?gen_at=' . time() ?>" class="face" title="点击进入该主页"></a>
            </div>

            <div class="left_user_info">

                地区&nbsp;<?= $unit['User']['city'] ? $unit['User']['city'] : '无'; ?><br>
                注册&nbsp;<?= date('Y-m-d', strtotime($unit['User']['reg_at'])); ?>
                <?php if ($unit['User']['start_year'] AND $unit['User']['speciality']): ?>
                    <br>专业&nbsp;<?= $unit['User']['start_year'] ?>级<?= $unit['User']['speciality'] ?>
                <?php endif; ?>
                <br>发帖&nbsp;<a href="<?= URL::site('search?for=bbs&user_id=' . $unit['user_id']) ?>" title="查看所有发帖" target="_blank" ><?= $unit['User']['bbs_unit_num'] ? $unit['User']['bbs_unit_num'] : '0'; ?></a>
                <br>积分&nbsp;<a href="<?= URL::site('user_point?id=' . $unit['user_id']) ?>" title="查看详情" target="_blank" ><?= $unit['User']['point'] ? $unit['User']['point'] : '0'; ?></a>
                <br>热心度&nbsp;<a href="<?= URL::site('user_point?id=' . $unit['user_id']) ?>" title="查看详情" target="_blank" ><?= $user_temp ?>°c</a>
                <br>
                <a href="javascript:;" onclick="sendMsg(<?= $unit['user_id'] ?>)"  title="发送站内信" >发消息&nbsp;<img src="/static/images/user/email.gif" style="vertical-align: middle"></a>
                <?php if ($unit['User']['homepage']): ?>&nbsp;<?= Common_Global::imgLink($unit['User']['homepage']) ?><?php endif; ?>

            </div>
        </td>
        <td valign="top" class="bbs_unit_content" style=" position: relative">

            <div class="topic_postdate">
                <p style="float:left"><span style="color:#f60; font-weight: bold">楼主</span>&nbsp;&nbsp;发布于<?= $unit['create_at'] ?></p>
                <p style="float:right">
                    <?php if ($reply): ?>
                        <a href="<?= URL::site('bbs/viewPost?id=' . $unit['id']) ?>" style="color:#999" >查看全部</a>
                    <?php else: ?>
                        <a href="<?= URL::site('bbs/viewPost?id=' . $unit['id']) ?>&reply=<?= $unit['user_id'] ?>" style="color:#999" title="只查看作者评论">只看该作者</a>
                    <?php endif; ?>
                </p>
            </div>

            <?php if ($unit['is_closed']): ?>
                <div style="color:#f60; background: #FAFAD6; border: 1px solid #F0EAAA; padding:5px 10px; margin-bottom:15px">提示：该贴已被屏蔽，目前只有管理员或作者可见:)</div>
            <?php endif; ?>

            <div class="comment_content" style="word-break:break-all">
                <?php if ($unit['event_id'] AND isset($event_data['event'])): ?>
                    <div >
                        <p style=" margin: 5px 0;font-weight: bold; height:30px; line-height: 30px; color: #CC0000; position: relative;font-family:'Microsoft YaHei','Microsoft JhengHei';font-size: 20px"><?= $event_data['event']['title'] ?></p>
                        <b>时间：</b><?php if (date('Y-m-d H:i', strtotime($event_data['event']['start'])) == date('Y-m-d H:i', strtotime($event_data['event']['start']))): ?><?= date('Y年m月d日', strtotime($event_data['event']['start'])) ?>&nbsp;<?= date('H:i', strtotime($event_data['event']['start'])) ?>~<?= date('H:i', strtotime($event_data['event']['finish'])) ?><?php else: ?><?= date('Y年m月d日 H:i', strtotime($event_data['event']['start'])) ?> 至 <?= date('m月d日 H:i', strtotime($event_data['event']['finish'])) ?><?php endif; ?><br>
                        <b>所属：</b><?php if ($event_data['event']['aa_id'] == '-1'): ?><a href="<?= URL::site('aa') ?>"><?= $_CONFIG->base['orgname'] ?></a><?php else: ?><a href="<?= URL::site('aa_home?id=' . $event_data['event']['aa_id']) ?>" title="进入校友会主页"><?= $event_data['aa']['name'] ?></a><?php endif; ?>
                        <?php if ($event_data['event']['club_id'] AND isset($event_data['event']['club'])): ?>
                            &raquo; <a href="<?= URL::site('club_home/?id=' . $event_data['event']['club_id']) ?>" target="_blank"><?= $event_data['event']['club']['name'] ?></a>
                        <?php endif; ?><br>
                        <b>地址：</b><?= $event_data['event']['address'] ?><br>
                        <b>人数：</b><?= $event_data['event']['sign_limit'] ? $event_data['event']['sign_limit'] . '人' : '不限'; ?><br>
                        <?php if (!empty($tags)): ?><b>标签：</b>
                            <?php foreach ($tags as $name): ?>
                                <a href="<?= URL::site('event?tag=' . urlencode($name)) ?>"><?= $name ?></a>&nbsp;&nbsp;
                            <?php endforeach; ?><br>
                        <?php endif; ?>
                        <b>状态：</b><?= Model_Event::status($event_data['event']) ?>
                        <?php if ($event_data['event']['votes'] > 0): ?><br><b>体验：</b><?php for ($i = 1; $i <= $event_data['event']['score']; $i++): ?><img src="/static/images/knewstuff.png" style="margin-right:2px;vertical-align: middle; margin: 0"><?php endfor; ?><?php for ($i = 1; $i <= 5 - $event_data['event']['score']; $i++): ?><img src="/static/images/knewstuff2.png" style="margin-right:2px;vertical-align: middle"><?php endfor; ?>&nbsp;&nbsp;<span style="color:#999"><?= $event_data['event']['votes'] ?>人评分</span><?php endif; ?>
                    </div><br>
                <?php endif; ?>

                <table border="0" id="temptable">
                    <tbody>
                        <tr>
                            <td id="lazyload">
                                <?php
                                //src="img/grey.gif"
                                //$content = preg_replace('/(<img[^>]+src\s*=\s*"?\/static\/upload\/([^>"\s]+)"?[^>]*>)/im', '<a href="/static/upload/$2?v=1" target="_blank"  class="colorboxPic" style="cursor:url(/static/big.cur),pointer">$1</a><br />', $unit['Post']['content']);
                                //延迟加载
                                $content = preg_replace('/(<img[^>]+src\s*=\s*"?\/static\/upload\/([^>"\s]+)"?[^>]*>)/im', '<a href="/static/upload/$2?v=1" target="_blank"  class="colorboxPic" style="cursor:url(/static/big.cur),pointer"><img src="/static/images/loading9.gif" data-src="/static/upload/$2"></a><br />', $unit['Post']['content']);
                                $content = preg_replace('/_bmiddle\.(jpg|gif|jpeg|png)\?v=1/i', '.$1', $content);
                                if ($vote) {
                                    if ($_UID AND !$vote['is_finish'] AND !$vote_user) {
                                        if ($vote['type'] == 'checkbox') {
                                            $content = preg_replace('/{option:(\d+)}/', '<input type="checkbox" name="unitvoteoption" onclick="selectOption($1)" id="unitOption_$1">', $content);
                                        } else {
                                            $content = preg_replace('/{option:(\d+)}/', '<input type="radio" name="unitvoteoption" onclick="selectOption($1)" id="unitOption_$1">', $content);
                                        }
                                    } else {
                                        $content = preg_replace('/{option:(\d+)}/', '', $content);
                                    }
                                }
                                echo Emotion::autoToUrl($content);
                                ?>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <?php if (isset($event_data['event'])): ?>
                    <?php if (count($event_data['signs']) > 0): ?>
                        <div style="padding:10px; background: #f6f6f6; color: #333">
                            <b>已报名(<?php if ($event_data['total_sign'] == count($event_data['signs'])): ?><?= $event_data['total_sign'] ?>人<?php else: ?><?= count($event_data['signs']) ?>条共计<?= $event_data['total_sign'] ?>人<?php endif; ?>)：</b>
                            <?php foreach ($event_data['signs'] AS $s): ?><?= $s['is_anonymous'] ? '匿名' : $s['User']['realname']; ?>&nbsp;<?php endforeach; ?></div>
                    <?php endif; ?>

                    <?php if ($event_data['album']): ?>
                        <?php if ($event_data['photos']): ?>
                            <div style="margin:5px"><b>活动掠影(<?= $event_data['album']['pic_num'] ?>张)</b></div>
                            <div>
                                <?php foreach ($event_data['photos'] as $p): ?>
                                    <a href="<?= URL::base() . str_replace('_mini', '', str_replace('resize/', '', $p['img_path'])) ?>" title="活动掠影"  class="colorboxPic" style="cursor:url(/static/big.cur),pointer" target="_blank"><img src="<?= URL::base() . $p['img_path'] ?>" style="border-width:0px;margin:0;width:120px;height:80px"/></a>&nbsp;
                                <?php endforeach; ?>
                                <div class="clear"></div>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <div id="interested_box" style="margin:15px 0"><?= View::factory('event/interested', array('event_id' => $event_data['event']['id'], 'interested_num' => $event_data['event']['interested_num'])) ?></div>

                    <br><span><a href="<?= Db_Event::getLink($event_data['event']['id'], $event_data['event']['aa_id'], $event_data['event']['club_id']) ?>" class="moreEventInfo" target="_blank"></a></span>

                <?php endif; ?>

                <?php if ($vote): ?><?php include "unit_vote.php"; ?><?php endif; ?>

                <?php if ($unit['is_good']): ?>
                    <div style="margin-top:30px;background:#FCFAE3 url(/static/ico/agree.gif) no-repeat 10px 12px;font-weight: bold;font-size:14px;width:50px;height:40px; line-height: 40px; color: #f60; padding-left: 30px" title="积分+<?= $point['good_unit'] ?>">推荐</div>
                <?php endif; ?>

            </div>

            <?php if ($unit['Post']['hidden']): ?>
                <div class="hidden" style="word-break:break-all">
                    <?php if (Model_Bbs::isReply($unit['id'], $_UID)): ?>
                        <?= $unit['Post']['hidden'] ?>
                    <?php else: ?>
                        有部分内容评论后可见。已回复？请<a href="javascript:history.go(0)">刷新一下</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if ($unit['is_fixed']): ?>
                <div class="unit_other_property"><img src="/static/images/yz_is_top.gif"></div>
            <?php elseif ($unit['is_good']): ?>
                <div class="unit_other_property"><img src="/static/images/is_recommend.gif" title="积分+<?= $point['good_unit'] ?>"></div>
            <?php elseif ($unit['reply_num'] >= 15): ?>
                <div class="unit_other_property"><img src="/static/images/yz_is_hot.gif"></div>
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <td>
            <div class="bbs_bottom_tools">
                <div style="text-align:right" class="manage_tool">
                    <?php if ($is_edit_permission): // 拥有编辑权限     ?>
                        <?php if ((time() - strtotime($unit['create_at']) < 3600 * 24 * 30 * 12) OR $is_system_permission): ?>
                            <?php if ($unit['event_id']): ?>
                                <a href="<?= URL::site('event/form?id=' . $unit['event_id']); ?>" class="ico_edit">编辑活动</a>&nbsp;
                            <?php else: ?>
                                <a href="<?= URL::site('bbs/unitForm?id=' . $unit['id']); ?>" class="ico_edit">修改</a>&nbsp;
                            <?php endif; ?>

                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if ($is_system_permission)://总会管理员  ?>
                        <a href="javascript:homepage(<?= $unit['id'] ?>)" class="<?= $unit['SysFilter']['id'] ? 'ico_yes' : 'ico_no' ?>" title="在网站首页显示该话题" id="homepage_link">首页显示</a>&nbsp;
                        <a href="<?= URL::site('admin_bbs/focusForm?id=' . $unit['id']); ?>" class="ico_television" title="增加到论坛首页幻灯片播放">幻灯片</a>&nbsp;
                        <script type="text/javascript">
            function homepage(cid) {
                new Request({
                    url: '<?= URL::site('admin_bbs/homepage?cid=') ?>' + cid,
                    type: 'post',
                    success: function() {
                        var homepage_link = document.getElementById('homepage_link');
                        homepage_link.className = homepage_link.className == 'ico_yes' ? 'ico_no' : 'ico_yes';
                    }
                }).send();
            }
                        </script>
                    <?php endif; ?>

                    <?php if ($is_control_permission): // 地方校友会或或俱乐部权限       ?>
                        <a href="javascript:setProperty('is_fixed',<?= $unit['id'] ?>)" class="<?= $unit['is_fixed'] ? 'ico_yes' : 'ico_no' ?>" title="推荐话题" id="is_fixed_link">置顶</a>&nbsp;
                        <a href="javascript:setProperty('is_good',<?= $unit['id'] ?>)" class="<?= $unit['is_good'] ? 'ico_yes' : 'ico_no' ?>" title="置顶话题" id="is_good_link">推荐</a>&nbsp;
                        <a href="javascript:close(<?= $unit['id'] ?>)" class="ico_del" title="屏蔽该话题">屏蔽</a>&nbsp;
                        <script type="text/javascript">
                            function setProperty(property, cid) {
                                new Request({
                                    url: '<?= URL::site('bbs/setProperty?cid=') ?>' + cid + '&property=' + property,
                                    type: 'post',
                                    success: function(data) {
                                        if (data != '') {
                                            alert(data);
                                            return false;
                                        }
                                        else {
                                            var link = document.getElementById(property + '_link');
                                            link.className = link.className == 'ico_yes' ? 'ico_no' : 'ico_yes';
                                        }

                                    }
                                }).send();
                            }

                            function close(cid) {
                                var b = new Facebox({
                                    title: '屏蔽确认！',
                                    message: '确定要屏蔽该话题吗？屏蔽后您还可以通过后台再次显示！',
                                    icon: 'question',
                                    ok: function() {
                                        new Request({
                                            url: '<?= URL::site('bbs/close?cid=') ?>' + cid,
                                            type: 'post',
                                            success: function() {
                                                window.location.href = '<?= URL::site($bbs_url) ?>';
                                            }
                                        }).send();
                                        b.close();
                                    }
                                });
                                b.show();
                            }
                        </script>
                    <?php endif; ?>
                </div>

                <div style="height:40px">
                    <div style="margin:8px 0; float: right;">
                        <!-- Baidu Button BEGIN -->
                        <div id="bdshare" class="bdshare_t bds_tools get-codes-bdshare">
                            <span class="bds_more">分享到：</span>
                            <a class="bds_qzone"></a>
                            <a class="bds_tsina"></a>
                            <a class="bds_tqq"></a>
                            <a class="bds_renren"></a>
                            <a class="shareCount"></a>
                        </div>
                        <script type="text/javascript" id="bdshare_js" data="type=tools&amp;uid=6755433" ></script>
<script type="text/javascript" id="bdshell_js"></script>
                        <!-- Baidu Button END -->
                    </div>
                </div>

                <?php if ($unit['update_at']): ?>
                    <div class="update">作者在 <?= $unit['update_at'] ?> 做了修改 </div>
                <?php endif; ?>

                <?php if ($unit['bubble']): ?>
                    <div class="dotted_line"></div>
                    <div class="sigline"><img src="/static/images/pen_alt_fill_12x12.gif" title="签名档" style="vertical-align:middle;margin:0px 4px"><?= $unit['User']['intro'] ? Text::limit_chars($unit['User']['intro'], 100, '...') : '这家伙真懒，什么都没填写...'; ?></div>
                <?php endif; ?>
            </div>

        </td>
    </tr>
</table>

<!--//主题 -->
<div id="scrollToComment" style="height:2px"></div>

<!--评论模块 -->
<div id="unitComment" >
    <?php
    $cmtParams['bbs_unit_id'] = $unit['id'];
    $cmtParams['reply'] = $reply;
    if ($unit['event_id']) {
        $cmtParams['event_id'] = $unit['event_id'];
    }
    ?>

    <?=View::factory('bbs/comment_form', array('params' => $cmtParams)) ?>

    
</div>
<!--评论表单 -->
<script type="text/javascript">
    readyScript.scrollToComment = function() {
<?php if (Arr::get($_GET, 'cmt') == 'y'): ?>
            scrollTo('comment_form', 500);
<?php endif; ?>
<?php if (Arr::get($_GET, 'page')): ?>
            setTimeout(function() {
                scrollTo('scrollToComment', 500);
            }, 500);
<?php endif; ?>
    };
    readyScript.lazyLoading = function() {
        $lazyload=$('#lazyload');
        $lazyload.find('img').unveil(0);
        $lazyload.find('.colorboxPic').colorbox({rel: "colorboxPic"});
        document.getElementById("bdshell_js").src = "http://bdimg.share.baidu.com/static/js/shell_v2.js?cdnversion=" + new Date().getHours();
    };
</script>


