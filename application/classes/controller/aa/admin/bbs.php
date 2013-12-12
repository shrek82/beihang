<?php

class Controller_Aa_Admin_Bbs extends Layout_Aa {

    function before() {
        parent::before();

        // 管理组成员
        if(!$this->_aa_manager){
            Request::instance()->redirect('main/deny?reason=' . urlencode('很抱歉，您没有管理权限'));
            exit;
        }

        $actions = array(
            'aa_admin_bbs/index' => '话题管理',
            'aa_admin_bbs/category' => '版块管理',
        );
        $this->_render('_body_action', compact('actions'), 'aa_global/admin_action');
    }

    # 修改论坛

    function action_form() {
        $bbs_id = Arr::get($_GET, 'bbs_id');

        $bbs = Doctrine_Query::create()
                        ->from('Bbs b')
                        ->where('b.id = ?', $bbs_id)
                        ->fetchOne();

        if ($_POST) {

            if ($bbs != FALSE && ($bbs['name'] != Arr::get($_POST, 'name'))) {
                if (Model_Bbs::isExist(array('aa_id' => $this->_id, 'name' => Arr::get($_POST, 'name')))) {
                    echo Candy::MARK_ERR . '已经有同名，请更换';
                    exit;
                }
            }

            if ($bbs) {
                $bbs->synchronizeWithArray($_POST);
            } else {
                $bbs = new Bbs();
                $bbs->aa_id = $this->_id;
                $bbs->name = Arr::get($_POST, 'name');
                $bbs->intro = Arr::get($_POST, 'intro');
            }
            $bbs->save();
            exit;
        }

        echo View::factory('aa_admin_bbs/form', compact('bbs'));
    }

    function action_index() {

        //查询条件
        $condition = array(
            'aa_id' => $this->_id,
            'q' => Arr::get($_POST,'q'),
            'page_size' => 20,
            'replyname'=>false,
            'count_total'=>true,
            'admin_mode'=>true,
            'page' => Arr::get($_GET,'page'),
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

    //版块管理
    function action_category() {
        $bbs_id = Arr::get($_GET, 'bbs_id');
        $aa_id = $this->_id;
        $bbs_edit = null;
        $ids = Model_Bbs::getIDs(array('aa_id' => $this->_id));
        $bbs_ids = array_keys($ids);
        if (count($bbs_ids) == 0) {
            $bbs_ids = array(0);
        }

        $all_bbs = Doctrine_Query::create()
                        ->from('Bbs b')
                        ->whereIn('b.id', $bbs_ids)
                        ->orderBy('b.order_num ASC')
                        ->fetchArray();

        $bbs = Doctrine_Query::create()
                        ->from('Bbs b')
                        ->where('b.id = ?', $bbs_id)
                        ->fetchOne();

        if ($_POST) {
            if ($bbs) {
                $bbs->synchronizeWithArray($_POST);
            } else {
                $bbs = new Bbs();
                $bbs->aa_id = $this->_id;
                $bbs->name = Arr::get($_POST, 'name');
                $bbs->order_num = Arr::get($_POST, 'order_num');
                $bbs->intro = Arr::get($_POST, 'intro');
            }
            $bbs->save();
            $this->_redirect(URL::site('aa_admin_bbs/category?id=' . $this->_id));
        }


        $this->_title('论坛管理');
        $this->_render('_body', compact('aa_id', 'all_bbs', 'bbs', 'bbs_id'));
    }

    # 修改论坛话题某个单元字段
    function action_set() {
        $this->auto_render=false;
        echo Db_Bbs::set($_POST);
    }

}