<?php

/**
  +-----------------------------------------------------------------
 * 名称：禁止危险字符
 * 版本：1.0
 * 版权：www.usho.cn
 * 作者：zhaojiangang
 * 最后更新日期：12-10-12 上午10:52
  +-----------------------------------------------------------------
 */
class Common_Safe {

    //过滤危险的字符或数组内字符
    public static function filterData($data) {
        if (!$data) {
            return null;
        }
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = self::filterStr($value);
            }
        } else {
            $data = self::filterStr($data);
        }
        return $data;
    }

    //过滤危险的字符
    public static function filterStr($str) {
        if (!$str) {
            return '';
        } else {
            $s = str_replace('', chr(32), trim($str));
            $s = str_replace('', chr(9), $s);
            $s = str_replace('', chr(34), $s);
            $s = str_replace('', chr(39), $s);
            $s = str_replace("'", '', $s);
            $s = str_replace('=', '', $s);
            $s = str_replace('"', '', $s);
            $s = str_replace('&', '', $s);
            $s = str_replace(')', '', $s);
            $s = str_replace('(', '', $s);
            $s = str_replace('&gt', '', $s);
            $s = str_replace('&lt', '', $s);
            $s = str_replace('<SCRIPT>', '', $s);
            $s = str_replace('</SCRIPT>', '', $s);
            $s = str_replace('<script>', '', $s);
            $s = str_replace('</script>', '', $s);
            $s = str_replace('<', '', $s);
            $s = str_replace('>', '', $s);
            $s = str_replace('>', '', $s);
            $s = str_replace('/', '', $s);
            $s = str_replace('\\', '', $s);
            return $s;
        }
    }

}
