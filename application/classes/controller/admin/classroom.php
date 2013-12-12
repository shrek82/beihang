<?php

class Controller_Admin_Classroom extends Layout_Admin {

    function before() {
        parent::before();

        $leftbar_links = array(
            'admin_classroom/index' => '所有班级',
            'admin_classroom/applyManager' => '申请加入',
        );

        $this->_render('_body_left', compact('leftbar_links'), 'admin/leftbar');
    }

    function action_index() {

        $q = Arr::get($_GET, 'q');
        $verify = Arr::get($_GET, 'verify');
        $start_year = Arr::get($_GET, 'start_year');

        // 可选的班级名称
        $classroom = Doctrine_Query::create()
                ->select('cr.id,start_year,speciality,create_at,name,institute,finish_year,verify,member_num')
                ->from('ClassRoom cr');

        if ($start_year) {
            $classroom->addWhere('cr.start_year=?', $start_year);
        }

        if ($verify == '0' OR $verify == '1') {
            $classroom->addWhere('cr.verify=?', $verify);
        }

        if ($q) {
            $classroom->addWhere('cr.speciality LIKE ?', '%' . $q . '%');
        }

        $pager = Pagination::factory(array(
                    'total_items' => $classroom->count(),
                    'items_per_page' => 20,
                    'view' => 'pager/common'
                ));

        $classroom = $classroom->orderBy('verify ASC,start_year DESC,id DESC')
                ->offset($pager->offset)
                ->limit($pager->items_per_page)
                ->fetchArray();

        $view = compact('classname', 'verify', 'start_year', 'q', 'classroom', 'new_classroom', 'hot_classroom', 'pager');
        $this->_render('_body', $view);
    }

    //审核操作
    function action_verify() {
        $this->auto_render = FALSE;
        $cid = Arr::get($_POST, 'cid');

        $classroom = Doctrine_Query::create()
                ->from('ClassRoom')
                ->where('id = ?', $cid)
                ->fetchOne();

        if ($classroom['verify']) {
            $classroom->verify = FALSE;
            $action_name = '关闭';
        } else {
            $classroom->verify = TRUE;
            $action_name = '审核通过';
        }
        $classroom->save();

        $log_data = array();
        $log_data['type'] = '班级审核';
        $log_data['classroom_id'] = $cid;
        $log_data['description'] = $action_name . '了班级“' . $classroom['speciality'] . '(' . $classroom['institute'] . ' ' . $classroom['start_year'] . ' ~' . $classroom['finish_year'] . '年)”';
        Common_Log::add($log_data);
    }

    //删除班级及其相关内容
    function action_del() {
        $this->auto_render = FALSE;
        $cid = Arr::get($_GET, 'cid');
        if ($cid > 0) {

            $c = Doctrine_Query::create()
                    ->select('id,start_year,speciality,create_at,name,institute,finish_year,verify,member_num')
                    ->from('ClassRoom')
                    ->where('id=?', $cid)
                    ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

            //操作日志
            $log_data = array();
            $log_data['type'] = '删除班级';
            $log_data['classroom_id'] = $cid;
            $log_data['description'] = '删除班级“' . $c['speciality'] . '(' . $c['institute'] . ' ' . $c['start_year'] . ' ~' . $c['finish_year'] . '年)”及其所有相关信息';
            Common_Log::add($log_data);

            $del = Doctrine_Query::create()
                    ->delete('ClassRoom')
                    ->where('id =?', $cid)
                    ->execute();

            //班级成员
            $del = Doctrine_Query::create()
                    ->delete('ClassMember')
                    ->where('class_room_id =?', $cid)
                    ->execute();

            Db_Comment::delete(array('class_room_id' => $cid));

            //班级申请加入信息
            $del = Doctrine_Query::create()
                    ->delete('JoinApply')
                    ->where('class_room_id =?', $cid)
                    ->execute();

            //班级论坛所有版块id
            $class_bbs_ids = Doctrine_Query::create()
                    ->select('id')
                    ->from('ClassBbs')
                    ->where('classroom_id = ?', $cid)
                    ->execute(array(), 6);

            //班级所有话题id
            if ($class_bbs_ids) {
                $class_bbs_unit_ids = Doctrine_Query::create()
                        ->select('id')
                        ->from('ClassBbsUnit')
                        ->whereIn('bbs_id ', $class_bbs_ids)
                        ->execute(array(), 6);
            } else {
                $class_bbs_unit_ids = null;
            }


            //班级帖子评论
            if ($class_bbs_unit_ids) {
                Db_Comment::delete(array('class_unit_id' => $class_bbs_unit_ids));
            }

            //删除话题
            if ($class_bbs_ids AND $class_bbs_unit_ids) {
                $del = Doctrine_Query::create()
                        ->delete('ClassBbsUnit')
                        ->whereIn('bbs_id', $class_bbs_ids)
                        ->execute();
            }


            //删除班级版块
            $del = Doctrine_Query::create()
                    ->delete('ClassBbs')
                    ->where('classroom_id = ?', $cid)
                    ->execute();
        }
    }

