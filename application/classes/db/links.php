<?php

class Db_Links {

    public static function get($condition) {

        $sql = DB::select(DB::expr('l.*'))->from(array('links', 'l'));
        if (isset($condition['is_logo'])) {
            $sql = $sql->where('is_logo', '=', 1);
        }

        if (isset($condition['type'])) {
            $sql = $sql->where('type', '=', $condition['type']);
        }

        if (isset($condition['limit'])) {
            $sql = $sql->limit($condition['limit']);
        }

        $links = $sql->order_by('order_num', 'ASC')->order_by('id', 'desc')->execute()->as_array();
    }

}

?>
