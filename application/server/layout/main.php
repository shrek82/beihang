<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?php if ($_C . '/' . $_A == 'index/index'||$_C . '/' . $_A == 'main/index'): ?><?= isset($_title) ? $_title : $_CONFIG->base['sitename'] ?><? else: ?><?= isset($_title) ? $_title . '- ' : '' ?><?= @$_CONFIG->base['sitename'] ?><?php endif; ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="keywords" content="北航校友会,浙江大学校友会,北航校友总会,浙江大学校友总会,北航校友,浙江大学校友" />
        <meta name="description" content="加入北航校友网，与校友亲切交流，参与校友会各种活动，从这里您也可以了解到母校最新动态，让我们共同在快乐中进步！" />
        <link rel="icon" href="/favicon.ico" type="image/x-icon" />
        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
        <link rel="stylesheet" href="/static/css/global.css?v=<?= time() ?>" type="text/css"  />
        <link rel="stylesheet" href="/static/css/home.css" type="text/css"  />
<style type="text/css">
html { 
filter: url(/static/filters.svg#grayscale); /* Firefox 3.5+ */
filter: gray; /* IE6-9 */
-moz-filter: grayscale(100%);
-ms-filter: grayscale(100%);
-webkit-filter: grayscale(1);
-o-filter: grayscale(100%);
} 
</style>
        <script type="text/javascript" src="/static/jquery-1.7.1.min.js"></script>
        <script type="text/javascript" src="/static/jquery.form.js"></script>
        <script type="text/javascript" src="/static/artDialog4.1.5/jquery.artDialog.source.js?skin=default"></script>
        <script type="text/javascript" src="/static/jquery.scrollTo.js"></script>
        <script type="text/javascript" src="/static/tips.js?v=20120818"></script>
        <script type="text/javascript" src="/static/global.js?v=20121220"></script>
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