<!-- help/forgetAccount:_body -->
<style type="text/css">
    ul li{ margin-bottom: 5px;font-size:13px}
</style>

<p><img src="/static/images/help_title.gif"></p>
<div style="margin:0px 20px;padding:4px 10px; ">

    <!-- 返回已找到的账号 -->
    <?php if ($q && $type == 'name'): ?>
        <h2>找到以下姓名为<?= $q ?>的注册用户：</h2>
        <ul style="margin:0px 20px;padding:0;font-size:14px; font-family: verdana">
	<?php foreach ($user as $key => $u): ?>
    	<li><?= $key + 1 ?>. <?= $u['realname'] ?>，<?= $u['sex'] ?>，<?= $u['speciality'] ?><span style="color:#006600;font-weight: bold;font-size:14px">（<?= Model_User::safeEmail($u['account']) ?>)</span></li>
	<?php endforeach; ?>
        </ul>

    <?php if (count($user) == 0): ?>
	<div class="nodata">很抱歉，没有找到姓名为<span style="color:blue">“<?= $q ?>”</span>的用户，可能是还没有注册或未加入，请返回重新注册或重试，谢谢。<a href="<?=URL::site('help/forgetAccount')?>">返回</a></div>
    <?php else: ?>
		    <div style="padding:20px; color: #c00;font-size:14px">
			<b>温馨提示</b>：如以上姓名和邮件中包含有您的信息，请使用完整的E-mail地址登陆；如忘记密码，请使用取回功能，祝您使用愉快，谢谢。<br><br><a href="<?=URL::site('help/forgetAccount')?>">返回</a>
		    </div>
    <?php endif; ?>

		    <!-- 返回E-mail找回密码结果 -->
    <?php elseif ($q && $type == 'email'): ?>
		        <h2>通过E -mail重设密码：</h2>
    <?php if ($err): ?>
    <?= $err ?><a href="javascript:window.history.back()">返回</a>
    <?php elseif ($success): ?>
    <?= $success ?>。
    <?php else: ?>
    <?php endif; ?>
				    <!-- 显示密码修改界面 -->
    <?php elseif ($uid && $addr && $enc): ?>
				        <h2>重新修改密码：</h2>
    <?php if ($err): ?>
    <?= $err ?>
    <?php elseif ($success): ?>
    <span style="color:#006600"><?= $success ?></span> <a href="<?= URL::site('user/login?account=' . $addr) ?>">立即登录</a>。
    <?php else: ?>

						    <form action="<?= URL::site('help/forgetAccount?uid=' . $uid . '&addr=' . urlencode($addr) . '&enc=' . $enc) ?>" method="post" >
							<ul style="margin:0px 20px;padding:0;font-size:12px">
							    <li><input type="hidden" name="account" value="<?= $addr ?>" >登录账号：<?= $addr ?></li>
							    <li><span  id="str">新密码</span>：<input type="text" name="newpassword" value="" class="input_text" style="width:250px;"><input type="submit" value="确定" class="button_blue"></li>
							</ul>
						    </form>
    <?php endif; ?>

						    <!-- 显示默认找回密码和账号界面 -->
    <?php else: ?>
						        <h2>已有账号：</h2>
						        <ul style="margin:0px 20px;padding:0;font-size:12px">
						    	<li>1、已有<?=$_CONFIG->base['alumni_name']?>总会账号：请使用E-mail+密码方式<span style="color:#666">（E-mail地址为个人资料内容，密码为原注册密码）</span>。</li>
						    	<li>2、已有杭州或无锡校友会注册账号：请使用E-mail+密码方式，E-mail地址为个人资料内容，密码保持不变。</li>
						    	<li>3、杭州校友会和无锡校友会校友，如已有总会网站注册账号，请以总会注册账号信息为准。</li>
						        </ul>
						        <p class="dotted_line" style="margin:20px 0"></p>
						        <h2 style="margin-top:30px">忘记了密码或账号：</h2>
						        <form action="<?= URL::site('help/forgetAccount') ?>" method="post" >
						    	<ul style="margin:0px 20px;padding:0;font-size:12px">
						    	    <li><input type="radio" name="type" value="name" id="type1" checked   onclick="document.getElementById('str').innerHTML='输入姓名'"><label for="type1">我忘记了所有注册信息，根据姓名查找我的注册信息。</label></li>
						    	    <li><input type="radio" name="type" value="email" id="type2" onclick="document.getElementById('str').innerHTML='输入E-mail'"><label for="type2">我知道E-mail地址，但我忘记了登录密码，我要修改密码。</label></li>

						    	    <li>&nbsp;</li>
							    <li><span  id="str">输入姓名</span>：<input type="text" name="q" id="q" value="" class="input_text" style="width:250px;">&nbsp;&nbsp;<input type="submit" value="下一步" class="button_blue"></li>
						    	</ul>
						        </form>
						        <p class="dotted_line" style="margin:20px 0"></p>
						        <h2 style="margin-top:30px">还没有账号：</h2>
						        <ul style="margin:0px 20px;padding:0;font-size:12px">
						    	<li>没有任何账号？<a href="<?= URL::site('user/register') ?>">立即新注册</a>。</li>
						        </ul>
    <?php endif; ?>
</div>

<script language="javascript">
    /**
     * send
     */
    function send() {
	if(document.getElementById('q').value=='')
	{
	    alert('请输入姓名或邮件地址，谢谢!');
	    document.getElementById('q').focus();
	    return false;
	}
    }
</script>