<!-- publication/index:_body -->
<div id="main_left">
    <p><img src="/static/images/publication_title.gif"></p>
    <div id="pub_list">
        <?php if($publication):?>
        <ul>
            <?php foreach ($publication AS $p): ?>
                <li>
                    <a href="/<?= $p['pdf'] ?>" target="_blank" title="点击在线浏览或下载">
                        <img src="/<?= $p['cover'] ?>" width="120" height="162" style="padding: 5px;border: 1px solid #eee"/>
                    </a>
                    <br>
                    <?= $p['issue'] ?>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php else:?>
        <div style="color: #999;padding: 10px">暂时还没有内容</div>
        <?php endif?>
        <div class="clear"></div>
        <div><?= $pager ?></div>
    </div>
</div>
<div id="sidebar_right">
    <?php include 'sidebar.php'; ?>
</div>
<div class="clear"></div>