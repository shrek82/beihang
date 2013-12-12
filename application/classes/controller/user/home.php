<?php

class Controller_User_Home extends Layout_User {

    function before() {
        parent::before();
        $this->_last_login = $this->_sess->get('last_login', date('Y-m-d H:i:s'));
    }

    function action_index() {

        if ($this->_id != $this->_user_id) {
            $this->userPermissions('userHome');
        }

        // 最新状态
        $newthing = Doctrine_Query::create()
                ->from('WeiboContent')
                ->where('user_id = ?', $this->_id)
                ->orderBy('id DESC')
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
        $view['newthing'] = $newthing;

        $view['user'] = $this->_user;

        //我加入的组织
        $view['aa'] = Doctrine_Query::create()
                ->select('a.id,a.sname,a.name,a.ename')
                ->from('Aa a')
                ->whereIn('a.id', Model_User::aaIds($this->_id))
                ->fetchArray();

        // 上次登录时间
        $last_login = $this->_last_login;
        $aa_ids = Model_User::aaIds($this->_id);

        // 新闻速递(过滤到只更新加入的组织)

        $news = Doctrine_Query::create()
                ->select('n.id,n.title,n.create_at,n.title_color,n.is_pic,n.is_fixed')
                ->from('News n')
                ->leftJoin('n.NewsCategory c')
                ->where('n.is_release = ?', TRUE)
                ->andWhere('n.create_at > ?', $last_login)
                ->andWhereIn('c.aa_id', $aa_ids)
                ->orderBy('n.create_at DESC')
                ->limit(4)
                ->useResultCache(true, 300, 'user_home_n' . $this->_id)
                ->fetchArray();

        $view['news'] = $news;

        // 论坛回帖
        $unit = Doctrine_Query::create()
                ->from('BbsUnit bu')
                ->leftJoin('bu.Comments c')
                ->where('bu.user_id = ?', $this->_id)
                ->andWhere('c.user_id != ?', $this->_id)
                ->andWhere('c.post_at > ?', $last_login)
                ->useResultCache(true, 300, 'user_home_uc' . $this->_id)
                ->fetchArray();

        $view['units'] = $unit;

        // 活动提醒
        $ids = Model_Event::joinIDs($this->_id);
        $event = Doctrine_Query::create()
                ->from('Event e')
                ->where('e.start > curdate()')
                ->andWhereIn('e.id', $ids)
                ->useResultCache(true, 300, 'user_home_e' . $this->_id)
                ->fetchArray();
        $view['events'] = $event;

        // 自动上次等后被关注
        $mark = Doctrine_Query::create()
                ->from('UserMark m')
                ->leftJoin('m.User')
                ->where('m.user = ?', $this->_id)
                ->andWhere('m.mark_at > ?', $last_login)
                ->useResultCache(true, 300, 'user_home_m' . $this->_id)
                ->fetchArray();
        $view['marks'] = $mark;

        // 用户隐私控制
        $view['private'] = Model_User::privateRules($this->_id);
        $this->_render('_body', $view);
    }

