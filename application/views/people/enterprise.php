<!--left -->
<div id="main_left">
    <div style="border-bottom:1px solid #E1ECFA">
        <img src="/static/images/people_enterprise.gif" />
    </div>
    <div class="con_list a14">
        <?php if (!$news): ?>
            <div class="nodata">暂时还没有内容</div>
        <?php endif; ?>
        <ul>
            <?php foreach ($news as $n): ?>
                <li>
                    <a href="<?= URL::site('people/eView?id=' . $n['id']) ?>" target="_blank"><?= Text::limit_chars($n['title'], 40) ?></a><span><?= date('Y-m-d', strtotime($n['create_at'])); ?></span></li>
            <?php endforeach; ?>
        </ul>

    </div>
    <div style=" text-align: center">
        <?= $pager ?>
    </div>
</div>
<!--//left -->

<?php
include 'sidebar.php';
?>
<div class="clear"></div>