<?php

class Controller_User extends Layout_Main {

    function action_modalumni() {
//	        Doctrine_Query::create()
//                ->update('Alumni')
//                ->where('speciality= "电力系统及其自动化"')
//                ->set('institute', '"电气学院"')
//                ->execute();
    }

    # 所有用户加入组织的申请处理

    function action_apply() {
        $this->auto_render = False;
        $user_id = $this->_sess->get('id');
        if (!$user_id) {
            echo Candy::MARK_ERR . '没登录不能执行该操作';
            exit;
        }

        $id = Arr::get($_POST, 'id', 0);
        $data['user_id'] = $user_id;
        $data['content'] = Arr::get($_POST, 'content');
        $data['apply_at'] = date('Y-m-d H:i:s');
        $data['aa_id'] = Arr::get($_POST, 'aa_id');
        $data['club_id'] = Arr::get($_POST, 'club_id');
        $data['class_room_id'] = Arr::get($_POST, 'class_room_id');

        Model_User::apply($data, $id);

        //来自新版校友会
        if (Arr::get($_POST, 'aa_id') > 0) {
            $this->_redirect('aa_home?id=' . Arr::get($_POST, 'aa_id'));
        }
    }

    //加入班级并成为首位管理员(中间导入用户)
    function action_joinClassBeManager() {
        $user_id = $this->_sess->get('id');
        $total_member = (int) Arr::get($_GET, 'total_member');
        if (!$user_id) {
            echo Candy::MARK_ERR . '没登录不能执行该操作';
            exit;
        }
        $classMember = new ClassMember();
        $post['user_id'] = $user_id;
        $post['class_room_id'] = Arr::get($_POST, 'class_room_id');
        if ($total_member > 0) {
            $post['is_verify'] = False;
        } else {
            $post['is_manager'] = 1;
            $post['title'] = '管理员';
            $post['is_verify'] = true;
        }
        $post['join_at'] = date('Y-m-d H:i:s');
        $post['visit_at'] = date('Y-m-d H:i:s');
        $classMember->fromArray($post);
        $classMember->save();
    }

    # 设置用户隐私权限

    function action_private() {
        $this->auto_render = FALSE;
        $user_id = $this->_sess->get('id');
        if (!$user_id)
            exit;

        if (Request::$is_ajax && $_POST) {

            $new_rule = array($_POST['name'] => $_POST['rule']);

            $private = Doctrine_Query::create()
                    ->from('UserPrivate')
                    ->where('user_id = ?', $user_id)
                    ->fetchOne();

            if (!$private) {
                $private = new UserPrivate();
                $private->user_id = $user_id;
                $private->rules = serialize($new_rule);
                $private->save();
            } else {
                $rules = unserialize($private['rules']);
                // 替换原有value
                if (array_key_exists($_POST['name'], $rules)) {
                    $rules[$_POST['name']] = $_POST['rule'];
                } else {
                    $rules += $new_rule;
                }
                $private['rules'] = serialize($rules);
                $private->save();
            }
        }
    }

    function action_logout() {
        $redirect = Arr::get($_GET, 'redirect', '/');
        $this->_sess->destroy();
        @Cookie::delete('zuaa_ac');
        @Cookie::delete('zuaa_uid');
        @Cookie::delete('zuaa_account');
        @Cookie::delete('zuaa_realname');
        @Cookie::delete('zuaa_role');
        @Cookie::delete('zuaa_city');
        @Cookie::delete('zuaa_last_login');
        @Cookie::delete('actived');

        $this->request->redirect($redirect);
    }

    # 在线用户

    function action_online() {
        if (Request::$is_ajax) {
            $user_online = Model_User::online();
            echo json_encode($user_online);
            exit;
        }
    }

    #ajax登陆