    //我加入的组织动态
    function action_joinSyn() {
        $this->auto_render = FALSE;
        $tab = 'all';
        $last_time = strtotime($this->_last_login);
        $week = date('Y-m-d H:i:s', $last_time - Date::DAY * 7);
        $aa_id = Arr::get($_GET, 'aa_id', 0);
        $cache_time = 3600;

        $data = array();

        //总会动态和杭州动态从缓存读取
        if ($aa_id == 0 OR $aa_id == 1) {
            $data = $this->_cache->get('user_home_mainaa_syn_' . $aa_id);
            if ($data AND count($data) > 0) {
                echo View::factory('user_home/joinSyn/all', compact('data'));
                exit;
            }
        }

        //新闻分类
        $news_category = Doctrine_Query::create()
                ->select('id')
                ->from('NewsCategory')
                ->where('aa_id=?', $aa_id)
                ->useResultCache(true, $cache_time, 'join_sys_news_category_' . $aa_id)
                ->execute(array(), 6);

        //新闻
        $news = Doctrine_Query::create()
                ->select('n.title,n.create_at,n.is_pic')
                ->addSelect('UNIX_TIMESTAMP(n.create_at) AS syn_at')
                ->from('News n')
                ->leftJoin('n.NewsCategory c')
                ->where('n.is_release = ?', TRUE)
                ->andWhere('c.aa_id = ?', $aa_id)
                ->andWhere('n.create_at > ?', $week)
                ->orderBy('n.create_at DESC')
                ->limit(5)
                ->useResultCache(true, $cache_time, 'join_sys_news_' . $aa_id)
                ->fetchArray();

        foreach ($news as $key => $row) {
            $key = $row['syn_at'];
            $data[$key]['data'] = $row;
            $data[$key]['syn_type'] = 'news';
        }


        //查询条件
        $condition = array(
            'aa_id' => $aa_id,
            'count_total' => false,
            'replyname' => false,
            'page_size' => 15,
        );

        $query_data = Db_Bbs::getUnits($condition);
        $bbs_unit = $query_data['units'];

        foreach ($bbs_unit as $key => $row) {
            $key = strtotime($row['create_at']);
            $data[$key]['data'] = $row;
            $data[$key]['syn_type'] = 'bbs';
        }

        //活动
        $event = Doctrine_Query::create()
                ->select('e.id,e.aa_id,e.club_id,e.title,e.start,e.finish,e.publish_at,e.user_id')
                ->addSelect('UNIX_TIMESTAMP(e.publish_at) AS syn_at')
                ->addSelect('(SELECT SUM(ss.num) FROM EventSign ss WHERE ss.event_id = e.id) AS sign_num')
                ->from('Event e')
                ->where('aa_id=?', $aa_id)
                ->andWhere('e.publish_at > ?', $week)
                ->addWhere('(e.is_closed = ? OR e.is_closed IS NULL)', FALSE)
                ->limit(10)
                ->useResultCache(true, $cache_time, 'join_sys_events_' . $aa_id)
                ->orderBy('e.publish_at DESC')
                ->fetchArray();

        if (count($event) > 0) {
            foreach ($event as $key => $row) {
                $key = $row['syn_at'];
                $data[$key]['data'] = $row;
                $data[$key]['syn_type'] = 'event';
            }
        }

        //俱乐部
        $clubs = Doctrine_Query::create()
                ->select('id,name')
                ->from('Club')
                ->where('aa_id=?', $aa_id)
                ->fetchArray();

        $club_ids = array();

        if (count($clubs) > 0) {
            foreach ($clubs as $c) {
                $club_ids[] = $c['id'];
            }
            $where = '(aa_id=' . $aa_id . ' OR club_id IN(' . implode(",", $club_ids) . '))';
        } else {
            $where = 'aa_id=' . $aa_id;
        }

        //相册
        $album = Doctrine_Query::create()
                ->select('id,name')
                ->addSelect('UNIX_TIMESTAMP(update_at) AS syn_at')
                ->from('Album')
                ->where($where)
                ->andWhere('update_at > ?', $week)
                ->orderBy('update_at DESC')
                ->limit(3)
                ->fetchArray();

        if (count($album) > 0) {
            foreach ($album as $row) {
                $key = $row['syn_at'];
                $data[$key]['syn_type'] = 'album';
                $photo = Doctrine_Query::create()
                        ->select('p.upload_at, p.name,p.img_path,a.name as album_name')
                        ->from('Pic p')
                        ->leftJoin('p.Album a')
                        ->where('p.album_id=?', $row['id'])
                        ->orderBy('p.upload_at DESC')
                        ->limit(3)
                        ->fetchArray();
                $data[$key]['data'] = $photo;
            }
        }

        krsort($data);
        if ($aa_id == 0 OR $aa_id == 1) {
            $this->_cache->set('user_home_mainaa_syn_' . $aa_id, $data, 3600);
        }

        echo View::factory('user_home/joinSyn/all', compact('data'));
    }

    #某一个校友的动态

