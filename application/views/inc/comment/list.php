<?php
/**
  +-----------------------------------------------------------------
 * 名称：论坛外其余模块评论列表
 * 版本：1.0
 * 版权：www.usho.cn
 * 作者：zhaojiangang
 * 最后更新日期：datetime
  +-----------------------------------------------------------------
 */
?>
<div id="comments_list" >

    <?php if (count($comments) == 0): ?>
        <div class="nodata" style="margin:15px 0" id="not_cmt"><?=!$_SETTING['close_other_comment']?'暂无任何讨论，去抢个沙发？':'';?></div>
        <div id="comment_page_box"></div>

    <?php else: ?>
        <?php foreach ($comments as $ix => $cmt): ?>
            <div class="one_comment" id="comment_<?= $cmt['id'] ?>">
                <div class="one_cmt_left" >
                    <?= View::factory('inc/user/avatar2', array('id' => $cmt['user_id'], 'size' => 48, 'sex' => $cmt['sex'], 'online' => $cmt['online'])) ?>
                </div>
                <div class="one_cmt_right" style="position: relative;" >
                    <div  style=" position: absolute;right: 10px;top:0px;color:#999"><?= $floor['floor_' . $cmt['id']] ?>F</div>
                    <p class="user"><?php if ($cmt['online']): ?><img src="/static/images/online1.gif" style="margin-bottom:-2px" title="当前在线"><?php endif; ?><a href="<?= URL::site('user_home?id=' . $cmt['user_id']) ?>" class="commentor" id="comment_author_<?= $cmt['id'] ?>"><?= $cmt['realname'] ?></a> <?= Date::ueTime($cmt['post_at']) ?>：</p>
                    <div class="comment_content" style="line-height:1.7em">
                        <?php if ($cmt['quote_ids']): ?>
                            <?= View::factory('comment/quote', array('ids' => $cmt['quote_ids'], 'floor' => $floor)) ?>
                        <?php endif; ?><div id="comment_content_<?= $cmt['id'] ?>">
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
                    <div class="topic_tool"><a href="javascript:quote_comment(<?= $cmt['id'] ?>)" class="ico_quote">引用</a>
                        <?php if ($cmt['user_id'] == $_UID OR $_SESS->get('role') == '管理员'): // 修改 ?>
                            &nbsp;&nbsp;&nbsp;<a href="javascript:modify_comment(<?= $cmt['id'] ?>)" class="ico_edit">修改</a>
                        <?php endif; ?></div>
                </div>
                <div class="clear"></div>
            </div>
        <?php endforeach; ?>
        <?php if (!isset($cmt_id)): ?>
            <div id="comment_page_box"><?= $pager ?></div>
        <?php endif; ?>
    <?php endif; ?>
</div>