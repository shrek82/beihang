<!-- user/active:_body -->
<div id="user_active" style="padding: 10px 20px 200px 20px">
    <h3>邮件验证和激活</h3>

    <?php if ($actived == TRUE): ?>

        <div style="color: #087E12; font-size: 16px; font-weight: bold; line-height: 50px; height: 100px; text-align: center">
            <img src="/static/images/accepted_48.png" style="vertical-align: middle">&nbsp;激活成功！<br>
            &nbsp;恭喜您！您的邮箱“<?= $account ?>”验证成功，感谢您的支持，祝您使用愉快:) &nbsp;<?php if($_UID):?><a href="/user_home" style="font-weight: bold;">进入个人主页</a><?php endif;?>
        </div>

    <?php else: ?>

        <div class="candy_form_err">
            抱歉，您所要激活的账户不存在或者激活码有误，激活操作失败！
        </div>

    <?php endif; ?>
</div>