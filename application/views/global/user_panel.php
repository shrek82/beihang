<?php
    if($_URI == 'user/login'){
        $redirect = URL::site('user_home');
        $href = '';
    } else {
        $redirect = $_URL;
        $href = 'javascript:faceboxUserLogin()';
    }
?>
<div id="user_panel">
    <?php if( ! $_UID): ?>
    <a href="<?= URL::site('user/register') ?>">注册新账户</a>
    <?php if($_C.'/'.$_A != 'main/index'): ?>
    <a href="<?= $href ?>" style="color: #c4df9f" id="flogin_btn">[登录校友网]</a>
    <?php else: ?>
    <span id="flogin_btn"></span>
    <?php endif; ?>
    <script type="text/javascript">
    var ac = candyGetCookie('zuaa_ac');
    var pw = candyGetCookie('zuaa_pw');
    if(ac && pw){
        new Request({
            url: '<?= URL::site('user/login') ?>',
            data: 'account='+ac+'&password='+pw+'&rememberme=2',
            type: 'post',
            beforeSend: function(){
                $('flogin_btn').set('html', '自动登录中..');
            },
            success: function(data){
                if(data.contains('<?= Candy::MARK_SUCCESS ?>')){
                    history.go(0);
                }
            }
        }).send();
    }
    var faceboxUserLogin = function(){
        new Facebox({
            url: '<?= URL::site('user/login') ?>',
            title: '登录您的账号',
            width: 400
        }).show();
    };
    var userLoginForm = new ajaxForm('userLogin', {
        redirect: '<?= $redirect ?>'
    });
    </script>
    <?php else: ?>
欢迎回来，<?= $_SESS->get('realname') ?>

    <a href="<?= URL::site('user_home') ?>">[个人主页]</a>
    <?= View::factory('inc/user/msg') ?>
    <? View::factory('inc/user/sys_msg') ?>
    <? View::factory('inc/event/vote') ?>
    <?php if($_SESS->get('chairman_aa')):?>
    <a href="<?= URL::site('aa_admin?id='.$_SESS->get('chairman_aa')) ?>">[管理校友会]</a>
    <?php endif;?>

    <?php if($_SESS->get('role') == '管理员'): ?>
    <a href="<?= URL::site('admin') ?>">[管理后台]</a>
    <?php endif; ?>

    <a href="<?= URL::site('user/logout') ?>">[退出]</a>
    <?php endif; ?>
</div>

