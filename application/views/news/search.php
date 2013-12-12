<!-- news/search:_body -->
<style type="text/css">
#news_search_result td{ border-bottom: 1px dotted #eee; }
</style>
<div class="span-16">
<h5>跟“<?= $tag ?>”相关的新闻结果(<?= $pager->total_items ?>条)：</h5>
</div>
<div class="span-7 last" style="text-align: right">
<form action="<?= URL::site('news/search') ?>" method="get">
    <input type="text" name="q" value="<?= $tag ?>" onclick="this.value=''" />
    <input type="submit" value="找" />
</form>
</div>

<?php if(count($news) == 0): ?>
<div class="notice">
没有找到相关的新闻，看看其他标签？
</div>
<div>
    <?= View::factory('inc/news/tags_cloud', array('tags'=>Model_News::rand(), 'url'=>'')) ; ?>
</div>
<?php else: ?>
<?= $pager ?>
<table width="100%" id="news_search_result">
    <tr>
        <th>标题</th>
        <th>点击</th>
        <th>更新时间</th>
    </tr>
    <?php foreach($news as $n): ?>
    <tr>
        <td><a href="<?= URL::site('news/view?id='.$n['id']) ?>"><?= $n['title'] ?></a></td>
        <td class="center"><?= $n['hit'] ?></td>
        <td class="center"><?= $n['update_at'] ?></td>
    </tr>
    <?php endforeach; ?>
</table>
<?= $pager ?>
<?php endif; ?>