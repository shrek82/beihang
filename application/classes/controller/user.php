<?php

class Controller_User extends Layout_Main {

    //校友注册
    function action_register($step = 1) {
        switch ($step) {
            case 1: // 身份验证
                $this->_redirect('/user/auth');
                break;
            case 2: // 帐号信息,生成用户
                $this->_redirect('/user/account');
                break;
            case 3: // 填写工作,推荐加入校友会
                $this->_redirect('/user/job');
                break;
            case 4: // 推荐可关注的校友以及班级录
                $this->_redirect('/user/recommend');
                break;
        }
    }

    //注册第一步：填写入学年份、专业等信息
    function action_auth() {
        header("Content-Type:text/html; charset=utf-8");

        if ($_POST) {

            $v = Validate::setRules($_POST, 'auth');
            $post = $v->getData();
            $post = Common_Safe::filterData($post);

            if (!$v->check()) {
                echo Candy::MARK_ERR . $v->outputMsg($v->errors('validate'));
                exit;
            } else {

                if ($post['start_year'] == 0 && $post['graduation_year'] == 0) {
                    echo Candy::MARK_ERR . '入学年份跟毕业年份必须填写其中一项';
                    exit;
                }

                $alumni = new Model_Alumni();
                $alumni->conn();
                $data_array = $alumni->matchAlumni($post);

                if (count($data_array['resp']) > 0) {
                    // 符合多条则专业关键字过滤
                    // 专业关键字逐字拆分
                    $keywords = UTF8::str_split($post['speciality']);
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
                $this->_sess->set('temp_postdate', $post);

                //返回字符
                echo View::factory('inc/register/auth_result', array('resp' => $data_array['resp']));
            }
            exit;
        }

        $this->_title('加入校友会');
        $this->_render('_body');
    }

    //选择档案
    function action_doauth() {
        $archive_id = Arr::get($_POST, 'archive', 0);
        //选择匹配的档案编号
        $this->_sess->set('reg_archive_id', $archive_id);
        //进行过身份验证标记
        $this->_sess->set(Model_User::AUTHED, TRUE);
        $this->_redirect('user/register/2');
    }

    //注册第3步骤：填写帐号
    function action_account() {

        if ($this->_uid) {
            $this->_redirect('/user_home');
        }

        // 没有进行过身份验证，先进行验证
        if (!$this->_sess->get(Model_User::AUTHED)) {
            $this->_redirect('/user/auth');
        }

        //第一步提交的姓名 专业 入学年份资料
        $base_post = $this->_sess->get('temp_postdate');

        //选择的档案编号
        $post['alumni_id'] = $this->_sess->get('reg_archive_id');
        $base_post['alumni_id'] = $this->_sess->get('reg_archive_id');


        if ($_POST) {

            //拼接提交的内容
            $post = array_merge($base_post, $_POST);

            //调用注册功能
            $register = Model_User::register($post);

            //注册返回错误信息
            if (isset($register['error'])) {
                echo Candy::MARK_ERR . $register['error'];
                exit;
            }
            //注册成功，跳往填写工作信息
            else {
                echo $register['uid'];
                exit;
            }
        }

        $view['alumni'] = array();
        if ($post['alumni_id']) {
            $alumni = new Model_Alumni();
            $alumni->conn();
            $view['alumni'] = $alumni->getOne(array('alumni_id' => $post['alumni_id']));
        }

        $view['basedata'] = $base_post;
        $this->_render('_body', $view);
    }

    //注册第3步：填写工作 所在地等
    function action_job() {

        $user_id = $this->_sess->get('id');

        if (!$user_id || !$this->_sess->get(Model_User::AUTHED)) {
            $this->_redirect('/user/auth');
        }

        if ($_POST) {
            $v = Validate::setRules($_POST, 'reg_work');
            $post = $v->getData();

            if (!$v->check()) {
                echo Candy::MARK_ERR . $v->outputMsg($v->errors('validate'));
                exit;
            } else {
                // 更新用户的city
                Db_User::updateField($user_id, array('city' => $post['city']));
                // 加入最新的工作信息
                $post['user_id'] = $user_id;
                Model_User::createWork($post);
                echo URL::site('user/register/4');
            }
            exit;
        }

        // 行业分类数据
        $view['industry'] = $this->_conf('industry');
        $this->_render('_body', $view);
    }

    /**
     * 注册第4步：推荐加入组织
     */
    function action_recommend() {
        $user_id = $this->_sess->get('id', 0);
        $post_data = $this->_sess->get('temp_postdate');
        $post_speciality_key = $post_data['speciality'];
        $albmni_institute = null;

        if (!$user_id || !$this->_sess->get(Model_User::AUTHED)) {
            $this->_redirect('/user/auth');
        }

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
            $this->_sess->delete('temp_postdate');
            $this->_sess->delete('reg_archive_id');
            exit;
        }

        // 如果有对应的档案则推荐加入班级
        if ($row['alumni_id']) {
            $alumni = new Model_Alumni();
            $alumni->conn();
            $alumni = $alumni->getOne(array('alumni_id' => $row['alumni_id']));
            $albmni_institute = $alumni['institute'];

            $classroom = Doctrine_Query::create()
                    ->from('ClassRoom')
                    ->where('name =?  OR speciality=?', array($alumni['speciality'], $alumni['speciality']));

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
                $view['aa']->where('name LIKE ? OR range LIKE ? OR institute_speciality_key LIKE ? OR name=? OR name=?', array('%' . $row['city'] . '%', '%' . $row['city'] . '%', '%' . $post_speciality_key . '%', $albmni_institute, $albmni_institute . '(筹)'));
            }
            //根据城市或专业名称
            elseif ($post_speciality_key) {
                $view['aa']->where('name LIKE ? OR range LIKE ? OR (institute_speciality_key LIKE ? AND institute_speciality_key is not null)', array('%' . $row['city'] . '%', '%' . $row['city'] . '%', '%' . $post_speciality_key . '%'));
            }
            //根据城市
            else {
                $view['aa']->where('name LIKE ? OR range LIKE ? ', array('%' . $row['city'] . '%', '%' . $row['city'] . '%'));
            }
            $view['aa'] = $view['aa']->fetchArray();
            $view['row'] = $row;

            $this->_render('_body', $view);
        }
    }

    //用户详细信息（供公管理员、校友会管理员、俱乐部、班级管理员等查询）
    function action_userDetail() {

        $alumni = new Model_Alumni();
        $alumni->conn();

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

        $view['work'] = Doctrine_Query::create()
                ->select('w.*')
                ->from('UserWork w')
                ->where('w.user_id =?', $id)
                ->orderBy('w.id DESC')
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

        //工作信息
        //总会管理员可查询
        if ($webmanager) {
            //已与档案挂钩
            if ($view['user']['alumni_id'] AND $view['user']['file_no']) {
                $view['alumni'] = $alumni->getOne(array('id' => $view['user']['alumni_id']));
            }
            //查找与姓名相同的档案信息
            else {
                $like_alumni = $alumni->matchAlumni(array('name' => $view['user']['realname'], 'speciality' => $view['user']['speciality'], 'start_year' => $view['user']['start_year'], 'finish_year' => $view['user']['finish_year']));
                $view['like_alumni'] = isset($like_alumni['resp']) ? $like_alumni['resp'] : array();
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
        }
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

    //我加入的俱乐部select菜单
    function action_getClubSelect() {
        $aa_id = Arr::get($_GET, 'aa_id');
        $selected_id = Arr::get($_GET, 'selected_id');
        $aa_manager = DB_Aa::isManager($aa_id, $this->_uid);

        $clubs = Doctrine_Query::create()
                ->select('id,name')
                ->from('Club');

        if ($aa_manager) {
            $clubs = $clubs->where('id>0');
        } else {
            $clubs = $clubs->whereIn('id', Model_User::clubIds($this->_uid));
        }

        if ($aa_id >= 0) {
            $clubs = $clubs->addWhere('aa_id=?', $aa_id);
        }

        $clubs = $clubs->fetchArray();

        $html = '<select id="club_id" name="club_id">';
        $html.='<option value="0">选择俱乐部...</option>';
        foreach ($clubs AS $c) {
            $selected = $selected_id == $c['id'] ? 'selected="selected"' : '';
            $html.='<option value="' . $c['id'] . '"  ' . $selected . '>' . $c['name'] . '</option>';
        }
        $html.='</select>';
        echo $html;
    }

    //发送站内信
    function action_sendmsg() {
        $this->auto_render = False;
        $uid = Arr::get($_GET, 'uid');
        $reply_id = Arr::get($_GET, 'reply_id', 0);

        if (!$this->_uid) {
            echo '请登录后再发送消息!';
            exit;
        }
        $msg = array();
        $user = array();
        if ((int) $reply_id > 0) {
            $msg = DB::select(DB::expr('m.*,u.realname AS sender_name'))
                    ->from(array('user_msg', 'm'))
                    ->join(array('user', 'u'))
                    ->on('m.user_id', '=', 'u.id')
                    ->where('m.send_to', '=', $this->_uid)
                    ->where('m.id', '=', $reply_id)
                    ->limit(1)
                    ->order_by('m.id', 'ASC')
                    ->execute()
                    ->as_array();
            if (!$msg) {
                echo '短消息不存在或已被删除!';
                exit;
            } else {
                DB::update('user_msg')
                        ->set(array('read_at' => date('Y-m-d H:i:s')))
                        ->where('id', '=', $reply_id)
                        ->execute();
            }
            $msg = $msg[0];
            $user['id'] = $msg['user_id'];
            $user['realname'] = $msg['sender_name'];
            $msg['realname'] = $msg['sender_name'];
        } else {
            $user = Db_User::getInfoById($uid);
        }
        echo View::factory('user/msgform', compact('user', 'msg'));
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

    //退出登录
    function action_logout() {
        $redirect = Arr::get($_GET, 'redirect', '/');
        Model_User::logout();
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

    //普通方式登陆加跳转
    function action_sslogin() {
        $view['next'] = Arr::get($_POST, 'next') ? Arr::get($_POST, 'next') : Arr::get($_GET, 'next');
        $this->_title('登录');
        $this->_render('_body', $view, 'user/login');
    }

    #ajax登陆

    function action_login() {

        if ($_POST) {
            $this->auto_render = False;
            $v = Validate::setRules($_POST, 'login');
            $post = $v->getData();
            if (!$v->check()) {
                echo Candy::MARK_ERR . $v->outputMsg($v->errors('validate'));
                exit;
            } else {
                $login_result = Model_User::login($post);
                //返回有错误提示错误
                if (isset($login_result['error'])) {
                    echo Candy::MARK_ERR . $login_result['error'];
                    exit;
                }
                //登录成功
                elseif (isset($login_result['id'])) {

                    $this->_uid = $this->_sess->get('id');

                    #查找是否为某校友会管理员,是则保存校友会id
                    $chairman_aa = $this->action_chairman($this->_uid);
                    if ($chairman_aa) {
                        $this->_sess->set('chairman_aa', $chairman_aa);
                    }
                    //跳转到其他地方
                    if (isset($post['next']) AND $post['next']) {
                        $redirect = $this->create_token($post['next']);
                        echo $redirect;
                        exit;
                    }
                    //返回用户id
                    else {
                        echo $this->_uid;
                        exit;
                    }
                }
                //其他错误
                else {
                    echo Candy::MARK_ERR . '很抱歉，服务器错误，请重试或与管理员联系！';
                }
            }
        }

        //facebox登录
        if (Request::$is_ajax) {
            echo View::factory('user/facebox_login', array());
            exit;
        } else {
            //普通方式登录
            $this->_title('登录');
            $this->_render('_body', array(), 'user/login');
        }
    }

    //创建登录令牌
    function create_token($next) {
        if ($next) {
            $timestamp = time();
            $token = md5($this->_uid . $timestamp . Model_User::SECRET_KEY);
            $redirect = urldecode($next) . '?id=' . $this->_uid . '&timestamp=' . $timestamp . '&token=' . $token;
            return $redirect;
        }
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
        $this->auto_render = false;
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
                    ->set('is_sended_active', TRUE)
                    ->execute();

            $actived = TRUE;
            // 更换sess
            $this->_sess->set('actived', TRUE);
        }

        $this->_title('激活账号');
        $this->_render('_body', compact('account', 'actived'));
    }

}
