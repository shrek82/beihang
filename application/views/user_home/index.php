<!-- user_home/index:_body -->
<script type="text/javascript" src="/static/js/newthing_comment.js"></script>
<!--主内容-->
<div id="center">
    <!--姓名及身份显示 -->
    <div class="real_name">
        <span style="font-weight: bold;font-size: 18px; line-height: 1.6em">&nbsp;<?= $user['realname'] ?></span>
        <span style="color:#999"><?php if ($user['role'] == '校友(已认证)'): ?><span style="color:#009900"><?= $user['role'] ?></span><?php elseif ($user['role'] == '管理员'): ?><span style="color:#f60"><?= $user['role'] ?></span><?php else: ?><?= $user['role'] ?><?php endif; ?>&nbsp;<?= $user['start_year'] ? $user['start_year'] . '级&nbsp;' : ''; ?><?= $user['speciality'] ? $user['speciality'] : ''; ?></span>
    </div>
    <!--//姓名及身份 -->

    <!--最近的记录 -->
    <div id="home_newthing">
        <?php if ($_MASTER): ?>
            <form action="<?= URL::site('user_newthing/post') ?>" id="blow_form" method="post">
                <div>
                    <textarea class="textarea_newthing" id="textarea_newthing" name="content" onclick="if(this.value=='分享新鲜事...'){this.value='';this.style.color='#333';}else{this.style.color='#999'};" onblur="if(this.value==''){this.value='分享新鲜事...';this.style.color='#999';}"  >分享新鲜事...</textarea>
                </div>
                <div class="last_newthing" >
                    <span id="last_newthing_guest">
                        <?php if ($newthing): ?>
                        最近更新：<?= Text::limit_chars(Common_Global::weibotext($newthing['content']), 25) ?><span style="color: #999">&nbsp;约<?= Date::span_str(strtotime($newthing['post_at'])) ?>前</span>
                        <?php else: ?>
                            最近没啥说的...
                        <?php endif; ?>
                    </span>
                    <span class="button_newthing"><input type="button" id="button_newthing" onclick="post_my_newthing()"  style=" cursor: pointer"  value="发表"></span>
                </div>
            </form>
        <?php else: ?>
            <div class="last_newthing_guest" >
                <?php if ($newthing): ?>
                    最近更新：<?= Common_Global::weibotext($newthing['content']); ?><span style="color: #999">&nbsp;约<?= Date::span_str(strtotime($newthing['post_at'])) ?>前</span>
                <?php else: ?>
                    最近没啥说的...
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
    <!--//最近状态 -->

    <!--我加入的组织和我关注的人 -->
    <div class="tab_gray" id="syn_tab" style="margin-top:10px; ">
        <ul>
            <?php if ($_MASTER): ?>
                <li class="tab_name" style="width:90px;padding:0 " >我关注的动态：</li>
                <li ><a href="javascript:aa_sys('0');" id="aa_tab_0" style=" text-align: left; padding: 0px 10px" >总会</a></li>
                <?php foreach ($aa as $key => $a): ?>
                    <?php if ($key <= 3): ?>
                        <li ><a  href="javascript:aa_sys('<?= $a['id'] ?>');" id="aa_tab_<?= $a['id'] ?>"   style=" text-align: left; padding: 0px 10px" title="<?= $a['sname'] ?>校友会"><?= Text::limit_chars($a['sname'], 2, '') ?></a></li>
                    <?php endif; ?>
                <?php endforeach; ?>
                <li ><a href="javascript:user_sys('all');" id="tab_for_all" style=" text-align: left; padding: 0px 10px" >关注的人</a></li>
            <?php else: ?>
                <li class="tab_name"><?= $user['sex'] == '男' ? '他' : '她'; ?>的动态</li>
                <li><a  href="javascript:user_sys('all')" id="tab_for_all">全部</a></li>
                <li><a href="javascript:user_sys('weibo')" id="tab_for_weibo">新鲜事</a></li>
                <li><a href="javascript:user_sys('bbs')" id="tab_for_bbs">话题</a></li>
                <li><a href="javascript:user_sys('event')" id="tab_for_event">活动</a></li>
                <li><a href="javascript:user_sys('photo')" id="tab_for_photo">照片</a></li>
                <li><a href="javascript:user_sys('invite_register')" id="tab_for_invite_register">邀请</a></li>
            <?php endif; ?>
        </ul>
    </div>

    <div id="syn_loader" class="blue_link" ></div>
    <!--// -->

