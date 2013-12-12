<?php

/**
  +-----------------------------------------------------------------
 * 名称：用户模型(静态常量不同，其他相同)
 * 版本：1.0
 * 版权：www.usho.cn
 * 作者：zhaojiangang
 * 最后更新日期：
  +-----------------------------------------------------------------
 */
class Model_User {

    const AUTHED = 'user_authed';
    const USEACTIVE = TRUE; # 使用邮箱激活
    const USER_AVATAR_DIR = 'static/upload/avatar/';
    const PEOPLE_PATH = 'static/upload/people/';
    const URLKEY = 'zuaa@2010'; //加密参数添加字符
    const TOKENKEY = 'zuaa_Api@zju.edu.cn';
    const SECRET_KEY = '*-!zgih4!=f3%)ppggnt$y5d7p2cf@eqj$jhwj-exnsmrj-1-p'; //浙大创业中国密钥

    static $up_rule = array(
        'Upload::valid' => array(),
        'Upload::not_empty' => array(),
        'Upload::type' => array('Upload::type' => array('gif', 'jpg', 'jpeg', 'png')),
        'Upload::size' => array('1M')
    );

    //在线事务处理
    public static function onLineWorks($user = array()) {

        if (!isset($user['id'])) {
            return false;
        }

        //刷新在线时间(每隔10秒)
        $last_online_time = (isset($user['last_online_time']) && $user['last_online_time']) ? strtotime($user['last_online_time']) : 0;
        if (strtotime('now') - $last_online_time >= 10) {
            self::stillAlive($user['id']);
        }

        //增加每日积分奖励
        $last_login_time = (isset($user['login_time']) && $user['login_time']) ? $user['login_time'] : date('Y-m-d');
        if (date('Y-m-d') != date('Y-m-d', strtotime($last_login_time))) {
            Db_User::updatePoint('login');
            Session::instance()->set('login_time', date('Y-m-d'));
        }
    }

    //每次登录成功后执行的事务
    public static function onLoginSuccessed($user = array()) {
        if (!isset($user['id'])) {
            return false;
        }
        $sess = Session::instance();
        //检查参加过的活动
        $sess->set('checkJoinEvent', 'yes');
    }

    //用户信息
    public static function userInfo() {
        $sess = Session::instance();
        $uid = $sess->get('id');
        $user = array();

        //1、没有登录或超时，并且存在Cookie用户id和token，自动从cookie登录
        if (!$uid) {
            if (isset($_COOKIE['zuaa_uid']) AND isset($_COOKIE['zuaa_token'])) {
                $user = self::login(array('uid' => $_COOKIE['zuaa_uid'], 'token' => $_COOKIE['zuaa_token']));
            }
        }
        //2、已经登录
        else {
            $user['id'] = $sess->get('id');
            $user['account'] = $sess->get('account');
            $user['token'] = $sess->get('token');
            $user['realname'] = $sess->get('realname');
            $user['city'] = $sess->get('city');
            $user['role'] = $sess->get('role');
            $user['actived'] = $sess->get('actived');
            $user['login_time'] = $sess->get('login_time');
            $user['last_online_time'] = $sess->get('last_online_time');
            $user['login_method'] = 'session';
        }

        //3、登录用户执行不同情景事务处理
        if (isset($user['id'])) {

            //实时更新在线状态
            self::onLineWorks($user);

            //仅在登录后执行
            self::onLoginSuccessed($user);
        }

        return $user;
    }

