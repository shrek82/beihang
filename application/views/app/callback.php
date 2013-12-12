<!-- app/callback:_body -->
<h2 style="color:#666"><img src="/static/logo/sina/32x32.png" style="vertical-align: middle"> 绑定微博新浪</h2>
<div style="width:100%; text-align: center; height: 350px; padding-top: 30px">
        <?php if(@empty($error)):?>
    <?php if(isset($synchronous_info['avatar_large']) AND !empty($synchronous_info['avatar_large'])):?>
    <div style="width:100%; text-align: center; height: 120px; padding: 10px">
        <img src="<?=$synchronous_info['avatar_large']?>" style="width:110px;height:110px;-webkit-border-radius: 10px;border-radius: 10px;"/>
    </div>
    <iframe frameborder="0" src="/test/downloadAvatar" style="width:0;height: 0;border-width:0"></iframe>
    <?php endif;?>
        <div style="color: #087E12; font-size: 18px; font-weight: bold; line-height: 100px; height: 100px">
                <img src="/static/images/accepted_48.png" style="vertical-align: middle">
                &nbsp;恭喜您，微博绑定成功！<a href="/user_home" style="font-size: 18px; font-weight: bold;">进入个人主页</a>
        </div>
        <?php else:?>
        <div style="color: #E15342; font-size: 18px; font-weight: bold; line-height: 100px; height: 100px">
                <img src="/static/images/error.gif" style="vertical-align: middle">
                &nbsp;<?=$error;?> <a href="/user_info/binding" style="font-size: 18px; font-weight: bold;">返回重试</a>
        </div>
        <?php endif;?>
</div>