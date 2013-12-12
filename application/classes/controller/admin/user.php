<?php

class Controller_Admin_User extends Layout_Admin {

    function before() {
        parent::before();

        $leftbar_links = array(
            'admin_user/index' => '用户列表',
            'admin_user/people' => '北航校友',
        );

        $this->_render('_body_left', compact('leftbar_links'), 'admin/leftbar');
    }

    function action_index() {
        // 所有权限
        $roles = Kohana::config('acl.Roles');
        $roles_color = Kohana::config('acl.RolesColor');
        $search_type = Arr::get($_GET, 'search_type');
        $view['search_type'] = $search_type;
        $view['roles'] = $roles;
        $view['roles_color'] = $roles_color;

        $q = trim(Arr::get($_GET, 'q'));
        $view['q'] = urldecode($q);

        $role = Arr::get($_GET, 'role');
        $view['role'] = urldecode($role);

        $file_no = Arr::get($_GET, 'file_no');
        $view['file_no'] = $file_no;

        $user = Doctrine_Query::create()
                ->select('u.id,u.realname,u.account,u.speciality,u.start_year,u.file_no,u.login_time,u.login_num,u.role,u.sex,u.actived,u.city,u.reg_at,u.is_sended_active')
                ->addSelect('(select count("id") from bbs_unit where user_id=u.id) AS bbs_unit_num')
                ->addSelect('(select count("id") from comment where user_id=u.id) AS comment_num')
                ->addSelect('(select mobile from user_contact  where user_id=u.id limit 1) AS mobile')
                ->from('User u')
                ->orderBy('u.id DESC')
                ->where('u.id>0');

        if ($q) {
            //按真实姓名
            if ($search_type == 'realname') {
                $user->addWhere('u.realname like ?', '%' . $q . '%');
            } elseif ($search_type == 'account') {
                $user->addWhere('u.account=?', $q);
            } elseif ($search_type == 'uid') {
                $user->addWhere('u.id=?', $q);
            } elseif ($search_type == 'city') {
                $user->addWhere('u.city like ?', '%' . $q . '%');
            } elseif ($search_type == 'student_no') {
                $user->addWhere('u.student_no=?', $q);
            } elseif ($search_type == 'file_no') {
                $user->addWhere('u.file_no=?', $q);
            } elseif ($search_type == 'start_year') {
                $user->addWhere('u.start_year=?', $q);
            } elseif ($search_type == 'finish_year') {
                $user->addWhere('u.finish_year=?', $q);
            } elseif ($search_type == 'speciality') {
                $user->addWhere('u.speciality like ?', '%' . $q . '%');
            } else {

            }
        }

        //按身份查看
        if ($role) {
            if ($role == '已与档案挂钩') {
                $user->addWhere('u.file_no>0');
            } elseif ($role == '未与档案挂钩') {
                $user->addWhere('u.file_no IS NULL');
            } else {
                $user->addWhere('u.role = ?', $role);
            }
        }


        //exit;

        $total_user = $user->count();

        $pager = Pagination::factory(array(
                    'total_items' => $total_user,
                    'items_per_page' => 15,
                    'view' => 'pager/common',
                ));
        $view['pager'] = $pager;

        $view['users'] = $user->offset($pager->offset)
                ->limit($pager->items_per_page)
                ->fetchArray();


        $this->_title('用户列表');
        $this->_render('_body', $view);
    }

    //手动设置挂钩并设置为已经认证
    function action_setHook() {
        $user_id = Arr::get($_POST, 'user_id');
        $user_id = Arr::get($_GET, 'user_id',$user_id);
        $alumni_id = Arr::get($_POST, 'alumni_id');
        $alumni_id = Arr::get($_GET, 'alumni_id',$alumni_id);
        
        $alumnidb = new Model_Alumni();
        $alumnidb->conn();
        $alumni = $alumnidb->getOne(array('id' => $alumni_id));

        $user = Doctrine_Query::create()
                ->select('*')
                ->from('User')
                ->where('id =?', $user_id)
                ->fetchOne();

        $post['alumni_id'] = $alumni['id'];
        $post['student_no'] = $alumni['student_no'];
        $post['file_no'] = $alumni['file_no'];
        $post['role'] = '校友(已认证)';
        $user->fromArray($post);
        $user->save();

        //记录操作日志
        $log_data = array();
        $log_data['type'] = '审核认证';
        $log_data['user_id'] = $user_id;
        $log_data['description'] = '将“' . $user['realname'] . '”设置为“校友(已认证)”，并设置为与档案挂钩。';
        Common_Log::add($log_data);

        $this->_redirect(URL::site('admin_user?role=校友(未审核)'));
    }

