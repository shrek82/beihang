<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?= $_SETTING['home_title'] ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="keywords" content="<?= $_SETTING['keywords'] ?>" />
        <meta name="description" content="<?= $_SETTING['description'] ?>" />
        <link rel="icon" href="/favicon.ico" type="image/x-icon" />
        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
        <link rel="stylesheet" href="/static/css/global.css" type="text/css"  />
        <link rel="stylesheet" href="/static/css/home.css" type="text/css"  />
        <script type="text/javascript" src="/static/js/jquery-1.7.1.min.js"></script>
        <script type="text/javascript" src="/static/js/jquery.form.js"></script>
        <script type="text/javascript" src="/static/artDialog4.1.5/jquery.artDialog.source.js?skin=default"></script>
        <script type="text/javascript" src="/static/js/tips.js"></script>
        <script type="text/javascript" src="/static/js/global.js"></script>
        <script type="text/javascript" src="/static/js/home.js?v=20130712"></script>
        <script type="text/javascript" src="/static/js/swfobject_source.js"></script>
        <?= @$_static_media ?><?= @$_custom_media ?>
        <?php if ($_SETTING['gray_site']): ?><style type="text/css">
                html { 
                    filter:progid:DXImageTransform.Microsoft.BasicImage(grayscale=1); 
                    filter: url(/static/filters.svg#grayscale); /* Firefox 3.5+ */
                    filter: gray; /* IE6-9 */
                    -moz-filter: grayscale(100%);
                    -ms-filter: grayscale(100%);
                    -webkit-filter: grayscale(1);
                    -o-filter: grayscale(100%);
                } 
            </style><?php endif; ?>
    </head>
    <body id="htmlbody">
        <div id="append_parent"></div>
        <div class="container">
            <div id="header">
                <?= @$_header_top ?>
                <div class="clear"></div>
            </div>
            <div id="body">
                <?= @$_body ?>
                <div class="clear"></div>
            </div>
            <div id="footer">
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