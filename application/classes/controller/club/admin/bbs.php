<?php

class Controller_Club_Admin_Bbs extends Layout_Clubadmin {

    function before() {
        parent::before();

        $actions = array(
            'club_admin_bbs/index' => '所有话题'
        );
        $this->_render('_body_action', compact('actions'), 'club_global/admin_action');
    }

    //话题列表
    function action_index() {

        //查询条件
        $condition = array(
            'aa_id' => $this->_club['aa_id'],
            'club_id' => $this->_id,
            'q' => Arr::get($_POST, 'q'),
            'page_size' => 20,
            'replyname' => false,
            'count_total' => true,
            'admin_mode' => true,
            'page' => Arr::get($_GET, 'page'),
        );

        $query_data = Db_Bbs::getUnits($condition);
        $view['units'] = $query_data['units'];

        $total_items = $query_data['total_items'];

        $view['pager'] = Pagination::factory(array(
                    'total_items' => $total_items,
                    'items_per_page' => 20,
                    'view' => 'pager/bbs',
                ));

        $this->_title('论坛管理');
        $this->_render('_body', $view);
    }

    # 修改论坛版块

    function action_form() {
        $bbs_id = Arr::get($_GET, 'bbs_id');

        $bbs = Doctrine_Query::create()
                ->from('Bbs b')
                ->where('b.id = ?', $bbs_id)
                ->fetchOne();

        if ($_POST) {

            if ($bbs != FALSE && ($bbs['name'] != $_POST['name'])) {
                if (Model_Bbs::isExist(array('club_id' => $this->_id, 'name' => $_POST['name']))) {
                    echo Candy::MARK_ERR . '已经有同名，请更换';
                    exit;
                }
            }

            if ($bbs) {
                $bbs->synchronizeWithArray($_POST);
            } else {
                $bbs = new Bbs();
                $bbs->aa_id = $this->_club['aa_id'];
                $bbs->club_id = $this->_id;
                $bbs->name = Arr::get($_POST, 'name');
                $bbs->intro = Arr::get($_POST, 'intro');
            }
            $bbs->save();
            exit;
        }

        echo View::factory('club_admin_bbs/form', compact('bbs'));
    }

    # 修改论坛话题某个单元字段
    function action_set() {
        $this->auto_render=false;
        echo Db_Bbs::set($_POST);
    }

}