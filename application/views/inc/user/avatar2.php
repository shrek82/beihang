<?php
//版本：1.0
$id = isset($id) ? $id : $_SESS->get('id', 0);
$online=isset($online)?$online:False;
$csize = $size;
if ($size < 48) {
    $size = 48;
}
if (!isset($sex)) {
    $sex = 'none';
}
?>
<div class="new_user_avatar"><div class="face<?= $online? '_online' : ''; ?>"><a href="<?= URL::site('user_home?id=' . $id) ?>" style="font-size:12px"><img src="<?= Model_User::avatar($id, $size, $sex) ?>"></a></div>
</div>