    //用户登录(可使用email+密码或uid+令牌登录)
    public static function login($data) {

        $sess = Session::instance();
        $u = false;
        $token = false;

        //登录查询信息
        $query = DB::select(DB::expr('id,realname,role,password,account,sex,city,actived,login_time,login_num,device_token,login_time,login_num,login_clients'))
                ->from('user');

        //使用email登录
        if (isset($data['account']) AND isset($data['password']) AND $data['account']) {
            $u = $query->where('account', '=', $data['account'])->limit(1)->execute()->as_array();
        }
        //使用令牌登录
        elseif (isset($data['uid']) AND isset($data['token']) AND $data['uid']) {
            $u = $query->where('id', '=', $data['uid'])->limit(1)->execute()->as_array();
        }
        //其他登录方式
        else {
            return array('error' => '登录帐号或令牌应该至少有一项！');
        }

        //数据库存在用户
        if ($u) {

            $user = $u[0];

            //生成用户令牌
            $token = self::createToken($user['id'], $user['password']);

            $user['token'] = $token;

            //对比令牌或密码
            //使用Email帐号登录
            //数据库有一些帐号是通过手机注册的，密码是经过2次加密的
            if (isset($data['account'])) {

                $login_success = False;

                //网站注册和通过网站登录
                if ($user['password'] == md5($data['password'])) {
                    $login_success = True;
                }
                //网站注册和通过app登录(手机发来的密码是经过md5(md5(input pass)+zuaa2012))，数据库已经加密过一次
                elseif (md5($user['password'] . 'zuaa2012') == $data['password']) {
                    $login_success = True;
                }
                //app注册通过app登录(发来的密码就已经是2次加密过的了)，注册时发来的就是经过2次加密的了
                elseif ($user['password'] == $data['password']) {
                    $login_success = True;
                }
                //app注册通过网站登录，要对用户的密码进行2次加密后对比
                elseif ($user['password'] == md5(md5($data['password']) . 'zuaa2012')) {
                    $login_success = True;
                } else {
                    return array('error' => '很抱歉，密码错误，请检查密码是否输入正确！');
                }
            }

            //使用令牌自动登录
            if (isset($data['token']) AND $token != $data['token']) {
                return array('error' => '很抱歉，自动登录失败，可能是密码已修改！');
            }

            //保存session和cookie
            $sess->set('id', $user['id']);
            $sess->set('sex', $user['sex']);
            $sess->set('account', $user['account']);
            $sess->set('token', $token);
            $sess->set('realname', $user['realname']);
            $sess->set('role', $user['role']);
            $sess->set('city', $user['city']);
            $sess->set('actived', $user['actived']);
            $sess->set('login_time', $user['login_time']);

            //记住我的状态
            if (isset($data['rememberme']) AND $data['rememberme']) {
                $keeptime = time() + Date::MONTH;
                setcookie('zuaa_uid', $user['id'], $keeptime, '/');
                setcookie('zuaa_token', $token, $keeptime, '/');
            }

            //返回用户数据
            unset($user['password']);

            //返回登录方式标记
            if (isset($data['account'])) {
                $user['login_method'] = 'account';
            } elseif (isset($data['token'])) {
                $user['login_method'] = 'token';
            } else {
                $user['login_method'] = 'other';
            }

            //更新最后登录时间
            $update_data = array();
            $update_data['login_time'] = date('Y-m-d H:i:s');
            $update_data['login_num'] = $user['login_num'] + 1;
            $update_data['login_clients'] = isset($data['login_clients']) ? $data['login_clients'] : null;

            //iphone 记住推送地址
            if (isset($data['device_token']) AND $data['device_token']) {
                $update_data['device_token'] = $data['device_token'];
            }

            //记住微信openid
            if (isset($data['weixin_openid']) AND trim($data['weixin_openid'])) {
                $update_data['weixin_openid'] = $data['weixin_openid'];
            }

            //更新数据库
            Db_User::updateField($user['id'], $update_data);

            //登录成功后执行的方法
            self::onLoginSuccessed($user);

            return $user;
        }
        //数据库不存在用户
        else {
            return array('error' => '很抱歉，用户不存在，请检查账户是否正确！');
        }
    }

    //退出登录
    public static function logout() {
        Session::instance()->destroy();
        @Cookie::delete('zuaa_uid');
        @Cookie::delete('zuaa_token');
    }

    # 申请,修改加入组织

    static function apply($data, $id = 0) {
        $apply = Doctrine_Query::create()
                ->from('JoinApply')
                ->where('id = ?', $id)
                ->fetchOne();

        if (!$apply) {
            $apply = new JoinApply();
            $apply->fromArray($data);
        } else {
            $apply->synchronizeWithArray($data);
        }
        $apply->save();
    }

    /**
     * 阻止用户的默认跳转操作
     * @param <type> $reason 被阻止的原因
     */
    static function deny($reason = null) {
        Request::instance()->redirect('main/deny?reason=' . urlencode($reason));
    }