    function action_login() {
        $view['err'] = '';
        $next = Arr::get($_POST, 'next') ? Arr::get($_POST, 'next') : Arr::get($_GET, 'next');

        if ($_POST) {
            $v = Validate::setRules($_POST, 'login');
            $post = $v->getData();
            if (!$v->check()) {
                echo Candy::MARK_ERR . $v->outputMsg($v->errors('validate'));
            } else {
                $login_result = Model_User::login($post);
                if (is_numeric($login_result)) {
                    echo Candy::MARK_ERR . '账号密码错误，还剩' . $login_result . '次尝试机会';
                } else {

                    $user_id = $this->_sess->get('id');

                    #查找是否为某校友会管理员,是则保存校友会id
                    $chairman_aa = $this->action_chairman($user_id);
                    if ($chairman_aa) {
                        $this->_sess->set('chairman_aa', $chairman_aa);
                    }

                    // 如果没有填写工作城市则跳转过去
                    $city = $this->_sess->get('city');

                    if (!$city) {
                        //$this->_sess->set('reg_user_id', $user_id);
                        //$this->_redirect(('/user/job'));
                        //echo URL::site('/user_info/base/');
                        // exit;
                    }
                    // 如果没有加入任何校友会就跳转到推荐页面
                    $joined_aa = Doctrine_Query::create()
                            ->from('AaMember')
                            ->where('user_id = ?', $user_id)
                            ->count();
                    if ($joined_aa == 0) {
                        // $this->_sess->set('reg_user_id', $user_id);
                        //$this->_redirect(('/user/recommend'));
                        //echo URL::site('user/recommend');
                        //exit;
                    }
                }
            }

            if ($next) {
                $this->_redirect($next);
            } else {
                exit;
            }
        }

        if (Request::$is_ajax) {
            echo View::factory('user/facebox_login');
        }

        $view['account'] = Arr::get($_GET, 'account');

        $this->_title('登录');
        $this->_render('_body', $view);
    }

    //北航创业中国
    function create_token($next) {
        if ($next) {
            $timestamp = time();
            $token = md5($this->_sess->get('id') . $timestamp . Model_User::SECRET_KEY);
            $this->_redirect(urldecode($next) . '?id=' . $this->_sess->get('id') . '&timestamp=' . $timestamp . '&token=' . $token);
            exit;
        }
    }

    //普通方式登陆
    function action_sslogin() {
        $next = Arr::get($_POST, 'next') ? Arr::get($_POST, 'next') : Arr::get($_GET, 'next');
        $view['next'] = $next;
        $view['err'] = '';

        if ($next AND $this->_sess->get('id')) {
            $this->create_token($next);
        }

        $view['err'] = '';
        if ($_POST) {
            $v = Validate::setRules($_POST, 'login');
            $post = $v->getData();
            if (!$v->check()) {
                $view['err'] .= $v->outputMsg($v->errors('validate'));
            } else {
                $login_result = Model_User::login($post);
                if (is_numeric($login_result)) {
                    $view['err'] .= '账号密码错误，还剩' . $login_result . '次尝试机会';
                } else {

                    $user_id = $this->_sess->get('id');

                    #查找是否为某校友会管理员,是则保存校友会id
                    $chairman_aa = $this->action_chairman($user_id);
                    if ($chairman_aa) {
                        $this->_sess->set('chairman_aa', $chairman_aa);
                    }

                    // 如果没有填写工作城市则跳转过去
                    $city = $this->_sess->get('city');

                    // 如果没有加入任何校友会就跳转到推荐页面
                    $joined_aa = Doctrine_Query::create()
                            ->from('AaMember')
                            ->where('user_id = ?', $user_id)
                            ->count();


                    //北航创业中国跳转登陆
                    if ($next) {
                        $this->create_token($next);
                    } else {
                        $this->_redirect('user_home');
                    }
                }
            }
        }

        $view['account'] = Arr::get($_GET, 'account');

        $this->_title('登录');
        $this->_render('_body', $view, 'user/login');
    }

    #检测是否为某校友会管理员，并返回校友会id

    function action_chairman($user_id) {
        $aa_id = Doctrine_Query::create()
                ->select('aa_id')
                ->from('AaMember')
                ->where('user_id = ?', $user_id)
                ->addWhere('chairman=?', 1)
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

        return $aa_id['aa_id'];
    }

    # 重新发送激活邮件

    function action_reactive() {
        $data['account'] = $this->_sess->get('account');
        $data['realname'] = $this->_sess->get('realname');
        if (!$data['account'] || !$data['realname']) {
            echo 'deny';
        } else {
            Model_User::sendActiveMail($data);
            echo '发送成功！';
        }
    }

    # 激活账号

