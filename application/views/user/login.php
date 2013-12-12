<!-- user/login:_body -->
<div id="user_login" style="padding: 50px 50px 100px 50px">
    <form id="userLogin" action="<?= URL::site('user/login') ?>" method="post">
        <table style="margin: 0; ">
            <tr>
                <td colspan="2" style="border-left:3px solid #2E760F; font-size: 14px;padding: 0px 10px;height: 22px; color: #2E760F; font-weight: bold">登录校友网</td>
            </tr>
            <tr>
                <td style="text-align:right;height:25px">邮箱： </td>
                <td style="text-align:left"><input type="text" name="account" value="<?= @$account ?>" class="input_text" style="width:180px"   /></td>
            </tr>
            <tr>
                <td style="text-align:right;height:25px">密码：</td>
                <td style="text-align:left"><input type="password" name="password" class="input_text"  style="width:180px" /></td>
            </tr>
            <tr>
                <td>
                    <input type="hidden" name="rememberme" value="0" />

                </td>
                <td style="height:20px">
                    <input id="rememberme" type="checkbox" name="rememberme" value="1" checked="checked"/>
                    <label for="rememberme" style="font-weight: normal; font-size: 12px;color:#333">下次自动登陆</label></td>
            </tr>
            <tr>
                <td></td>
                <td height="25" colspan="2">
                    <?php if (isset($next) AND $next): ?>
                        <input type="hidden" name="next" id="next" value="<?= $next ?>">
                    <?php endif; ?>
                    <input type="submit" value="登录"  id="submit_button" class="button_login" onclick="loginsub();return false;"/>
                    <input type="button" onClick="window.location.href='<?= URL::site('user/register') ?>'" value="注册" class="button_register" />
                </td>
            </tr>
            <tr>
                <td></td>
                <td height="30" colspan="2">
                    <a href="<?= URL::site('help/forgetAccount') ?>" style="margin-right: 50px;">找回账号或密码？</a>
                </td>
            </tr>
        </table>
    </form>

    <?php if (isset($err) AND $err): ?>
        <div class="notice"><?= $err; ?></div>
    <?php endif; ?>
</div>

<?php if (isset($next) AND $next): ?>
    <script type="text/javascript">
        login_redirect=redirect;
    </script>
<?php endif; ?>

