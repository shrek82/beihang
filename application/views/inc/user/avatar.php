<?php
//版本：1.0
$id = isset($id) ? $id : $_SESS->get('id', 0);
$csize = $size;
if ($size < 48) {
    $size = 48;
}
if (!isset($sex)) {
    $sex = 'none';
}
?><div class="user_avatar" style="width:<?= $size ?>px" ><img src="<?= Model_User::avatar($id, $size, $sex) ?>" style="height: <?= $csize ?>px;width: <?= $csize ?>px;border-width:0"></div>