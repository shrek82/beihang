<style type="text/css">
    /*被转载样式*/
    .forwardContent{width:660px}
    .forwardContent .forward_top{height:16px;background: url(/static/homepage/images/forward_top@2x.png) no-repeat top;}
    .forwardContent .forward_con{background:#FBFBFB; border-left: 1px solid #E1E4E5; border-right:1px solid #E1E4E5;padding:3px 15px;font-size:12px;color:#777777}
    .forwardContent .forward_bottom{height:11px;background: url(/static/homepage/images/forward_bottom@2x.png) no-repeat top;}
</style>
<!--main left -->
<div id="main_left" >
    <div id="weibo_content_list" style="padding:0px">
        <h2 ><a href="/weibo?id=<?=$_ID?>&uid=<?= $w['user_id'] ?>" title="Ta发布的所有新鲜事" style="color:#325F98;font-size:22px"><?= $w['realname'] ?></a>&nbsp;
            <?= View::factory('user/mark', array('uid' => $w['user_id'])); ?>
        </h2>
        <?php if ($w): ?>
            <div class="weibo_content" id="weibo_del_<?= $w['id'] ?>" >

                <div class="weibo_content_right" style="width:650px">
                    <?= Common_Global::weibohtml($w['content'], $_ID) ?>

                    <?php if ($w['img_paths']): ?>
                        <?php $img_path_array = explode('||', $w['img_paths']) ?>
                        <?php foreach ($img_path_array AS $key => $path): ?>

                            <div id="insert_img_div_<?= $w['id'] ?>_<?= $key ?>" class="view_big_img" >
                                <div class="imagetool" style="text-align: left"><a href="/<?= str_replace('_mini', '_bmiddle', $path) ?>"  target="_blank" style="font-size:12px;color:#999">查看原图</a></div>
                                <img src="/<?= str_replace('_mini', '_bmiddle', $path) ?>" style="width:550px" >
                            </div>

                        <?php endforeach; ?>
                        <div class="clear"></div>
                    <?php endif; ?>

                    <?php if ($w['from_forward']): ?>
                        <?= View::factory('weibo/forwardContent', array('wid' => $w['from_forward'])) ?>
                    <?php endif; ?>

                </div>
                <div class="clear"></div>
                <div class="weibo_content_tool">
                    <p class="wct_left">&nbsp;&nbsp;<?= Date::span_str(strtotime($w['post_at'])); ?>前发布&nbsp;&nbsp;<span class="clients"><?= $w['clients'] ? '来自<span class="cname">' . $w['clients'] . '</span>' : ''; ?></span></p>
                    <p class="wct_right"><?php if ($_UID == $w['user_id']): ?><a href="javascript:;" onclick="delweibo(<?= $w['id'] ?>)" title="删除">删除</a>&nbsp;|&nbsp;<?php endif; ?><a href="javascript:;" onclick="retweet(<?= $w['id'] ?>)">转发<?= $w['forward_num'] > 0 ? '(' . $w['forward_num'] . ')' : ''; ?></a>&nbsp;|&nbsp;<a href="javascript:scrollTo('scrollToComment',500)" )">评论<?= $w['reply_num'] > 0 ? '(' . $w['reply_num'] . ')' : ''; ?></a></p>
                </div>
                <!--回复及评论 -->
                <p class="comments_title" id="scrollToComment">评论<?= $w['reply_num'] > 0 ? '(' . $w['reply_num'] . ')' : ''; ?></p>
                <form method="POST"  id="comment_form" action="/comment/post">

                    <div style="padding:0px 5px">
                        <textarea style="border:1px solid #C6C6C6;width:650px;height:60px;padding:4px;overflow: none" id="comment_textarea" name="content"></textarea>
                        <div id="expbox" style="height:1px"></div>
                    </div>
                    <div style="margin:6px 5px;height:30px">
                        <div style="float:left;width:200px"><a href="javascript:;" onclick="open_expression('expbox','comment_textarea')" class="tool_ico_face">表情</a></div>
                        <div style="float:right;width:300px; text-align: right;padding-right: 10px"><input type="button" class="greenButton" value="发布"  onclick="contentViewPostComment(<?= $w['id'] ?>)"></div>
                    </div>
                    <input type="hidden" name="weibo_id" value="<?= $w['id'] ?>">
                </form>

                <div style="height:25px;margin-top: -15px">
                    <div id="loading" style="text-align: center;color:#79ADDB"><img src="/static/ico/loading6.gif" style="vertical-align: middle;">&nbsp;努力加载中，请稍候...</div>
                </div>

                <div id="get_comment_list" ></div>
                <!--//回复及评论 -->
            </div>
            <!--//content -->
        <?php else: ?>
            <div class="nodata">很抱歉，内容不存在或者已经被删除。</div>
        <?php endif; ?>
    </div>
</div>
<!--main right -->
<div id="main_right">
    <div class="rightbox">
        <div class="userbox_left">
            <img src="<?= Model_User::avatar($w['user_id'], 128) ?>" style="width:84px;height: 84px">
        </div>
        <div class="userbox_right">
            <p style="color:#265B98;font-size:14px; font-weight: bold"><?= $w['realname'] ?></p>
            <span style="color:#6295D0; line-height: 1.7em"><?php if ($w['start_year'] && $w['speciality']): ?><?= $w['start_year'] ?>级<?= $w['speciality'] ?><br><?php endif; ?><a href="/user_home?id=<?= $w['user_id'] ?>" target="_blank">Ta的个人主页</a></span>
            <p><a href="javascript:;" onclick="sendMsg(<?=$w['user_id']?>)"  title="发送站内信" >发私信</a></p>
        </div>
        <div class="clear"></div>
    </div>

    <div class="con_title">
        <span class="title_name">Ta发起的话题</span>
    </div>
    <div class="aa_block">
        <?php if ($user_topics): ?>
            <ul class="aa_conlist">
                <?php foreach ($user_topics AS $t): ?>
                    <li><a href="/weibo?id=<?= $_ID ?>&topic=<?= urlencode($t['topic']) ?>"><?= $t['topic'] ?></a>&nbsp;<span style="color:#999">(<?= $t['num'] ?>)</span></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <div class="nodata">暂时还没有话题</div>
        <?php endif; ?>
    </div>

</div>

<script type="text/javascript">
    function get_comment(page){
        document.getElementById('loading').style.display='block';
        if(page > 1){
            scrollTo('scrollToComment',500)
        }
        var list = new Request({
            url: '<?= URL::site('weibo/contentComments?id=' . $_ID . '&wid=' . $w['id']) ?>'+'&page='+page,
            type: 'post',
            success: function(data){
                $('#get_comment_list').html(data);
                document.getElementById('loading').style.display='none';
            }
        });
        list.send();
    }
    get_comment();
</script>