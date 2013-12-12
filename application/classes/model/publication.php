<?php
class Model_Publication{
       const TXT_PATH = 'static/upload/publication/txt/';
       const PDF_PATH = 'static/upload/publication/pdf/';
       const COVER_PATH = 'static/upload/publication/cover/';
       static $up_rule = array(
        'Upload::valid' => array(),
        'Upload::not_empty' => array(),
        'Upload::type' => array('Upload::type' => array('pdf','txt','gif','jpg','jpeg','png')),
        'Upload::size' => array('70M')
    );

       //刊物类型
       static $pub_type=array(
	   '1001'=>'北航学人'
       );
}
