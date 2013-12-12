<div class="clear"></div>
<div id="get_comment_list" >
    <div style="text-align: center"><img src="/static/ico/loading4.gif" style="margin:10px"></div>
</div>
<?php if (!$_UID): ?>
    <div class="notice" style="margin:10px 0">
        您还没有注册或登录，请<a href="javascript:;" onclick="loginForm('<?= $_URL ?>')"><b>登录</b></a> 后进行回帖或讨论。
    </div>

<?php elseif ($_SETTING['close_bbs_comment']): ?>
    <div class="notice" style="margin:10px 0">
        很抱歉，暂时关闭话题评论，感谢您的参与！
    </div>

<?php else: ?>

    <table border="0" cellspacing="0" cellpadding="0" class="unit_table">
        <thead>
            <tr>
                <th colspan="2" class="topic_topbar" style="text-align: left">发表回复：</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td valign="top" class="unit_left_bg">
                    <div class="left_user_face">
                        <img src="<?= Model_User::avatar($_UID, 128, $_SESS->get('sex')) . '?gen_at=' . time() ?>" class="face" />
                    </div>
                </td>
                <td valign="top"  style="background:#fff;padding:10px 20px">

                    <form  action="<?= URL::site('comment/post') ?>" id="comment_form" method="POST">
                        <div class="quote_box" id="quote_box" style="color: #f70; padding: 5px; margin: 5px 0;width:95%;font-size: 14px;display:none;"></div>
                        <div style="height: 220px">
                            <input type="hidden" id="quote_id" name="quote_id">
                            <input type="hidden" id="cmt_id" name="cmt_id">
                            <textarea  style="width:98%; height: 180px" id="cmt_content" name="content" ></textarea>                           <br>
                        </div>
                        <div>
                            <?php if (isset($params)): ?>
                                <?php foreach ($params as $key => $val): ?>
                                    <input type="hidden" name="<?= $key ?>" value="<?= $val ?>" style="display:none" />
                                <?php endforeach; ?>
                            <?php endif; ?>

                            <?php
                            $postData = "{'form':'comment_form','getUrl':'/bbs/comment','query':'" . http_build_query(@$params, '', '&') . "'}";
                            ?>
                            <input type="button" id="submit_button" onclick="<?= 'post_comment(' . $postData . ')'; ?>" value="发表回复" class="button_blue" />
                    </form><br>
                    <div id="statusTools" style="clear:both;margin:5px;padding:5px;"></div>
                </td>
            </tr>
        </tbody>
    </table>
    <div class="bbs_topic_topbar" ></div>

    <!--快速回复 -->

<?php endif; ?>

<script type="text/javascript" id="bdshare_js" data="type=tools&amp;uid=600886" ></script>
<script type="text/javascript" id="bdshell_js"></script>

<script type="text/javascript">
            getCommentJson = {
                "page":<?= Arr::get($_GET, 'page', 1); ?>,
                'scrollTo': false,
                'query': '<?= http_build_query(@$params, '', '&'); ?>',
                'getUrl': '/bbs/comment'
            };
            readyScript.comment = function() {
                get_comment(getCommentJson);
            };
</script>


<?=
View::ueditor('cmt_content', array(
    'toolbars' => Kohana::config('ueditor.simple'),
    'minFrameHeight' => 200,
    'autoHeightEnabled' => 'false',
    'elementPathEnabled' => 'false',
    'focus' => 'false',
));
?>

