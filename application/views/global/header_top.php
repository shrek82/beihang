<!-- global/header_top:_header_top -->
<!--top -->
<?php
if ($_URI == 'user/login') {
    $redirect = URL::site('user_home');
} else {
    $redirect = $_URL;
}
?>
<div id="zuaa_bg">
    <div class="logo_tool" >
        <div class="left"><a href="/" style="display:block;width:250px;height:58px"></a></div>
        <div class="right"><div class="user_nav"><?= View::factory('global/user_tool'); ?></div>
            <div class="site_search">
                <?php
                $for = 'news';
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
                    <p><input name="q" type="text" class="site_search_input" title="输入新闻、组织、活动、姓名等关键字进行搜索" ></p>
                    <p><input type="submit" class="site_search_button" value="" ></p>
                    <input type="hidden" name="for" value="<?= $for ?>"  >
                </form>
            </div>
        </div>
    </div>
</div>

    <div id="new-nav">
        <div id="menu" class="menu clear">
            <div class="main-menu" style="clear:both;">
                <div class="head">
                    <div class="outer">
                        <div class="inner">
                            <ul>
        <li <?= $_C == 'main' || $_C == 'index' ? 'class="first current" ' : 'class="first" ' ?>><a href="/" >首页</a></li>
        <li <?= $_C == 'news'? 'class="current" ' : '' ?>><a href="<?= URL::site('news') ?>" >新闻中心</a></li>
        <li <?= $_C == 'aa'? 'class="current" ' : '' ?>><a href="<?= URL::site('aa/branch') ?>" >校友组织</a></li>
        <li <?= $_C == 'event'? 'class="current" ' : '' ?>><a href="<?= URL::site('event') ?>" >校友活动</a></li>
        <li <?= $_C == 'classroom'? 'class="current" ' : '' ?>><a href="<?= URL::site('classroom') ?>" >班级录</a></li>
        <li <?= $_C == 'bbs'? 'class="current" ' : '' ?>> <a href="<?= URL::site('bbs/list') ?>" >交流园地</a></li>
        <li <?= $_C == 'people'? 'class="current" ' : '' ?>><a href="<?= URL::site('people') ?>" >北航校友</a></li>
        <li <?= $_C == 'donate'? 'class="current" ' : '' ?>><a href="<?= URL::site('donate') ?>" >校友捐赠</a></li>
        <li <?= $_C == 'publication'? 'class="current" ' : '' ?>><a href="<?= URL::site('publication') ?>" >校友刊物</a></li>
        <li <?= $_C == 'service'? 'class="current" ' : '' ?>><a href="<?= URL::site('service') ?>" >校友服务</a></li>

                            </ul>
                            <span class="total"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!--//top -->