    function action_syn($tab) {
        $tab = $tab ? $tab : 'all';
        $this->auto_render = FALSE;

        $last_time = strtotime($this->_last_login);
        $browse_myself = False;

        //浏览我关注的多个人动态(本人或未指定)
        if (($this->_user_id == $this->_id) || (!$this->_id)) {
            $browse_myself = True;
            $ids = Model_Mark::arr('user', $this->_id);
            $week = date('Y-m-d H:i:s', $last_time - Date::DAY * 7);
            $weeks = date('Y-m-d H:i:s', $last_time - Date::DAY * 14);
            //我关注的人里面增加自己
            $ids[] = $this->_sess->get('id');
        }
        //某一人动态
        else {
            $ids = array($this->_id);
            $week = date('Y-m-d H:i:s', $last_time - Date::DAY * 15);
            $weeks = date('Y-m-d H:i:s', $last_time - Date::DAY * 60);
        }
        $data = array(); // 收集数据
        //个人信息
        if ($tab == 'info') {
            $user_info = Doctrine_Query::create()
                    ->from('User u')
                    ->leftJoin('u.Contact')
                    ->leftJoin('u.Private')
                    ->where('u.id = ?', $this->_id)
                    ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

            $works = Doctrine_Query::create()
                    ->from('UserWork')
                    ->where('user_id = ?', $this->_id)
                    ->orderBy('start_at ASC')
                    ->fetchArray();

            $rules = Model_User::privateRules($this->_id);
            $tel = Model_User::privateChecker($rules, 'tel', $this->_id);
            echo View::factory('user_home/syn/info', compact('user_info', 'tel', 'rules', 'works'));
            exit;
        }

        // 新鲜事
        if ($tab == 'weibo' || $tab == 'all') {

            if ($tab == 'weibo') {
                $week = $weeks;
            }

            $rows = Doctrine_Query::create()
                    ->select('c.*')
                    ->addSelect('(SELECT u.realname FROM User u WHERE u.id = c.user_id) AS realname')
                    ->from('WeiboContent c')
                    ->whereIn('c.user_id', $ids)
                    ->addWhere('c.is_original =?', true)
                    ->addWhere('c.aa_id >0');
            if (!$browse_myself) {
                $rows->limit(20);
            } else {
                $rows->andWhere('c.post_at > ?', $week);
            }
            $rows = $rows->orderBy('c.post_at DESC')
                    ->fetchArray();

            foreach ($rows as $row) {
                $time = strtotime($row['post_at']);
                $data[$time]['weibo'][] = $row;
            }
        }

        // 帖子
        if ($tab == 'bbs' || $tab == 'all') {

            if ($tab == 'bbs') {
                $week = $weeks;
            }

            $rows = Doctrine_Query::create()
                    ->select('un.title, un.type, un.create_at, un.reply_num, un.user_id, un.hit')
                    ->addSelect('(SELECT u.realname FROM User u WHERE u.id = un.user_id) AS realname')
                    ->from('BbsUnit un')
                    ->whereIn('un.user_id', $ids)
                    ->addWhere('un.is_closed = 0');
            if (!$browse_myself) {
                $rows->limit(20);
            } else {
                $rows->andWhere('un.create_at > ?', $week);
            }
            $rows = $rows->orderBy('un.create_at DESC')
                    ->useResultCache(true, 300, 'ssysn_bbs_' . $this->_id)
                    ->fetchArray();

            foreach ($rows as $row) {
                $time = strtotime($row['create_at']);
                $data[$time]['bbs'][] = $row;
            }
        }

        // 活动
        if ($tab == 'event' || $tab == 'all') {

            if ($tab == 'event') {
                $week = $weeks;
            }

            // 发布的活动
            $rows = Doctrine_Query::create()
                    ->select('e.id,e.aa_id,e.club_id,e.title,e.start,e.finish,e.publish_at,e.user_id')
                    ->addSelect('(SELECT u.realname FROM User u WHERE u.id = e.user_id) AS realname')
                    ->addSelect('(SELECT SUM(ss.num) FROM EventSign ss WHERE ss.event_id = e.id) AS sign_num')
                    ->from('Event e')
                    ->whereIn('e.user_id', $ids);
            if (!$browse_myself) {
                $rows->limit(20);
            } else {
                $rows->andWhere('e.publish_at > ?', $week);
            }
            $rows->addWhere('(e.is_closed = ? OR e.is_closed IS NULL)', FALSE);
            $rows = $rows->orderBy('e.publish_at DESC')
                    ->useResultCache(true, 300, 'ssyn_event_pubss_' . $this->_id)
                    ->fetchArray();

            foreach ($rows as $row) {
                $time = strtotime($row['publish_at']);
                $data[$time]['event_pub'][] = $row;
            }

            // 报名的
            $rows = Doctrine_Query::create()
                    ->select('e.title,e.aa_id,e.club_id,e.publish_at, e.num, es.user_id, es.sign_at, es.event_id')
                    ->addSelect('(SELECT u.realname FROM User u WHERE u.id = es.user_id) AS realname')
                    ->from('EventSign es')
                    ->leftJoin('es.Event e')
                    ->whereIn('es.user_id', $ids);
            if (!$browse_myself) {
                $rows->limit(20);
            } else {
                $rows->andWhere('es.sign_at > ?', $week);
            }

            $rows = $rows->andWhere('es.is_anonymous =0')
                    ->orderBy('es.sign_at DESC')
                    ->useResultCache(true, 300, 'syn_event_signs_' . $this->_id)
                    ->fetchArray();

            foreach ($rows as $row) {
                $time = strtotime($row['sign_at']);
                $data[$time]['event_sign'][] = $row;
            }
        }

        // 相册
        if ($tab == 'photo' || $tab == 'all') {

            if ($tab == 'photo') {
                $week = $weeks;
            }

            $rows = Doctrine_Query::create()
                    ->select('p.upload_at, p.name,p.img_path, ab.*, p.user_id')
                    ->addSelect('(SELECT u.realname FROM User u WHERE u.id = p.user_id) AS realname')
                    ->from('Pic p')
                    ->leftJoin('p.Album ab')
                    ->whereIn('p.user_id', $ids)
                    ->andWhere('p.upload_at > ?', $week)
                    ->orderBy('p.upload_at DESC')
                    ->fetchArray();

            foreach ($rows as $row) {
                $time = strtotime($row['upload_at']);
                $data[$time]['photo'][] = $row;
            }
        }

        // 邀请注册
        if ($tab == 'invite_register' || $tab == 'all') {

            if ($tab == 'invite_register') {
                $week = $weeks;
            }
            $rows = Doctrine_Query::create()
                    ->select('i.*,u.realname as realname')
                    ->from('UserInvite i')
                    ->leftJoin('i.User u')
                    ->whereIn('i.user_id', $ids)
                    ->addWhere('i.receiver_user_id>0')
                    ->addWhere('(type="regMail" OR type="regLink" )')
                    ->orderBy('i.accept_date DESC')
                    ->limit(10)
                    ->fetchArray();

            foreach ($rows as $row) {
                $time = strtotime($row['accept_date']);
                $data[$time]['invite_register'][] = $row;
            }
        }

        krsort($data); // 新内容优先显示
        $limit = $tab == 'all' ? 5 : 20;
        $data_resort = array();
        foreach ($data as $t => $item) {
            $type = key($item); // 获取内容分类
            $counter = array(); // 同人计数器
            // 如果没有碰到比较优先的其他内容则收集相同内容的纪录
            foreach ($data as $t2 => $item2) {
                $type2 = key($item2);
                // 不同分类的跳出
                if ($type != $type2) {
                    break;
                } elseif ($t2 <= $t) {
                    $user_id = $item2[$type2][0]['user_id'];
                    // 相同人限制收集X条
                    if (isset($counter[$user_id]) && $counter[$user_id] > $limit) {
                        unset($data[$t2]);
                        continue;
                    } else {
                        // 加入纪录
                        $data_resort[$t][$type][] = $item2[$type2][0];
                        unset($data[$t2]);
                        if (isset($counter[$user_id])) {
                            $counter[$user_id] += 1;
                        } else {
                            $counter[$user_id] = 1;
                        }
                    }
                }
            }
        }
        $data = $data_resort;
        echo View::factory('user_home/syn/all', compact('data'));
    }

