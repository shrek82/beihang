<?php
/**
  +-----------------------------------------------------------------
 * 名称：捐赠模型
 * 版本：1.0
 * 版权：www.usho.cn
 * 作者：zhaojiangang
 * 最后更新日期：12-10-12 上午10:27
  +-----------------------------------------------------------------
 */
class Model_Donate{
       const EXCEL_PATH = 'static/upload';
       static $up_rule = array(
        'Upload::valid' => array(),
        'Upload::not_empty' => array(),
        'Upload::type' => array('Upload::type' => array('xls')),
        'Upload::size' => array('5M')
    );
}
?>
