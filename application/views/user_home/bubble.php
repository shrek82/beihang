<div class="bubble candyCorner">
    <?php if($bubble): ?>
    <?= Date::span_str(strtotime($bubble['blow_at'])) ?>前: “
    <?= $bubble['content'] ?>”
    <?php else: ?>
    最近没啥说的...
    <?php endif; ?>

    <?php if($_MASTER): ?>
    <a href="<?= URL::site('user_bubble/index') ?>" style="float: right">编辑</a>
    <?php endif; ?>
</div>