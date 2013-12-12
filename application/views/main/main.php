<?php
$_static_media =Html::style('static/css/home.css');
include_once 'xhtml.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?php if ($_C . '/' . $_A == 'main/index'): ?><?= isset($_title) ? $_title : $_CONFIG->base['sitename'] ?><? else: ?><?= isset($_title) ? $_title . '- ' : '' ?><?= @$_CONFIG->base['sitename'] ?><?php endif; ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link rel="icon" href="/favicon.ico" type="image/x-icon" />
        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
        <link rel="stylesheet" href="/static/css/global.css?v=20120818" type="text/css"  />
        <link rel="stylesheet" href="/static/css/home" type="text/css"  />
        <script type="text/javascript" src="/static/js/jquery-1.7.1.min.js"></script>
        <script type="text/javascript" src="/static/js/jquery.form.js"></script>
        <script type="text/javascript" src="/static/artDialog4.1.5/jquery.artDialog.source.js?skin=default"></script>
        <script type="text/javascript" src="/static/js/jquery.scrollTo.js"></script>
        <script type="text/javascript" src="/static/js/tips.js?v=20120818"></script>
        <script type="text/javascript" src="/static/js/global.js?v=20121126"></script>
    </head>
    <body id="htmlbody">
        <div id="append_parent"></div>
        <div class="container">
            <div id="header">
                <?= @$_header_top ?>
                <?= @$_header ?>
                <?= @$_header_bottom ?>
                <div class="clear"></div>
            </div>

            <div id="body">
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