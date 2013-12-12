<!-- invite/index:_body -->
<style type="text/css">
        #body{background: #fff;}
        .invite_body{ margin:0px 20px;background: #fff; font-size: 16px; height: 300px; padding: 20px;}
        .invite_message{ padding: 10px 40px; line-height: 1.8em}
        .invite_title{padding: 10px 20px; line-height: 1.7em; font-size: 14px}
        .message{color:#c00;border: 1px dotted #CEB740;  background: #FCFCF0; padding: 20px; margin: 15px 0}
        .regButton{ width: 117px; height: 34px; background: url(/static/images/zhuce_bg.png) no-repeat; color: #fff; font-size: 14px; border-width: 0; cursor: pointer}
        .regButton:hover{ color: #E8F5E5}
        .invite_body,.regButton{font-family:'Microsoft YaHei','Microsoft JhengHei';font-weight: normal}
</style>
<div class="invite_body">
        <?php if (isset($invite)): ?>
        <?php
        $user_info=$invite['start_year']?$invite['start_year'].'级':'';
        $user_info=$invite['speciality']?$user_info.$invite['speciality']:$user_info;
        ?>
                <div style="font-weight:bold"> 亲爱的<?=$_CONFIG->base['alumni_name']?>：</div>
                <?php if ($invite['type'] == 'regLink'): ?>
                <div class="invite_message">您好！您正在浏览<span style="color:#3964CE"><?=$invite['realname']?></span><?=$user_info?'('.$user_info.')':'';?>发送给大家的加入校友网公开邀请，<?=$invite['sex']=='女'?'她':'他';?>想说：
                <?php elseif ($invite['type'] == 'regLink'): ?>
                <?php elseif ($invite['type'] == 'regLink'): ?>
                <?php endif; ?>
                        <div class="message"><?= nl2br($invite['message']) ?></div>
                        <div style="text-align:right"><input type="button" class="regButton" value="立即加入 >>" onclick="window.location.href='/user/register'"></div>
                        </div>

        <? elseif (isset($error)): ?>
                <?= $error; ?>
        <? endif; ?>
</div>
