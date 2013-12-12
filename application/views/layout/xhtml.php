<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
<title><?= isset($_title) ? $_title . '- ' : '' ?><?= @$_SETTING['site_name'] ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="keywords" content="<?= $_SETTING['keywords'] ?>" />
        <meta name="description" content="<?= $_SETTING['description'] ?>" />
        <link rel="icon" href="/favicon.ico" type="image/x-icon" />
        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
        <link rel="stylesheet" href="/static/css/global.css" type="text/css"  />
        <script type="text/javascript" src="/static/js/jquery-1.7.1.min.js"></script>
        <script type="text/javascript" src="/static/js/jquery.form.js"></script>
        <script type="text/javascript" src="/static/artDialog4.1.5/jquery.artDialog.source.js?skin=default"></script>
        <script type="text/javascript" src="/static/js/tips.js"></script>
        <script type="text/javascript" src="/static/js/jquery.scrollTo.js"></script>
        <script type="text/javascript" src="/static/js/global.js"></script>
        <?= @$_static_media ?><?= @$_custom_media ?>
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