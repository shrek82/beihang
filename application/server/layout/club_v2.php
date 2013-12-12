<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?= isset($_title) ? $_title . ' - ' : ''; ?><?= $_CLUB['name'] ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <script type="text/javascript" src="/static/jquery-1.7.1.min.js"></script>
        <script type="text/javascript" src="/static/jquery.form.js"></script>
        <script type="text/javascript" src="/static/jquery.scrollTo.js"></script>
        <script type="text/javascript" src="/static/artDialog4.1.5/jquery.artDialog.source.js?skin=default"></script>
        <script type="text/javascript" src="/static/global.js"></script>
        <script type="text/javascript" src="/static/homepage/aa_v2.pack.js"></script>
        <link rel="stylesheet" href="/static/homepage/global.css" type="text/css"  />
        <?php if ($_THEME): ?>
            <link rel="stylesheet" href="/static/homepage/<?= $_THEME['theme'] ?>/style.css" type="text/css" id="customCssPath" />
            <?php if ($_THEME['usercustom']): ?><style type="text/css">body{<?= $_THEME['background_color'] ? 'background-color:' . $_THEME['background_color'] . ';' : ''; ?><?php if ($_THEME['background_image']): ?>background-image:url(/static/homepage/background/<?= $_THEME['background_image'] ?>);background-position: top center;background-repeat:no-repeat;<?php endif; ?>}</style><?php endif; ?>
        <?php endif; ?>
        <script type="text/javascript" >
            //窗口对象
            var $window=jQuery(window);
            //返回顶部居左宽度
            var $scrollToTopLeft=($window.width()-950)/2+945;
        </script>
    </head>
    <body>
        <?= @$_body_top ?>
        <div id="main">
            <?= @$_body_action ?>
            <?= @$_body ?>
            <div class="clear"></div>
        </div>
        <div id="footer">
            <?= @$_footer_bottom ?>
        </div>
    </body>
</html>