<!-- global/header_top:_header_top -->
<div id="top">
    <?php
    $links = Kohana::config('links.userHome');
    ?>

    <div id="nav">

        <div class="logo">
            <a href="/"  title="回到网站首页"><img src="/static/images/user/nav_logo.jpg" width="219" height="56" border="0"></a>
        </div>

        <ul>
            <?php foreach ($links as $uri => $name): ?>
                <li><a href="<?= URL::site($uri) ?>" <?= strstr($uri, $_C) ? 'class="cur"' : '' ?>><?= $name ?></a></li>
            <?php endforeach; ?>
        </ul>

        <?php
        if ($_URI == 'user/login') {
            $redirect = URL::site('user_home');
            $href = '';
        } else {
            $redirect = $_URL;
            $href = 'javascript:faceboxUserLogin()';
        }
        ?>
        <div id="user_panel">

            <?php if (!$_UID): ?>
                <a href="<?= URL::site('user/register') ?>">注册新账户</a>
                <?php if ($_C . '/' . $_A != 'main/index'): ?>
                    <a href="<?= $href ?>" style="color: #c4df9f" id="flogin_btn">[登录校友网]</a>
                <?php endif; ?>
            <?php else: ?>
                欢迎回来，<?= $_SESS->get('realname') ?>&nbsp;&nbsp;<?= View::factory('inc/user/msg') ?><? //View::factory('inc/user/sys_msg')  ?><a href="<?= URL::site('user_home') ?>">个人主页</a>&nbsp;|&nbsp; <?php if ($_SESS->get('role') == '管理员'): ?>
                    <a href="<?= URL::site('admin') ?>">管理后台</a>&nbsp;&nbsp;
                <?php endif; ?><a href="<?= URL::site('user/logout') ?>">退出</a>
            <?php endif; ?>
        </div>
    </div>
</div>