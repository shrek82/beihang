<?php
/**
  +-----------------------------------------------------------------
 * 名称：班级模型
 * 版本：1.0
 * 版权：www.usho.cn
 * 作者：zhaojiangang
 * 最后更新日期：12-10-12 上午9:46
  +-----------------------------------------------------------------
 */
class Db_Classroom {

    //根据id获取某一班级信息
    public static function getInfoById($id) {
            $info = DB::select(DB::expr('c.*'))
                    ->select(DB::expr('(SELECT COUNT(m.id) FROM class_member m WHERE c.id = m.class_room_id) AS mcount'))
                    ->from(array('class_room', 'c'))
                    ->where('c.id', '=', $id)
                    ->execute()
                    ->as_array();
        $info = $info ? $info[0] : false;
        return $info;
    }

    
}
?>