    /**
     * 返回访问隐私控制内容结果
     * @param <type> $private 控制规则数组
     * @param <type> $field 访问的信息关键字
     * @param <type> $user_id 隐私控制者id
     * @return <boolean>
     */
    static function privateChecker($private, $field, $user_id) {
        // 访问者ID
        $accessor = Session::instance()->get('id', 0);

        if ($accessor == 0)
            return false;

        // 获取对应内容的控制值，默认为关注人可见
        $rule = isset($private[$field]) ? $private[$field] : '1';

        // 全可见
        if ($rule == '0') {
            return true;
        }
        // 关注的人才可见
        if ($rule == '1') {
            return (bool) Doctrine_Query::create()
                            ->from('UserMark')
                            ->where('user_id = ?', $user_id)
                            ->andWhere('user = ?', $accessor)
                            ->count();
        }
    }

    # 获取某用户的隐私控制arr

    static function privateRules($user_id) {
        $private = Doctrine_Query::create()
                ->select('rules')
                ->from('UserPrivate')
                ->where('user_id = ?', $user_id)
                ->execute(array(), Doctrine_Core::HYDRATE_SINGLE_SCALAR);

        return $private ? unserialize($private) : $private;
    }

# 隐私设置select助手

    static function privateSelector($privateArr, $name) {
        $rules = array(
            '1' => '仅我关注的人可见',
            '0' => '所有人可见',
        );

        echo '<select class="user_private" name="private[' . $name . ']" rel=' . $name . '>';
        foreach ($rules as $value => $rule) {
            $option = '<option value="' . $value . '" selected>' . $rule . '</option>';
            if (!isset($privateArr[$name]) || $privateArr[$name] != $value) {
                $option = str_replace('selected', '', $option);
            }
            echo $option;
        }
        echo '</select>';
    }

//加入的校友会数组
    static function joinedAas($user_id) {
        if (!$user_id) {
            return false;
        }
        $aas = Doctrine_Query::create()
                ->select('m.id,a.id AS aa_id,a.name AS aa_name, a.sname AS sname')
                ->from('AaMember m')
                ->leftJoin('m.Aa a')
                ->where('m.user_id = ?', $user_id)
                ->fetchArray();
        if ($aas) {
            return $aas;
        } else {
            return array();
        }
    }

    # 加入的校友会ID

    static function aaIds($user_id) {

        if (!$user_id) {
            return array();
        }

        $ids = Doctrine_Query::create()
                ->select('aa_id')
                ->from('AaMember m')
                ->where('m.user_id = ?', $user_id)
                ->addWhere('m.aa_id >0')
                ->execute(array(), Doctrine_Core::HYDRATE_SINGLE_SCALAR);
        if (!$ids) {
            return array(-1);
        } elseif (is_string($ids)) {
            return array($ids);
        }
        return $ids;
    }

    # 加入的俱乐部ID

    static function clubIds($user_id) {
        $ids = Doctrine_Query::create()
                ->select('club_id')
                ->from('ClubMember m')
                ->where('m.user_id = ?', $user_id)
                ->execute(array(), Doctrine_Core::HYDRATE_SINGLE_SCALAR);
        if (!$ids) {
            return array(0);
        } elseif (is_string($ids)) {
            return array($ids);
        }

        return $ids;
    }

    # 加入的班级ID

    static function classroomIds($user_id) {
        $ids = Doctrine_Query::create()
                ->select('class_room_id')
                ->from('ClassMember m')
                ->where('m.user_id = ?', $user_id)
                ->execute(array(), Doctrine_Core::HYDRATE_SINGLE_SCALAR);
        if (!$ids) {
            return array(0);
        } elseif (is_string($ids)) {
            return array($ids);
        }
        return $ids;
    }

    /**
      +-----------------------------------------------------------------
     * 更新在线时间
      +-----------------------------------------------------------------
     */
    static function stillAlive($user_id, $update_session = true) {

        $user_ol = DB::select()->from('ol')->where('uid', '=', $user_id)->execute()->as_array();

        if ($user_ol) {
            DB::update('ol')->set(array('time' => time()))->where('uid', '=', $user_id)->execute();
        } else {
            DB::insert('ol', array('uid', 'time'))->values(array($user_id, time()))->execute();
        }
        //更新session
        if ($update_session) {
            Session::instance()->set('last_online_time', date('Y-m-d H:i:s'));
        }
    }

    #返回在线人员

