<?php

class Layout_Test extends Candy_Controller {

    protected $_acl;
    protected $_role;
    public $template = 'layout/main';
    public $_uid;

    function before() {
        parent::before();

        // acl
        $this->_acl = new Acl();
        foreach ($this->_conf('acl.Roles') as $r) {
            $this->_acl->add_role($r);
        }

        $this->_uid = $this->_sess->get('id');

        //session失效自动填充并验证
        if (empty($this->_uid) AND isset($_COOKIE['zuaa_uid']) AND isset($_COOKIE['zuaa_token'])) {

            //获取相应的用户最新信息
            $user = Doctrine_Query::create()
                    ->select('id,realname,role,password,account,sex,city,actived,login_num,login_time')
                    ->from('User')
                    ->where('id = ?', $_COOKIE['zuaa_uid'])
                    ->fetchOne();

            //真实令牌
            $new_token = Model_User::createToken($user['id'], $user['password']);

            //与客户端保存的令牌做对比
            if ($_COOKIE['zuaa_token'] != $new_token) {
                Model_User::logout();
                $this->_redirect('user/login');
                exit;
            } else {
                //为本地cookie续期
                $keeptime = time() + Date::MONTH;
                $this->_sess->set('id', $user['id']);
                $this->_sess->set('sex', $user['sex']);
                $this->_sess->set('account', $user['account']);
                $this->_sess->set('token', $new_token);
                $this->_sess->set('realname', $user['realname']);
                $this->_sess->set('role', $user['role']);
                $this->_sess->set('city', $user['city']);
                $this->_sess->set('actived', $user['actived']);
                setcookie('zuaa_uid', $user['id'], $keeptime, '/');
                setcookie('zuaa_token', $new_token, $keeptime, '/');

                //判断每日登录增加积分
                if (date('Y-m-d') != date('Y-m-d', strtotime($user['login_time']))) {
                    Db_User::updatePoint('login');
                }
                //更新最后登录时间和次数
                $user['login_time'] = date('Y-m-d H:i:s');
                $user['login_num'] = $user['login_num'] + 1;
                $user->save();
                //检查可以发活动体会的活动
                $this->_sess->set('checkJoinEvent', 'yes');
            }
        }

        //实时更新在线状态(每隔10秒)
        if ($this->_uid) {
            $last_online_time = $this->_sess->get('last_online_time');
            if (strtotime('now') - strtotime($last_online_time) >= 10) {
                Model_User::stillAlive($this->_uid);
            }
        }

        // 获取最终身份
        $role = $this->_sess->get('role', '游客');
        $this->_role = $role;

        //模板全局变量
        View::set_global('_ROLE', $role);
        View::set_global('_UID', $this->_uid);

        //$this->_sess->set('checkJoinEvent','yes');
        // 设置全局公共视图
        $this->_render('_header_top', null, 'global/header_top');
        //全局底部试图
        $this->_render('_footer_bottom', null, 'global/footer_bottom');
        }

