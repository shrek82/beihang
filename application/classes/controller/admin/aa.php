<?php

class Controller_Admin_Aa extends Layout_Admin {

    function before() {
        parent::before();

        $leftbar_links = array(
            'admin_aa/index' => '分会列表',
            'admin_aa/applyManager' => '最近加入申请',
        );

        $this->_render('_body_left', compact('leftbar_links'), 'admin/leftbar');
    }

    function action_index() {

        $group = Arr::get($_GET, 'group');
        $q = urldecode(Arr::get($_GET, 'q'));
        $class = Arr::get($_GET, 'class', '地方校友会');
        $view['class'] = $class;

        $view['q'] = $q;

        $aa_group = Kohana::config('aa_group');
        $view['aa_group'] = array_merge($aa_group[0], $aa_group[1]);

        $aa = Doctrine_Query::create()
                ->select('a.name, a.ename,a.class, m.title, u.realname, u.account,a.group,a.order_num')
                ->addSelect('(SELECT COUNT(t.id) FROM AaMember t WHERE t.aa_id = a.id) AS total_member')
                ->addSelect('(SELECT COUNT(j.id) FROM JoinApply j WHERE j.aa_id = a.id AND j.is_reject=False) AS total_join')
                ->from('Aa a')
                ->leftJoin('a.Members m WITH m.chairman = 1')
                ->leftJoin('m.User u');

        //搜索关键字
        if ($q) {
            $aa->addWhere('a.name LIKE ?', '%' . $q . '%');
        } else {

            //按校友会类型
            $aa->where('a.class= "' . $class . '"');

            //地区
            if ($group) {
                $aa->addWhere('a.group =?', $group);
            }
        }

        $aa->orderBy('a.group ASC,a.order_num ASC,a.id DESC');
        $total_aa = $aa->count();

        $pager = Pagination::factory(array(
                    'total_items' => $total_aa,
                    'items_per_page' => 15,
                    'view' => 'pager/common',
                ));

        $view['group'] = $group;
        $view['pager'] = $pager;
        $view['aa'] = $aa->offset($pager->offset)
                ->limit($pager->items_per_page)
                ->fetchArray();

        $this->_title('校友会列表');
        $this->_render('_body', $view);
    }

    //添加和修改校友会信息
    function action_form() {

        $id = Arr::get($_GET, 'id');
        $aa_group = Kohana::config('aa_group');
        $view['manager'] = '';
        $view['aa_group'] = array_merge($aa_group[0], $aa_group[1]);

        $view['err'] = '';
        $aa = Doctrine_Query::create()
                ->from('Aa')
                ->where('id = ?', $id)
                ->addWhere('id >0')
                ->fetchOne();
        $view['aa'] = $aa;

        //管理员信息
        if ($id) {
            $view['manager'] = Doctrine_Query::create()
                    ->from('AaMember am')
                    ->leftJoin('am.User u')
                    ->where('am.aa_id = ?', $id)
                    ->addWhere('am.chairman = ?', True)
                    ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
        }

        if ($_POST) {
            $post['name'] = Arr::get($_POST, 'name');
            $post['sname'] = Arr::get($_POST, 'sname');
            $post['class'] = Arr::get($_POST, 'class');
            $post['group'] = Arr::get($_POST, 'group');
            $post['intro'] = Arr::get($_POST, 'intro');
            $post['found_at'] = Arr::get($_POST, 'found_at');
            $post['institute_speciality_key'] = Arr::get($_POST, 'institute_speciality_key');
            $post['contacts'] = Arr::get($_POST, 'contacts');
            $post['email'] = Arr::get($_POST, 'email');
            $post['tel'] = Arr::get($_POST, 'tel');
            $post['address'] = Arr::get($_POST, 'address');
            $post['order_num'] = Arr::get($_POST, 'order_num');
            $old_chairman = Arr::get($_POST, 'old_chairman');
            $chairman = Arr::get($_POST, 'chairman');

            if (!$aa) {
                //创建分会
                $last_aa = Doctrine_Query::create()
                        ->select('id')
                        ->from('Aa')
                        ->orderBy('id DESC')
                        ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
                $post['id'] = $last_aa['id'] + 1;
                $aa = new Aa();
                $aa->fromArray($post);
                $aa->save();
                $action_name = '添加';
            } else {
                $aa->synchronizeWithArray($post);
                $action_name = '修改';
                $aa->save();
            }

            //设置管理员
            if ($aa->id AND ($old_chairman != $chairman) AND $chairman) {
                $this->action_setChairman($aa->id, $chairman);
            } else {

            }

            //取消管理员
            if (!$chairman) {
                $this->action_setChairman($aa->id, false);
            }

            //操作日志
            if ($this->_role == '管理员') {
                $log_data = array();
                $log_data['type'] = '地方校友会';
                $log_data['aa_id'] = $id;
                $log_data['description'] = $action_name . '了“' . $post['name'] . '”信息';
                Common_Log::add($log_data);
            }

            $this->_redirect(URL::site('admin_aa/form?id=' . $aa->id));
        }

        $this->_title('编辑校友会');
        $this->_render('_body', $view);
    }