    static function online($user_id = null, $spantime = 900) {
        $alive = (time() - $spantime);
        $user = Doctrine_Query::create()
                ->from('Ol')
                ->where('time > ?', $alive);

        if ($user_id) {
            $result = (bool) $user->andWhere('uid = ?', $user_id)->count();
        } else {
            $arr = array();
            $result = $user->fetchArray();
            if (count($result) > 0) {
                foreach ($result as $r) {
                    $arr[$r['uid']] = $r['time'];
                }
            } else {
                $arr = array(0);
            }
            $result = $arr;
        }
        return $result;
    }

    //关注的校友ids
    public static function markArr($user_id) {
        $user = Doctrine_Query::create()
                ->select('user')
                ->from('UserMark')
                ->where('user_id = ?', $user_id)
                ->andWhere('user IS NOT NULL')
                ->orderBy('mark_at DESC')
                ->execute(array(), Doctrine_Core::HYDRATE_SINGLE_SCALAR);

        $users = array();
        if (count($user) == 0) {
            $users = array(0);
        } else {
            $users = $user;
        }

        return $users;
    }

    //关注状态
    public static function markStatus($obj, $val) {
        $user_id = Session::instance()->get('id');
        $mark = Doctrine_Query::create()
                ->from('UserMark')
                ->where($obj . ' = ?', $val)
                ->andWhere('user_id = ?', $user_id)
                ->fetchOne();

        if ($mark) {
            return '取消关注';
        } else {
            return '关注';
        }
    }

    //查询用户数量
    public static function totalNumber($filter = array()) {
        $user = Doctrine_Query::create()->from('User');

        if (count($filter) > 0) {
            foreach ($filter as $field => $value) {
                $user->where($field . ' = ?', $value);
            }
        }

        return $user->count();
    }

    //用户头像
    public static function avatar($user_id, $size = 48, $sex = 'none') {
        $file = $user_id . '.jpg';
        $pic_url = URL::base() . self::USER_AVATAR_DIR . $size . '/';
        $pic_path = DOCROOT . self::USER_AVATAR_DIR . $size . '/';

        if (!file_exists($pic_path . $file)) {
            if ($sex == '男') {
                return $pic_url . 'face_man.jpg';
            } elseif ($sex == '女') {
                return $pic_url . 'face_madam.jpg';
            } else {
                return $pic_url . 'default.jpg';
            }
        }
        return $pic_url . $file;
    }

    //发送邮件
    public static function sendActiveMail($data) {
        if (self::USEACTIVE) {
            $data['realname'] = isset($data['name']) && $data['name'] ? $data['name'] : $data['realname'];
            $data['account'] = isset($data['email']) && $data['email'] ? $data['email'] : $data['account'];
            if ($data['realname'] && $data['account']) {
                $mailer = new Model_Mailer('first');
                $mailer->userActive($data['realname'], $data['account']);
            }
        }
    }

