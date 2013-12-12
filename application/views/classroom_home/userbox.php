<div class="rightbox">
    <?php if ($_UID && $_MEMBER): ?>
        <div class="userbox_left">
            <img src="<?= Model_User::avatar($_UID, 128) ?>" style="width:84px;height: 84px">
        </div>
        <div class="userbox_right">
            <p style="margin-bottom: 5px; font-weight: bold"><?= $_SESS->get('realname') ?></p>
            <div style="padding:5px 0; line-height: 1.5em">您已加入班级<br>上次访问<?= Date::span_str(strtotime($_MEMBER['visit_at'])) ?>前
                <br><a href="javascript:exit_classroom()">退出班级</a>
            </div>

        </div>
        <div class="clear"></div>
    <?php elseif ($_UID): ?>
        <div class="userbox_left">
            <img src="<?= Model_User::avatar($_UID, 128) ?>" style="width:84px;height: 84px">
        </div>
        <div class="userbox_right">
            <span class="username"><?= $_SESS->get('realname') ?></span><br>
            <span>你还没有加入<br>现在就加入吧！</span>
        </div>
        <div class="clear"></div>
        <div style=" text-align: center; padding: 10px 0">
            <input type="button" class="sign_button_green" value="" onclick="join()">
        </div>
    <?php else: ?>
        <div style=" text-align: center; padding: 10px 0">
            <input type="button" class="sign_button_green" value="" onclick="loginForm('/classroom_home?id=<?= $_ID ?>')">
        </div>
    <?php endif; ?>
</div>

<script type="text/javascript">
    //直接加入
    function join(){
	var join=new Request({
	    url: '/classroom_home/join?id=<?=$_ID?>',
	    type: 'post',
	    success: function(){
		location.reload();
	    }
	});
	join.send();
    }
    //申请加入
    function joinApply_classroom(){
	var box = new Facebox({
	    title: '申请加入<?=$_CLASSROOM['name']?>',
	    url: '/classroom_home/joinApply?id=<?=$_ID?>',
	    submitValue: '确定',
	    submitFunction: function(){
		new CandyForm('apply_form', {callback:function(){
			box.close();
			location.reload();
		    }}).send();
	    }
	});
	box.show();
    }

    function exit_classroom(){
        new candyConfirm({
            message: '确定要退出<?=$_CLASSROOM['name']?>吗？',
            url: '/classroom_home/exitclass?id=<?=$_ID?>',
            callback:function(){
                location.reload();
            }
        }).open();
    }
</script>