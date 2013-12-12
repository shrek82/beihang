<!DOCTYPE html><html lang="zh">
    <head><?php $iPhone = strpos($_SERVER['HTTP_USER_AGENT'], "iPhone"); ?>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width"/>
    <rel="apple-touch-icon" href="/apple-touch-icon.png"/>
    <?php if ($iPhone): ?><meta name="viewport"  content="width=device-width,minimum-scale=1.0, maximum-scale=1.0" /><? endif; ?>
    <title> <?= $event['title']; ?></title>
    <style type="text/css">
        @media screen and (max-device-width: 480px){
            body{
                -webkit-text-size-adjust:none;
                font-family:Helvetica, Arial, Verdana, sans-serif;
                padding:5px;
                color: #333;
            }
            img{max-width:100%;height:auto;}
            div{
                clear:both!important;
                display:block!important;
                width:100%!important;
                float:none!important;
                margin:0!important;
                padding:0!important;
            }
            a:hover,a.hover{color:#f30}
        }
    </style>
</head>
<body>
    <h3 style="color:#c00"> <?= $event['title']; ?></h3>
    <div style="word-wrap: break-word; word-break: break-all;overflow:hidden;"><?= $event['content']; ?></div>
</body>
</html>