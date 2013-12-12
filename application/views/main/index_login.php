<div id="login">
    <p class="title">登录校友网</p>
    <div style="margin:10px 20px;" id="user_login">
	<form id="userLogin" action="<?= URL::site('user/login') ?>" method="post" >
	    <p>&nbsp;&nbsp;<input type="text" name="account" value="<?= @$account ?>" class="input_text icon_account"  tabindex="1"  style="width:170px" placeholder='邮箱'  /></p>
	    <p>&nbsp;&nbsp;<input type="password" name="password" class="input_text icon_password"  tabindex="2" style="width:170px;" placeholder='密码'/></p>
	    <p style="padding:10px 0px 0px 5px; ">
		<input type="hidden" name="rememberme" value="0" />
		<input id="rememberme" type="checkbox" name="rememberme" value="1" />
		<label for="rememberme" style="font-weight: normal; font-size: 12px;color:#666">记住我</label>
		<input type="submit" value="登录" class="button_green" id="home_login_button" onclick="home_login();return false;" >
		</p>
		<p style="padding:10px 0px 0px 12px; "><a  href="<?= URL::site('user/register') ?>">立即注册</a>&nbsp;&nbsp;<a  href="/help/forgetAccount" style="color:#999">找回密码</a></p>
	</form>
    </div>
</div>