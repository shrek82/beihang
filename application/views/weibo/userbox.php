<div class="rightbox">
    <?php if ($_UID && $_MEMBER): ?>
        <div class="userbox_left">
            <img src="<?= Model_User::avatar($_UID, 128) ?>" style="width:84px;height: 84px">
        </div>
        <div class="userbox_right">
            <p style="margin-bottom: 5px; font-weight: bold"><?= $_SESS->get('realname') ?></p>
            <div style="padding:5px 0">已加入校友会<br>上次访问<?= Date::span_str(strtotime($_MEMBER['visit_at'])) ?>前</div>
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
            <input type="button" class="sign_button_green" value="" onclick="artDialogSignAa()">
        </div>
    <?php else: ?>
        <div style=" text-align: center; padding: 10px 0">
            <input type="button" class="sign_button_green" value="" onclick="loginForm('/aa_home?id=<?= $_ID ?>')">
        </div>
    <?php endif; ?>
</div>