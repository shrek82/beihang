<!-- user/facebox_login:_body -->
<div id="user_login" >
    <form id="userLogin" action="<?= URL::site('user/login') ?>" method="post" style="margin:5px 20px">
        <table style="margin: 0; ">
            <tr>
                <td style="text-align:right;height:35px">邮箱&nbsp;:&nbsp;</td>
                <td style="text-align:left"><input type="text" name="account" value="<?= @$account ?>" class="input_text" style="width:200px"   /></td>
            </tr>
            <tr>
                <td style="text-align:right;height:35px">密码&nbsp;:&nbsp;</td>
                <td style="text-align:left"><input type="password" name="password" class="input_text"  style="width:200px" /></td>
            </tr>
            <tr>
                <td >
                    <input type="hidden" name="rememberme" value="0" />

                </td>
                <td style="height:30px">
		     <input id="rememberme" type="checkbox" name="rememberme" value="1" />
		    <label for="rememberme" style="font-weight: normal; font-size: 12px;color:#333">下次自动登录</label></td>
            </tr>
            <tr>
             <td></td>
                <td height="35" colspan="2">
<input type="submit" id="submit_button" onClick="loginsub();return false;" value="登录" class="input_submit" style=" background: #005EAC;width:80px;height:27px;font-size:14px; padding: 6px 20px; border-color: #B8D4E8 #114681 #114681 #B8D4E8; border-width: 1px; color: #FFFFFF; cursor: pointer" />
<input type="button" onClick="window.location.href='<?= URL::site('user/register') ?>'" value="注册" style="background:#67A54B;width:80px;height:27px;font-size:14px; padding: 6px 20px;border-width:1px;border-color: #95BF82 #3C6F23 #3C6F23 #95BF82;color: #FFFFFF; cursor: pointer" />
                </td>
            </tr>
              <tr>
              <td></td>
                <td height="35" colspan="2">
                    <a href="<?= URL::site('help/forgetAccount') ?>" style="margin-right: 50px;">找回账号或密码？</a>
                </td>
            </tr>

        </table>

    </form>
</div>