<div class="rightbox">
    <?php if ($_UID && $_MEMBER): ?>
        <div class="userbox_left">
            <img src="<?= Model_User::avatar($_UID, 128) ?>" style="width:84px;height: 84px">
        </div>
        <div class="userbox_right">
            <p style="margin-bottom: 5px; font-weight: bold"><?= $_SESS->get('realname') ?></p>
            <span style="line-height: 1.7em"><?= $_MEMBER['title'] ? $_MEMBER['title'] : '正式成员'; ?><br>上次访问 <?= Date::span_str(strtotime($_MEMBER['visit_at'])) ?>前</span><br>
            <a href="javascript:;" onclick="leave()" title="退出俱乐部">退出</a>
        </div>
        <div class="clear"></div>
    <?php elseif ($_UID): ?>
        <div class="userbox_left">
            <img src="<?= Model_User::avatar($_UID, 128) ?>" style="width:84px;height: 84px">
        </div>
        <div class="userbox_right">
            <span class="username"><?= $_SESS->get('realname') ?></span><br>
            <span style="line-height: 1.7em">你还没有加入<br>现在就加入吧！</span>
        </div>
        <div class="clear"></div>
        <div style=" text-align: center; padding: 10px 0">
            <input type="button" class="sign_button_green" value="" onclick="join_club(<?= $_ID ?>)">
        </div>
    <?php else: ?>
        <div style=" text-align: center; padding: 10px 0">
            <input type="button" class="sign_button_green" value="" onclick="loginForm('/club_home?id=<?= $_ID ?>')">
        </div>
    <?php endif; ?>
</div>
<script type="text/javascript">
    function applyjoin_club(cid){

        var box = new Facebox({
            title: '申请加入俱乐部',
            url: '/club_home/join?id='+cid,
            okVal: '确定',
            ok: function(){
                new ajaxForm('apply_form', {callback:function(){
                        box.close();
                    }}).send();
            }
        });
        box.show();
    }

    //直接加入校友会
    function join_club(cid){
        var join=new Request({
            url: '/club_home/join?id='+cid,
            type: 'post',
            success: function(){
                location.reload();
            }
        });
        join.send();
    }

function leave(){
    new candyConfirm({
        message: '您确定退出本俱乐部吗？',
        url:'/club_home/leave?id=<?= $_ID ?>',
        callback:function(){
            location.reload();
        }
    }).open();
}
</script>