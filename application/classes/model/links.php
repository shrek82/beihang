<?php
/**
  +-----------------------------------------------------------------
 * 名称：友情链接模型
 * 版本：1.0
 * 版权：www.usho.cn
 * 作者：zhaojiangang
 * 最后更新日期：12-10-12 上午10:27
  +-----------------------------------------------------------------
 */
class Model_Links{
       const LOGO_PATH = 'static/upload/logo/';
       static $up_rule = array(
        'Upload::valid' => array(),
        'Upload::not_empty' => array(),
        'Upload::type' => array('Upload::type' => array('gif','jpg','jpeg','png')),
        'Upload::size' => array('1M')
    );
}
?>
