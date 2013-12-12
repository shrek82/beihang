<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?= $event['title'] ?></title>
<?= View::factory('api/static_media') ?>
    </head>
    <body style="background: #EFEFEF;color: #333">
        <div style="font-size:18px;font-weight:bold; text-align:left; color: #c00"><?= $event['title'] ?></div>
        <div style="padding:5px 2px;word-break:break-all; color: #333; line-height: 1.6em">
            <?= $content; ?>
        </div>
    </body>
</html>