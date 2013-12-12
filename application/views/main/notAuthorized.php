<div id="not_authorized" >
<?php if($_UID): ?>
    <img src="/static/images/ico4.gif" style="vertical-align: middle;margin: 5px">很抱歉，您<span style="color:blue">(<?=$role?>)</span>目前还不能使用该功能，可能是尚未通过认证，请等待或与管理员联系，谢谢！
<?php else:?>
    <img src="/static/images/ico4.gif" style="vertical-align: middle;margin:5px">很抱歉，您还没有登录或尚未通过审核，请先<a href="javascript:;" onclick="loginForm('<?= isset($redirect) ? $redirect : $_URL; ?>')" >登录</a> 或<a href="<?=URL::site('user/register')?>">注册</a>。
<?php endif;?>
</div>