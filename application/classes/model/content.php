<?php
/**
  +-----------------------------------------------------------------
 * 名称：内容模型
 * 版本：1.0
 * 版权：www.usho.cn
 * 作者：zhaojiangang
 * 最后更新日期：12-10-12 上午10:27
  +-----------------------------------------------------------------
 */
class Model_Content {
        const FILE_PATH = 'static/upload/content/';
        const ZU_CARD_ID=3; //content category id
        const ATTACHED_PATH = 'static/upload/attached/';

        static $up_rule = array(
            'Upload::valid' => array(),
            'Upload::not_empty' => array(),
            'Upload::type' => array('Upload::type' => array('gif', 'jpg', 'jpeg', 'png')),
            'Upload::size' => array('5M')
        );
        static $attached_rule = array(
            'Upload::valid' => array(),
            'Upload::not_empty' => array(),
            'Upload::type' => array('Upload::type' => array('bmp', 'gif', 'jpg', 'jpeg', 'png', 'pdf', 'doc', 'xls', 'rar', 'zip', 'txt', 'mdb', 'docx', 'flv', 'xlsx')),
            'Upload::size' => array('50M')
        );

        //龙卡菜单
        public static function cardMenu() {
                $menus = Doctrine_Query::create()
                        ->select('id,title,redirect')
                        ->from('Content')
                        ->where('type=' . self::ZU_CARD_ID)
                        ->orderBy('order_num ASC')
                        ->fetchArray();

                return $menus;
        }

}

?>
