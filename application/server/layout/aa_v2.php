<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
        <head>
                <title><?= isset($_title) ? $_title . ' - ' : ''; ?><?= $_AA['name'] ?></title>
                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                <script type="text/javascript" src="/candybox/Media/js/mootools1.2.4.js"></script>
                <script type="text/javascript" src="/candybox/Media/js/candy.js"></script>
                <script type="text/javascript" src="/candybox/Facebox/facebox.js"></script>
                <script type="text/javascript" src="/static/global.js?version=20120920"></script>
                <script type="text/javascript" src="/static/jquery-1.4.2.min.js"></script>
               <script type="text/javascript" src="/static/artDialog4.0.5/artDialog.js?skin=bluesky"></script>
                <script type="text/javascript" src="/static/aa/aa_v2.pack.js?var=1.3"></script>
                <link rel="stylesheet" href="/static/aa/global.css?var=1.6" type="text/css"  />
                <?php if ($_THEME): ?>
               <link rel="stylesheet" href="/static/aa/<?= $_THEME['theme'] ?>/style.css" type="text/css"  />
               <?php if ($_THEME['usercustom']): ?><style type="text/css">body{<?= $_THEME['background_color'] ? 'background-color:' . $_THEME['background_color'] . ';' : ''; ?><?php if ($_THEME['background_image']): ?>background-image:url(/static/aa/background/<?= $_THEME['background_image'] ?>);background-position: top center;background-repeat:no-repeat;<?php endif; ?>}</style><?php endif; ?>
                <?php else: ?><link rel="stylesheet" href="/static/aa/deftheme/style.css" type="text/css"  /><?php endif; ?>
                <script type="text/javascript" >
                        var AA_ID=<?= $_ID ?>;
                        //释放$控制权
                        var $jquery = jQuery.noConflict();
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