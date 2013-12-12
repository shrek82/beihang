<!-- user_newthing/index:_body -->
<script type="text/javascript" src="/static/js/newthing_comment.js"></script>
<div id="big_right">
    <div id="plugin_title">我的新鲜事</div>
    <!--最近的状态 -->
    <div id="home_newthing" >
        <form action="<?= URL::site('user_newthing/post') ?>" id="newthing_form" method="post">
            <textarea class="textarea_newthing" name="content" onclick="if(this.value=='分享新鲜事...'){this.value='';this.style.color='#333';};" onblur="if(this.value==''){this.value='分享新鲜事...';this.style.color='#999';}"  style="vertical-align:middle;" id="textarea_newthing">分享新鲜事...</textarea>
            <input type="button" id="button_newthing" onclick="post_newthing()" value="发布" style="vertical-align:middle; margin-top: 30px; cursor: pointer">
        </form>
    </div>
    <!--//最近状态 -->

    <div class="tab_gray" id="user_topbar" style="margin-top:10px">
        <ul>
            <li><a href="<?= URL::site('user_newthing?display=self') ?>" style="width:80px" <?=$display=='self'?'class="cur"':null;?>>我发布的</a></li>
            <li><a href="<?= URL::site('user_newthing?display=mark') ?>"  style="width:80px" <?=$display=='mark'?'class="cur"':null;?>>关注校友的</a></li>
            <li><a href="<?= URL::site('user_newthing?display=cmted') ?>"  style="width:80px" <?=$display=='cmted'?'class="cur"':null;?>>评论过的</a></li>
        </ul>
    </div>

    <!--状态列表 -->
    <div id="newthing_list" style="margin-top: 10px;width: 95%">
        <?php if (!$newthings): ?>
            <div class="nodata">您还没有记录呢！</div>
        <?php endif; ?>
        <?php foreach ($newthings as $newthing): ?>
            <div class="a_newthing" id="b_<?= $newthing['id'] ?>" style="border-bottom-width:0">
                <div style="padding-top:5px">
                    <span style="color:#999"><?= Date::ueTime($newthing['post_at']); ?>: </span> <?= Common_Global::weibohtml($newthing['content'], $newthing['aa_id']) ?> <a href="javascript:del(<?= $newthing['id'] ?>)" style="color:#F7B4B7" title="删除">x</a>
                </div>
                <div class="gray_text blue_link" style=" margin-top:10px">
                    <?= View::factory('/inc/comment/newthing_list_form', array('row' => $newthing)) ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?= $pager ?>
</div>

<script type="text/javascript">
    function del(cid){
        new candyConfirm({
            message:'确定要删除该新鲜事吗？注意删除新鲜事同时也将删除相关评论。',
            url: '/user_newthing/del?cid='+cid,
            removeDom:'b_'+cid
        }).open();
    }

    new Request({
        url: '/event/userjoin',
        type: 'post',
        data: 'id='+1
    }).send();
</script>