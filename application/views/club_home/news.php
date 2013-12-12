<!-- aa_home/news:_body -->
<div id="main_left">

   <?php if (count($news) == 0): ?>
        <p class="nodata">暂时没有新闻信息。</p>
    <?php else: ?>

        <table width="100%" class="hp_table" id="news_table" cellspacing="0" cellpadding="0">
            <tr>
                <th style="text-align:left">&nbsp;标题</th>
                <th style="text-align:center;width:150px">作者</th>
                <th style="text-align:center;width:50px">时间</th>
            </tr>
            <?php foreach ($news as $n): ?>
                <tr style="border-bottom: 1px dotted #ccc">
                    <td>

                        <span class="cat">&nbsp;[<a href="<?= URL::query(array('category' => $n['category_id'])) ?>"><?= $n['category_name'] ?></a>]</span>

                        <a href="<?= URL::site('club_home/newsDetail?id=' . $_ID . '&nid=' . $n['id']) ?>" <?= $n['is_fixed'] ? 'style="color:#f30"' : ''; ?>><?= Text::limit_chars($n['title'], 30, '...'); ?></a><?php if ($n['is_fixed']): ?>&nbsp;&nbsp;<font style="color:#f60"><img src="/static/images/is_top.gif" title="置顶新闻"></font><?php endif; ?>
                    </td>
                    <td class="center">
                        <?php if ($n['author_name']): ?>
                            <?= $n['author_name'] ?>
                        <?php else: ?>
                            <a href="<?= URL::site('user_home?id=' . $n['user_id']) ?>"><?= $n['User']['realname'] ?></a>
                        <?php endif; ?>

                    </td>
                    <td class="center"><?= Date::span_str(strtotime($n['create_at'])) ?>前</td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    <?= $pager ?>
</div>

<div id="main_right">
    <p class="column_tt">新闻分类</p>
    <div class="aa_block">
        <ul class="aa_conlist"><?php foreach ($category as $c): ?>
                <li><a href="/aa_home/news?id=<?= $c['aa_id'] ?>&category=<?= $c['id'] ?>" style="<?= $category_id == $c['id'] ? 'font-weight:bold' : ''; ?>"><?= $c['name'] ?></a></li><?php endforeach; ?>
        </ul>
    </div>
</div>
<div class="clear"></div>