    function action_avatarbak() {
        $view = array();

        if ($_FILES) {
            $v = Validate::factory($_FILES);
            $v->rules('avatar', Model_Album::$up_rule);

            if (!$v->check()) {
                $view['error'] = $v->outputMsg($v->errors('validate'));
            } else {
                $path = DOCROOT . Model_User::USER_AVATAR_DIR;
                Upload::save($_FILES['avatar'], $this->_user_id . '.jpg', $path);
                $view['photo_path'] = DOCROOT . Model_User::USER_AVATAR_DIR . $this->_user_id . '.jpg';

                Image::factory($path . $this->_user_id)
                        ->resize(128, 128, Image::NONE)
                        ->background('#fff', 0)
                        ->save($path . '128/' . $this->_user_id . '.jpg');

                Image::factory($path . $this->_user_id)
                        ->resize(48, 48, Image::NONE)
                        ->background('#fff', 0)
                        ->save($path . '48/' . $this->_user_id . '.jpg');

                unlink($path . $this->_user_id);

                $this->request->redirect('user_home/avatar');
            }
        }

        $this->_title('我的形象');
        $this->_render('_body', $view);
    }

    // 我(Ta)关注人列表
    function action_userm() {
        $mark = Doctrine_Query::create()
                ->select('um.*,u.realname,u.sex')
                ->from('UserMark um')
                ->leftJoin('um.MUser u')
                ->where('um.user_id = ?', $this->_id)
                ->andWhere('um.user IS NOT NULL')
                ->orderBy('um.mark_at DESC');

        if (Request::$is_ajax == TRUE) {
            $view['mark'] = $mark->limit(9)->fetchArray();
            $view['title'] = '';
            echo View::factory('user_home/userm', $view);
            exit;
        }

        $view['title'] = '关注他们';
        $view['mark'] = $mark->fetchArray();

        $this->_title('关注他们');
        $this->_render('_body', $view);
    }

