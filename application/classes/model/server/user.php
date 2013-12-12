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
            // self::onLoginSuccessed($user);
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
            if(isset($data['weixin_openid']) AND trim($data['weixin_openid'])){
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
        Request::instance()->redirect('index/deny?reason=' . urlencode($reason));
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
            $mailer = new Model_Mailer('first');
            $mailer->userActive($data['realname'], $data['account']);
        }
    }

    //创建用户帐号
    public static function create($data) {
        $data['password'] = md5($data['password']);
        $data['Contact']['mobile'] = $data['mobile'];
        $user = new User();
        if ($data['school']) {
            $data['Edus'][0]['school'] = $data['school'];
            $data['Edus'][0]['speciality'] = $data['speciality'];
            $data['Edus'][0]['grade'] = $data['grade'];
            $data['Edus'][0]['start_at'] = $data['start_at'];
            $data['Edus'][0]['finish_at'] = $data['finish_at'];
        }
        $user->fromArray($data);
        $user->save();
        return $user->id;
    }

    //创建联系方式
    public static function createContact($data) {
        if (isset($data['user_id'])) {
            $post['user_id'] = $data['user_id'];
            $post['mobile'] = $data['mobile'];
            $user_contact = new UserContact();
            $user_contact->fromArray($post);
            $user_contact->save();
            return $user_contact->id;
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