    //修改校友资料
    function action_form() {
        $id = Arr::get($_GET, 'id');
        $roles = Kohana::config('acl.Roles');
        $view['roles'] = $roles;
        $view['err'] = '';
        $user = Doctrine_Query::create()
                ->from('User u')
                ->where('u.id = ?', $id)
                ->fetchOne();

        $contact = Doctrine_Query::create()
                ->from('UserContact')
                ->where('user_id = ?', $id)
                ->fetchOne();

        if ($_POST) {
            $user->synchronizeWithArray($_POST);
            $user->save();
            if ($contact) {
                $contact->fromArray($_POST);
                $contact->save();
            } else {
                $contact = new UserContact();
                $contact->fromArray($_POST);
                $contact->user_id = $id;
                $contact->save();
            }

            $this->_redirect('admin_user');
            //操作日志
            if ($this->_role == '管理员') {
                $log_data = array();
                $log_data['type'] = '修改帐号';
                $log_data['user_id'] = $id;
                $log_data['description'] = '修改了“' . $user['realname'] . '(' . $user['role'] . '&nbsp;' . $user['account'] . ')”个人基本信息';
                Common_Log::add($log_data);
            }
        }
        $view['user'] = $user;
        $view['contact'] = $contact;
        $this->_render('_body', $view);
    }

    function action_setRole() {
        $this->auto_render = FALSE;
        $val = Arr::get($_GET, 'val');
        $user_id = Arr::get($_GET, 'id', 0);

        $user = Doctrine_Query::create()
                ->from('User u')
                ->where('u.id = ?', $user_id)
                ->fetchOne();
        if ($user) {
            $user['role'] = $val;
            $user->save();
        }
        $this->updateSession($user_id, 'create');

        //记录操作日志
        $log_data = array();
        $log_data['type'] = '审核认证';
        $log_data['user_id'] = $user_id;
        $log_data['description'] = '将“' . $user['realname'] . '”设置为“' . $val . '”';
        Common_Log::add($log_data);
    }

    function action_set($type) {
        $user = Doctrine_Query::create()
                ->from('User u')
                ->where('u.id = ?', Arr::get($_GET, 'id', 0))
                ->fetchOne();

        if ($type == 'password') {
            if ($_POST) {
                if ($user) {
                    $new_pass = Arr::get($_POST, 'password');
                    if (UTF8::strlen($new_pass) < 6) {
                        echo Candy::MARK_ERR . '密码长度不得小于6位';
                        exit;
                    }
                    $user['password'] = md5($new_pass);
                    $user->save();
                }
                exit;
            }

            $view['user'] = $user;
            echo View::factory('admin_user/password', $view);
        }

        if ($type == 'memo') {

            if ($_POST) {
                if ($user) {
                    $user['memo'] = Arr::get($_POST,'memo');
                    $user->save();
                }
                exit;
            }

            $view['user'] = $user;
            echo View::factory('admin_user/memo', $view);
        }
    }

    #删除校友及其相关资料

