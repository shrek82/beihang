<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?= isset($_title) ? $_title . ' - ' : ''; ?><?= isset($_AA['name']) ? $_AA['name'] : ''; ?><?= isset($_CLUB['name']) ? $_CLUB['name'] : ''; ?><?= isset($_CLASSROOM['name']) ? $_CLASSROOM['name'] : ''; ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="keywords" content="<?= isset($_AA['name']) ? $_AA['name'] : ''; ?>" />
        <link type="text/css" href="/static/colorbox/colorbox.css" rel="stylesheet" />
        <link rel="stylesheet" href="/static/homepage/global.css?v=20121220" type="text/css"  />
        <?php if ($_THEME): ?><link rel="stylesheet" href="/static/homepage/<?= $_THEME['theme'] ?>/style.css" type="text/css" id="customCssPath" />
            <?php if ($_THEME['usercustom']): ?><style type="text/css">body{<?= $_THEME['background_color'] ? 'background-color:#' . $_THEME['background_color'] . ';' : ''; ?><?php if ($_THEME['background_image']): ?>background-image:url(<?= $_THEME['background_image'] ?>);background-position: top center;background-repeat:no-repeat;<?php endif; ?>}</style><?php endif; ?>
        <?php else: ?><link rel="stylesheet" href="/static/homepage/theme1/style.css" type="text/css" id="customCssPath" />
        <?php endif; ?><?= @$_static_media ?><?= @$_custom_media ?><script type="text/javascript" src="/static/jquery-1.7.1.min.js"></script>
        <script type="text/javascript" src="/static/jquery.form.js"></script>
        <script type="text/javascript" src="/static/jquery.scrollTo.js"></script>
        <script type="text/javascript" src="/static/artDialog4.1.5/jquery.artDialog.source.js?skin=default"></script>
        <script type="text/javascript" src="/static/global.js?v=20121220"></script>
        <script type="text/javascript" src="/static/homepage/aa_v2.pack.js?v=20120818"></script>
        <script type="text/javascript" src="/static/colorbox/jquery.colorbox-min.js"></script>
        <script type="text/javascript" src="/static/tips.js?v=20120818"></script>
        <script type="text/javascript" >var AA_ID=<?= $_ID ?>;</script>
    </head>
    <body data-stellar-background-ratio="0.5">
        <div id="append_parent"></div>
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