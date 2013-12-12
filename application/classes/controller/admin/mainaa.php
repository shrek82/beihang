<?php

class Controller_Admin_Mainaa extends Layout_Admin {

    function before() {
        parent::before();

        $leftbar_links = array(
            'admin_mainaa/main' => '基本信息',
            'admin_mainaa/index' => '总会介绍',
            'admin_content/index?type=9' => '总会大事记',
            'admin_mainaa/msg' => '系统公告',
        );

        $this->_render('_body_left', compact('leftbar_links'), 'admin/leftbar');
    }

    //内容管理首页
    function action_index() {

        $info = Doctrine_Query::create()
                ->from('MainInfo')
                ->orderBy('order_num ASC');

        $total_info = $info->count();

        $pager = Pagination::factory(array(
                    'total_items' => $total_info,
                    'items_per_page' => 15,
                    'view' => 'pager/common',
        ));

        $view['pager'] = $pager;
        $view['info'] = $info->offset($pager->offset)
                ->limit($pager->items_per_page)
                ->fetchArray();

        $this->_title('总会介绍管理');
        $this->_render('_body', $view);
    }

    //添加或修改内容
    function action_form() {
        $id = Arr::get($_GET, 'id', 0);
        $view['err'] = '';
        $info = Doctrine_Query::create()
                ->from('MainInfo')
                ->where('id = ?', $id)
                ->fetchOne();
        $view['info'] = $info;
        if ($_POST) {
            $valid = Validate::setRules($_POST, 'aa_info');
            $post = $valid->getData();
            if (!$valid->check()) {
                $view['err'] .= $valid->outputMsg($valid->errors('validate'));
            } else {

                $post['content'] = $_POST['content'];

                if (strlen($post['content']) < 1) {
                    $post['content'] = '暂无介绍';
                }

                $post['type'] = Arr::get($_POST, 'type');
                // 添加或修改内容
                if ($info) {
                    unset($post['id']);
                    $post['update_at'] = date('Y-m-d H:i:s');
                    $info->synchronizeWithArray($post);
                    $info->save();
                } else {
                    $info = new MainInfo();
                    $post['update_at'] = date('Y-m-d H:i:s');
                    $info->fromArray($post);
                    $info->save();
                }

                // 处理完毕后刷新页面
                $this->request->redirect('admin_mainaa/index');
            }
        }

        $this->_render('_body', $view);
    }

    function action_main() {
        $main = Doctrine_Query::create()
                ->from('MainAa main')
                ->fetchOne();

        if ($_POST) {

            $valid = Validate::setRules($_POST, 'main_aa');
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
                            $tel_str .= $key . ':' . $tel_num . ';';
                        }
                    }
                    // 有效号码们
                    if (strlen($tel_str) > 0) {
                        $data['tel'] = substr($tel_str, 0, -1);
                    }
                }

                if (!$main) {
                    $main = new MainAa();
                    $main->fromArray($data);
                } else {
                    $main->synchronizeWithArray($data);
                }
                $main->save();
            }
            exit;
        }

        $view['main'] = $main;

        $this->_custom_media(Candy::import('ckeditor'));
        $this->_custom_media(Candy::import('ckfinder'));
        $this->_title('总会信息设置');
        $this->_render('_body', $view);
    }

    function action_msg() {
        $msg = Doctrine_Query::create()
                ->from('SysMessage')
                ->orderBy('post_at ASC')
                ->fetchArray();

        // view
        $view['msgs'] = $msg;
        $this->_title('系统消息列表');
        $this->_render('_body', $view);
    }

    function action_msgForm() {
        $id = Arr::get($_GET, 'id', 0);
        $del = Arr::get($_GET, 'del');

        $one = Doctrine_Query::create()
                ->from('SysMessage')
                ->where('id = ?', $id)
                ->fetchOne();

        if ($del == 'y' && $one) {
            $one->delete();
            exit;
        }

        if ($_POST) {

            $v = Validate::setRules($_POST, 'sys_msg');
            $post = $v->getData();
            if (!$v->check()) {
                echo Candy::MARK_ERR . $v->outputMsg($v->errors('validate'));
            } else {

                if (strtotime($post['start_at']) >= strtotime($post['expire_at'])) {
                    echo Candy::MARK_ERR . '开始时间必须是过期时间之前';
                }

                // new
                if (!$one) {
                    $post['post_at'] = date('Y-m-d H:i:s');
                    $post['user_id'] = $this->_sess->get('id');
                    $msg = new SysMessage();
                    $msg->fromArray($post);
                    $msg->save();
                } else {
                    $post['post_at'] = date('Y-m-d H:i:s');
                    $post['user_id'] = $this->_sess->get('id');
                    $one->synchronizeWithArray($post);
                    $one->save();
                }
            }
            exit;
        }

        $view['msg'] = $one;

        $this->_title('系统消息表单');
        $this->_render('_body', $view);
    }

    #删除内容

    function action_del() {
        $this->auto_render = FALSE;
        $cid = Arr::get($_GET, 'cid');
        $del = Doctrine_Query::create()
                ->delete('MainInfo')
                ->where('id =?', $cid)
                ->execute();
    }

}