        //操作用户积分(统计或奖励的内容,是否奖励)
        function Db_User::updatePoint($info = 'all', $rewards = False, $user_id = null) {

        $user_id = empty($user_id) ? $this->_uid : $user_id;

        if ((!$user_id) OR ($rewards AND !$user_id)) {
            return false;
        }

        $point = Kohana::config('point')->add;
        $msg = Kohana::config('point')->msg;

        //查找当前用户积分
        $user_point = Doctrine_Query::create()
                ->from('UserPoint')
                ->where('user_id = ?', $user_id)
                ->fetchOne();

        //如果是上次统计超过3天,重新统计一次积分(可能管理员从后台删除帖子等)
        if ($user_point) {
            if (strtotime('now') - strtotime($user_point['update_at']) > 3600 * 24 * 3) {
                $info = 'all';
            }
        }

        //所有内容积分统计
        $count_point = 0;

        //新建用户统计数据并统计所有
        if (!$user_point OR $info == 'all') {

            //公共话题
            $bbs_unit = Doctrine_Query::create()
                    ->select('id')
                    ->from('BbsUnit')
                    ->where('user_id = ?', $user_id)
                    ->addWhere('is_closed=?', False)
                    ->count();
            $count_point+=$bbs_unit * $point['bbsunit'];

            //班级话题
            $class_unit = Doctrine_Query::create()
                    ->select('id')
                    ->from('ClassBbsUnit')
                    ->where('user_id = ?', $user_id)
                    ->addWhere('is_closed=?', False)
                    ->count();
            $count_point+=$class_unit * $point['classunit'];

            //评论总数
            $comment = Doctrine_Query::create()
                    ->select('id')
                    ->from('Comment')
                    ->where('user_id = ?', $user_id)
                    ->addWhere('is_closed=?', False)
                    ->count();
            $count_point+=$comment * $point['comment'];

            //照片总数
            $photo = Doctrine_Query::create()
                    ->select('id')
                    ->from('Pic')
                    ->where('user_id = ?', $user_id)
                    ->count();
            $count_point+=$photo * $point['photo'];


            //发起的活动
            $event = Doctrine_Query::create()
                    ->select('id')
                    ->from('Event')
                    ->where('user_id = ?', $user_id)
                    ->count();
            $count_point+=$event * $point['event'];

            //参加的活动
            $event_signed_ids = Model_Event::joinIDs($user_id);
            $eventsign = Doctrine_Query::create()
                    ->select('e.id')
                    ->from('Event e')
                    ->whereIn('e.id', $event_signed_ids)
                    ->addWhere('e.start < now()')
                    ->andWhere('e.is_closed = ? OR e.is_closed IS NULL', FALSE)
                    ->count();
            $count_point+=$eventsign * $point['eventsign'];

            //邀请他人注册
            $reg_invites = Doctrine_Query::create()
                    ->select('i.id,u.id AS uid')
                    ->from('UserInvite i')
                    ->leftJoin('i.RUser u')
                    ->where('i.user_id = ?', $user_id)
                    ->addWhere('i.receiver_user_id is not null')
                    ->addWhere('(i.type="regLink" OR type="regMail")')
                    ->addWhere('i.is_accept=?', TRUE)
                    ->addWhere('u.role=?', '校友(已认证)')
                    ->count();
            $count_point+=$reg_invites * $point['invite_register'];

            //邀请他人参加活动
            $eventsign_invites = Doctrine_Query::create()
                    ->select('i.id')
                    ->from('UserInvite i')
                    ->where('i.user_id = ?', $user_id)
                    ->addWhere('i.receiver_user_id is not null')
                    ->addWhere('i.type="inviteSignEvent"')
                    ->addWhere('i.is_accept=?', TRUE)
                    ->count();
            $count_point+=$eventsign_invites * $point['inviteSignEvent'];

            //原创新鲜事
            $original_weibos = Doctrine_Query::create()
                    ->select('id')
                    ->from('WeiboContent')
                    ->where('user_id = ?', $user_id)
                    ->addWhere('is_original=?', true)
                    ->count();
            $count_point+=$original_weibos * $point['weibo'];

            //绑定微博
            $bindings = Doctrine_Query::create()
                    ->select('id')
                    ->from('WeiboBinding')
                    ->where('user_id = ?', $user_id)
                    ->count();
            $count_point+=$bindings * $point['weibo_binding'];
        }

        $post['update_at'] = date('Y-m-d H:i:s');

        //不是新注册但之前没有统计过的，首次统计给予10点积分
        if (!$user_point) {
            $post['count_point'] = $count_point;
            $post['rewards_point'] = $rewards ? $rewards : 10;
            $post['point'] = $rewards ? $rewards + $count_point : $count_point + 10;
        }

        //存在用户，增加登录奖励
        elseif ($info == 'login') {
            $post['rewards_point'] = $user_point['rewards_point'] + $point['login'];
            $post['point'] = $user_point['point'] + $point['login'];
        }

        //存在用户，重新统计所有
        elseif ($info == 'all') {
            $post['count_point'] = $count_point;
            $post['point'] = $user_point['rewards_point'] + $count_point;
        }

        //上传头像，给予固定奖励
        elseif ($info == 'upload_profile') {
            $post['rewards_point'] = $user_point['rewards_point'] + $point[$info];
            $post['point'] = $user_point['point'] + $point[$info];
        }

        //存在用户，额外给予积分奖励
        elseif ($rewards AND isset($point[$info])) {
            $post['rewards_point'] = $user_point['rewards_point'] + $point[$info];
            $post['point'] = $user_point['point'] + $point[$info];
        }

        //存在用户，只统计某一项
        elseif (isset($point[$info])) {
            $post['count_point'] = $user_point['count_point'] + $point[$info];
            $post['point'] = $user_point['point'] + $point[$info];
        } else {
            return false;
        }

        //额外积分奖励
        //保存入库
        if ($user_point) {
            $user_point->fromArray($post);
            $user_point->save();
        } else {
            $user_point = new UserPoint();
            $post['user_id'] = $user_id;
            $user_point->fromArray($post);
            $user_point->save();
        }

        Doctrine_Query::create()
                ->update('User')
                ->where('id= ?', $user_id)
                ->set('point', $post['point'])
                ->execute();

        //仅对自己的积分操作做前台提示
        if ($this->_uid == $user_id AND isset($msg[$info]) AND isset($point[$info])) {
            //这些内容在跳转后才显示出来，所以需要flash,其他在js中处理
            if ($info == 'login' OR $info == 'bbsunit' OR $info == 'event' OR $info == 'eventsign' OR $info == 'weibo_binding' OR $info == 'upload_profile' OR $info == 'add_works') {
                if ($point[$info] > 0) {
                    $this->setPrompt($msg[$info] . ' 积分+' . $point[$info]);
                }
            }
        }
    }

    //生成和删除需更新session的用户文件
    function updateSession($uid, $action = null) {
        
    }

    //发送到新浪微博
    function sinaWeiboUpdate($data) {
        $user_id = $this->_uid;
        $appconfig = Kohana::config('app');
        $text = isset($data['text']) ? $data['text'] : null;
        $pic_url = isset($data['pic_url']) ? $data['pic_url'] : null;

        //帐号绑定信息
        //校友会的
        if (isset($data['aa_id']) AND $data['aa_id'] >= 0) {
            $binding = Model_Binding::getBinding(array(
                        'aa_id' => $data['aa_id'],
                        'service' => 'sina',
            ));
        }
        //个人的
        else {
            $binding = Model_Binding::getBinding(array(
                        'user_id' => $this->_uid,
                        'service' => 'sina',
            ));
        }

        if (!$binding) {
            return $back['error'] = '没有绑定帐号';
        }

        //导入新浪微博接口
        Candy::import('sinaWeiboApi');
        $c = new SaeTClientV2($appconfig->sina['WB_AKEY'], $appconfig->sina['WB_SKEY'], $binding['access_token']);

        //内容转换
        $text = $text ? Common_Global::sinatext($text) : null;

        //内容链接
        $text = isset($data['link']) ? $text . ' ' . $data['link'] : $text;

        //发布微博内容
        if ($text AND $pic_url) {
            $ret = $c->upload($text, $pic_url);
        } else {
            $ret = $ret = $c->update($text);
        }

        //返回发送结果
        if (isset($ret['error_code']) && $ret['error_code'] > 0) {
            return $back['error'] = "发送失败，错误：{$ret['error_code']}:{$ret['error']}";
        } else {
            return $ret;
        }
    }

}