    function action_active() {
        $account = urldecode(Arr::get($_GET, 'addr'));
        $enc = Arr::get($_GET, 'enc');
        $actived = FALSE;

        if ($enc == md5($account . 'zuaa')) {
            Doctrine_Query::create()->update('User')
                    ->where('account = ?', $account)
                    ->set('actived', TRUE)
                    ->execute();

            $actived = TRUE;
            // 更换sess
            $this->_sess->set('actived', TRUE);
        }

        $this->_title('激活账号');
        $this->_render('_body', compact('account', 'actived'));
    }

    function action_register($step = 1) {
        switch ($step) {
            case 1: // 身份验证
                $this->_redirect('user/auth');
                break;
            case 2: // 帐号信息,生成用户
                $this->_redirect(('user/account'));
                break;
            case 3: // 填写工作,推荐加入校友会
                $this->_redirect(('user/job'));
                break;
            case 4: // 推荐可关注的校友以及班级录
                $this->_redirect('user/recommend');
                break;
        }
    }

    /**
     * 推荐加入组织
     * @param <type> $type
     */
    function action_recommend() {
        $user_id = $this->_sess->get('reg_user_id', 0);
        $post_data = $this->_sess->get('reg_auth_data');
        $post_speciality_key = $post_data['speciality'];
        $albmni_institute = null;
        // 根据地点推荐加入校友会
        $row = Doctrine_Query::create()
                ->select('u.city, u.alumni_id,u.realname,u.sex')
                ->from('User u')
                ->where('u.id = ?', $user_id)
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

        if ($_POST) {
            // 有档案id的直接加入班级跟校友会
            if ($row['alumni_id']) {
                // 校友会
                if (isset($_POST['aa_id'])) {
                    foreach ($_POST['aa_id'] as $aa_id) {
                        Model_Aa::join($aa_id, $user_id);
                    }
                }
                // 班级
                if (isset($_POST['classroom_id'])) {
                    foreach ($_POST['classroom_id'] as $cr_id) {
                        Model_Classroom::join($cr_id, $user_id);
                    }
                }
            }
            // 没有档案id的待审核
            else {
                if (isset($_POST['aa_id'])) {
                    // 只加入校友会，并进入待审核状态
                    foreach ($_POST['aa_id'] as $aa_id) {
                        $data['user_id'] = $user_id;
                        $content = '注册时没有档案认证，系统根据工作城市自动申请加入，请审核。';
                        $data['content'] = $content;
                        $data['apply_at'] = date('Y-m-d H:i:s');
                        $data['aa_id'] = $aa_id;
                        Model_User::apply($data);
                    }
                }
            }

            // 清除注册临时信息
            $this->_sess->delete('reg_auth_data');
            $this->_sess->delete('reg_archive_id');
            exit;
        }

        // 如果有对应的档案则推荐加入班级
        if ($row['alumni_id']) {
            $alumni = Doctrine_Query::create()
                    ->select('id,speciality, begin_year, graduation_year,institute')
                    ->from('Alumni')
                    ->where('id = ?', $row['alumni_id'])
                    ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

            $albmni_institute = $alumni['institute'];

            $classroom = Doctrine_Query::create()
                    ->from('ClassRoom')
                    ->where('name ="' . $alumni['speciality'] . '"  OR speciality="' . $alumni['speciality'] . '"');

            if ($alumni['begin_year'] > 0) {
                $classroom->andWhere('start_year = ' . $alumni['begin_year'] . '');
            }

            $view['classroom'] = $classroom->fetchArray();
        }


        if (!$row['city'] || $user_id == 0) {
            $this->_redirect('user/login');
        } else {

            // 过滤掉市
            if (strstr($row['city'], '市') && UTF8::strlen($row['city']) > 2) {
                $row['city'] = str_replace('市', '', $row['city']);
            }

            $view['aa'] = Doctrine_Query::create()
                    ->from('Aa');

            //城市或学院或专业
            if ($albmni_institute and $post_speciality_key) {
                $view['aa']->where('range LIKE ? OR institute_speciality_key LIKE ? OR name=? OR name=?', array('%' . $row['city'] . '%', '%' . $post_speciality_key . '%', $albmni_institute, $albmni_institute . '(筹)'));
            }
            //根据城市或专业名称
            elseif ($post_speciality_key) {
                $view['aa']->where('range LIKE ? OR (institute_speciality_key LIKE ? AND institute_speciality_key is not null)', array('%' . $row['city'] . '%', '%' . $post_speciality_key . '%'));
            } else {
                //根据城市
                $view['aa']->where('range LIKE ?', array('%' . $row['city'] . '%'));
            }

            $view['aa'] = $view['aa']->fetchArray();
            $view['row'] = $row;

            $this->_render('_body', $view);
        }
    }