    //注册
    public static function register($post) {
        $post['name'] = isset($post['name']) ? trim($post['name']) : null;
        $post['realname'] = isset($post['realname']) && $post['realname'] ? trim($post['realname']) : $post['name'];
        $post['name'] = $post['name'] ? $post['name'] : $post['realname'];
        $post['start_year'] = isset($post['start_year']) ? trim($post['start_year']) : null;
        $post['graduation_year'] = isset($post['graduation_year']) ? trim($post['graduation_year']) : null;
        $post['graduation_year'] = empty($post['graduation_year']) && $post['start_year'] ? $post['start_year'] + 4 : null;
        $post['speciality'] = isset($post['speciality']) ? trim($post['speciality']) : null;
        $post['password'] = isset($post['password']) ? trim($post['password']) : null;
        $post['mobile'] = isset($post['mobile']) ? trim($post['mobile']) : null;
        $post['account'] = isset($post['account']) && $post['account'] ? trim($post['account']) : null;
        $post['email'] = isset($post['email']) && $post['email'] ? trim($post['email']) : $post['account'];
        $post['account'] = $post['account'] ? $post['account'] : $post['email'];
        $post['mobile'] = isset($post['mobile']) ? trim($post['mobile']) : null;
        $post['city'] = isset($post['city']) ? trim($post['city']) : null;
        $post['sex'] = isset($post['sex']) ? trim($post['sex']) : null;
        $post['login_clients'] = isset($post['login_clients']) ? trim($post['login_clients']) : null;
        //根据城市自动发出加入申请(手机端需要，PC端会自助选择)
        $post['auto_apply_joinaa'] = isset($post['auto_apply_joinaa']) ? $post['auto_apply_joinaa'] : false;

        //提供了档案编号
        $post['alumni_id'] = isset($post['alumni_id']) ? trim($post['alumni_id']) : null;

        //检查注册必须项目
        if (!$post['realname']) {
            return array('error' => '姓名不能为空!');
        }

        if ($post['start_year'] AND !is_numeric($post['start_year'])) {
            return array('error' => '入学年份不是有效的数字!');
        }

        if ($post['graduation_year'] AND !is_numeric($post['graduation_year'])) {
            return array('error' => '毕业年份不是有效的数字!');
        }

        if (!$post['speciality']) {
            return array('error' => '毕业专业不能为空!');
        }

        if (!$post['email']) {
            return array('error' => '登录帐号(邮箱)不能为空!');
        } else {
            //email格式
            $valid_email = Common_Global::email($post['email']);
            if (!$valid_email) {
                return array('error' => 'Email地址输入错误!');
            }

            //帐号是否可用
            $valid_account = Model_User::validAccount($post['email']);
            if (!$valid_account) {
                return array('error' => '很抱歉，该Email已经注册过，请换用其他邮件地址');
            }
        }


        if (!$post['password']) {
            return array('error' => '密码不能为空!');
        }

        if (isset($post['password2']) AND $post['password'] != $post['password2']) {
            return array('error' => '两次密码输入不一致，请重新输入!');
        }

        $alumni = new Model_Alumni();
        $alumni->conn();
        $files = $alumni->getOne($post);
        
        //积分值
        $point = Kohana::config('point')->add;

        //存在档案
        if ($files) {
            //同一档案编号是否已经注册过
            $registered = Doctrine_Query::create()
                    ->from('User')
                    ->select('id,account,realname,start_year,reg_at')
                    ->where('file_no = ?', $files['file_no'])
                    ->addWhere('file_no IS NOT NULL')
                    ->limit(1)
                    ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
            if ($registered) {
                return array('error' => '很抱歉，您目前的档案信息已于' . $registered['reg_at'] . '使用' . $registered['account'] . '帐号注册，如忘记登录密码，请使用网站密码找回功能，如果其他问题，请直接与管理员联系，谢谢!');
            }
            //修正注册填写资料从档案
            $post['alumni_id'] = $files['id'];
            $post['file_no'] = $files['file_no'];
            $post['student_no'] = $files['student_no'];
            $post['start_year'] = $files['begin_year'] > 0 ? $files['begin_year'] : $post['start_year'];
            $post['finish_year'] = $files['graduation_year'] > 0 ? $files['graduation_year'] : $post['graduation_year'];
            $post['speciality'] = $files['speciality'] ? $files['speciality'] : $post['speciality'];
            $post['birthday'] = $files['birthday'];
            $post['education'] = $files['education'];
            $post['school'] = $files['school'];
            $post['institute'] = $files['institute'];
            $post['institute_no'] = $files['institute_no'];
            $post['sex'] = $files['sex'] ? $files['sex'] : $post['sex'];
            $post['authentic'] = 1;
        }
        $post['role'] = '校友(未审核)';
        $post['actived'] = false;
        $post['reg_at'] = date('Y-m-d H:i:s');
        $post['login_time'] = date('Y-m-d H:i:s');
        $post['point'] = $point['register'];

        //密码
        //iphone客户端密码注册有问题，发来的都是加密2次的，所以不再加密了
        if (UTF8::strlen($post['password']) < 30) {
            $post['password'] = md5($post['password']);
        }

        //创建用户
        $new_uid = self::create($post);

        //注册成功回客户端
        if ($new_uid) {

            //发出加入校友会申请
            if ($post['city'] AND $post['auto_apply_joinaa']) {
                Model_Aa::applyJoinAaForCity($post['city'], $new_uid);
            }

            //自动登录网站
            Model_User::login($post);

            //添加联系方式
            self::createContact(array('user_id' => $new_uid, 'mobile' => $post['mobile']));

            //初始化自己的积分，并奖励10分
            Db_User::createPoint(array('user_id' => $new_uid, 'point' => $point['register'], 'rewards_point' => $point['register'], 'update_at' => date('Y-m-d H:i:s')));

            //来自受邀注册
            $sess = Session::instance();
            //邀请标记
            $invite_id = $sess->get('reg_invite');
            if ($invite_id) {
                //给予自己受邀注册奖励
                Db_User::updatePoint('register_from_invite');
                //保存邀请日志
                Model_Invite::saveLog(array(
                    'invite_id' => $invite_id,
                    'receiver_user_id' => $new_uid
                ));
                //清空邀请session
                $this->_sess->set('reg_invite', null);
                $this->_sess->set('receiver_email', null);
            }

            //发送激活邮件
            @self::sendActiveMail($post);

            //返回注册信息
            $back['uid'] = $new_uid;
            $back['user_id'] = $new_uid;
            $back['realname'] = $post['realname'];
            $back['account'] = $post['account'];
            $back['email'] = $post['email'];
            $back['role'] = '校友(未审核)';
            $back['token'] = self::createToken($new_uid, $post['password']);
            $back['login_time'] = date('Y-m-d H:i:s');
            return $back;
        } else {
            return array('error' => '很抱歉，帐号注册失败，请重试或与管理员联系。');
        }
    }

