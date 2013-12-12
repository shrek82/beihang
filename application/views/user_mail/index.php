<!-- user_mail/index:_body -->
<div id="big_right">
     <div id="plugin_title">校友邮箱</div>
<div class="tab_gray" id="user_topbar" style="margin-top:10px">
   <ul>
       <li><a href="<?= URL::site('user_event/index') ?>" class="cur" style="width:80px">我的邮箱</a></li>
    </ul>
</div>
<?php if($mail):?>
     <div style="line-height:1.7em;font-size:12px">
         邮箱地址：<a href="<?=URl::site('mail')?>" style="color:#0B60AF" title="点击进入" target="_blank"><?=$mail['username']?>@zuaa.zju.edu.cn</a>
         <br>申请日期：<?=$mail['create_at']?><br>
</div>
<?php else:?>
 很抱歉，您还没有申请校友有邮箱，<a href="<?=URl::site('mail')?>" style="color:#0B60AF">点击申请“@zuaa.zju.edu.cn”邮箱</a>
<?php endif;?>

</div>
