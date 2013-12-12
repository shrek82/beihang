<?php

class Controller_User_Msg extends Layout_User {

    function before() {
        parent::before();

        $topbar_links = array(
            'user_msg/form' => '撰写发送',
            'user_msg/index' => '我的信件',
            'user_msg/sys' => '系统消息',
        );
        View::set_global('topbar_links', $topbar_links);
    }

    //显示最后一条消息详情
    function action_faceboxmsg() {
        $this->auto_render = FALSE;
        $msg = DB::select(DB::expr('m.*,u.realname AS sender_name'))
                ->from(array('user_msg', 'm'))
                ->join(array('user', 'u'))
                ->on('m.user_id', '=', 'u.id')
                ->where('m.send_to', '=', $this->_user_id)
                ->where('m.read_at', 'IS', NULL)
                ->limit(1)
                ->order_by('m.id', 'ASC')
                ->execute()
                ->as_array();
        if ($msg) {
            $msg = $msg[0];
            $title = '短消息';
            $msg_body = strip_tags($msg['content']);
            $msg_body = Text::limit_chars($msg_body, 100);
            $time = '<span style="color:#999">(' . Date::ueTime($msg['send_at']) . ')</span>';
            if ($msg['sort_in'] > 0) {
                $msg_body = $msg['sender_name'] . $time . '回复：' . $msg_body;
            } else {
                $msg_body = $msg['sender_name'] . $time . '：' . $msg_body;
            }
            echo json_encode(array('user_id' => $msg['user_id'], 'title' => $title, 'msg_id' => $msg['id'], 'msg_content' => $msg_body));
        }
    }

    //已阅读消息
    function action_setReaded() {
        $this->auto_render = FALSE;
        $msg_id = Arr::get($_GET, 'msg_id');
        DB::update('user_msg')
                ->set(array('read_at' => date('Y-m-d H:i:s')))
                ->where('id', '=', $msg_id)
                ->where('send_to', '=', $this->_user_id)
                ->execute();
    }

    //至显示数目
    function action_check() {
        $this->auto_render = FALSE;
        $count = DB::select('id')
                ->from('user_msg')
                ->where('send_to', '=', $this->_user_id)
                ->where('read_at', 'IS', NULL)
                ->execute()
                ->count();
        echo $count;
    }

    # 察看系统消息

    function action_sys() {
        $msg = Doctrine_Query::create()
                ->from('SysMessage')
                ->where('expire_at > curdate() AND start_at <= curdate()')
                ->fetchArray();

        $new_msg = array();

        foreach ($msg as $m) {
            $reader = explode(' ', $m['reader']);
            if (!in_array($this->_user_id, $reader)) {
                $new_msg[] = $m['id'];
            }
        }

        // 全部改为已读
        if (count($new_msg) > 0) {
            $q = Doctrine_Query::create()
                    ->update('SysMessage sm')
                    ->andWhereIn('sm.id', $new_msg)
                    ->set('sm.reader', 'concat_ws(?, ?, sm.reader)', array(' ', $this->_user_id))
                    ->execute();
        }

        $this->_title('系统消息');
        $this->_render('_body', compact('new_msg', 'msg'));
    }

    function action_form() {
        if ($_POST) {
            $content = Arr::get($_POST, 'content');
            $send_to = Arr::get($_POST, 'send_to');
            if (!$content) {
                echo Candy::MARK_ERR . '* 内容不能为空';
                exit;
            } elseif (!count($send_to)) {
                echo Candy::MARK_ERR . '* 发送对象不能为空';
                exit;
            } else {

                if (!is_array($send_to) AND is_string($send_to)) {
                    $send_to =trim($send_to);
                    $send_to =  explode(',',$send_to);
                }

                foreach ($send_to as $u) {
                    $msg = new UserMsg();
                    $msg->send_to = $u;
                    $msg->sort_in = 0;
                    $msg->user_id = $this->_user_id;
                    $msg->send_at = date('Y-m-d H:i:s');
                    $msg->update_at = date('Y-m-d H:i:s');
                    $msg->content = $content;
                    $msg->save();
                }
            }
            exit;
        }

        $sort_in = Arr::get($_GET, 'in');
        $send_to = Arr::get($_GET, 'to');

        if (!is_array($send_to)) {
            $send_to = array(0);
        }

        $user = Doctrine_Query::create()
                ->from('User')
                ->whereIn('id', $send_to)
                ->fetchArray();

        $receiver = array();
        foreach ($user as $u) {
            $receiver[] = $u['realname'];
        }

        $mark_user = Doctrine_Query::create()
                ->from('UserMark um')
                ->leftJoin('um.MUser u')
                ->where('um.user_id = ?', $this->_user_id)
                ->addWhere('u.id>0')
                ->fetchArray();

        $users = '发送';
        if (count($user) > 1) {
            $users .= $user[0]['realname'] . '等人';
        } elseif (count($user) == 1) {
            $users .= $user[0]['realname'];
        }

        $this->_title($users . '站内信息');
        $this->_render('_body', compact('send_to', 'user', 'mark_user', 'receiver'));
    }

