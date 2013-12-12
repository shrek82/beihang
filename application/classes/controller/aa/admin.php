<?php

class Controller_Aa_Admin extends Layout_Aa {

    function before() {
        parent::before();
        // 管理组成员
        if(!$this->_aa_manager){
            Request::instance()->redirect('main/deny?reason=' . urlencode('很抱歉，您没有管理权限'));
            exit;
        }
        $actions = array(
            'aa_admin/index' => '成员申请',
            'aa_admin_member/index' => '正式成员',
        );
        $this->_render('_body_action', compact('actions'), 'aa_global/admin_action');
    }

    // 待审核名单 ＋ 其他信息概要？
    function action_index() {
        header("Content-Type:text/html; charset=utf-8");
        $apply = Doctrine_Query::create()
                        ->select('jp.*,u.id,u.realname,u.role,u.sex')
                        ->addSelect("IF(u.role='校友(已认证)',1,0) AS role_statu")
                        ->from('JoinApply jp')
                        ->leftJoin('jp.User u')
                        ->where('jp.aa_id ='.$this->_id);

        $total_items = $apply->count();

        $pager = Pagination::factory(array(
                    'total_items' => $total_items,
                    'items_per_page' => 10,
                    'view' => 'pager/common',
                ));

        $view['pager']=$pager;
        $view['apply'] =$apply->offset($pager->offset)
                        ->orderby('role_statu DESC,jp.apply_at DESC, jp.is_reject ASC')
                        ->limit($pager->items_per_page)
                        ->fetchArray();

        $this->_render('_body', $view);
    }

    function action_apply($handler) {
        $id = Arr::get($_GET, 'apply_id');
        $aa_id = $this->_id;

        $apply = Doctrine_Query::create()
                        ->from('JoinApply')
                        ->where('aa_id = ?', $aa_id)
                        ->andWhere('id = ?', $id)
                        ->fetchOne();

        if ($apply) {
            $aa = Doctrine_Query::create()
                            ->select('id,sname,name')
                            ->from('Aa')
                            ->where('id = ?', $apply['aa_id'])
                            ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
        }

        if (!$apply)
            exit;

        //通过审核
        if ($handler == 'accept') {
            $user_id = $apply['user_id'];

            Model_Aa::join($aa_id, $user_id);
            $apply->delete();

            //发送站内信
            unset($post);
            $post['send_to'] = $user_id;
            $post['sort_in'] = 0;
            $post['user_id'] = $this->_sess->get('id');
            $post['content'] = '亲爱的校友您好，欢迎您加入<a href="' . URL::site('aa_home?id=' . $aa['id']) . '" style="color:blue">' . $aa['name'] . '</a>，您的加入申请已经通过，请点击名称直接访问，谢谢！';
            $post['send_at'] = date('Y-m-d H:i:s');
	    $post['update_at'] = date('Y-m-d H:i:s');
            $msg = new UserMsg();
            $msg->fromArray($post);
            $msg->save();
        }

        // 拒绝申请处理
        if ($handler == 'reject') {
            $reason = trim(Arr::get($_POST, 'reject_reason'));
            if (!$reason) {
                $view['reason'] = $apply['reject_reason'];
                $view['action'] = URL::site('aa_admin/apply/reject') . URL::query();
                echo View::factory('inc/reject_reason', $view);
            } else {
                $apply['reject_reason'] = $reason;
                $apply['is_reject'] = TRUE;
                $apply->save();
            }
        }
    }

    //上传附件
    function action_uploadAttachment() {
        $this->auto_render = False;
        $view['error'] = '';
        $view['file_path'] = '';
        $view['file_extend']='';
	$view['backgroundColor']='#ffffff';
        $file_name = date('YmdHis') . rand(100,1000);

        //自动创建目录
        if (!is_dir('static/upload/attached/' . date('Ym'))) {
            mkdir('static/upload/attached/' . date('Ym'), 0777);
        }

        if ($_POST) {

            //如果上传了附件
            if ($_FILES['file']['size'] > 0) {

                $valid = Validate::factory($_FILES);
                $valid->rules('file', Model_News::$attached_rule);
                if (!$valid->check()) {
                    $view['err'] .= $valid->outputMsg($valid->errors('validate'));
                } else {
                    // 处理附件
                    $file_extend = strtolower(trim(substr(strrchr($_FILES['file']['name'], '.'), 1)));
                    $view['file_extend']=$file_extend;
                    $replace = '/\.' . $file_extend . '/';
                    $view['old_file_name'] = preg_replace($replace, '', $_FILES['file']['name']);
                    $path = DOCROOT . Model_News::ATTACHED_PATH.date('Ym').'/';
                    $view['file_path'] = URL::base() . Model_News::ATTACHED_PATH .date('Ym').'/'. $file_name . '.'.$file_extend;
                    Upload::save($_FILES['file'], $file_name .'.'.$file_extend, $path);
                }
            }
            // 处理完毕后刷新页面
        }
        echo View::factory('aa_admin/uploadAttachment', $view);
    }

}