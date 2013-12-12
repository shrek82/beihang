<?php $site_url = Kohana::config('links.site'); ?>
<h2>尊敬的<?= $realname ?>校友：</h2>
<p>
    请点击下面链接，重新修改您在本站的登录密码：<br />
    <a href="<?= $site_url.'help/forgetAccount?uid='.$uid.'&addr='.urlencode($account).'&enc='.$enc ?>">
        <?= $site_url.'help/forgetAccount?uid='.$uid.'&addr='.urlencode($account).'&enc='.$enc ?>
    </a><br /><br>
     祝您使用愉快！<br>
</p>
<p>
    此信是由系统发出，系统不接收回信，请勿直接回复。
    如有任何疑问请<a href="mailto:linyupark@gmail.com">联系技术人员</a>。
</p>