    // 关注我(Ta)的人列表
    function action_focusOnThis() {
        $focus = Doctrine_Query::create()
                ->from('UserMark um')
                ->leftJoin('um.FUser u')
                ->where('um.user = ?', $this->_id)
                ->addWhere('um.user_id IS NOT NULL')
                ->orderBy('um.mark_at DESC');



        if (Request::$is_ajax == TRUE) {
            $view['focus'] = $focus->limit(6)->fetchArray();
            $view['title'] = '';
            echo View::factory('user_home/focusOnThis', $view);
            exit;
        }

        $view['title'] = '关注这里';
        $view['focus'] = $focus->fetchArray();

        $this->_title('关注这里');
        $this->_render('_body', $view);
    }

    //最近访问
    function action_visitor() {
        $visitor = Doctrine_Query::create()
                ->select('v.*,u.realname,u.sex')
                ->from('UserVisitor v')
                ->leftJoin('v.User u')
                ->where('v.user_id = ?', $this->_id)
                ->addWhere('u.id>0')
                ->orderBy('v.visit_at DESC');

        if (Request::$is_ajax == TRUE) {
            $view['visitor'] = $visitor->limit(9)->fetchArray();
            $view['title'] = '';
            echo View::factory('user_home/visitor', $view);
            exit;
        }

        $view['title'] = '访客纪录';
        $view['visitor'] = $visitor->fetchArray();

        $this->_title('访客记录');
        $this->_render('_body', $view);
    }

    function action_deny() {
        $this->_render('_body_left', null, 'global/nothing');
        $this->_render('_body_top', null, 'global/nothing');
        $this->_title('拒绝访问');
        $this->_render('_body');
    }

