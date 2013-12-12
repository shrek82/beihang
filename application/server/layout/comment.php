<!--论坛回帖列表 -->
<?php if (count($comments) == 0): ?>
    <p style="color:#999;margin:20px 0">暂时还没有讨论，赶紧来抢个沙发吧？</p>
<?php else: ?>
    <?php
//热心度
    $div_point = Common_Point::divPoint();
    ?>
    <?= $pager ?>
    <?php foreach ($comments as $ix => $cmt): ?>
        <table border="0" cellspacing="0" cellpadding="0" class="unit_table"  id="comment_<?= $cmt['id'] ?>">
            <tbody>
                <tr>
                    <td valign="top" class="unit_left_bg">
                        <div class="left_user_face">
                            <img src="/static/images/online<?= $cmt['online'] ? '1' : '0'; ?>.gif" style="vertical-align:middle" title="当前<?= $cmt['online'] ? '在线' : '不在线'; ?>"  />
                            <a href="<?= URL::site('user_home?id=' . $cmt['user_id']) ?>" class="commentor" id="comment_author_<?= $cmt['id'] ?>"><?= $cmt['User']['realname'] ?></a><br>
                            <a href="<?= URL::site('user_home?id=' . $cmt['user_id']) ?>" target="_blank">
                                <img src="<?= Model_User::avatar($cmt['user_id'], 128, $cmt['User']['sex']) ?>" class="face" title="点击进入该主页" style="width:100px; height: 100px;"></a>
                        </div>
                        <div class="left_user_info">
                            地区&nbsp;<?= $cmt['User']['city'] ?><br>
                            注册&nbsp;<?= date('Y-m-d', strtotime($cmt['User']['reg_at'])); ?>
                            <?php if ($cmt['User']['start_year'] AND $cmt['User']['speciality']): ?>
                                <br>专业&nbsp;<?= $cmt['User']['start_year'] ?>级<?= $cmt['User']['speciality'] ?>
                            <?php endif; ?>
                            <br>发帖&nbsp;<a href="<?= URL::site('search?for=bbs&user_id=' . $cmt['user_id']) ?>" title="查看所有发帖" target="_blank" ><?= $cmt['User']['bbs_unit_num'] ? $cmt['User']['bbs_unit_num'] : '0'; ?></a>
                            <br>积分&nbsp;<a href="<?= URL::site('user_point?id=' . $cmt['user_id']) ?>" title="查看详情" target="_blank" ><?= $cmt['User']['point'] ? $cmt['User']['point'] : '0'; ?></a>
                            <br>热心度&nbsp;<a href="<?= URL::site('user_point?id=' . $cmt['user_id']) ?>" title="查看详情" target="_blank" ><?= Common_Point::getTemp($div_point, $cmt['User']['point']); ?>°C</a>
                            <br><a href="<?= URL::site('user_msg/form?to%5B%5D=' . $cmt['user_id']) ?>"  target="_blank" title="发送站内信"><img src="/static/images/user/email.gif"></a>
                            <?php if ($cmt['User']['homepage']): ?>
                                &nbsp;<?= Common_Global::imgLink($cmt['User']['homepage']) ?>
                            <?php endif; ?>
                        </div>

                    </td>
                    <td valign="top" class="bbs_comment_content">
                        <div class="topic_postdate">
                            <p style="float:left">
                                <span style="color:#f60;"><strong><?= ($pager->current_page - 1) * $pager->items_per_page + $ix + 1 ?></strong> 楼</span>
                                &nbsp;&nbsp;发布于<span id="comment_postdate_<?= $cmt['id'] ?>"><?= $cmt['post_at'] ?></span></p>
                            <p style="float:right">
                                <?php if ($reply): ?>
                                    <a href="javascript:get_comment(1)" style="color:#999" >查看全部</a>
                                <?php else: ?>
                                    <a href="<?= URL::site('bbs/viewPost?id=' . $bbs_unit_id) ?>&reply=<?= $cmt['user_id'] ?>#comment" style="color:#999" title="只查看作者评论">只看该作者</a>
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="comment_content" id="comment_content_<?= $cmt['id'] ?>">
                            <?php if ($cmt['quote_ids']): ?>
                                <?=View::factory('comment/quote', array('ids' => $cmt['quote_ids'],'floor'=>$floor)) ?>
                            <?php endif; ?>
                            <div id="comment_content_<?= $cmt['id'] ?>">
                            <?php
                            $content = $cmt['content'];
                            if (strstr($cmt['content'], '_bmiddle')) {
                                $content = preg_replace('/(<img[^>]+src\s*=\s*"?\/static\/upload\/attached\/([^>"\s]+)"?[^>]*>)/is', '<div><a href="/static/upload/attached/$2" onclick="colorboxShow(this.href);return false;" style="cursor:url(/static/big.cur),pointer">$1</a></div>', $content);
                                $content = preg_replace('/(<img[^>]+src\s*=\s*"?http:\/\/zuaa.zju.edu.cn\/static\/upload\/attached\/([^>"\s]+)"?[^>]*>)/is', '<div><a href="/static/upload/attached/$2" onclick="colorboxShow(this.href);return false;" style="cursor:url(/static/big.cur),pointer">$1</a></div>', $content);
                                $content = preg_replace('/(<a\b[^>]*)_bmiddle/', '$1', $content);
                            }
                            echo $content;
                            ?>
                            </div>
                        </div>

                        <div class="bbs_bottom_tools" >
                            <div style="margin:10px 0;text-align: right">
                                <a href="javascript:quote_comment(<?= $cmt['id'] ?>)" class="ico_quote">引用</a>
                                <?php if ($cmt['user_id'] == $_SESS->get('id') OR $_SESS->get('role') == '管理员'): // 修改 ?>
                                    &nbsp;&nbsp;&nbsp;<a href="javascript:modify_comment(<?= $cmt['id'] ?>)" class="ico_edit">修改</a>
                                <?php endif; ?>
                            </div>

                            <?php if ($cmt['update_at']): ?>
                                <div class="update">作者在<?= $cmt['update_at'] ?> 做了修改 </div>
                            <?php endif; ?>

                            <?php if ($cmt['bubble']): ?>
                                <div class="dotted_line"></div>
                                <div class="sigline"><img src="/static/images/pen_alt_fill_12x12.gif" title="最新记录" style="vertical-align:middle;margin:0px 4px"><?= Text::limit_chars($cmt['bubble'], 50, '...') ?></div>
                                <?php endif; ?>
                        </div>

                    </td>
                </tr>
            </tbody>
        </table>
    <?php endforeach; ?>

    <?= $pager ?>
<?php endif; ?>
<!--//回帖 -->