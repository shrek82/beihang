<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?= isset($_title) ? $_title . '- ' : '' ?><?= @$_SETTING['site_name'] ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="keywords" content="<?= $_SETTING['keywords'] ?>" />
        <meta name="description" content="<?= $_SETTING['description'] ?>" />
        <link rel="icon" href="<?= URL::base() ?>favicon.ico" type="image/x-icon" />
        <link rel="shortcut icon" href="<?= URL::base() ?>favicon.ico" type="image/x-icon" />
        <link type="text/css" href="/static/css/global.css" rel="stylesheet" />
        <link rel="stylesheet" href="/static/colorbox/colorbox.css" type="text/css"  />
        <link type="text/css" href="/static/css/bbs.css" rel="stylesheet" />
        <script type="text/javascript" src="/static/js/jquery-1.7.1.min.js"></script>
        <script type="text/javascript" src="/static/js/jquery.form.js"></script>
        <script type="text/javascript" src="/static/artDialog4.1.5/jquery.artDialog.source.js?skin=default"></script>
        <script type="text/javascript" src="/static/js/jquery.scrollTo.js"></script>
        <script type="text/javascript" src="/static/js/tips.js"></script>
        <script type="text/javascript" src="/static/js/jquery.unveil.js"></script>
        <script type="text/javascript" src="/static/js/global.js"></script>
        <?= @$_static_media ?><?= @$_custom_media ?>
        <script type="text/javascript" >
            function colorboxShow(href) {
                $.colorbox({href: href});
            }
            ;
        </script>
    </head>
    <body id="htmlbody">
        <div id="append_parent"></div>
        <div class="container" >

            <div id="header" >
                <?= @$_header_top ?>
                <div class="clear"></div>
            </div>
            <div id="body" >
                <?php if (!$_SETTING['close_bbs']): ?>
                    <?= @$_body ?>
                    <div class="clear"></div>
                <?php else: ?>
                    <div style='padding-top:140px;height: 200px;text-align: center;font-size: 12px;color: #999'><img src="/static/images/ico_confirm.jpg" style="border-width: 0;vertical-align: middle">&nbsp;;(&nbsp; 很抱歉，论坛临时关闭中，请稍候访问，谢谢！</div>
                <?php endif; ?>
            </div>
            <div id="footer">
                <?= @$_footer_bottom ?>
            </div>
        </div>
    </body>
</html>