<?php

//用户接口
class Controller_Api_User extends Layout_Mobile {

    function before() {
        parent::before();
        $this->auto_render = FALSE;
    }

    //
    function action_index() {
        $this->error('Hi~what are you doing?');
    }

    //
    function action_usertoken() {
        $this->error('Hi~what are you doing?');
    }

    //用户登录
    function action_login() {
        //基本登录信息
        $post['account'] = $this->getParameter('account');
        $post['password'] = $this->getParameter('password');
        $post['login_clients'] = $this->_clients;
        $post['rememberme'] = $this->getParameter('rememberme');
        //额外可选绑定帐号
        $post['device_token'] = $this->getParameter('device_token');
        $post['weixin_openid'] = $this->getParameter('weixin_openid');

        $login_result = Model_User::login($post);

        //返回有错误提示错误
        if (isset($login_result['error'])) {
            $this->error($login_result['error']);
        }
        //登录成功
        elseif (isset($login_result['id'])) {
            $login_result['uid'] = $login_result['id'];
            unset($login_result['id']);
            unset($login_result['password']);
            unset($login_result['actived']);
            unset($login_result['login_method']);
            $this->response($login_result);
        }
        //未知错误
        else {
            $this->error('数据返回错误');
        }
    }

    //登出
    function action_logout() {
        Model_User::logout();
        $this->response(array('uid' => null), 'success', 'success');
    }

    //浏览用户详情
    function action_view() {
        $user_id = Arr::get($_GET, 'user_id');
        $user_id = $user_id ? $user_id : Arr::get($_POST, 'user_id');

        if ($this->_user) {
            $search_id = $user_id ? $user_id : $this->_uid;
            $record = Doctrine_Query::create()
                    ->select('id AS uid,account,realname,sex,role,point,speciality,start_year,birthday,city,intro,login_time,reg_at')
                    ->from('User')
                    ->where('id=?', $search_id)
                    ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
            if ($record) {
                $div_point = Common_Point::divPoint();
                $user_temp = Common_Point::getTemp($div_point, $record['point']);
                $user = array();
                $user['uid'] = $record['uid'];
                foreach ($record AS $key => $value) {
                    $user[$key] = $value;
                }
                $user['speciality'] = $record['start_year'] && $record['speciality'] ? $record['start_year'] . '级' . $record['speciality'] : $record['speciality'];
                $user['profile_image_url'] = $this->_siteurl . Model_User::avatar($search_id, 48, $record['sex']);
                $user['avatar_large'] = $this->_siteurl . Model_User::avatar($search_id, 128, $record['sex']);
                $user['description'] = '这家伙真懒，什么都没写!';
                $user['enthusiastic_temperature'] = $user_temp;
                $user['events_count'] = Doctrine_Query::create()->select('id')->from('Event')->where('user_id=?', $search_id)->count();
                $user['sign_event_count'] = Doctrine_Query::create()->select('id')->from('EventSign')->where('user_id=?', $search_id)->count();
                $user['bbsunit_count'] = Doctrine_Query::create()->select('id')->from('BbsUnit')->where('user_id=?', $search_id)->count();
                $user['comment_count'] = Doctrine_Query::create()->select('id')->from('Comment')->where('user_id=?', $search_id)->count();
                $user['contacts'] = array();
                $user['sina_weibo'] = array();
                unset($user['id']);
                unset($user['start_year']);
                //工作信息
                $work = Doctrine_Query::create()
                        ->select('industry,company,job')
                        ->from('UserWork')
                        ->where('user_id=?', $search_id)
                        ->orderBy('id DESC')
                        ->limit(1)
                        ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
                if ($work) {
                    unset($work['id']);
                    $work['company'] = str_replace('股份', '', $work['company']);
                    $work['company'] = str_replace('有限', '', $work['company']);
                    $work['company'] = str_replace('公司', '', $work['company']);
                    $work['job'] = $work['company'] . $work['job'];
                    $user['work'] = $work;
                }

                //联系方式
                if ($this->_uid == $search_id) {
                    $contacts = Doctrine_Query::create()
                            ->select('tel,mobile,address,qq')
                            ->from('UserContact')
                            ->where('user_id=?', $search_id)
                            ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
                    if ($contacts) {
                        unset($contacts['id']);
                        $user['contacts'] = $contacts;
                    }
                    $sinaweibo = Doctrine_Query::create()
                            ->select('uid,screen_name,description')
                            ->from('WeiboBinding')
                            ->where('user_id=?', $search_id)
                            ->addWhere('service="sina"')
                            ->orderBy('id DESC')
                            ->limit(1)
                            ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
                    if ($sinaweibo) {
                        unset($sinaweibo['id']);
                        $user['sina_weibo'] = $sinaweibo;
                    }
                }
                $this->response($user, 'user', 'user');
            } else {
                $this->error('很抱歉，没有找到用户');
            }
        } else {
            $this->error('您尚未登录，暂时不能浏览用户信息');
        }
    }

    function action_userdetail() {
        $uid = Arr::get($_GET, 'uid');
        $user = Doctrine_Query::create()
                ->select('u.id,u.realname,u.account,u.start_year,u.finish_year,u.sex,u.reg_at,u.role,u.education,')
                ->from('User u')
                ->leftJoin('u.Contact')
                ->where('u.id = ?', $uid)
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

        if ($user) {
            echo json_encode($user);
        }
    }

    //注册帐号(自动分析接收的表单数据)
    function action_register() {

        $register = Model_User::register($_POST);
        if (isset($register['error'])) {
            $this->error($register['error']);
        } else {
            $this->response($register, 'success', 'success');
        }
    }

    //修改头像
    function action_avatar() {
        $this->checkLogin();
    }

    //修改个人信息
    function action_update() {
        $this->checkLogin();
        $start_year = $this->getParameter('start_year');
        $speciality = $this->getParameter('speciality');
        $company = $this->getParameter('company');
        $mobile = $this->getParameter('mobile');
        $education = $this->getParameter('education');
        $job = $this->getParameter('job');
        $city = $this->getParameter('city');
        $device_token = $this->getParameter('device_token');
        $user = Doctrine_Query::create()
                ->from('User')
                ->where('id=?', $this->_uid)
                ->fetchOne();
        if ($user) {
            if ($device_token) {
                $user['device_token'] = str_replace(' ', '', trim($device_token));
            }
            if ($speciality) {
                $user['speciality'] = trim($speciality);
            }
            if ($city) {
                $user['city'] = trim($city);
            }
            $user->login_time = date('Y-m-d H:i:s');
            $user->login_num = $user['login_num'] + 1;
            $user->login_clients = $this->_clients;
            $user->save();
            $back = array();
            $back['uid'] = $this->_uid;
            $this->response($back, 'success', 'success');
        } else {
            $this->error('用户帐号不存在或已被删除');
        }
    }

}

?>
