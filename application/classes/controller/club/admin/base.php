<?php

class Controller_Club_Admin_Base extends Layout_Clubadmin {

    function before() {
        parent::before();

        $actions = array(
            'club_admin_base/index' => '基本介绍',
            'club_admin_base/info' => '详细介绍',
        );
        $this->_render('_body_action', compact('actions'), 'club_global/admin_action');
    }

    //基本介绍
    function action_index() {

        $club = Doctrine_Query::create()
                ->from('Club')
                ->where('id = ?', $this->_id)
                ->fetchOne();

        if ($_POST) {
            $club->synchronizeWithArray($_POST);
            $club->save();
            Db_Club::delteInfoCache($this->_id);
            exit();
        }
        $this->_title('本群组俱乐部介绍');
        $this->_render('_body', compact('club'));
    }

    //更多介绍
    function action_info() {

        $infos = Doctrine_Query::create()
                ->from('AaInfo')
                ->where('club_id = ?', $this->_id)
                ->orderBy('order_num ASC')
                ->fetchArray();

        $view['infos'] = $infos;
        $this->_title('详细介绍');
        $this->_render('_body', $view);
    }

    //添加新介绍
    function action_form() {

        $cid = Arr::get($_GET, 'cid', 0);

        $info = Doctrine_Query::create()
                ->from('AaInfo')
                ->where('id = ?', $cid)
                ->addWhere('club_id=?', $this->_id)
                ->fetchOne();

        if ($_POST) {
            $this->auto_render = false;
            $count = Doctrine_Query::create()
                    ->from('AaInfo')
                    ->where('club_id = ?', $this->_id)
                    ->count();

            $valid = Validate::setRules($_POST, 'aa_info');
            $post = Validate::getData();
            if (!$valid->check()) {
                echo Candy::MARK_ERR . $valid->outputMsg($valid->errors('validate'));
            } else {
                if (!$info) {
                    $new_info = new AaInfo();
                    $post['club_id'] = $this->_id;
                    $post['order_num'] = $count + 1;
                    $post['update_at'] = date('Y-m-d H:i:s');
                    $new_info->fromArray($post);
                    $new_info->save();
                    echo $new_info->id;
                } else {
                    // 更新
                    $post['update_at'] = date('Y-m-d H:i:s');
                    $info->synchronizeWithArray($post);
                    $info->save();
                    echo $info->id;
                }
            }
            exit;
        }

        $view['info'] = $info;
        $this->_render('_body', $view);
    }

    # 修改某项值

    function action_set() {
        if ($_POST) {
            $cid = Arr::get($_POST, 'cid');
            $field = Arr::get($_POST, 'field');
            $bool_field = Arr::get($_POST, 'bool_field');
            $field_value = Arr::get($_POST, 'field_value');

            $info = Doctrine_Query::create()
                    ->from('AaInfo')
                    ->where('id = ?', $cid)
                    ->fetchOne();

            //修改bool值
            if ($info && $bool_field && isset($info[$bool_field])) {
                $info[$bool_field] = $info[$bool_field] ? FALSE : TRUE;
                $info->save();
            }
            //修改其他字段
            elseif ($info && $field) {
                $info[$field] = $field_value;
                $info->save();
            } else {
                return false;
            }
        }
    }

    //删除信息
    function action_del() {
        $this->auto_render = FALSE;
        $cid = Arr::get($_GET, 'cid');
        Doctrine_Query::create()
                ->delete('AaInfo')
                ->where('id =? and club_id=?', array($cid, $this->_id))
                ->execute();
    }

}