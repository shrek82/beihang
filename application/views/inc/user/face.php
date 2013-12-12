<?php
//版本：1.0
?>
<?php
    $id = isset($id) ? $id : $_SESS->get('id', 0);
    $csize = $size;
    if($size < 48){
        $size = 48;
    }
    if(!isset($sex)){
        $sex='none';
    }
?>

<div class="user_avatar candyCorner" style="
background-image: url(<?= Model_User::avatar($id, $size,$sex)?>);
background-repeat:  no-repeat;
background-position: center;
height: <?= $csize ?>px;
width: <?= $csize ?>px;
padding:3px;" rel="<?= $id ?>"></div>