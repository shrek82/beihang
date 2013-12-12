<?php
/**
  +-----------------------------------------------------------------
 * 名称：管理日志
 * 版本：1.0
 * 版权：www.usho.cn
 * 作者：zhaojiangang
 * 最后更新日期：
  +-----------------------------------------------------------------
 */
class Common_Log {

    //添加日志
    public static function add($data) {

	// session
        $sid = Arr::get($_GET, 'SESSID');
	$data['manager_id']=Session::instance(null, $sid)->get('id');
	$data['manage_at']=date('Y-m-d H:i:s');
	$log = new AdminLog();
        $log->fromArray($data);
        $log->save();
	return $log->id;
    }
}

?>