    //班级加入申请
    function action_applyManager() {
        // 可选的班级名称
        $apply = Doctrine_Query::create()
                ->select('a.*,u.realname AS realname,u.role as role,c.*')
                ->addSelect('(select speciality from class_room where id=a.class_room_id) AS speciality')
                ->from('JoinApply a')
                ->leftJoin('a.User u')
                ->leftJoin('a.ClassRoom c')
                ->where('a.class_room_id>0');

        $pager = Pagination::factory(array(
                    'total_items' => $apply->count(),
                    'items_per_page' => 20,
                    'view' => 'pager/common'
                ));

        $apply = $apply->orderBy('a.id DESC')
                ->offset($pager->offset)
                ->limit($pager->items_per_page)
                ->fetchArray();

        $view = compact('apply', 'pager');
        $this->_render('_body', $view);
    }

    //批准加入到班级
    function action_applyAccept() {
        $this->auto_render = FALSE;
        $cid = Arr::get($_GET, 'cid');
        $user_id = Arr::get($_GET, 'user_id');
        $class_room_id = Arr::get($_GET, 'class_room_id');

        //查找班级是否有成员
        $total_member = Doctrine_Query::create()
                ->from('ClassMember')
                ->where('class_room_id = ?', $class_room_id)
                ->addWhere('is_manager = ?', True)
                ->count();

        //设置为班级管理员
        $classMember = New ClassMember;
        $post['user_id'] = $user_id;
        $post['class_room_id'] = $class_room_id;
        $post['join_at'] = date('Y-m-d H:i:s');
        $post['visit_at'] = date('Y-m-d H:i:s');
        $post['is_verify'] = True;
        if ($total_member == 0) {
            $post['is_manager'] = True;
            $post['title'] = '管理员';
        }
        $classMember->fromArray($post);
        $classMember->save();

        //发送通知
        unset($post);
        $post['send_to'] = $user_id;
        $post['sort_in'] = 0;
        $post['user_id'] = $this->_sess->get('id');
        $post['content'] = '恭喜您，您已经成为班级管理员！';
        $post['send_at'] = date('Y-m-d H:i:s');
        $post['update_at'] = date('Y-m-d H:i:s');
        $msg = new UserMsg();
        $msg->fromArray($post);
        $msg->save();

        //删除申请信息
        $apply = Doctrine_Query::create()
                ->delete('JoinApply')
                ->where('id =?', $cid)
                ->execute();

        //更新班级成员总数
        Model_Classroom::updateMemberNum($class_room_id);
    }

    //拒绝加入申请
    function action_applyReject() {
        $this->auto_render = FALSE;
        $cid = Arr::get($_GET, 'cid');
        $user_id = Arr::get($_GET, 'user_id');
        $class_room_id = Arr::get($_GET, 'class_room_id');
        $reject_reason = Arr::get($_POST, 'reject_reason');

        $apply = Doctrine_Query::create()
                ->from('JoinApply')
                ->where('id = ?', $cid)
                ->fetchOne();

        if ($_POST) {
            $post['reject_reason'] = $reject_reason;
            $post['is_reject'] = True;
            $apply->fromArray($post);
            $apply->save();

            //发送通知
            unset($post);
            $msg = new UserMsg();
            $post['sort_in'] = 0;
            $post['send_to'] = $apply['user_id'];
            $post['user_id'] = $this->_sess->get('id');
            $post['content'] = '您申请班级管理员已经得到回复：<br>' . $reject_reason;
            $post['send_at'] = date('Y-m-d H:i:s');
            $post['update_at'] = date('Y-m-d H:i:s');
            $msg->fromArray($post);
            $msg->save();
        }

        $view['reason'] = $apply['reject_reason'];
        $view['action'] = URL::site('admin_classroom/applyReject?cid=' . $cid);
        echo View::factory('inc/reject_reason', $view);
    }

    //班级话题
    function action_bbs() {
        $bbs_id = Arr::get($_GET, 'bbs_id', 0);

        $unit = Doctrine_Query::create()
                ->select('bu.id,bu.title,bu.user_id,bu.update_at,bu.hit,bu.create_at,bu.is_fixed,bu.comment_at,bu.reply_num,b.classroom_id,u.realname')
                ->from('ClassBbsUnit bu')
                ->leftJoin('bu.ClassBbs b')
                ->leftJoin('bu.User u')
                ->orderBy(' bu.create_at DESC');

        if ($bbs_id > 0) {
            $unit->andWhere('u.bbs_id = ?', $bbs_id);
        }

        $total_unit = $unit->count();

        $pager = Pagination::factory(array(
                    'total_items' => $total_unit,
                    'items_per_page' => 20,
                    'view' => 'pager/common',
                ));

        $unit = $unit->offset($pager->offset)
                ->limit($pager->items_per_page)
                ->fetchArray();

        $this->_render('_body', compact('unit', 'pager', 'bbs_id'));
    }

}