    function action_job() {
        // 检测是否为正常注册

        Model_User::login(array('account' => '37294812@qq.com', 'password' => '123456'));

        $user_id = $this->_sess->get('id');

        if (!$user_id || !$this->_sess->get(Model_User::AUTHED)) {
            $this->_redirect('user/register/');
        }

        if ($_POST) {
            $v = Validate::setRules($_POST, 'reg_work');
            $post = $v->getData();
            if (!$v->check()) {
                echo Candy::MARK_ERR . $v->outputMsg($v->errors('validate'));
            } else {
                // 更新用户的city
                $user = Doctrine_Core::getTable('User')->find($user_id);
                $user->city = $post['city'];
                $user->save();
                // 加入最新的工作信息
                $post['user_id'] = $user_id;
                $work_n = new UserWork();
                $work_n->fromArray($post);
                $work_n->save();
                echo URL::site('user/register/4');
            }
            exit;
        }

        // 行业分类数据
        $view['industry'] = $this->_conf('industry');
        $this->_render('_body', $view);
    }

    function action_account() {
        // 检测是否为正常注册
        if (!$this->_sess->get(Model_User::AUTHED)) {
            $this->_redirect('user/register');
        }

        //第一步提交的姓名 专业 入学年份资料
        $post_data = $this->_sess->get('reg_auth_data');
        //选择的档案编号
        $archive_id = $this->_sess->get('reg_archive_id');
        // 默认先将提交的个人信息保存为注册帐号信息
        $view['realname'] = $post_data['name'];
        $view['start_at'] = $post_data['start'];
        $view['finish_at'] = $post_data['finish'];
        $view['sex'] = '';
        $view['school'] = '';
        $view['alumni_id'] = 0;
        $view['registered'] = '';
        $registered = false;

        if ($archive_id > 0) {
            //获取档案序号
            $archive = Doctrine_Query::create()
                    ->from('Alumni a')
                    ->where('a.id = ?', $archive_id)
                    ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

            //是否已经注册过
            $registered = Doctrine_Query::create()
                    ->from('User')
                    ->select('id,account,realname')
                    ->where('file_no = ?', $archive['file_no'])
                    ->limit(1)
                    ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

            $view['registered'] = $registered;

            // 注册信息从档案补全
            $view['school'] = $archive['school'];
            $view['speciality'] = $archive['speciality'];
            $view['grade'] = $archive['education'];
            $view['start_at'] = $archive['begin_year'];
            $view['finish_at'] = $archive['graduation_year'];
            $view['sex'] = $archive['sex'];
            $view['alumni_id'] = $archive['id'];
            $view['archive'] = $archive;
        }


        if ($_POST) {
            if ($registered) {
                $this->deny('很抱歉，您已经通过该档案注册过帐号了,不需要再注册了！');
                exit;
            }
            $v = Validate::setRules($_POST, 'register');
            $post = $v->getData();
            $v->rule('password2', 'matches', array('password'));
            $v->rule('account', 'Model_User::validAccount');
            $point_config = Kohana::config('point')->add;
            //$v->rule('mobile', 'Model_User::validMobile');
            if (!$v->check()) {
                echo Candy::MARK_ERR .
                $v->outputMsg($v->errors('validate'));
            } else {
                //存在档案
                if ($archive_id > 0) {
                    $post['file_no'] = $archive['file_no'];
                    $post['student_no'] = $archive['student_no'];
                    $post['start_year'] = $archive['begin_year'] > 0 ? $archive['begin_year'] : $post_data['start'];
                    $post['finish_year'] = $archive['graduation_year'] > 0 ? $archive['graduation_year'] : $post_data['finish'];
                    $post['speciality'] = $archive['speciality'] ? $archive['speciality'] : $post_data['speciality'];
                    $post['birthday'] = $archive['birthday'];
                    $post['education'] = $archive['education'];
                    $post['school'] = $archive['school'];
                    $post['institute'] = $archive['institute'];
                    $post['institute_no'] = $archive['institute_no'];
                    $post['authentic'] = 1;
                }
                //不存在档案
                else {
                    $post['start_year'] = $post_data['start'];
                    $post['finish_year'] = $post_data['finish'];
                    $post['speciality'] = $post_data['speciality'];
                }

                $post['password'] = $post['password'];
                $post['sex'] = $post['sex'];
                $post['role'] = '校友(未审核)';
                $post['actived'] = False;
                $post['reg_at'] = date('Y-m-d H:i:s');
                $post['login_time'] = date('Y-m-d H:i:s');
                $post['point'] = $point_config['register'];
                $user_id = Model_User::create($post);

                //注册成功后保存手机号码
                if ($user_id > 0 AND $this->_sess->get('reg_mobile')) {
                    $post_contact['user_id'] = $user_id;
                    $post_contact['mobile'] = $this->_sess->get('reg_mobile');
                    Model_User::createContact($post_contact);
                }

                //登录session
                if (!Model_User::login($post)) {
                    echo Candy::MARK_ERR . '自动登录失败！';
                }

                $this->_sess->set('reg_archive_id', null);
                $this->_sess->set('reg_user_id', $user_id);

                //通过受邀注册
                $invite_id = $this->_sess->get('reg_invite');
                if ($invite_id) {

                    //给予自己受邀注册奖励
                    Db_User::updatePoint('all', $point_config['register_from_invite']);

                    //保存日志
                    Model_Invite::saveLog(array(
                        'invite_id' => $invite_id,
                        'receiver_user_id' => $user_id
                    ));

                    //清空邀请session
                    $this->_sess->set('reg_invite', null);
                    $this->_sess->set('receiver_email', null);
                }

                //普通注册积分初始化
                else {
                    Db_User::updatePoint('all', $point_config['register']);
                }
            }
            exit;
        }

        $this->_render('_body', $view);
    }

