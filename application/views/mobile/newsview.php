<div class="g  one-whole" >
    <article class="post" >
        <h4 style="font-weight: bold;clear: both"><?= $news['title'] ?></h4>
        <div style="word-wrap: break-word; word-break: break-all;overflow:hidden;"><?= $news['content'] ?></div>

<div style="color: #999; text-align: right"><?= $news['author_name'] ? '作者:' . $news['author_name'] . '&nbsp;&nbsp;' : ''; ?></div>

    </article>

<?= View::factory('mobile/commentlist', array('search' => array('news_id' => $news['id'], 'order' => 'DESC', 'limit' =>5))); ?>
</div>