</div>
<!--//主内容结束 -->

<!--右侧 -->
<div id="right">

    <!--最近访客 -->
    <p class="title">最近来访<span class="more"><a  href="<?= URL::site('user_home/visitor') . URL::query() ?>">more</a></span></p>
    <div id="home_visitor"><img src="/static/images/user/loading6.gif"></div>
    <div class="clear"></div>
    <!--//最近访客 -->

    <!--关注的人 -->
    <p class="title" style="margin-top: 15px">

        <?php if ($_MASTER): ?>
            我关注的人
        <?php else: ?>
            <?= $user['sex'] == '男' ? '他' : '她'; ?>关注的人
        <?php endif; ?>

        <span class="more"><a  href="<?= URL::site('user_home/userm') . URL::query() ?>">more</a></span></p>
    <div id="user_marked"><img src="/static/images/user/loading6.gif"></div>
    <div class="clear"></div>
    <!--//关注的人 -->

    <!--关注我的人 -->
    <p class="title" style="margin: 15px 0 10px 0">

        <?php if ($_MASTER): ?>
            关注我的人
        <?php else: ?>
            关注<?= $user['sex'] == '男' ? '他' : '她'; ?>的人
        <?php endif; ?>

        <span class="more"><a  href="<?= URL::site('user_home/focusOnThis') . URL::query() ?>">more</a></span></p>
    <div id="focusOnThis"><img src="/static/images/user/loading6.gif"></div>
    <div class="clear"></div>
    <!--//关注我的人 -->

    <!-- //首页寻找校友-->
    <p class="title" style="margin-top: 15px">寻找校友</p>
    <div id="home_search_member">
        <form action="<?= URL::site('alumni') ?>" method="get" style="margin:0">
            <input name="name" type="text" style="width:120px; height: 18px; padding: 2px 4px;border: 1px solid #ccc;margin-right:5px"  >
            <input type="submit" value="搜索" style="background:#F4F6F6; border: 1px solid #ccc;padding:3px 4px;">
        </form>
        <div class="clear"></div>
    </div>
    <!--//寻找校友 -->
    <div class="clear"></div>
</div>

<!--//右侧结束 -->


<script type="text/javascript">
    //我关注的组织或校友
    function aa_sys(aa_id){
        $('#syn_tab a').removeClass('cur');
        $('#aa_tab_'+aa_id).addClass('cur');
        var url='<?= URL::site('user_home/joinSyn') ?>?aa_id='+aa_id;
        new Request({
            url: url,
            type: 'post',
            beforeSend: function(){
                $('#syn_loader').html('<img src="<?= URL::base() . 'candybox/Media/image/loading.gif' ?>" />');
            },
            success: function(html){
                $('#syn_loader').html(html);
            }
        }).send();
    }
    //其他人的动态
    function user_sys(tab){
        $('#syn_tab a').removeClass('cur');
        $('#tab_for_'+tab).addClass('cur');
        new Request({
            url: '<?= URL::site('user_home/syn') ?>/'+tab+'<?= URL::query() ?>',
            type: 'post',
            beforeSend: function(){
                $('#syn_loader').html('<img src="/candybox/Media/image/loading.gif" />');
            },
            success: function(html){
                $('#syn_loader').html(html);
            }
        }).send();
    }

setTimeout(function(){
$('#home_visitor').load('<?= URL::site('user_home/visitor') . URL::query() ?>');
$('#user_marked').load('<?= URL::site('user_home/userm') . URL::query() ?>');
$('#focusOnThis').load('<?= URL::site('user_home/focusOnThis') . URL::query() ?>');
},300);
</script>


<script type="text/javascript">
<?php if ($_MASTER): ?>
        aa_sys('0');
<?php else: ?>
        user_sys('all');
<?php endif; ?>
</script>
