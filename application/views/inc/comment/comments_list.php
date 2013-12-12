<div id="comment_list">
    <?php if (count($comments) == 0): ?>
        <div class="nodata" id="not_cmt"><?=!$_SETTING['close_other_comment']?'暂无任何讨论，去抢个沙发？':'';?></div>
        <div id="comment_page_box"></div>
    <?php else: ?>

        <?php foreach ($comments as $ix => $cmt): ?>
            <div class="one_comment" id="comment_<?= $cmt['id'] ?>">
                <div class="left">
                    <img src="<?= Model_User::avatar($cmt['user_id'], 128, $cmt['sex']) . '?gen_at=' . time() ?>" class="face">
                </div>
                <div class="right">
                    <p class="user"><a href="<?= URL::site('user_home?id=' . $cmt['user_id']) ?>" class="commentor"><?= $cmt['User']['realname'] ?></a> <?= $cmt['post_at'] ?>前：</p>
                    <div class="content"><?= $cmt['content'] ?></div>
                </div>
                <div class="clear"></div>
            </div>
        <?php endforeach; ?>
        <?php if (!$cmt_id): ?>
            <div id="comment_page_box"><?= $pager ?></div>
        <?php endif; ?>
    <?php endif; ?>
</div>