    function action_del() {
        $this->auto_render = FALSE;
        $cid = Arr::get($_GET, 'cid',0);

        if(!$cid){
            return 1;
            exit;
        }

        $userinfo = Doctrine_Query::create()
                ->from('User')
                ->where('id=?', $cid)
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

        $arr_table = array(
            'AaMember',
            'Album',
            'ClassMember',
            'ClassBbsUnit',
            'ClubMember',
            'Comment',
            'JoinApply',
            'Pic',
            'BbsUnit',
            'UserMsg',
            'UserWork',
            'UserVisitor',
            'UserPrivate',
            'UserMark',
            'UserEdu',
            'UserPoint',
            'UserContact',
            'UserBubble',
            'UserInvite',
            'Event',
            'EventSign',
            'ClassAbook',
            'WeiboContent',
            'WeiboBinding',

        );

        //删除以上
        foreach ($arr_table AS $t) {
            $del = Doctrine_Query::create()
                    ->delete($t)
                    ->where('user_id =?', $cid)
                    ->addWhere('user_id>0')
                    ->execute();
        }

        //删除用户
        $del = Doctrine_Query::create()
                ->delete('User')
                ->where('id =?', $cid)
                ->addWhere('id>0')
                ->execute();

        //删除在线状况
        $del = Doctrine_Query::create()
                ->delete('Ol')
                ->where('uid =?', $cid)
                ->execute();

        //删除关注
        $del = Doctrine_Query::create()
                ->delete('UserMark')
                ->where('user =?', $cid)
                ->execute();

        //删除短信
        $del = Doctrine_Query::create()
                ->delete('UserMsg')
                ->where('send_to =?', $cid)
                ->execute();

        //删除短信
        $del = Doctrine_Query::create()
                ->delete('UserMsg')
                ->where('send_to =?', $cid)
                ->execute();

        //删除头像
        @unlink(URL::base() . 'static/upload/avatar/48/' . $cid . '.jpg');
        @unlink(URL::base() . 'static/upload/avatar/128/' . $cid . '.jpg');

        //操作日志
        if ($this->_role == '管理员') {
            $log_data = array();
            $log_data['type'] = '删除帐号';
            $log_data['user_id'] = $cid;
            $log_data['description'] = '删除了“' . $userinfo['realname'] . '(' . $userinfo['role'] . '&nbsp;' . $userinfo['account'] . ')”注册帐号及其所有相关资料';
            Common_Log::add($log_data);
        }

        $this->updateSession($cid, 'create');
    }

    //档案管理
    function action_alumni() {
        $page_size = 50;
        $view['err'] = '';
        $total_alumni = Doctrine_Query::create()
                ->from('AlumniTemp')
                ->count();
        $view['total_alumni'] = $total_alumni;
        $view['total_page'] = ceil($total_alumni / $page_size);
        $this->_render('_body', $view);
    }

    function action_alumniSub() {
        $this->auto_render = False;
        $page_size = 50;
        $type = Arr::get($_POST, 'type');

        if ($_POST) {
            $total_alumni = Arr::get($_POST, 'total_alumni');
            $page = Arr::get($_POST, 'page', 1);
            $total_page = ceil($total_alumni / $page_size);
            $offset = ($page - 1) * $page_size;

            //添加档案
            if ($type == 'add' AND $total_alumni > 0) {
                $this->addAlumni($page_size);
            }
            //更新档案
            if ($type == 'update' AND $total_alumni > 0) {
                $this->updateAlumni($page_size, $offset);
            }

            //完成导入或继续下一页导入
            if ($total_page >= $page + 1) {
                echo $page + 1;
                exit;
            } else {
                exit;
            }
        }
    }

