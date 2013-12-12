<?php if (!$_UID): ?>
    欢迎回来！<a href="<?= URL::site('user/register') ?>" >加入校友网</a>&nbsp;&nbsp;
    <?php if ($_C . '/' . $_A != 'main/index'): ?>
        <a href="javascript:;" onclick="loginForm('<?= isset($redirect) ? $redirect : $_URL; ?>')" id="flogin_btn">登录</a>
    <?php endif; ?>
<?php else: ?>
欢迎回来，<a href="<?= URL::site('user_home') ?>" title="进入个人主页"><?=$_SESS->get('realname')?></a>&nbsp;&nbsp;<?= View::factory('inc/user/msg') ?>
    &nbsp;&nbsp;
    <?php if ($_SESS->get('chairman_aa')): ?>
        <a href="<?= URL::site('aa_admin?id=' . $_SESS->get('chairman_aa')) ?>">管理校友会</a>&nbsp;&nbsp;
    <?php endif; ?><?php if ($_SESS->get('role') == '管理员'): ?>
        <a href="<?= URL::site('admin') ?>">管理后台</a>&nbsp;&nbsp;
    <?php endif; ?><a href="<?= URL::site('user/logout') ?>">退出</a>
<?php endif; ?>