    //ucenter flash face
    function action_avatar() {

        $error = null;
        $upload_path = Model_User::USER_AVATAR_DIR;    // 临时上传存放目录
        $temp_path = $upload_path . 'temp/';    // 临时上传存放目录
        $thumb_width = "128";      // 裁切后小图的初始宽度
        $thumb_height = "128";      // 裁切后小图的初始高度
        $thumb_image_path = ''; //裁剪后的图片路径
        $large_image_path = ''; //原图路径
        $upload_file_name = $this->_user_id . '.jpg';

        //如果是上传照片
        if ($_FILES) {
            $v = Validate::factory($_FILES);
            $v->rules('image', Model_Album::$up_rule);

            if (!$v->check()) {
                $view['error'] = $v->outputMsg($v->errors('validate'));
            } else {

                Upload::save($_FILES['image'], $upload_file_name, $temp_path);

                $file = Image::factory($temp_path . $upload_file_name);
                $w = $file->width;
                $h = $file->height;

                if ($w > 550) {
                    $thumb_file = Image::factory($temp_path . $upload_file_name)
                            ->resize(550, 550)
                            ->save($temp_path . $upload_file_name);
                    //获取缩小后的原图尺寸
                    $file = Image::factory($temp_path . $upload_file_name);
                    $w = $file->width;
                    $h = $file->height;
                } else {
                    $file->save($temp_path . $upload_file_name);
                }
                $large_image_path = $temp_path . $upload_file_name;
                $view['current_large_image_width'] = $w;
                $view['current_large_image_height'] = $h;
            }
        }

        $view['large_image_path'] = $large_image_path;
        $view['thumb_image_path'] = $thumb_image_path;

        //如果是保存裁剪图片
        if (isset($_POST["upload_thumbnail"])) {
            $x1 = $_POST["x1"];
            $y1 = $_POST["y1"];
            $x2 = $_POST["x2"];
            $y2 = $_POST["y2"];
            $w = $_POST["w"];
            $h = $_POST["h"];
            $scale = $thumb_width / $w;
            $large_image_path = Arr::get($_POST, 'large_image_path');
            $thumb_image_path = $upload_path . '128/' . $upload_file_name;
            //存在原文件
            if (file_exists($large_image_path)) {
                //裁剪128*128
                $cropped = $this->resizeThumbnailImage($thumb_image_path, $large_image_path, $w, $h, $x1, $y1, $scale);

                //生成48*48
                $thumb_file = Image::factory($cropped)
                        ->resize(48, 48)
                        ->save($upload_path . '48/' . $upload_file_name);

                $view['face48'] = $upload_path . '48/' . $upload_file_name;
                $view['face128'] = $upload_path . '128/' . $upload_file_name;

                //增加积分
                Db_User::updatePoint('upload_profile');

                $this->_redirect('user_home');

                @unlink($large_image_path);
            }
        }

        $view['error'] = $error;
        $view['upload_path'] = $upload_path;
        $view['thumb_width'] = $thumb_width;
        $view['thumb_height'] = $thumb_height;
        $view['rand'] = rand(10, 10000);
        $this->_render('_body', $view);
    }

    //裁切主函数
    function resizeThumbnailImage($thumb_image_name, $image, $width, $height, $start_width, $start_height, $scale) {
        list($imagewidth, $imageheight, $imageType) = getimagesize($image);
        $imageType = image_type_to_mime_type($imageType);

        $newImageWidth = ceil($width * $scale);
        $newImageHeight = ceil($height * $scale);
        $newImage = imagecreatetruecolor($newImageWidth, $newImageHeight);
        switch ($imageType) {
            case "image/gif":
                $source = imagecreatefromgif($image);
                break;
            case "image/pjpeg":
            case "image/jpeg":
            case "image/jpg":
                $source = imagecreatefromjpeg($image);
                break;
            case "image/png":
            case "image/x-png":
                $source = imagecreatefrompng($image);
                break;
        }
        imagecopyresampled($newImage, $source, 0, 0, $start_width, $start_height, $newImageWidth, $newImageHeight, $width, $height);
        switch ($imageType) {
            case "image/gif":
                imagegif($newImage, $thumb_image_name);
                break;
            case "image/pjpeg":
            case "image/jpeg":
            case "image/jpg":
                imagejpeg($newImage, $thumb_image_name, 90);
                break;
            case "image/png":
            case "image/x-png":
                imagepng($newImage, $thumb_image_name);
                break;
        }
        //chmod($thumb_image_name, 0777);
        return $thumb_image_name;
    }

    //我的最近一条记录
    function action_myBubble() {
        $this->auto_render = False;
        $bubble = Doctrine_Query::create()
                ->from('UserBubble')
                ->where('user_id = ?', $this->_id)
                ->orderBy('blow_at DESC')
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

        echo '最近更新：' . $bubble['content'] . '<span style="color: #999">&nbsp;约1秒前</span>';
    }

}
