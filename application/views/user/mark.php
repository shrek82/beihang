<?php if($_UID): ?>
<?php
$target_uid=$uid;
$my_id=$_UID;
//是否已经关注该校友
$is_marked = Model_Mark::is_mark($my_id, $target_uid);
//是否被关注
$is_markMe = Model_Mark::is_mark($target_uid,$my_id);
?>
<?php if($is_marked AND $is_markMe):?>
<input type="button" value="互关注" class="markMutual" title="点击取消关注" id="markButton" onclick="markUser(<?=$target_uid?>)">
<?php elseif($is_markMe):?>
<input type="button" value="关注" class="markMe" title="点击关注" id="markButton" onclick="markUser(<?=$target_uid?>)">
<?php elseif($is_marked):?>
<input type="button" value="已关注" class="marked" title="点击取消关注" id="markButton" onclick="markUser(<?=$target_uid?>)">
<?php else:?>
<input type="button" value="关注" class="markUser" title="点击关注" id="markButton" onclick="markUser(<?=$target_uid?>)">
<?php endif;?>
<?php endif;?>