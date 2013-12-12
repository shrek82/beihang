<?php
/**
  +-----------------------------------------------------------------
 * 名称：检查是否包含非法关键词，包含则返回该关键词。
 * 版本：1.0
 * 版权：www.usho.cn
 * 作者：zhaojiangang
 * 最后更新日期：12-10-12 上午10:27
  +-----------------------------------------------------------------
 */

class Model_Filter {

    public static function check($data) {
        $filter_key = Doctrine_Query::create()
                        ->from('Filter')
                        ->fetchArray();

        $is_contain = FALSE;
        $contain_key = '';
        //检查数组
        if (is_array($data)) {
            foreach ($data AS $str) {
                foreach ($filter_key as $key) {
                    $str=is_array($str)?implode('', $str):$str;
                    if ($str AND $key['string'] AND is_string($key['string']) AND strstr($str, $key['string'])) {
                        $is_contain = TRUE;
                        $contain_key = $key['string'];
                        break;
                    }
                }

                if ($is_contain) {
                    return $contain_key;
                    break;
                }
            }
        }
        //检查字符串
        else {
            foreach ($filter_key as $key) {
                if ($data AND $key['string'] AND is_string($key['string']) AND strstr($data, $key['string'])) {
                    return $key['string'];
                    break;
                }
            }
        }
    }

}

?>
