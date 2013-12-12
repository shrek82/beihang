<?php
/**
  +-----------------------------------------------------------------
 * 名称：内容模型
 * 版本：1.0
 * 版权：www.usho.cn
 * 作者：zhaojiangang
 * 最后更新日期：12-10-12 上午9:46
  +-----------------------------------------------------------------
 */
class Db_Content {

    //首页静态图片
    public static function getStaticPic() {

        $taday = date('Y-m-d');
        $static_pic = DB::select(DB::expr('id,img_path,redirect,title'))
                ->from('content')
                ->where('type', '=', 17)
                ->where('is_close', '=', 0)
                ->where('start_date', '<=', $taday)
                ->where('end_date', '>=', $taday)
                ->order_by('id', 'DESC')
                ->limit(1)
                ->execute()
                ->as_array();

        if (count($static_pic) > 0) {
            return $static_pic[0];
        } else {
            return array();
        }
    }

        //首页静态图片
    public static function getStaticPics() {

        $taday = date('Y-m-d');

        $static_pic = DB::select(DB::expr('id,img_path,redirect,title'))
                ->from('content')
                ->where('type', '=', 17)
                ->where('is_close', '=', 0)
                ->where('start_date', '<=', $taday)
                ->where('end_date', '>=', $taday)
                ->order_by('id', 'DESC')
                ->limit(1)
                ->execute()
                ->as_array();

        if (count($static_pic) > 0) {
            return $static_pic[0];
        } else {
            return array();
        }
    }

}

?>
