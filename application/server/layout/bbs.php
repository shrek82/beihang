<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>
            <?php if ($_C . '/' . $_A == 'index/index'): ?>
                <?= @isset($_title) ? $_title : $_CONFIG->base['sitename'] ?>
            <? else: ?>
                <?= isset($_title) ? $_title . '- ' : '' ?><?= @$_CONFIG->base['sitename'] ?>
            <?php endif; ?>
        </title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="keywords" content="北航校友BBS，北航校友,浙江大学校友总会,北航校友总会,浙江大学校友" />
        <meta name="description" content="登录北航校友网，参与校友活动、查看校友新闻、参与校友讨论，在共同中共同进步！" />
        <link rel="icon" href="<?= URL::base() ?>favicon.ico" type="image/x-icon" />
        <link rel="shortcut icon" href="<?= URL::base() ?>favicon.ico" type="image/x-icon" />
        <link type="text/css" href="/static/css/global.css?v=20121220" rel="stylesheet" />
        <link rel="stylesheet" href="/static/colorbox/colorbox.css" type="text/css"  />
        <link type="text/css" href="/static/css/bbs.css?v=201303" rel="stylesheet" />
        <script type="text/javascript" src="/static/jquery-1.7.1.min.js"></script>
        <script type="text/javascript" src="/static/jquery.form.js"></script>
        <script type="text/javascript" src="/static/artDialog4.1.5/jquery.artDialog.source.js?skin=default"></script>
        <script type="text/javascript" src="/static/colorbox/jquery.colorbox-min.js"></script>
        <script type="text/javascript" src="/static/jquery.scrollTo.js"></script>
        <script type="text/javascript" src="/static/tips.js?v=20120818"></script>
        <script type="text/javascript" src="/static/global.js?v=20121220"></script>
        <script type="text/javascript" >
            $(document).ready(function(){
                $(".colorboxPic").colorbox({rel:'colorboxPic'});
            });
            function colorboxShow(href) {
                $.colorbox({href:href});
            }
        </script>
    </head>
    <body id="htmlbody">
        <div id="append_parent"></div>
        <div class="container" >

            <div id="header" >
                <?= @$_header_top ?>
                <?= @$_header ?>
                <?= @$_header_bottom ?>
                <div class="clear"></div>
            </div>
            <div id="body" >
                <div class="clear"></div>
                <?= @$_body_top ?>
                <?= @$_body_left ?>
                <?= @$_body ?>
                <?= @$_body_right ?>
                <?= @$_body_bottom ?>
                <div class="clear"></div>
            </div>

            <div id="footer">
                <div class="clear"></div>
                <?= @$_footer_top ?>
                <?= @$_footer ?>
                <?= @$_footer_bottom ?>
                <div class="clear"></div>
            </div>

            <?php if (isset($_debug)): ?>
                <div id="debug" class="candy-debug">
                    <?= $_debug ?>
                </div>
            <?php endif; ?>

        </div>
    </body>
</html>