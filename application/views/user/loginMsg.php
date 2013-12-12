<!-- user/login_msg:_body -->
<div style="padding: 10px; line-height: 1.8em">
<form id="loginMsgForm" method="post" action="<?= URL::site('user/loginMsg') ?>">

    亲爱的<?=$_CONFIG->base['alumni_name']?>：<br>
    &nbsp;&nbsp;&nbsp;&nbsp;欢迎回来^_^！<br>
    &nbsp;&nbsp;&nbsp;&nbsp;为了更好的凝聚校友与服务母校，我们现已经对网站及用户系统做了升级，如果您<span style="color: green">已有账号</span>，请直接使用原注册信息<span style="color: green">E-mail+密码</span>进行登录，如忘记邮件或密码，请使用<a href="<?=URL::site('help/forgetAccount')?>">账号找回</a>功能，谢谢！
    <input type="submit" name="我知道了" style="display: none">
</form>
    </div>
