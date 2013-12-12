<!DOCTYPE html>
<html lang=en>
    <head>
        <meta charset=UTF-8>
        <meta name=viewport content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">
        <title><?= isset($_title) ? $_title . ' - ' : '' ?> 北航校友网</title>
        <link rel=stylesheet href="/static/mobile/inuitcss.css">
        <link rel=stylesheet href="/static/mobile/bootstrap/css/bootstrap.min.css">
        <link rel="shortcut icon" href=apple-touch-icon.png>
        <link rel=apple-touch-icon-precomposed href=apple-touch-icon.png>
        <script type="text/javascript" src="/static/mobile/zepto.min.js"></script>
        <script type="text/javascript" src="/static/mobile/global.js"></script>
       <?php if($_UID):?>
        <script type="text/javascript">
       (function(){
zuaa.user.uid=<?=$_UID?>;zuaa.user.role='<?=$_ROLE?>';zuaa.user.token='<?=$_TOKEN?>';
       })();
       </script>
       <?php endif;?>
    </head>
    <body>
        <div class="wrapper" >
            <header class="page-head" >
                <?= @$_header; ?>
            </header>
            <div class="gw" >
                <?= @$_body; ?>
            </div>
        </div>
        <div class="page-foot">
            <?= @$_footer ?>
        </div>
    </body>
</html>