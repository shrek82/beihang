<?php

class Controller_Aa_Admin_Base extends Layout_Aa {

    function before() {
        parent::before();

        // 管理组成员
        if(!$this->_aa_manager){
            Request::instance()->redirect('main/deny?reason=' . urlencode('很抱歉，您没有管理权限'));
            exit;
        }

        $actions = array(
            'aa_admin_base/index' => '基本介绍',
            'aa_admin_base/info' => '详细介绍',
            'aa_admin_base/contact' => '联系方式',
        );
        $this->_render('_body_action', compact('actions'), 'aa_global/admin_action');
    }

    # 校友会基本介绍

    function action_index() {
        $aa = Doctrine_Query::create()
                ->from('Aa a')
                ->where('a.id = ?', $this->_id)
                ->fetchOne();
        if ($_POST) {
            $post['intro'] = Arr::get($_POST, 'intro');
            $aa->synchronizeWithArray($post);
            $aa->save();
            exit();
        }
        $this->_title('本会首页介绍');
        $this->_render('_body', compact('aa'));
    }

    # 联系方式

    function action_contact() {
        $aa = Doctrine_Query::create()
                ->from('Aa a')
                ->where('a.id = ?', $this->_id)
                ->fetchOne();

        if ($_POST) {
            $valid = Validate::setRules($_POST, 'aa_contact');
            $data = Validate::getData();

            if (!$valid->check()) {
                echo Candy::MARK_ERR . Validate::outputMsg($valid->errors('validate'));
            } else {
                // 校验电话号码
                if (count($data['tel_key']) > 0) {
                    $tel_str = '';
                    foreach ($data['tel_key'] as $i => $key) {
                        // 电话分类和号码都存在
                        if ($key && $data['tel_num'][$i]) {
                            $tel_num = $data['tel_num'][$i];
                            if (!Validate::phone($tel_num, array(7, 8, 9, 10, 11, 12))) {
                                echo Candy::MARK_ERR . '有号码格式不正确';
                            } else {
                                // 整合为字符串
                                $tel_str .= $key . ':' . $tel_num . ';';
                            }
                        }
                    }
                    // 有效号码们
                    if (strlen($tel_str) > 0) {
                        $data['tel'] = substr($tel_str, 0, -1);
                    }
                }
                $aa->synchronizeWithArray($data);
                $aa->save();
            }
            exit;
        }
        $this->_title('设置联系方式');
        $view['contact'] = $aa->toArray();
        $this->_render('_body', $view);
    }

    # 校友会其他信息
    function action_info() {

        // 现有info
        $infos = Doctrine_Query::create()
                ->from('AaInfo')
                ->where('aa_id = ?', $this->_id)
                ->orderBy('order_num ASC')
                ->fetchArray();

        $view['infos'] = $infos;

        // view
        $this->_title('详细介绍');
        $this->_render('_body', $view);
    }

    //添加新介绍
    function action_form() {

        $cid = Arr::get($_GET, 'cid', 0);

        $info = Doctrine_Query::create()
                ->from('AaInfo')
                ->where('id = ?', $cid)
                ->addWhere('aa_id=?', $this->_id)
                ->fetchOne();

        if ($_POST) {
            $this->auto_render = false;
            $count = Doctrine_Query::create()
                    ->from('AaInfo')
                    ->where('aa_id = ?', $this->_id)
                    ->count();

            $valid = Validate::setRules($_POST, 'aa_info');
            $post = Validate::getData();
            if (!$valid->check()) {
                echo Candy::MARK_ERR . $valid->outputMsg($valid->errors('validate'));
            } else {
                if (!$info) {
                    $new_info = new AaInfo();
                    $post['aa_id'] = $this->_id;
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
                ->where('id =? and aa_id=?', array($cid, $this->_id))
                ->execute();
    }

}