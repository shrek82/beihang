<?php
/**
  +-----------------------------------------------------------------
 * 名称：新浪微博模型
 * 版本：1.0
 * 版权：www.usho.cn
 * 作者：zhaojiangang
 * 最后更新日期：12-10-12 上午10:48
  +-----------------------------------------------------------------
 */
class Model_Sinaweibo {

        //保存微博
        public static function create($data) {
                if ($data) {
                        $weibo = new SinaWeibo();
                        $data['colletion_at'] = date('Y-m-d H:i:s');
                        $weibo->fromArray($data);
                        $weibo->save();
                        return $weibo->id;
                } else {
                        return false;
                }
        }
}

?>