    function action_doauth() {
        $archive_id = Arr::get($_POST, 'archive', 0);
        $this->_sess->set('reg_archive_id', $archive_id);
        $this->_sess->set(Model_User::AUTHED, TRUE);
        $this->_redirect('user/register/2');
    }

    function action_auth() {
        if ($_POST) {

            $v = Validate::setRules($_POST, 'auth');
            $post = $v->getData();

            if (!$v->check()) {

                echo Candy::MARK_ERR . $v->outputMsg($v->errors('validate'));
                exit;
            } else {

                if ($post['start'] == 0 && $post['finish'] == 0) {
                    echo Candy::MARK_ERR . '入学年份跟毕业年份必须填写其中一项';
                    exit;
                }
                $url = Kohana::config('links.site') . 'api_alumni_auth';
                $json = Candy_Network::httpRequest('get', $url, $post);

                $data_array = json_decode($json, TRUE);

                if (count($data_array['resp']) > 0) { // 符合多条则专业关键字过滤
                    // 专业关键字逐字拆分
                    $keywords = UTF8::str_split($post['speciality']);
                    $departs = array();
                    foreach ($data_array['resp'] as $ix => $data) {
                        $in_data = FALSE;
                        foreach ($keywords as $key) {
                            if (strstr($data['speciality'], $key) OR strstr($data['institute'], $key) OR strstr($data['school'], $key)) {
                                $in_data = TRUE;
                            }
                        }
                        if ($in_data == FALSE) {
                            unset($data_array['resp'][$ix]);
                        }
                    }
                }

                // 提交数据先暂存session
                $this->_sess->set('reg_auth_data', $post);

                // 手机号码
                $this->_sess->set('reg_mobile', Arr::get($_POST, 'mobile'), False);

                // 返回结果，直接追加到表单底部
                //echo Candy::MARK_RESP .
                //返回字符
                echo View::factory('inc/register/auth_result', array('resp' => $data_array['resp']));
            }
            exit;
        }

        $this->_title('加入校友会');
        $this->_render('_body');
    }