    //创建用户帐号
    public static function create($data) {

        $user = new User();
        $user->fromArray($data);
        $user->save();
        return $user->id;
    }

    //创建联系方式
    public static function createContact($data) {
        if (isset($data['user_id'])) {
            $post['user_id'] = $data['user_id'];
            $post['mobile'] = Arr::get($data, 'mobile');
            $post['tel'] = Arr::get($data, 'tel');
            $post['address'] = Arr::get($data, 'address');
            $post['qq'] = Arr::get($data, 'qq');
            $post['memo'] = Arr::get($data, 'memo');
            $post['location_x'] = Arr::get($data, 'location_x');
            $post['location_y'] = Arr::get($data, 'location_y');
            $post['localhost_label'] = Arr::get($data, 'localhost_label');
            $user_contact = new UserContact();
            $user_contact->fromArray($post);
            $user_contact->save();
            return $user_contact->id;
        }
    }

    //创建工作信息
    public static function createWork($data) {
        if (isset($data['user_id'])) {
            $post['user_id'] = $data['user_id'];
            $post['industry'] = Arr::get($data, 'industry');
            $post['company'] = Arr::get($data, 'company');
            $post['job'] = Arr::get($data, 'job');
            $post['start_at'] = Arr::get($data, 'start_at');
            $post['leave_at'] = Arr::get($data, 'leave_at');
            $work = new UserWork();
            $work->fromArray($post);
            $work->save();
            return $work->id;
        }
    }

    //验证email帐号是否可用
    public static function validAccount($email) {
        $match = Doctrine_Query::create()
                ->from('User')
                ->where('account = ?', $email)
                ->count();
        if ($match == 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    //验证手机号码是否可用
    public static function validMobile($number) {
        $match = Doctrine_Query::create()
                ->from('UserContact')
                ->where('mobile = ?', $number)
                ->count();

        if ($match == 0)
            return TRUE;
        else
            return FALSE;
    }

    //替换email中间几位为星号(*)
    public static function safeEmail($email) {

        $emailName = substr($email, 0, strpos($email, '@'));
        $position = 'center';
        $maskPart = 30;
        $strlen = strlen($emailName);
        $maskNum = floor($strlen * $maskPart / 100);
        $maskName = '';

        if ($position == 'center') {
            $beginMask = floor(($strlen - $maskNum) / 2);
        } elseif ($position == 'left') {
            $beginMask = 0;
        } else {
            $beginMask = $strlen - $maskNum;
        }

        $count = 0;
        for ($i = 0; $i < $strlen; ++$i) {
            if ($i >= $beginMask && $count < $maskNum) {
                $maskName .= '*';
                ++$count;
            } else {
                $maskName .= $emailName{$i};
            }
        }

        return str_replace($emailName . '@', $maskName . '@', $email);
    }

    //获取某校友信息
    public static function getModel($id, $fields) {
        $user = Doctrine_Query::create()
                ->from('User');

        if ($fields) {
            $user = $user->select($fields);
        }
        return $user->where('id = ?', $id)
                        ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
    }

    //返回token
    public static function createToken($uid, $password) {
        $token = md5(self::TOKENKEY . $uid . Text::limit_chars($password, 6));
        return $token;
    }

    //根据用户id生成一个token
    public static function getToken($uid) {
        $user = Doctrine_Query::create()
                ->from('User')
                ->select('id,password')
                ->where('id=?', $uid)
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
        if ($user) {
            return md5(self::TOKENKEY . $uid . Text::limit_chars($user['password'], 6));
        } else {
            return false;
        }
    }

}
