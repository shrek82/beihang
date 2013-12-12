<!-- global/header_top:_header_top -->
<!--top -->
<?php
if ($_URI == 'user/login') {
    $redirect = URL::site('user_home');
    $href = '';
} else {
    $redirect = $_URL;
    $href = 'javascript:faceboxUserLogin()';
}
?>
<div id="zuaa_bg">
    <div class="logo_tool" >
        <div class="left"><a href="<?= URL::site('main') ?>" style="display:block;width:250px;height:58px"></a></div>
        <div class="right">
            <div class="user_panel">
                <?php if (!$_SESS->get('id')): ?>
            		欢迎回来！<a href="<?= URL::site('user/register') ?>" >立即加入校友网</a>
                <?php if ($_C . '/' . $_A != 'main/index'): ?>
                        &nbsp;|&nbsp;<a href="<?= $href ?>" id="flogin_btn">登录</a>
                <?php endif; ?>

                        <script type="text/javascript">
                            var ac = candyGetCookie('zuaa_ac');
                            var pw = candyGetCookie('zuaa_pw');
                            if(ac && pw){
                                new Request({
                                    url: '<?= URL::site('user/login') ?>',
                                    data: 'account='+ac+'&password='+pw,
                                    type: 'post',
                                    beforeSend: function(){
                                        $('home_login_button').set('html', '登录中..');
                                    },
                                    success: function(data){
                                        if(data.contains('<?= Candy::MARK_SUCCESS ?>')){
                                            history.go(0);
                                        }
                                    }
                                }).send();
                            }
                            function faceboxUserLogin(){
                                 var loginbox=new Facebox({
                                    url: '<?= URL::site('user/login') ?>',
                                    title: '登录账号',
                                    width: 380
                                });
                                loginbox.show();
                            }

                            var userLoginForm = new CandyForm('userLogin', {
                                redirect: '<?= $redirect ?>'
                            });

                        </script>
                <?php else: ?>
            	    	    		欢迎回来，<?= $_SESS->get('realname') ?>&nbsp;&nbsp; <?= View::factory('inc/user/msg') ?><?=View::factory('inc/event/vote') ?><?=View::factory('inc/user/weibobinding') ?><a href="<?= URL::site('user_home') ?>">个人主页</a>&nbsp;|&nbsp; <?php if ($_SESS->get('chairman_aa')): ?>
                                <a href="<?= URL::site('aa_admin?id=' . $_SESS->get('chairman_aa')) ?>">管理校友会</a>&nbsp;|&nbsp;
                <?php endif; ?><?php if ($_SESS->get('role') == '管理员'): ?>
                                    <a href="<?= URL::site('admin') ?>">管理后台</a>&nbsp;&nbsp;
                <?php endif; ?><a href="<?= URL::site('user/logout') ?>">退出</a>
                <?php endif; ?>
                                </div>
                                <div class="site_search">
                <?php
                $for='news';
                switch ($_C) {
                                        case "news":
                                            $for = 'news';
                                             break;
                                        case "event":
                                             $for = 'event';
                                             break;
                                        case "classroom":
                                             $for = 'classroom';
                                             break;
                                        case 'aa':
                                        $for = 'org';
                                            break;
                                        case 'aa_home':
                                            $for = 'org';
                                            break;
                                        case 'user_home':
                                            $for = 'user';
                                            break;
                                        case 'bbs':
                                            $for = 'bbs';
                                            break;
                                    }
                ?>
                                    <form action="<?= URL::site('search') ?>" method="get" >
                                        <p><input name="q" type="text" class="site_search_input" title="输入新闻、组织、活动、姓名等关键字进行搜索"></p>
                                        <p><input type="submit" class="site_search_button" value="搜索" ></p>
                                       <input type="hidden" name="for" value="<?=$for?>"  >
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div id="nav">
                            <a href="<?= URL::site('main') ?>" <?= $_C == 'main' ? 'class="cur" ' : '' ?><?= $_C == 'news' ? 'class="nonebg" ' : '' ?>>首页</a>
                            <a href="<?= URL::site('news') ?>" <?= $_C == 'news' ? 'class="cur" ' : '' ?><?= $_C == 'aa' || $_C == 'aa_home' ? 'class="nonebg" ' : '' ?>>新闻中心</a>
                            <a href="<?= URL::site('aa/branch') ?>" <?= $_C == 'aa' || $_C == 'aa_home' ? 'class="cur" ' : '' ?><?= $_C == 'event' ? 'class="nonebg" ' : '' ?>>校友组织</a>
                            <a href="<?= URL::site('event') ?>" <?= $_C == 'event' ? 'class="cur" ' : '' ?><?= $_C == 'classroom' || $_C == 'classroom_home' ? 'class="nonebg" ' : '' ?>>校友活动</a>
                            <a href="<?= URL::site('classroom') ?>" <?= $_C == 'classroom' || $_C == 'classroom_home' ? 'class="cur" ' : '' ?><?= $_C == 'bbs' ? 'class="nonebg" ' : '' ?>>班级录</a>
                            <a href="<?= URL::site('bbs/list') ?>" <?= $_C == 'bbs' ? 'class="cur" ' : '' ?><?= $_C == 'people' ? 'class="nonebg" ' : '' ?>>交流园地</a>
                            <a href="<?= URL::site('people') ?>" <?= $_C == 'people' ? 'class="cur" ' : '' ?><?= $_C == 'donate' ? 'class="nonebg" ' : '' ?>>求是群芳</a>
                            <a href="<?= URL::site('donate') ?>" <?= $_C == 'donate' ? 'class="cur" ' : '' ?><?= $_C == 'publication' ? 'class="nonebg" ' : '' ?>>校友捐赠</a>
                            <a href="<?= URL::site('publication') ?>" <?= $_C == 'publication' ? 'class="cur" ' : '' ?><?= $_C == 'service' ? 'class="nonebg" ' : '' ?>>校友刊物</a>
                            <a href="<?= URL::site('service') ?>" <?= $_C == 'service' ? 'class="cur" ' : '' ?><?= $_C == 'mail' ? 'class="nonebg" ' : '' ?>>为您服务</a>
                            <a href="<?= URL::site('mail') ?>" <?= $_C == 'mail' ? 'class="cur" ' : 'nonebg' ?> >邮箱</a>
    </div>
</div>
<!--//top -->