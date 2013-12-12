<?php

//管理员管理日志

class Controller_Admin_Log extends Layout_Admin {

    function before() {
        parent::before();  
    }

    //管理首页
    function action_index() {

	$search_type=  Arr::get($_GET, 'search_type');
	$loginfo=  Arr::get($_GET, 'loginfo');
	$manager_id=  Arr::get($_GET, 'manager_id');
	$q = urldecode(Arr::get($_GET, 'q'));
	$view['q'] = $q;
	$view['search_type']=$search_type;
	$view['loginfo']=$loginfo;

        $logs = Doctrine_Query::create()
                        ->from('AdminLog log')
			->select('log.*,u.realname AS realname')
			->leftJoin('log.User u')
                        ->orderBy('log.id DESC');

	if ($loginfo) {
	    $logs->where('log.'.$loginfo.'>0');
	}

	if ($manager_id) {
	    $logs->where('manager_id=?',$manager_id);
	}

	if ($q) {
	    $logs->andWhere('log.description LIKE ?', '%' . $q . '%');
	}

        $total_logs = $logs->count();

        $pager = Pagination::factory(array(
                    'total_items' => $total_logs,
                    'items_per_page' => 20,
                    'view' => 'pager/common',
                ));

        $view['pager'] = $pager;
        $view['logs'] = $logs->offset($pager->offset)
                        ->limit($pager->items_per_page)
                        ->fetchArray();

       $this->_title('登录日志');
       $this->_render('_body', $view);
    }

}