    // 设置校友会会长
    function action_setChairman($aa_id, $account) {
        if ($aa_id > 0 AND $account) {
            $user = Doctrine_Query::create()
                    ->select('id,account')
                    ->from('User')
                    ->where('account = ?', $account)
                    ->orderBy('id DESC')
                    ->limit(1)
                    ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
            //修改或添加管理员
            if ($user) {
                Model_Aa::set_chairman($aa_id, $user['id'], '管理员');
            }
        }

        //取消管理员
        if ($aa_id > 0 AND $account == False) {
            Model_Aa::set_chairman($aa_id, False, '管理员');
        }
    }

    //保存校友会排序
    function action_setOrder() {
        $this->auto_render = False;
        $aa_id = $_POST['aa_id'];
        foreach ($aa_id as $id) {
            $aa = Doctrine_Query::create()
                    ->from('Aa')
                    ->where('id=?', $id)
                    ->fetchOne();
            $aa['order_num'] = Arr::get($_POST, 'order_num' . $id);
            $ename = Arr::get($_POST, 'ename' . $id);
            if ($ename) {
                if ($this->checkename($id, $ename)) {
                    $aa['ename'] = $ename;
                } else {
                    $aa['ename'] = null;
                }
            }
            $aa->save();
        }

        //程序生成.htaccess文件
        $this->createHtaccess();

        $this->_redirect('admin_aa/index?group=' . Arr::get($_GET, 'group') . '&page=' . Arr::get($_GET, 'page'));
    }

    //检查名称是否可用
    function checkename($id, $ename) {
        $sys_ename = array('aa', 'news', 'classroom', 'event', 'bbs', 'people', 'donate', 'publication', 'service', 'mail', 'help');
        $count = Doctrine_Query::create()
                ->select('id')
                ->from('Aa')
                ->where('ename=?', $ename)
                ->addWhere('id<>?', $id)
                ->count();

        if (isset($sys_ename[$ename]) OR $count > 0) {
            return False;
        } else {
            return True;
        }
    }

    //生成htaccess文件
    function createHtaccess() {
        $this->auto_render = False;
        $aa = Doctrine_Query::create()
                ->select('a.id,a.ename')
                ->from('Aa a')
                ->orderBy('a.id ASC')
                ->where('a.ename IS NOT NULL')
                ->fetchArray();

        if ($aa) {
            $aa_url = '';
            foreach ($aa AS $a) {
                if ($a['ename']) {
                    $aa_url.='RewriteRule ^' . $a['ename'] . '$ aa_home?id=' . $a['id'] . ' [NC]' . "\r\n";
                }
            }

            $templates = '';
            $PHP_OS = PHP_OS;
            if (strtolower($PHP_OS) == 'linux') {
                $templates_file = 'static/htaccess_debian.txt';
            } else {
                $templates_file = 'static/htaccess_windows.txt';
            }

            if (file_exists($templates_file)) {
                $templates = file_get_contents($templates_file);
            }

            if ($templates AND $aa_url) {
                $templates = str_replace('#url_start#', $aa_url, $templates);
                $fp = fopen('.htaccess', 'w');
                if (fwrite($fp, $templates)) {
                    fclose($fp);
                } else {
                    fclose($fp);
                }
            }
        }
    }