    //导入档案数据
    function addAlumni($page_size, $offset) {

        $alumni_temp = Doctrine_Query::create()
                ->from('AlumniTemp')
                ->limit($page_size)
                ->orderBy('id')
                ->fetchArray();

        $alumnis = new Doctrine_Collection('Alumni');

        //循环添加
        foreach ($alumni_temp AS $key => $a) {
            $alumnis[$key]->name = $a['name'] ? $a['name'] : null;
            $alumnis[$key]->sex = $a['sex'] ? $a['sex'] : null;
            $alumnis[$key]->birthday = $a['birthday'] ? $a['birthday'] : null;
            $alumnis[$key]->native_place = $a['native_place'] ? $a['native_place'] : null;
            $alumnis[$key]->nation = $a['nation'] ? $a['nation'] : null;
            $alumnis[$key]->card_no = $a['card_no'] ? $a['card_no'] : null;
            $alumnis[$key]->full_time = $a['full_time'] ? $a['full_time'] : null;
            $alumnis[$key]->education = $a['education'] ? $a['education'] : null;
            $alumnis[$key]->school = $a['school'] ? $a['school'] : null;
            $alumnis[$key]->depart = $a['depart'] ? $a['depart'] : null;
            $alumnis[$key]->speciality = $a['speciality'] ? $a['speciality'] : null;
            $alumnis[$key]->begin_year = $a['begin_year'] ? $a['begin_year'] : null;
            $alumnis[$key]->graduation_year = $a['graduation_year'] ? $a['graduation_year'] : null;
            $alumnis[$key]->student_no = $a['student_no'] ? $a['student_no'] : null;
            $alumnis[$key]->file_no = $a['file_no'] ? $a['file_no'] : null;
            $alumnis[$key]->institute = $a['institute'] ? $a['institute'] : null;
            $alumnis[$key]->institute_no = $a['institute_no'] ? $a['institute_no'] : null;

            if ($key == 0) {
                $temp_start_id = $a['id'];
            }
            if ($key == count($alumni_temp) - 1) {
                $temp_end_id = $a['id'];
            }
        }
        $alumnis->save();

        //删除原数据，避免重复导入
        $delete_alumni_temp = Doctrine_Query::create()
                ->delete('AlumniTemp')
                ->where('id>=?', $temp_start_id)
                ->addWhere('id<=?', $temp_end_id)
                ->execute();
    }

    //更新档案数据
    function updateAlumni($page_size, $offset) {

        $alumni_temp = Doctrine_Query::create()
                ->from('AlumniTemp')
                ->offset($offset)
                ->limit($page_size)
                ->fetchArray();

        //循环添加
        foreach ($alumni_temp AS $key => $a) {
            $alumni = Doctrine_Query::create()
                    ->from('Alumni')
                    ->where('file_no= ?', $a['file_no'])
                    //->addWhere('name= ?', $a['name'])
                    ->limit(1)
                    ->fetchOne();

            //更新档案
            if ($alumni) {
                unset($a['id']);
                unset($a['file_no']);
                unset($a['name']);
                foreach ($a as $key => $val) {
                    if ($val) {
                        $alumni[$key] = $val;
                    }
                }
                $alumni->save();
                $alumni = null;
            }
            //添加新档案
            else {
                unset($a['id']);
                $new_alumni = new Alumni();
                foreach ($a as $key => $val) {
                    if ($val) {
                        $post[$key] = $val;
                    }
                }
                $new_alumni->fromArray($post);
                $new_alumni->save();
                $new_alumni = null;
                $post = null;
            }
        }
    }

    //导出信息
    function action_export() {
        $this->auto_render = FALSE;

        $this->request->headers['Content-Type'] = 'application/ms-excel';

        Candy::import('excelMaker');

        $id = Arr::get($_GET, 'id');

        $members = Doctrine_Query::create()
                ->select('u.id,u.realname,u.city,u.sex,u.start_year,u.start_year,u.finish_year,u.speciality,u.account AS email,c.tel AS tel,c.mobile AS mobile,c.address AS address,u.reg_at')
                ->from('User u')
                ->leftJoin('u.Contact c')
                ->where('u.alumni_id >0')
                ->orderBy('u.id ASC')
                ->fetchArray();

        $xls[1] = array('用户ID', '姓名', '所在城市', '性别', '入学年份', '毕业年份', '毕业专业', '电话', '手机', '邮箱', '地址', '注册日期');

        foreach ($members as $i => $m) {
            $xls[$i + 2][] = $m['id'];
            $xls[$i + 2][] = $m['realname'];
            $xls[$i + 2][] = $m['city'];
            $xls[$i + 2][] = $m['sex'];
            $xls[$i + 2][] = $m['start_year'] ? $m['start_year'] : '';
            $xls[$i + 2][] = $m['finish_year'] ? $m['finish_year'] : '';
            $xls[$i + 2][] = $m['speciality'];
            $xls[$i + 2][] = $m['tel'];
            $xls[$i + 2][] = $m['mobile'];
            $xls[$i + 2][] = $m['email'];
            $xls[$i + 2][] = $m['address'];
            $xls[$i + 2][] = $m['reg_at'];
        }

        $excel = new Excel_Xml('UTF-8');
        $excel->addArray($xls);
        $excel->generateXML('user');
    }

}