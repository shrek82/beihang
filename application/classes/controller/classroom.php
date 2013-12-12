<?php

class Controller_Classroom extends Layout_Main {

    //班级首页
    function action_index() {
        $start_year = 1900;
        $end_year = date('Y');
        $view['classroom'] = Doctrine_Query::create()
                ->select('start_year,count(*) AS class_count')
                ->from('ClassRoom cr')
                ->where('cr.start_year IS NOT NULL')
                ->addWhere('verify=?', 1)
                ->groupBy('start_year DESC')
                ->useResultCache(true, 86400, 'class_count_year')
                ->fetchArray();

        $view['class_count'] = Doctrine_Query::create()
                ->from('ClassRoom')
                ->where('start_year IS NOT NULL')
                ->addWhere('verify=?', 1)
                ->useResultCache(true, 3600, 'class_count')
                ->count();

        $view['class_join_count'] = Doctrine_Query::create()
                ->from('ClassMember')
                ->groupBy('user_id')
                ->useResultCache(true, 7200, 'class_join_count')
                ->count();

        $view['hot_classroom'] = Doctrine_Query::create()
                ->select('cr.id,cr.speciality,cr.name,cr.start_year,cr.member_num')
                ->from('ClassRoom cr')
                ->orderBy('member_num DESC')
                ->limit(9)
                ->useResultCache(true, 7200, 'hot_classroom')
                ->fetchArray();

        //最新加入
        $view['last_join'] = Doctrine_Query::create()
                ->select('m.id,u.id,u.realname,u.sex,cr.speciality,cr.name,cr.start_year,cr.id,m.join_at')
                ->from('ClassMember m')
                ->leftJoin('m.User u')
                ->leftJoin('m.ClassRoom cr')
			    ->where('m.user_id>0')
                ->orderBy('m.id DESC')
                ->limit(4)
			    ->useResultCache(true, 7200, 'last_join_classroom')
                ->fetchArray();

        //我加入的班级
        $view['myClassroom'] = null;
        if ($this->_uid) {
            $view['myClassroom'] = Doctrine_Query::create()
                    ->from('ClassMember cm')
                    ->where('user_id=?', $this->_uid)
                    ->leftJoin('cm.ClassRoom cr')
                    ->fetchArray();
        }

        $this->_title('班级录');
        $this->_render('_body', $view);
    }

// 班级列表
    function action_list() {

        $searchtype = Arr::get($_GET, 'searchtype', 'specialty');
        $start_year = Arr::get($_GET, 'start_year');
        $keyword = Arr::get($_GET, 'keyword');

        $hot_classroom = '';

       //根据同学信息查找
        if ($searchtype == 'classmate' AND $keyword) {
            $user_ids = Doctrine_Query::create()
                    ->select('u.id')
                    ->from('User u')
                    ->where('u.realname=?', trim($keyword))
                    ->execute(array(), 6);

            if ($user_ids) {
                $user_ids = is_array($user_ids) ? $user_ids : array($user_ids);
                $classroom_ids = Doctrine_Query::create()
                        ->select('m.class_room_id')
                        ->from('ClassMember m')
                        ->whereIn('m.user_id', $user_ids)
                        ->execute(array(), 6);
            } else {
                $classroom_ids = array(0);
            }
        }

        // 可选的班级名称
        $classroom = Doctrine_Query::create()
                ->select('cr.id,start_year,speciality,create_at,member_num,name,institute,finish_year')
                ->from('ClassRoom cr')
                ->addSelect('(SELECT u.realname  FROM User u WHERE u.id=(SELECT m.user_id FROM ClassMember m WHERE m.class_room_id=cr.id and m.is_manager=1 LIMIT 1) LIMIT 1) AS realname')
                ->where('verify=?', 1);

        if ($start_year) {
            $classroom->addWhere('cr.start_year=?', $start_year);
        }

        if ($keyword) {
            if ($searchtype == 'specialty') {
                $classroom->addWhere('cr.speciality LIKE ?', '%' . $keyword . '%');
            }

            if ($searchtype == 'classmate') {
                $classroom_ids = !$classroom_ids ? array(0) : $classroom_ids;
                $classroom->whereIn('cr.id', $classroom_ids);
            }
        }

        if ($start_year OR $keyword) {
            $order_by = 'create_at DESC';
        } else {
            $order_by = 'create_at DESC';
        }

        $pager = Pagination::factory(array(
                    'total_items' => $classroom->count(),
                    'items_per_page' => 20,
                    'view' => 'pager/common'
                ));

        $classrooms = $classroom->orderBy($order_by)
                ->offset($pager->offset)
                ->limit($pager->items_per_page)
                ->fetchArray();

        $hot_classroom = Doctrine_Query::create()
                ->select('cr.id,cr.speciality,cr.name,cr.start_year,cr.member_num')
                ->from('ClassRoom cr');

        if (($start_year)) {
            $hot_classroom->where('cr.start_year=?', $start_year);
        }
        $hot_classroom = $hot_classroom->orderBy('member_num DESC')
                ->limit(9)
                ->fetchArray();


        $view = compact('classnames', 'start_year', 'searchtype', 'keyword', 'classrooms', 'new_classroom', 'hot_classroom', 'pager');
        $this->_title('班级录');
        $this->_render('_body', $view);
    }

    # 申请加入（非推荐加入班级的途径）

    function action_join() {

    }

    #新建班级

    function action_create() {
        $user_id = $this->_uid;
        $view['success'] = Arr::get($_GET, 'success');
        if (!$user_id) {
            $this->_redirect(('user/login'));
            exit;
        }

        $view['err'] = '';

        if ($_POST) {

            $valid = Validate::setRules($_POST, 'new_classroom');
            $post = Validate::getData();

            if (!$valid->check()) {
                echo Candy::MARK_ERR .
                $valid->outputMsg($valid->errors('validate'));
            } else {
                //检查是否已经有该班级
                $same_class = Doctrine_Query::create()
                        ->from('ClassRoom')
                        ->where('start_year=?', Arr::get($_POST, 'start_year'))
                        ->addWhere('speciality=?', trim(Arr::get($_POST, 'speciality')))
                        ->addWhere('institute=?', trim(Arr::get($_POST, 'institute')))
                        ->fetchOne();

                if ($same_class) {
                    echo Candy::MARK_ERR . '很抱歉，您创建的班级已经存在！请返回后查询班级，谢谢！';
                    exit;
                }

                $post['create_at'] = date('Y-m-d H:i:s');
                $post['verify'] = FALSE;
                $classroom = new ClassRoom();
                $classroom->fromArray($post);
                $classroom->save();

                unset($post);

                //加入并成为管理员
                $post['class_room_id'] = $classroom->id;
                $post['user_id'] = $user_id;
                $post['title'] = '管理员';
                $post['is_manager'] = 1;
                $post['join_at'] = date('Y-m-d H:i:s');
                $member = new ClassMember();
                $member->fromArray($post);
                $member->save();
            }
        }

        $this->_title('创建班级');
        $this->_render('_body', $view);
    }

}