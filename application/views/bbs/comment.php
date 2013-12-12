<?php if (count($comments) == 0): ?>
    <p style="border: 0px dashed rgba(0,0,0,0.1);
border-radius: 5px 5px 5px 5px;margin-bottom: 20px;padding-bottom: 15px;
padding-top: 15px;color:#999;margin-top:10px;padding-left:15px" id="not_cmt">暂时还没有讨论，赶紧来抢个沙发吧？</p>
    <div id="comment_page_box"></div>
<?php else: ?>
    <?php
//热心度
    $div_point = Common_Point::divPoint();
    ?>

<?php if(trim($pager)):?>
    <div style="margin:15px 0;" >
    <?= $pager ?>
    </div>
<?php endif;?>

    <?php foreach ($comments as $ix => $cmt): ?>
        <table border="0" cellspacing="0" cellpadding="0" class="unit_table"  id="comment_<?= $cmt['id'] ?>" >
            <tbody>
                <tr>
                    <td valign="top" class="unit_left_bg"  rowspan="2">
                        <div class="left_user_face">
                            <img src="/static/images/online<?= $cmt['online'] ? '1' : '0'; ?>.gif" style="vertical-align:middle" title="当前<?= $cmt['online'] ? '在线' : '不在线'; ?>"  />
                            <a href="<?= URL::site('user_home?id=' . $cmt['user_id']) ?>" class="commentor" id="comment_author_<?= $cmt['id'] ?>"><?= $cmt['realname'] ?></a><br>
                            <a href="<?= URL::site('user_home?id=' . $cmt['user_id']) ?>" target="_blank">
                                <img src="<?= Model_User::avatar($cmt['user_id'], 128, $cmt['sex']) . '?gen_at=' . time() ?>" class="face" title="点击进入该主页" style="width:100px; height: 100px;"></a>
                        </div>
                        <div class="left_user_info">
                            地区&nbsp;<?= $cmt['city'] ?><br>
                            注册&nbsp;<?= date('Y-m-d', strtotime($cmt['reg_at'])); ?>
                            <?php if ($cmt['start_year'] AND $cmt['speciality']): ?>
                                <br>专业&nbsp;<?= $cmt['start_year'] ?>级<?= $cmt['speciality'] ?>
                            <?php endif; ?>
                            <br>发帖&nbsp;<a href="<?= URL::site('search?for=bbs&user_id=' . $cmt['user_id']) ?>" title="查看所有发帖" target="_blank" ><?= $cmt['bbs_unit_num'] ? $cmt['bbs_unit_num'] : '0'; ?></a>
                            <br>积分&nbsp;<a href="<?= URL::site('user_point?id=' . $cmt['user_id']) ?>" title="查看详情" target="_blank" ><?= $cmt['point'] ? $cmt['point'] : '0'; ?></a>
                            <br>热心度&nbsp;<a href="<?= URL::site('user_point?id=' . $cmt['user_id']) ?>" title="查看详情" target="_blank" ><?= Common_Point::getTemp($div_point, $cmt['point']); ?>°C</a>
                            <br><a href="javascript:;" onclick="sendMsg(<?= $cmt['user_id'] ?>)"  title="发送站内信" >发消息&nbsp;<img src="/static/images/user/email.gif" style="vertical-align: middle"></a>
                            <?php if ($cmt['homepage']): ?>
                                &nbsp;<?= Common_Global::imgLink($cmt['homepage']) ?>
                            <?php endif; ?>
                        </div>

                    </td>
                    <td valign="top" >
                        <div class="topic_postdate">
                            <p style="float:left">
                                <span style="color:#f60;"><strong><?= $floor['floor_' . $cmt['id']] ?></strong> 楼</span>
                                &nbsp;&nbsp;发布于<span id="comment_postdate_<?= $cmt['id'] ?>"><?= $cmt['post_at'] ?></span>
                            </p>
                            <p style="float:right">
                                <?php if ($reply): ?>
                                    <a href="<?= URL::site('bbs/viewPost?id=' . $bbs_unit_id) ?>"  style="color:#999" >查看全部</a>
                                <?php else: ?>
                                    <a href="<?= URL::site('bbs/viewPost?id=' . $bbs_unit_id) ?>&reply=<?= $cmt['user_id'] ?>#comment" style="color:#999" title="只查看作者评论">只看该作者</a>
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="comment_content" >
                            <?php if ($cmt['quote_ids']): ?>
                                <?= View::factory('comment/quote', array('ids' => $cmt['quote_ids'], 'floor' => $floor)) ?>
                            <?php endif; ?>
                            <div>
                                <?php if ($cmt['score'] > 0): ?><p style="color:#999;margin:4px 0"><span class="votescore<?= $cmt['score'] ?>" title="活动感受"></span></p><?php endif; ?>
                                <?php
                                $content = $cmt['content'];
                                if (strstr($cmt['content'], '_bmiddle')) {
                                    $content = preg_replace('/(<img[^>]+src\s*=\s*"?\/static\/upload\/([^>"\s]+)"?[^>]*>)/is', '<div><a href="/static/upload/$2" onclick="colorboxShow(this.href);return false;" style="cursor:url(/static/big.cur),pointer">$1</a></div>', $content);
                                    $content = preg_replace('/(<img[^>]+src\s*=\s*"?http:\/\/zuaa.zju.edu.cn\/static\/upload\/([^>"\s]+)"?[^>]*>)/is', '<div><a href="/static/upload/$2" onclick="colorboxShow(this.href);return false;" style="cursor:url(/static/big.cur),pointer">$1</a></div>', $content);
                                    $content = preg_replace('/(<a\b[^>]*)_bmiddle/', '$1', $content);
                                }
                                echo Emotion::autoToUrl($content);
                                ?>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td valign="bottom">
                            <div class="bbs_bottom_tools">
                            <div style="padding: 15px 0px;text-align: right">
                                <a href="javascript:quote_comment(<?= $cmt['id'] ?>)" class="ico_quote">引用</a>
                                <?php if ($cmt['user_id'] == $_UID OR $_SESS->get('role') == '管理员'): // 修改 ?>
                                    &nbsp;&nbsp;&nbsp;<a href="javascript:modify_comment(<?= $cmt['id'] ?>)" class="ico_edit">修改</a>
                                <?php endif; ?>
                            </div>
                            <?php if ($cmt['update_at']): ?>
                                <div class="update">作者在<?= $cmt['update_at'] ?> 做了修改 </div>
                            <?php endif; ?>

                            <?php if ($cmt['intro']): ?>
                                <div class="dotted_line" style=" position: "></div>
                                <div class="sigline"><img src="/static/images/pen_alt_fill_12x12.gif" title="个性签名" style="vertical-align:middle;margin:0px 4px"><?= $cmt['intro'] ? Text::limit_chars($cmt['intro'], 100, '...') : '这家伙真懒，什么都没填写...'; ?></div>
                            <?php endif; ?>
                                </div>
                    </td>
                </tr>
            </tbody>
        </table>
    <?php endforeach; ?>

    <?php if (!$cmt_id): ?>
        <div id="comment_page_box">
    <?php if($pager):?>
    <div style="margin:15px 0">
    <?= $pager ?>
    </div>
    <?php endif;?>
        </div>
    <?php endif; ?>

<?php endif; ?>
<!--//回帖 -->