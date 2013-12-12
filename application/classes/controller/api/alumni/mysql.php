<?php

header("content-type:text/html; charset=utf-8");

class Controller_Api_Alumni_Mysql extends Controller_REST {

    //mysql接口
    function action_index() {

        $name = Arr::get($_GET, 'realname');
        $start = Arr::get($_GET, 'start_year');
        $finish = Arr::get($_GET, 'graduation_year');
        $depart = Arr::get($_GET, 'depart');

        $alumni = Database::instance('alumni');

        //提交递减SQL
        if ($finish) {
            $query[] = DB::select(DB::expr('i.*'))->from(array('jsdx_st_info', 'i'))->where('i.stname', '=', $name)->limit(10)->where('i.stzy', 'LIKE', '%' . $depart . '%')->where('i.stoutyear', 'LIKE', '%' . $finish . '%');
            $query[] = DB::select(DB::expr('i.*'))->from(array('jsdx_st_info', 'i'))->where('i.stname', '=', $name)->limit(10)->where('i.stoutyear', 'LIKE', '%' . $finish . '%');
        }
        $query[] = DB::select(DB::expr('i.*'))->from(array('jsdx_st_info', 'i'))->where('i.stname', '=', $name)->limit(10)->where('i.stzy', 'LIKE', '%' . $depart . '%');
        $query[] = DB::select(DB::expr('i.*'))->from(array('jsdx_st_info', 'i'))->where('i.stname', '=', $name)->limit(10);

        //条件依次递减
        $files = array();
        foreach ($query as $sql) {
            $files = $alumni->query(Database::SELECT, $sql)->as_array();
            if ($files) {
                break;
            }
        }
        
        //查询每隔档案信息是否已经被注册
        if($files){
            foreach ($files as $key => $f) {
                $files[$key]['is_reged']=(boolean)DB::select('id')->from('user')->where('alumni_id', '=',$f['stid'])->execute()->count();
            }
        }

        //修正字段名称
        $files = Db_Alumni::oldToNewField($files);

        //返回查询结果
        echo json_encode(array('resp' => $files));
    }

}