    function action_reply() {
        if ($_POST) {
            $v = Validate::setRules($_POST, 'msg_reply');
            $post = $v->getData();
            if (!$v->check()) {
                echo Candy::MARK_ERR . $v->outputMsg($v->errors('validate'));
            } else {
                $post['send_at'] = date('Y-m-d H:i:s');
                $msg = new UserMsg();
                $msg->fromArray($post);
                $msg->save();
                // 更新最后对话时间
                Doctrine_Query::create()
                        ->update('UserMsg')
                        ->where('id = ?', $post['sort_in'])
                        ->set('update_at', '?', date('Y-m-d H:i:s'))
                        ->execute();
            }
            exit;
        }

        $id = Arr::get($_GET, 'msg_id');
        $alive = time() - 900;
        $msg = Doctrine_Query::create()
                ->select('m.*,u.id,u.realname')
                ->addSelect('(SELECT o.id FROM Ol o WHERE o.uid=u.id AND time>' . $alive . ' ) AS online')
                ->from('UserMsg m')
                ->leftJoin('m.User u')
                ->where('m.sort_in = ?', $id)
                ->orWhere('m.id = ?', $id)
                ->orderBy('m.send_at ASC')
                ->fetchArray();

        if (count($msg) == 0) {
            exit;
        } else {

            // read
            Doctrine_Query::create()
                    ->update('UserMsg')
                    ->where('sort_in = ? OR id = ?', array($id, $id))
                    ->andWhere('read_at IS NULL AND send_to = ?', $this->_user_id)
                    ->set('read_at', '?', date('Y-m-d H:i:s'))
                    ->execute();

            $view['id'] = $id;
            $view['msgs'] = $msg;
            echo View::factory('inc/user/msg_reply', $view);
        }
    }

    function action_index() {
        $msg = Doctrine_Query::create()
                ->select('m.*, u.id,u.realname,u.sex, r.*')
                ->from('UserMsg m')
                ->addSelect('(SELECT COUNT(m3.id) FROM UserMsg m3 WHERE (m3.sort_in = m.id OR m3.id = m.id) AND m3.send_to = ' . $this->_user_id . ' AND m3.read_at IS NULL) as new_num')
                ->addSelect('(SELECT COUNT(m2.id) FROM UserMsg m2 WHERE m2.sort_in = m.id) as replay_num')
                ->leftJoin('m.User u')
                ->leftJoin('m.Rec r')
                ->where('m.sort_in = ?', 0)
                ->andWhere('m.send_to = ? OR m.user_id = ?', array($this->_user_id, $this->_user_id))
                ->orderBy('new_num DESC,m.update_at DESC');

        $pager = Pagination::factory(array(
                    'total_items' => $msg->count(),
                    'items_per_page' => 20,
                    'view' => 'pager/common',
        ));

        $view['pager'] = $pager;
        $view['msgs'] = $msg->offset($pager->offset)
                ->limit($pager->items_per_page)
                ->fetchArray();

        $this->_title('我的信息');
        $this->_render('_body', $view);
    }

    //最新消息弹出提示
    function action_facebox() {
        $this->auto_render = False;
        echo View::factory('user_msg/facebox');
    }

}