<?php $site_url = Kohana::config('links.site'); ?>
<div style="font-size:14px">
<p>尊敬的<?=$_CONFIG->base['alumni_name']?>：</p>
<p>
您好！校友<b><?=$sender?></b>邀请你加入<?=$_CONFIG->base['sitename']?>，加入校友网，您可以：<br />

<div style="padding: 15px; line-height: 1.6em;background: #FCFADE; border: 1px solid #EAE38B; color: #6C4913;">
1、及时了解母校的信息；<br>
2、认识更多的<?=$_CONFIG->base['alumni_name']?>；<br>
3、寻找与您同城其他的<?=$_CONFIG->base['alumni_name']?>；<br>
4、加入俱乐部发起或参加各式活动；<br>
5、关注您的同学最新动态；<br>
6、分享您的新鲜事到校友网并同步到微博；<br>
7、更多...
</div>

<p>接受邀请请点击以下链接进入注册，祝您使用愉快 :)<br>
    <a href="<?=$url ?>"><?= $url ?></a>
</p>
<p style="color:#798699">
    本邮件为系统发出，请勿直接回复。
    如有任何疑问请直接与我们电话联系。<br>
</p>
</div>