<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title> <?= isset($_title) ? $_title . ' - ' : '' ?> <?= @$_CONFIG->base['sitename'] ?> - 管理中心</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link rel="icon" href="/favicon.ico" type="image/x-icon" />
        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
        <link rel="stylesheet" href="/static/css/admin.css?v=20120818" type="text/css" />
        <script type="text/javascript" src="/static/jquery-1.7.1.min.js"></script>
        <script type="text/javascript" src="/static/jquery.form.js"></script>
        <script type="text/javascript" src="/static/artDialog4.1.5/jquery.artDialog.source.js?skin=default"></script>
        <script type="text/javascript" src="/static/jquery.scrollTo.js"></script>
        <script type="text/javascript" src="/static/global.js?v=20121126"></script>
    </head>
    <body>
        <?= @$_body ?>
    </body>
</html>