    //设置地区分类
    function action_setGroup() {
        $this->auto_render = FALSE;
        $id = Arr::get($_GET, 'id');
        $group = Arr::get($_GET, 'group');
        $q = Doctrine_Query::create()
                ->update('Aa')
                ->set('group', $group)
                ->where('id=?', $id)
                ->execute();

        echo 'Done';
    }

    //加入校友会申请
    function action_applyManager() {

        $q = Arr::get($_GET, 'q');

        $apply = Doctrine_Query::create()
                ->select('j.*,u.realname AS realname,u.city AS city,u.role as role,a.sname AS aa_name,a.ename AS aa_ename')
                ->from('JoinApply j')
                ->leftJoin('j.User u')
                ->leftJoin('j.Aa a')
                ->where('j.aa_id>0');

        if ($q) {
            $apply->addWhere('u.realname=?', $q);
        }

        $pager = Pagination::factory(array(
                    'total_items' => $apply->count(),
                    'items_per_page' => 20,
                    'view' => 'pager/common'
                ));

        $apply = $apply->orderBy('j.id DESC')
                ->offset($pager->offset)
                ->limit($pager->items_per_page)
                ->fetchArray();

        $view = compact('apply', 'pager');
        $this->_render('_body', $view);
    }

    //批准成为管理员
    function action_applyAccept() {
        $this->auto_render = FALSE;
        $cid = Arr::get($_GET, 'cid');
        $user_id = Arr::get($_GET, 'user_id');
        $aa_id = Arr::get($_GET, 'aa_id');

        $apply = Doctrine_Query::create()
                ->from('JoinApply')
                ->where('id = ?', $cid)
                ->addWhere('user_id=?', $user_id)
                ->addWhere('aa_id=?', $aa_id)
                ->fetchOne();

        if ($apply) {
            $aa = Doctrine_Query::create()
                    ->select('id,sname,name')
                    ->from('Aa')
                    ->where('id = ?', $apply['aa_id'])
                    ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
            //加入到校友会
            Model_Aa::join($aa_id, $user_id);
            $apply->delete();

            //发送通知
            unset($post);
            $post['send_to'] = $user_id;
            $post['sort_in'] = 0;
            $post['user_id'] = $this->_sess->get('id');
            $post['content'] = '恭喜您，您已经成功加入<a href="' . URL::site('aa_home?id=' . $aa['id']) . '" style="color:blue">' . $aa['name'] . '</a>，请点击名称直接访问，谢谢！';
            $post['send_at'] = date('Y-m-d H:i:s');
            $post['update_at'] = date('Y-m-d H:i:s');
            $msg = new UserMsg();
            $msg->fromArray($post);
            $msg->save();
        }
    }

    //拒绝成为加入校友会
    function action_applyReject() {
        $this->auto_render = FALSE;
        $cid = Arr::get($_GET, 'cid');
        $user_id = Arr::get($_GET, 'user_id');
        $aa_id = Arr::get($_GET, 'aa_id');
        $reject_reason = Arr::get($_POST, 'reject_reason');

        $apply = Doctrine_Query::create()
                ->from('JoinApply')
                ->where('id = ?', $cid)
                ->fetchOne();

        if ($_POST) {
            $post['reject_reason'] = $reject_reason;
            $post['is_reject'] = True;
            $apply->fromArray($post);
            $apply->save();

            //发送通知
            unset($post);
            $msg = new UserMsg();
            $post['sort_in'] = 0;
            $post['send_to'] = $apply['user_id'];
            $post['user_id'] = $this->_sess->get('id');
            $post['content'] = '您申请校友会已经得到回复：<br>' . $reject_reason;
            $post['send_at'] = date('Y-m-d H:i:s');
            $post['update_at'] = date('Y-m-d H:i:s');
            $msg->fromArray($post);
            $msg->save();
        }

        $view['reason'] = $apply['reject_reason'];
        $view['action'] = URL::site('admin_classroom/applyReject?cid=' . $cid);
        echo View::factory('inc/reject_reason', $view);
    }

}