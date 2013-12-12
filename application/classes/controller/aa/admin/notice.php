<?php

class Controller_Aa_Admin_Notice extends Layout_Aa {

    function before() {
	parent::before();
        // 管理组成员
        if(!$this->_aa_manager){
            Request::instance()->redirect('main/deny?reason=' . urlencode('很抱歉，您没有管理权限'));
            exit;
        }

    }

    function action_index()
    {

        $aa = Doctrine_Query::create()
                        ->select('a.*')
                        ->from('Aa a')
                        ->where('a.id = ?', $this->_id)
                        ->fetchOne();
	if ($_POST) {
	    $aa['notice']=  Arr::get($_POST, 'notice');
	    $aa->save();
	}

        $view['aa'] = $aa;
        $this->_title('校友会公告');
        $this->_render('_body', $view);
    }



}