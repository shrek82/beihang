<?php
//版本：1.0
?>
<?php if($_SESS->get('role') == '校友(未激活)'): ?>
<div>
    <a id="user_reactive" href="javascript:void(0)" onclick="sendActiveMail()">发送激活邮件</a>
    <script type="text/javascript">
        function sendActiveMail(){
            new Request({
                url: '<?= URL::site('user/reactive') ?>',
                beforeSend: function(){
                    $('user_reactive').set('html', '发送中请稍等..');
                },
                success: function(resp){
                    $('user_reactive').set('html', resp);
                }
            }).send();
        }
    </script>
</div>
<?php else: ?>
<div style="height: 10px;"></div>
<?php endif; ?>

<div style="background: #f5f5f5;" class="candyCorner">
    <a href="<?= URL::site('user_info/base') ?>" class="<?= $_C == 'user_info' ? 'cur':'' ?>">资料</a> |
    <a href="<?= URL::site('user_home/avatar') ?>" class="<?= $_URI == 'user_home/avatar' ? 'cur':'' ?>">形象</a> |
    <a href="<?= URL::site('user_bubble/index') ?>" class="<?= $_C == 'user_bubble' ? 'cur':'' ?>">状态</a> |
    <a href="<?= URL::site('user_bbs/index') ?>" class="<?= $_C == 'user_bbs' ? 'cur':'' ?>">论坛</a>
</div>

<div>
    <a href="<?= URL::site('user_album/index') ?>" class="<?= $_C == 'user_album' ? 'cur':'' ?>">相册</a> |
    <a href="<?= URL::site('user_event/index') ?>" class="<?= $_C == 'user_event' ? 'cur':'' ?>">活动</a> |
    <a href="<?= URL::site('user_news/index') ?>" class="<?= $_C == 'user_news' ? 'cur':'' ?>">新闻</a> |
    <a href="<?= URL::site('user_msg/index') ?>" class="<?= $_C == 'user_msg' ? 'cur':'' ?>">消息</a>
</div>