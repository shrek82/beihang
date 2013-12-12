<?php

class Controller_User_Invite extends Layout_User {

    function before() {
        parent::before();

        $topbar_links = array(
            'user_invite/index' => '收到的邀请',
            'user_invite/sended' => '发出的邀请',
            'user_invite/generate' => '发送邀请',
        );
        View::set_global('topbar_links', $topbar_links);
    }

    //收到的邀请
    function action_index() {
        $invites = Doctrine_Query::create()
                ->select('i.*,u.realname AS realname')
                ->from('UserInvite i')
                ->leftJoin('i.User u')
                ->where('i.receiver_user_id = ?', $this->_user_id)
                ->addWhere('u.id>0')
                ->orderBy('i.create_date DESC');

        $total_invite = $invites->count();

        $pager = Pagination::factory(array(
                    'total_items' => $total_invite,
                    'items_per_page' => 6,
                    'view' => 'pager/common',
                ));

        $invites = $invites->offset($pager->offset)
                ->limit($pager->items_per_page)
                ->fetchArray();

        $this->_title('我收到的邀请');
        $this->_render('_body', compact('invites', 'pager'));
    }

    //发出的邀请
    function action_sended() {
        $invites = Doctrine_Query::create()
                ->select('i.*,u.realname,u.role')
                ->from('UserInvite i')
                ->leftJoin('i.RUser u')
                ->where('i.user_id = ?', $this->_id)
                ->addWhere('i.receiver_user_id is not null')
                ->addWhere('u.id>0')
                ->orderBy('i.create_date DESC');

        $total_invite = $invites->count();

        $pager = Pagination::factory(array(
                    'total_items' => $total_invite,
                    'items_per_page' => 6,
                    'view' => 'pager/common',
                ));

        $invites = $invites->offset($pager->offset)
                ->limit($pager->items_per_page)
                ->fetchArray();


        $this->_title('发送的邀请');
        $this->_render('_body', compact('invites', 'pager'));
    }

    //发布
    function action_generate() {
        $view = null;

        $inviteLink = Doctrine_Query::create()
                ->select('i.*')
                ->from('UserInvite i')
                ->where('i.user_id = ?', $this->_user_id)
                ->andWhere('i.type=?', 'regLink')
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
        $view['inviteLink'] = $inviteLink;

        if ($_POST) {
            $this->auto_render = False;
            //修改
            if (Arr::get($_POST, 'id')) {
                $invite_id = Arr::get($_POST, 'id');
                $invite = Doctrine_Query::create()
                        ->from('UserInvite i')
                        ->where('i.user_id = ?', $this->_user_id)
                        ->andWhere('i.id=?', $invite_id)
                        ->fetchOne();
                $invite->message = Arr::get($_POST, 'message');
                $invite->save();
            }
            //新建
            else {
                $invite_id = Model_Invite::create(array(
                            'user_id' => $this->_user_id,
                            'title' => '加入校友网公共邀请',
                            'type' => 'regLink',
                            'message' => Arr::get($_POST, 'message'),
                        ));
            }

            echo URL::base(FALSE, TRUE) . 'invite?code=' . base64_encode($invite_id);
        }
        $this->_title('创建邀请');
        $this->_render('_body', $view);
    }

    //发送邮件邀请
    function action_sendMailInvite() {
        $email = Arr::get($_POST, 'emails');
        if ($email) {

            //格式
            if (!$this->email($email)) {
                echo Candy::MARK_ERR . '很抱歉，E-mail格式不正确，请重新填写！';
                exit;
            }

            //判断该邮件地址是否已经注册
            $user = Doctrine_Query::create()
                    ->from('User')
                    ->select('id,realname')
                    ->where('account = ?', $email)
                    ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

            if ($user) {
                echo Candy::MARK_ERR . '很抱歉，该校友帐号(' . $user['realname'] . ' ' . $email . ')已经注册了，无需再邀请了！';
                exit;
            }

            $invite_id = Model_Invite::create(array(
                        'user_id' => $this->_user_id,
                        'title' => '加入校友网邮件邀请',
                        'type' => 'regMail',
                        'receiver_email' => $email,
                    ));

            $sender = $this->_sess->get('realname');
            $reg_url = URL::base(FALSE, TRUE) . 'invite?code=' . base64_encode($invite_id);
            $mailer = new Model_Mailer('first');
            $mailer->sendRegInvite($sender, $email, $reg_url);
        }
        else{
                echo Candy::MARK_ERR . '很抱歉，E-mail不能为空！';
                exit;
        }
    }

    //验证email格式
    function email($email) {
        $expression = '/^[-_a-z0-9\'+*$^&%=~!?{}]++(?:\.[-_a-z0-9\'+*$^&%=~!?{}]+)*+@(?:(?![-.])[-a-z0-9.]+(?<![-.])\.[a-z]{2,6}|\d{1,3}(?:\.\d{1,3}){3})(?::\d++)?$/iD';
        return (bool) preg_match($expression, (string) $email);
    }

}