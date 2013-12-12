<?php
/**
  +-----------------------------------------------------------------
 * 名称：评论视图载入和发表页面
 * 版本：1.0
 * 版权：www.usho.cn
 * 作者：zhaojiangang
 * 最后更新日期：12-10-11 下午3:08
  +-----------------------------------------------------------------
 */
?>
<div class="clear"></div>
<div id="get_comment_list" style="width:99%">
    <div style="text-align: center"><img src="/static/ico/loading4.gif" style="margin:10px"></div>
</div>
<?php
//获取和发布可以是不同组的参数
$get_params = isset($get_params) ? $get_params : $params;
?>
<?php if (!$_UID): ?>
    <div class="notice">
        您还没有注册或登录，请<a href="javascript:;" onclick="loginForm('<?= $_URL ?>')"><b>登录</b></a> 后进行回帖或讨论。
    </div>
<?php else: ?>

    <div id="comment_form_box" style="_width:100%">
        <div class="user_face">
            <?= View::factory('inc/user/avatar2', array('id' => $_UID, 'size' => 48, 'sex' => $_SESS->get('sex'), 'online' => true)) ?>
        </div>
        <div class="comment_form" >

            <form action="<?= URL::site('comment/post') ?>" id="comment_form" method="POST">
                <div class="quote_box" id="quote_box" style="color: #f70; padding: 5px; margin: 5px 0;width:95%;font-size: 12px;display:none;"></div>
                <div style="height:190px;">
                    <input type="hidden" id="quote_id" name="quote_id"></input>
                    <input type="hidden" id="cmt_id" name="cmt_id"></input>
                    <?php if (isset($params)): ?><?php foreach ($params as $key => $val): ?>
                            <input type="hidden" name="<?= $key ?>" value="<?= $val ?>" />
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <textarea id="cmt_content" name="content"  style="height:150px;"></textarea>
                    <?=
                    View::ueditor('cmt_content', array(
                        'toolbars' => Kohana::config('ueditor.simple'),
                        'minFrameHeight' => 150,
                        'autoHeightEnabled' => 'false',
                        'elementPathEnabled' => 'false',
                        'iframeCssUrl' => 'themes/default/iframe12px.css',
                        'focus' => 'false',
                    ));
                    ?>
                </div>

                <?php
                $postData = "{'form':'comment_form','getUrl':'/comment/list','query':'" . http_build_query(@$get_params, '', '&') . "'}";
                ?>
                <div style="display: none">
<input type="button" id="cmt_submit" onclick="post_comment(<?= $postData ?>)" value="发表回复" class="button_blue"  />
                    </div>

                      <div style="color: #f60">
                          因评论功能bug，导致部分评论丢失，目前正在恢复中，请稍候重试，谢谢
                        </div>

                <span style="color:#ccc"></span>
                <input value="" name="redirect" type="hidden">
            </form>
        </div>
        <div class="clear"></div>
    </div>

<?php endif; ?>
<script type="text/javascript">
    getCommentJson={
        "page":<?= Arr::get($_GET, 'page', 1); ?>,
        'scrollTo':false,
        'uid':false,
        'query':'<?= http_build_query(@$get_params, '', '&'); ?>',
        'getUrl':'/comment/list'
    }
    setTimeout(function(){
        get_comment(getCommentJson);
    },200)
</script>


