<?php
//版本：1.0
?>
<div>
    <a href="#" class="_mark_tigger" rel="<?= $_ID ?>" title="user">
        <?= Model_Mark::status('user', $_ID) ?></a>
</div>

<div style="background: #f5f5f5;" class="candyCorner">
    <a href="<?= URL::site('user_msg/form?to[]='.$_ID) ?>">发送消息</a> |
    <a href="<?= URL::site('user_album/index?id='.$_ID) ?>">TA的相册</a>
</div>