    //用户详细信息（供公管理员、校友会管理员、俱乐部、班级管理员等查询）
    function action_userDetail() {
        $id = Arr::get($_GET, 'id');
        $template = Arr::get($_GET, 'template', 'user/userDetail');
        $view['alumni'] = null;
        $webmanager = Arr::get($_GET, 'webmanager');
        $view['webmanager'] = $webmanager;
        $view['page'] = Arr::get($_GET, 'page');
        $view['role'] = Arr::get($_GET, 'role');
        $view['file_no'] = Arr::get($_GET, 'file_no');

        $view['user'] = Doctrine_Query::create()
                ->select('u.*')
                ->from('User u')
                ->where('u.id =?', $id)
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

        $view['contact'] = Doctrine_Query::create()
                ->select('c.*')
                ->from('UserContact c')
                ->where('c.user_id =?', $id)
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

        //总会管理员可查询
        if ($webmanager) {
            //已与档案挂钩
            if ($view['user']['alumni_id'] > 0 AND $view['user']['file_no'] > 0) {
                $view['alumni'] = Doctrine_Query::create()
                        ->select('id,name,file_no,student_no,school,institute,speciality,education,begin_year,graduation_year,birthday,native_place')
                        ->from('Alumni')
                        ->where('id =?', $view['user']['alumni_id'])
                        ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
            }


            //查找与姓名相同的档案信息
            else {
                $view['like_alumni'] = Doctrine_Query::create()
                        ->select('id,name,file_no,student_no,school,institute,speciality,education,begin_year,graduation_year,birthday,native_place')
                        ->from('Alumni')
                        ->where('name =?', $view['user']['realname'])
                        ->orderBy('begin_year,graduation_year')
                        ->fetchArray();
            }
        }

        //针对该校友的管理日志
        $view['admin_logs'] = Doctrine_Query::create()
                ->from('AdminLog log')
                ->select('log.*,u.realname AS realname')
                ->leftJoin('log.User u')
                ->where('user_id=?', $id)
                ->orderBy('log.id DESC')
                ->limit(5)
                ->fetchArray();

        echo View::factory($template, $view);
    }

    //原用户登录说明
    function action_loginMsg() {
        echo View::factory('user/loginMsg');
    }

    //关注或取消关注
    function action_markUser() {
        $this->auto_render = False;
        $user_id = $this->_sess->get('id');
        $mark_id = Arr::get($_GET, 'mark_id');
        //是否被关注
        $is_markMe = Doctrine_Query::create()
                ->from('UserMark')
                ->where('user_id = ?', $mark_id)
                ->andWhere('user = ?', $user_id)
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

        //是否已关注
        $is_marked = Doctrine_Query::create()
                ->from('UserMark')
                ->where('user_id = ?', $user_id)
                ->andWhere('user = ?', $mark_id)
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

        //已关注再次关注为取消关注
        if ($is_marked) {
            Doctrine_Query::create()
                    ->delete('UserMark')
                    ->where('user_id=?', $user_id)
                    ->addWhere('user=?', $mark_id)
                    ->execute();
            $actionName = '取消关注';
        }
        //增加关注
        else {
            $mark = new UserMark();
            $mark->user_id = $user_id;
            $mark->user = $mark_id;
            $mark->mark_at = date('Y-m-d H:i:s');
            $mark->save();
            $actionName = '已关注';
        }

        //返回
        if ($is_markMe AND $actionName == '已关注') {
            echo '互关注';
        } elseif ($is_markMe AND $actionName == '取消关注') {
            echo '关注了我';
        } elseif ($actionName == '已关注') {
            echo '已关注';
        } elseif ($actionName == '取消关注') {
            echo '互不关注';
        } else {
            echo '未知';
        }
    }

    //绑定微博
    function action_binding() {
        $view = null;
        $user_id = $this->_sess->get('reg_user_id', 0);
        // 根据地点推荐加入校友会
        $view['user'] = Doctrine_Query::create()
                ->select('u.city, u.alumni_id,u.realname,u.sex')
                ->from('User u')
                ->where('u.id = ?', $user_id)
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

        $appconfig = Kohana::config('app');
        Candy::import('sinaWeiboApi');
        $oauth = new SaeTOAuthV2($appconfig->sina['WB_AKEY'], $appconfig->sina['WB_SKEY']);
        $view['bindingSinaUrl'] = $oauth->getAuthorizeURL($appconfig->sina['WB_CALLBACK_URL']);
        $this->_render('_body', $view);
    }

}