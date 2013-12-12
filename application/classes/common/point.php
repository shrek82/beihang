<?php
/**
  +-----------------------------------------------------------------
 * 名称：校友积分
 * 版本：1.0
 * 版权：www.usho.cn
 * 作者：zhaojiangang
 * 最后更新日期：12-10-12 上午10:52
  +-----------------------------------------------------------------
 */
class Common_Point {

    //返回分界点积分(第?位的积分)
    public static function divPoint() {
        //注册校友总数
        $total_user = Doctrine_Query::create()
                        ->select('id')
                        ->from('User')
                        ->useResultCache(true, 1200, 'total_user')
                        ->count();

        //前0.5%
        $offset = round($total_user * 0.005);
		$offset = $offset>2?$offset:2;

        //分界点积分
        $div = Doctrine_Query::create()
                        ->select('point')
                        ->from('User')
                        ->orderBy('point DESC')
                        ->limit(1)
                        ->offset($offset - 1)
                        ->useResultCache(true, 1200, 'user_point_div')
                        ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
        return $div['point'];
    }

    //返回某一校友积分总排名
    public static function getTop($point){
        $top=Doctrine_Query::create()
                        ->select('id')
                        ->from('User')
                        ->where('point>?',$point)
                        ->count();
        return $top+1;
    }

    //返回校友温度(分界点积分，用户积分)
    public static function getTemp($divPoint,$point){
        if($point>=$divPoint){
             return 100;
        }
        else{
             return round(($point/$divPoint)*100);
        }
    }
}

?>