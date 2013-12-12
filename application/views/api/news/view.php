<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?= $news['title'] ?></title>
        <?= View::factory('api/static_media') ?>
    </head>
    <body style="background: #EFEFEF;color: #333">
        <div style="font-size:18px;font-weight:bold; text-align:center; color: #333"><?= $news['title'] ?></div>
        <div style="text-align:center;color:#999;font-size:12px;margin:5px 0;"><?= $news['create_at'] ?> &nbsp;&nbsp;来源：<?= $news['aa_name'] ? $news['aa_name'] : '校友总会'; ?></div>
        <div style="border-top:1px solid #DAD8D8;border-bottom:1px solid #f7f7f7"></div>
        <div style="padding:5px 2px;word-break:break-all;color: #333; line-height: 1.6em">
            <?= $news['content']; ?>
        </div>
        <?php if ($news['is_comment']): ?>
            <?= View::factory('api/comment/list', array('search' => array('news_id' => $news['id'], 'order' => 'DESC', 'limit' => 5))); ?>
        <?php endif; ?>
    </body>
</html>