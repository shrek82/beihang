<?php
//上传图片类型及大小设置
return array(
    'fileType' => array(".gif", ".png", ".jpg", ".jpeg", ".bmp"), //文件允许格式
    'fileSize' => 1000, //文件大小限制，单位KB
    'mini' => array('width' => 150, 'height' => 150),
    'thumbnail' => array('width' => 320, 'height' => 320),
    'bmiddle' => array('width' => 640, 'height' => 640),
    'original' => array('width' => 1000, 'height' => 1000)
        );
?>
