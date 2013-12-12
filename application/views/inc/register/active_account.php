<?php
$site_url = Kohana::config('links.site');
?>
<p>亲爱的<?= $realname ?>校友：</p>
<p>
    &nbsp;&nbsp;&nbsp;&nbsp;您好！欢迎加入<?=$config['sitename']?>，为了更好的服务和确保邮件的真实性，特请您点击以下链接激活您的帐号：<br />
<div style="border:1px dotted #999; margin: 15px 0; padding: 15px">
    <a href="<?= $site_url.'user/active?addr='.urlencode($account).'&enc='.md5($account.'zuaa') ?>" style="color:#0A900A">
        <?= $site_url.'user/active?addr='.urlencode($account).'&enc='.md5($account.'zuaa') ?>
    </a>
</div>

如果以上链接不能点击，你可以复制网址URL，然后粘贴到浏览器地址栏打开，完成确认。
<br /><br />
谢谢您的支持，您的登录帐号为：<a href="<?= $site_url.'user/login';?>"><?= $account ?></a>
</p>
<p style="color:#999">
    本邮件为系统发出，请勿直接回复。<br>
    如有任何疑问请直接与我们电话联系。
</p>