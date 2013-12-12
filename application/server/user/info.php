<?php

class Controller_User_Info extends Layout_User {

    function before() {
        parent::before();

        $topbar_links = array(
            'user_info/base' => '基础信息',
            //'user_info/edu' => '教育信息',
            'user_info/work' => '工作信息',
            'user_info/account' => '账号设置',
            'user_info/binding' => '微博绑定',
        );
        
        View::set_global('topbar_links', $topbar_links);
    }

    //查找帐号个数
    function accountcount($account) {
        $count = Doctrine_Query::create()
                ->from('User')
                ->where('account = ?', $account)
                ->count();
        return $count;
    }

    //验证email格式
    function email($email) {
        $expression = '/^[-_a-z0-9\'+*$^&%=~!?{}]++(?:\.[-_a-z0-9\'+*$^&%=~!?{}]+)*+@(?:(?![-.])[-a-z0-9.]+(?<![-.])\.[a-z]{2,6}|\d{1,3}(?:\.\d{1,3}){3})(?::\d++)?$/iD';
        return (bool) preg_match($expression, (string) $email);
    }

    //修改帐号及密码
    function action_account() {
        $this->_title('账号设置');
        $user = Doctrine_Query::create()
                ->from('User u')
                ->where('u.id = ?', $this->_uid)
                ->fetchOne();
        if ($_POST) {
            $old_account = trim(Arr::get($_POST, 'old_account'));
            $account = trim(Arr::get($_POST, 'account'));
            $password = Arr::get($_POST, 'password');
            $password2 = Arr::get($_POST, 'password2');

            //修改登录帐号
            if ($account AND $account != $old_account) {
                if (!$this->email($account)) {
                    echo Candy::MARK_ERR . '很抱歉，E-mail格式不正确，请重新填写！';
                    exit;
                } else {
                    if ($this->accountcount($account) == 0) {
                        $user->account = $account;
                    } else {
                        echo Candy::MARK_ERR . '很抱歉，该帐号已经被使用，请换用其他E-mail地址！';
                        exit;
                    }
                }
            }

            //修改密码
            if ($password AND $password != $password2) {
                echo Candy::MARK_ERR . '很抱歉，两次密码输入不一致，请重新输入，谢谢！';
                exit;
            }

            if ($password AND $password == $password2) {
                $user->password = md5($password);
            }

            //保存修改
            if (($old_account != $account) OR ($password AND $password == $password2)) {
                $user->save();
            } else {
                echo Candy::MARK_ERR . '您没有做任何修改！';
                exit;
            }
        }
        $view['user'] = $user;
        $this->_render('_body', $view);
    }

    # 工作信息

    function action_work() {
        $wid = Arr::get($_GET, 'wid');
        $work = null;

        $works = Doctrine_Query::create()
                ->from('UserWork')
                ->where('user_id = ?', $this->_uid);
        $view['works'] = $works->orderBy('start_at ASC')
                ->fetchArray();


        if ((int) $wid > 0) {
            $work = Doctrine_Query::create()
                    ->from('UserWork')
                    ->where('user_id=?', $this->_uid)
                    ->addWhere('id=?', $wid)
                    ->fetchOne();
            $view['work'] = $work;
        }

        $view['industry'] = Kohana::config('industry');

        $view['private'] = Model_User::privateRules($this->_user_id);

        if ($_POST) {
            $v = Validate::setRules($_POST, 'user_work');
            $post = $v->getData();
            if (!$v->check()) {
                echo Candy::MARK_ERR . $v->outputMsg($v->errors('validate'));
            } else {
                //修改工作
                if ($work) {
                    $work->synchronizeWithArray($post);
                    $work->save();
                }
                //添加新工作
                else {
                    $post['user_id'] = $this->_user_id;
                    $new_work = new UserWork();
                    $new_work->fromArray($post);
                    $new_work->save();
                    echo $new_work->id;
                    Db_User::updatePoint('add_works');
                }
            }
            exit;
        }

        $this->_title('设置工作信息');
        $this->_render('_body', $view);
    }

    //删除工作信息
    function action_delWork() {
        $this->auto_render = FALSE;
        $cid = Arr::get($_GET, 'cid');
        $del = Doctrine_Query::create()
                ->delete('UserWork')
                ->where('id =?', $cid)
                ->andwhere('user_id = ?', $this->_uid)
                ->execute();
    }

    # 基础信息

    function action_base() {
        $user = Doctrine_Query::create()
                ->from('User u')
                ->leftJoin('u.Contact')
                ->leftJoin('u.Private')
                ->where('u.id = ?', $this->_uid)
                ->fetchOne();

        $private = $user['Private']['rules'];

        if ($_POST) {
            $v = Validate::setRules($_POST, 'user_base');
            $post = $v->getData();
            if ($user['Contact']['mobile'] != $post['mobile']) {
                $v->rule('mobile', 'Model_User::validMobile');
            }
            if (!$v->check()) {
                echo Candy::MARK_ERR . $v->outputMsg($v->errors('validate'));
            } else {
                $post['Contact']['address'] = $post['address'];
                $post['Contact']['mobile'] = $post['mobile'];
                $post['Contact']['tel'] = $post['tel'];
                $post['Contact']['qq'] = $post['qq'];
                $user->fromArray($post);
                $user->save();
            }
            unset($post);
            $user_private = Doctrine_Query::create()
                    ->from('UserPrivate')
                    ->where('user_id = ?', $this->_user_id)
                    ->fetchOne();

            $post['rules'] = serialize(Arr::get($_POST, 'private'));
            $post['user_id'] = $this->_user_id;

            if (!$user_private) {
                $user_private = new UserPrivate();
            }
            $user_private->fromArray($post);
            $user_private->save();
            exit;
        }

        $view['user'] = $user;
        $view['private'] = $private ? unserialize($private) : $private;

        // view
        $this->_title('基础资料设置');
        $this->_render('_body', $view);
    }

    //微博绑定信息
    function action_binding() {
        $view = null;
        $view['bindingUrl'] = '';
        $binding = Doctrine_Query::create()
                ->from('WeiboBinding')
                ->where('user_id = ?', $this->_uid)
                ->orderBy('id ASC')
                ->fetchArray();
        if (!$binding) {
            $appconfig = Kohana::config('app');
            Candy::import('sinaWeiboApi');
            $oauth = new SaeTOAuthV2($appconfig->sina['WB_AKEY'], $appconfig->sina['WB_SKEY']);
            $view['bindingUrl'] = $oauth->getAuthorizeURL($appconfig->sina['WB_CALLBACK_URL']);
        }
        $view['binding'] = $binding;
        $this->_render('_body', $view);
    }

    //删除微博绑定
    function action_delBinding() {
        $this->auto_render = FALSE;
        $cid = Arr::get($_GET, 'cid');
        $del = Doctrine_Query::create()
                ->delete('WeiboBinding')
                ->where('id =?', $cid)
                ->andwhere('user_id = ?', $this->_uid)
                ->execute();
    }

}