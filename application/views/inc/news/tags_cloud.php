<?php if(count($tags) > 0): foreach($tags as $tag): ?>
<a href="<?= URL::site('news/search?q='.urlencode($tag['name'])) ?>"
   style="font-size: <?= Model_News::size($tag['rate']) ?>px">
    <?= $tag['name'] ?>
</a>
<?php endforeach; endif; ?>