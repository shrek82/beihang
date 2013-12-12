<?php

class Controller_Aa_Admin_Club extends Layout_Aa {

    function before() {
        parent::before();

        // 管理组成员
        if (!$this->_aa_manager) {
            Request::instance()->redirect('main/deny?reason=' . urlencode('很抱歉，您没有管理权限'));
            exit;
        }

        $actions = array(
            'aa_admin_club/index' => '所有俱乐部',
            'aa_admin_club/form' => '添加俱乐部',
        );
        $this->_render('_body_action', compact('actions'), 'aa_global/admin_action');
    }

    //创建和修改俱乐部
    function action_form() {
        $club_id = Arr::get($_GET, 'club_id');

        $club = Doctrine_Query::create()
                ->from('Club c')
                ->where('c.id = ?', $club_id)
                ->fetchOne();


        if ($_POST) {
            $valid = Validate::setRules($_POST, 'club');
            $post = $valid->getData();

            if (!$valid->check()) {
                echo Candy::MARK_ERR .
                $valid->outputMsg($valid->errors('validate'));
            } else {
                if ($_POST['name'] != $club['name'] && Model_Club::isExist($this->_id, $post['name'])) {
                    echo Candy::MARK_ERR . '*该俱乐部名已经存在';
                } elseif (!DB_Aa::isMember($this->_id, $post['user_id'])) {
                    echo Candy::MARK_ERR . '*该校友ID目前还不是校友会成员';
                } else {
                    if (!$club) {
                        $post['aa_id'] = $this->_id;
                        $post['create_at'] = date('Y-m-d H:i:s');
                        $post['member_num'] = 1;
                        $club = new Club();
                        $club->fromArray($post);
                        // 默认俱乐部论坛
                        $club->Bbses[0]->aa_id = $this->_id;
                        $club->Bbses[0]->name = $post['name'] . '默认版';
                        $club->Bbses[0]->intro = $post['name'] . '默认创建的论坛版块，管理员可以修改简介跟版块名称';
                        // 默认俱乐部相册
                        $club->Albums[0]->create_at = date('Y-m-d H:i:s');
                        $club->Albums[0]->aa_id = $this->_id;
                        $club->Albums[0]->name = $post['name'] . '相册';
                    } else {
                        $club->synchronizeWithArray($post);
                    }
                    $club->save();
                    Model_Club::set_chairman($club->id, $post['user_id'], $post['title']);
                }
            }
            exit();
        }

        // view
        $this->_title('设置俱乐部');
        $this->_render('_body', compact('club'));
    }

    function action_index() {

        $club = Doctrine_Query::create()
                ->select('c.*')
                ->addSelect('(SELECT COUNT(cm.id) FROM ClubMember cm WHERE cm.club_id = c.id) AS total_member')
                ->from('Club c')
                ->where('c.aa_id = ?', $this->_id)
                ->orderBy('c.order_num ASC,c.id ASC')
                ->fetchArray();

        // view
        $this->_title('本会俱乐部列表');
        $view['club'] = $club;
        $this->_render('_body', $view);
    }

    function action_setOrder() {

        $order = Arr::get($_GET, 'order');
        $order=  explode('||', $order);
        if ($order AND is_array($order)) {
            foreach ($order as $str) {
                $cid_order = explode('-', str_replace('c_','',$str));
                if (isset($cid_order[1]) AND $cid_order[1] AND $cid_order[0]) {
                    $fields = array('order_num'=>$cid_order[1]);
                    DB::update('club')->set($fields)->where('id', '=', $cid_order[0])->where('aa_id', '=',$this->_id)->execute();
                }
            }
        }
    }

}