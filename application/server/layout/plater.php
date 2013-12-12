<html>
<head>
    <title>
        <?php $plater->block('title') ?>
            Layout title
        <?php $plater->endblock('title') ?>
    </title>
</head>
<body>
    <?php $plater->block('content') ?>
        Layout content
    <?php $plater->endblock('content') ?>
</body>
</html>