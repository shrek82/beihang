<!-- news/search:_body -->
<?php if (count($news) == 0): ?><span class="nodata">很抱歉，暂时还没有您要找的活动。</span>

<?php else: ?>
        <p class="search_count">共找到 <?= $pager->total_items ?> 条符合条件的新闻：</p>

        <table width="100%" id="srtable" class="news_table">
            <tr>
                <th>标题</th>
                <th style="text-align: center">点击</th>
                <th style="text-align: center">发布时间</th>
            </tr>
    <?php foreach ($news as $n): ?>
            <tr>
                <td class="news_title"><a href="<?= URL::site('news/view?id=' . $n['id']) ?>" ><?= $n['title'] ?></a></td>
                <td style="text-align: center"><?= $n['hit'] ?></td>
                <td style="text-align: center"><?= date('Y-n-d', strtotime($n['create_at'])); ?></td>
            </tr>
    <?php endforeach; ?>

        </table>
<?= $pager ?>
<?php endif; ?>