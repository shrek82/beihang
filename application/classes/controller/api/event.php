<?php

//活动
class Controller_Api_Event extends Layout_Mobile {

    //获取新闻分类
    function action_category() {
        $category = Db_Event::getMobileCategory();
        $this->response($category, 'list', 'list');
    }

    //推荐活动
    function action_recommended() {
        $cat = $this->getParameter('cat', 'all');
        $limit = $this->getParameter('limit', 3);
        $recommend = null;

        //查询条件
        $condition = array();
        $condition['limit'] = $limit;
        $condition['page_size'] = $limit;
        $condition['cat'] = $cat;
        $condition['is_recommend'] = true;
        $condition['aa_info'] = true;

        //所有推荐活动从缓存读取
        if ($cat == 'all') {
            $recommend = $this->_cache->get('mobile_event_recommended');
            if (!$recommend) {
                $recommend = Db_Event::getEvents($condition);
                $this->_cache->set('mobile_event_recommended', $recommend, 600);
            }
        } else {
            $recommend = Db_Event::getEvents($condition);
        }

        $data = array();
        if (count($recommend) > 0) {
            foreach ($recommend AS $key => $e) {
                $data[$key]['id'] = $e['id'];
                $data[$key]['title'] = Db_Event::replaceTitle($e['title']);
                $data[$key]['image_path'] = $this->_siteurl . $e['poster_path'];
                $data[$key]['start_time'] = $e['start'];
                $data[$key]['finish_time'] = $e['finish'];
                $data[$key]['full_time'] = date('m月d日', strtotime($e['start'])) . ' ' . Date::getWeek($e['start']) . ' ' . date('H:i', strtotime($e['start'])) . '~' . date('H:i', strtotime($e['finish']));
                $data[$key]['address'] = $e['address'];
                $data[$key]['aa_name'] = $e['aa_name'];
                $data[$key]['tags'] = $e['tags'];
                $data[$key]['comments_count'] = $e['reply_num'];
                $data[$key]['create_date'] = $e['publish_at'];
            }
        }
        $this->response($data, 'list', null);
    }

    //获取活动列表
    function action_index() {

        $etype = Kohana::config('icon.etype');

        // 即将开始的活动
        $condition = array();
        $condition['limit'] = $this->getParameter('limit', 15);
        $condition['page_size'] = $condition['limit'];
        $condition['page'] = $this->getParameter('page', 1);
        $condition['cat'] = $this->getParameter('cat', 'all');
        $condition['q'] = $this->getParameter('q');
        $condition['aa_id'] = $this->getParameter('aa_id');
        $condition['offset'] = ($condition['page'] - 1) * $condition['limit'];
        $condition['max_id'] = $this->getParameter('max_id');
        $condition['since_id'] = $this->getParameter('since_id');
        $condition['get_sql'] = $this->getParameter('get_sql');
        //查询校友会信息
        $condition['aa_info'] = True;
        $condition['user_info'] = True;

        //默认活动首页从缓存读取
        if ($condition['cat'] == 'all' AND $condition['page'] == 1) {
            $events = $this->_cache->get('mobile_event_first_page1');
            if (!$events) {
                $events = Db_Event::getEvents($condition);
                $this->_cache->set('mobile_event_first_page1', $events, 600);
            }
        } else {
            $events = Db_Event::getEvents($condition);
        }

        $data = array();
        $data['column'] = '校友活动';
        $data['description'] = '近期活动列表';
        $format_events = array();
        foreach ($events AS $key => $e) {
            if (time() >= strtotime($e['start']) AND time() <= strtotime($e['finish'])) {
                $statuses = '进行中';
                $full_time = date('m月d日', strtotime($e['start'])) . ' ' . Date::getWeek($e['start']) . ' ' . date('H:i', strtotime($e['start'])) . '~' . date('H:i', strtotime($e['finish']));
            } elseif (time() <= strtotime($e['start'])) {
                $statuses = '报名中';
                $full_time = date('m月d日', strtotime($e['start'])) . ' ' . Date::getWeek($e['start']) . ' ' . date('H:i', strtotime($e['start'])) . '~' . date('H:i', strtotime($e['finish']));
            } else {
                $statuses = '已结束';
                $full_time = date('m月d日', strtotime($e['start'])) . ' ' . Date::getWeek($e['start']) . ' ' . date('H:i', strtotime($e['start'])) . '~' . date('H:i', strtotime($e['finish']));
            }

            if ($e['type'] AND isset($etype['icons'][$e['type']])) {
                $thumbnail_pic = $this->_siteurl . $etype['url'] . $etype['icons'][$e['type']];
            } else {
                $thumbnail_pic = $this->_siteurl . $etype['url'] . 'undefined.png';
            }

            $format_events[$key]['id'] = $e['id'];
            $format_events[$key]['title'] = Db_Event::replaceTitle($e['title']);
            $format_events[$key]['start_time'] = $e['start'];
            $format_events[$key]['finish_time'] = $e['finish'];
            $format_events[$key]['full_time'] = $full_time;
            $format_events[$key]['address'] = $e['address'];
            $format_events[$key]['quota'] = $e['sign_limit'] ? $e['sign_limit'] . '人' : '不限';
            $format_events[$key]['aa_id'] = $e['aa_id'];
            $format_events[$key]['aa_name'] = $e['aa_name'];
            $format_events[$key]['organiger_uid'] = $e['user_id'];
            $format_events[$key]['organiger'] = $e['realname'];
            $format_events[$key]['sign_count'] = $e['sign_num'] ? $e['sign_num'] : 0;
            $format_events[$key]['comments_count'] = $e['reply_num'];
            $format_events[$key]['hits'] = 56;
            $format_events[$key]['is_vcert'] = $e['is_vcert'] ? 'true' : 'false';
            $format_events[$key]['is_fixed'] = $e['is_fixed'] ? 'true' : 'false';
            $format_events[$key]['allow_comment'] = 'true';
            $format_events[$key]['thumbnail_pic'] = $thumbnail_pic;
            $format_events[$key]['tags'] = $e['tags'];
            $format_events[$key]['create_date'] = $e['publish_at'];
            $format_events[$key]['browser_url'] = $this->_siteurl . '/event/view?id=' . $e['id'];
            $format_events[$key]['statuses'] = $statuses;
        }
        $data['list'] = $format_events;

        $this->response($data, null, null);
    }

    /**
      +------------------------------------------------------------------------------
     * 查看活动详情
      +------------------------------------------------------------------------------
     */
    function action_view() {

        $id = $this->getParameter('id');

        //报名用户信息
        $signs_limit = $this->getParameter('signs_limit', 7);
        //获取最近几条评论
        $comments_limit = $this->getParameter('comments_limit', 5);
        //活动相册信息
        $album_photos_limit = $this->getParameter('album_photos_limit', 0);

        $id = $this->getParameter('id');

        $etype = Kohana::config('icon.etype');

        # 活动详细说明
        $event = Doctrine_Query::create()
                ->select('e.*')
                ->addSelect('a.id AS aa_id,a.name AS aa_name')
                ->addSelect('c.id AS club_id,c.name AS club_name')
                ->addSelect('u.id as uid,u.realname AS realname')
                ->addSelect('(SELECT SUM(ss.num) FROM EventSign ss WHERE ss.event_id = e.id) AS sign_num')
                ->from('Event e')
                ->leftJoin('e.Aa a')
                ->leftJoin('e.Club c')
                ->leftJoin('e.User u')
                ->where('e.id=?', $id)
                ->addWhere('e.is_closed = ? OR e.is_closed IS NULL', FALSE)
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

        if (!$event) {
            $this->error('活动不存在或已经');
        }

        $pics = array();
        $data = array();

        //分类图标
        if ($event['type'] AND isset($etype['icons'][$event['type']])) {
            $thumbnail_pic = $this->_siteurl . $etype['url'] . $etype['icons'][$event['type']];
        } else {
            $thumbnail_pic = $this->_siteurl . $etype['url'] . 'undefined.png';
        }

        $data['id'] = $event['id'];
        $data['aa_id'] = $event['aa_id'];
        $data['club_id'] = $event['club_id'];
        $data['title'] = $event['title'];
        $data['address'] = $event['address'];
        $data['start_time'] = $event['start'];
        $data['finish_time'] = $event['finish'];
        if (date('Y-m-d H:i', strtotime($event['start'])) == date('Y-m-d H:i', strtotime($event['start']))) {
            $data['full_time'] = date('Y年m月d日', strtotime($event['start'])) . '' . date('H:i', strtotime($event['start'])) . '~' . date('H:i', strtotime($event['finish']));
        } else {
            $data['full_time'] = date('Y年m月d日 H:i', strtotime($event['start'])) . '~' . date('m月d日 H:i', strtotime($event['finish']));
        }
        $data['address'] = $event['address'];
        $data['quota'] = $event['sign_limit'] ? $event['sign_limit'] . '人' : '不限';
        $data['aa_id'] = $event['aa_id'] ? $event['aa_id'] : '校友总会';
        $data['aa_name'] = isset($event['aa_name']) ? $event['aa_name'] : '校友总会';
        $data['organiger_uid'] = $event['uid'];
        $data['organiger'] = $event['realname'];
        $data['sign_count'] = $event['sign_num'] ? $event['sign_num'] : 0;
        $data['comments_count'] = $event['comments_num'] ? $event['comments_num'] : 0;
        $data['interested_count'] = $event['interested_num'] ? $event['interested_num'] : 0;
        $data['hits'] = 56;
        $data['is_vcert'] = $event['is_vcert'] ? 'true' : 'false';
        $data['is_fixed'] = $event['is_fixed'] ? 'true' : 'false';
        $data['is_recommended'] = $event['is_recommended'] ? 'true' : 'false';
        $data['allow_comment'] = 'true';
        $data['thumbnail_pic'] = $thumbnail_pic;
        $alubm = Doctrine_Query::create()
                ->select('id,name')
                ->from('Album')
                ->where('event_id=?', $id)
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
        $data['album_id'] = $alubm ? $alubm['id'] : 1564;
        $data['tags'] = $event['tags'];
        $data['create_date'] = $event['publish_at'];
        $data['browser_url'] = $this->_siteurl . '/event/view?id=' . $event['id'];
        $data['statuses'] = $this->getStatuses($event['start'], $event['finish']);
        $data['statuses_text'] = strip_tags(Model_Event::status($event));
        //活动概述
        $content = str_replace('&nbsp;', '', strip_tags($event['content']));
        $content = Text::limit_chars(trim($content), 150, '...');
        $content = str_replace('&#8230;', '', $content);
        $data['intro'] = $content;
        $data['user'] = array();
        $data['content'] = '';
        $data['pics'] = '';

        //当前登录校友报名信息
        if ($this->_uid) {
            $data['user']['uid'] = $this->_user['id'];
            $data['user']['realname'] = $this->_user['realname'];
            if (Model_Event::isJoined($event['id'], $this->_uid)) {
                $data['user']['is_signed'] = 'true';
            } else {
                $data['user']['is_signed'] = 'false';
            }
        } else {
            $data['user'] = '';
        }

        //活动评论
        if ($comments_limit > 0) {
            $last_comments = Db_Comment::get(array('event_id' => $id, 'order' => 'DESC', 'limit' => $comments_limit));
            $last_comments = $last_comments['comments'];
            if (count($last_comments) > 0) {
                $comment = array();
                foreach ($last_comments AS $key => $c) {
                    if ($c['sex'] == '女') {
                        $online_icon = isset($c['online']) ? $this->_siteurl . '/static/images/online1.gif' : $this->_siteurl . '/static/images/online0.gif';
                    } else {
                        $online_icon = isset($c['online']) ? $this->_siteurl . '/static/images/online1.gif' : $this->_siteurl . '/static/images/online0.gif';
                    }
                    $comment[$key]['id'] = $c['id'];
                    $comment[$key]['uid'] = $c['user_id'];
                    $comment[$key]['user']['id'] = $c['user_id'];
                    $comment[$key]['user']['realname'] = $c['realname'];
                    $comment[$key]['user']['sex'] = $c['sex'];
                    $comment[$key]['user']['online'] = isset($c['online']) ? 'ture' : 'false';
                    $comment[$key]['user']['online_icon'] = $online_icon;
                    $comment[$key]['user']['speciality'] = $c['start_year'] && $c['speciality'] ? $c['start_year'] . '级' . $c['speciality'] : $c['speciality'];
                    $comment[$key]['user']['profile_image_url'] = $this->_siteurl . Model_User::avatar($c['user_id'], 48, $c['sex']);
                    $comment[$key]['user']['avatar_large'] = $this->_siteurl . Model_User::avatar($c['user_id'], 128, $c['sex']);
                    unset($comment[$key]['user']['start_year']);
                    $comment[$key]['create_date'] = Date::ueTime($c['post_at']);
                    $comment[$key]['str_create_date'] = Date::ueTime($c['post_at']);
                    $cmt_content = str_replace('&nbsp;', '', $c['content']);
                    $comment[$key]['content'] = Text::limit_chars(trim(strip_tags($cmt_content), 150));
                    $comment[$key]['clients'] = $c['clients'];
                    $comment[$key]['quotes'] = array();
                    $comment[$key]['pics'] = '';
                }
                //数组倒序
                $data['comments'] = array_reverse($comment);
            } else {
                $data['comments'] = '';
            }
        } else {
            $data['comments'] = '';
        }


        //活动报名信息
        if ($signs_limit) {
            $signs = $this->getSigns($event['id'], $signs_limit);
            $data['signs'] = $signs;
        } else {
            $datat['signs'] = null;
        }

        //互动照片信息
        if ($album_photos_limit) {
            $album = $this->getPhotos($event['id'], $album_photos_limit);
            $data['album'] = $album;
        } else {
            $data['album'] = null;
        }

        $this->response($data, null, null);
    }

    function action_details() {

        $id = $this->getParameter('id');

        # 活动详细说明
        $event = Doctrine_Query::create()
                ->select('e.*')
                ->addSelect('a.id AS aa_id,a.name AS aa_name')
                ->addSelect('c.id AS club_id,c.name AS club_name')
                ->addSelect('u.id as uid,u.realname AS realname')
                ->addSelect('(SELECT SUM(ss.num) FROM EventSign ss WHERE ss.event_id = e.id) AS sign_num')
                ->from('Event e')
                ->leftJoin('e.Aa a')
                ->leftJoin('e.Club c')
                ->leftJoin('e.User u')
                ->where('e.id=?', $id)
                ->addWhere('e.is_closed = ? OR e.is_closed IS NULL', FALSE)
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

        if (!$event) {
            $this->error('活动不存在或已经');
        }

        $htmlAndPics = Common_Global::standardHtmlAndPics($event['content']);
        $content = $htmlAndPics['html'];
        $pics = $htmlAndPics['pics'];
        $data = array();
        $data['id'] = $event['id'];
        $data['content'] = View::factory('api/event/details', array('event' => $event, 'content' => $content));
        $data['comments_count'] = $event['comments_num'] ? $event['comments_num'] : 0;
        $data['allow_comment'] = 'true';
        $data['pics'] = $pics;
        //echo $data['content'];
        $this->response($data, null, null);
    }

    //适应手机浏览器的详情页面
    function action_html() {

        $id = $this->getParameter('id');

        # 活动详细说明
        $event = Doctrine_Query::create()
                ->select('e.*')
                ->addSelect('a.id AS aa_id,a.name AS aa_name')
                ->addSelect('c.id AS club_id,c.name AS club_name')
                ->addSelect('u.id as uid,u.realname AS realname')
                ->addSelect('(SELECT SUM(ss.num) FROM EventSign ss WHERE ss.event_id = e.id) AS sign_num')
                ->from('Event e')
                ->leftJoin('e.Aa a')
                ->leftJoin('e.Club c')
                ->leftJoin('e.User u')
                ->where('e.id=?', $id)
                ->addWhere('e.is_closed = ? OR e.is_closed IS NULL', FALSE)
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

        if (!$event) {
            $this->error('活动不存在或已经');
        }

        $htmlAndPics = Common_Global::standardHtmlAndPics($event['content']);
        $event['content'] = $htmlAndPics['html'];
        $view['pic']=$htmlAndPics['pics'];
        $view['event']=$event;
        echo View::factory('api/event/html', $view);
    }

    //获取报名用户
    public function action_signs() {
        $id = $this->getParameter('id');
        $max_id = $this->getParameter('max_id');
        $since_id = $this->getParameter('since_id');
        $limit = $this->getParameter('limit', 15);
        $page = $this->getParameter('page', 1);
        $signs = $this->getSigns($id, $limit, $max_id, $since_id, $page);
        $this->response($signs, 'list', null);
    }

    //获取报名用户
    public function action_photos() {
        $id = $this->getParameter('id');
        $max_id = $this->getParameter('max_id');
        $since_id = $this->getParameter('since_id');
        $limit = $this->getParameter('limit', 15);
        $page = $this->getParameter('page', 1);
        $photos = $this->getPhotos($id, $limit, $max_id, $since_id, $page);
        $this->response($photos, 'list', null);
    }

    //获取报名信息
    public function getSigns($event_id, $limit = 15, $max_id = null, $since_id = null, $page = 1) {
        $offset = ($page - 1) * $limit;
        $signs = Doctrine_Query::create()
                ->select('s.id AS sid,s.user_id,s.sign_at,s.is_anonymous,u.realname AS realname,u.sex AS sex,speciality AS speciality,start_year AS start_year,u.point AS point')
                ->from('EventSign s')
                ->leftJoin('s.User u')
                ->where('event_id = ?', $event_id);

        if ($since_id) {
            $signs->addWhere('s.id>?', $since_id);
        }

        if ($max_id) {
            $signs->addWhere('s.id<?', $max_id);
        }

        $order_by = $limit >= 15 ? 's.id ASC' : 's.id DESC';
        $signs = $signs->orderBy($order_by)
                ->offset($offset)
                ->limit($limit)
                ->fetchArray();

        foreach ($signs AS $key => $s) {
            $signs[$key]['sid'] = $s['id'];
            $signs[$key]['uid'] = !$s['is_anonymous'] ? $s['user_id'] : -1;
            $signs[$key]['sign_date'] = $s['sign_at'];
            $signs[$key]['str_sign_date'] = Date::ueTime($s['sign_at']);

            if ($s['is_anonymous']) {
                $signs[$key]['realname']='匿名';
                $signs[$key]['speciality'] =$s['start_year']? $s['start_year'] . '级':'';
                $signs[$key]['profile_image_url'] = $this->_siteurl . Model_User::avatar(0, 48, $s['sex']);
                $signs[$key]['avatar_large'] = $this->_siteurl . Model_User::avatar(0, 128, $s['sex']);
            } else {
                $signs[$key]['speciality'] = $s['start_year'] && $s['speciality'] ? $s['start_year'] . '级' . $s['speciality'] : $s['speciality'];
                $signs[$key]['profile_image_url'] = $this->_siteurl . Model_User::avatar($s['user_id'], 48, $s['sex']);
                $signs[$key]['avatar_large'] = $this->_siteurl . Model_User::avatar($s['user_id'], 128, $s['sex']);
            }
            unset($signs[$key]['sign_at']);
            unset($signs[$key]['id']);
            unset($signs[$key]['user_id']);
            unset($signs[$key]['start_year']);
        }
        return $signs;
    }

    //获取照片信息
    public function getPhotos($event_id, $limit = 15, $max_id = null, $since_id = null, $page = 1) {
        $offset = ($page - 1) * $limit;
        $photos = null;

        $album = Doctrine_Query::create()
                ->select('*')
                ->from('Album')
                ->where('event_id=?', $event_id)
                ->limit(1)
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

        if (!$album) {
            return false;
        }

        //活动照片
        $photos = Doctrine_Query::create()
                ->select('p.*,u.realname AS realname,u.sex AS sex,speciality AS speciality,start_year AS start_year,u.point AS point')
                ->from('Pic p')
                ->where('p.album_id=?', $album['id']);

        if ($since_id) {
            $signs->addWhere('p.id>?', $since_id);
        }

        if ($max_id) {
            $signs->addWhere('p.id<?', $max_id);
        }

        $photos = $photos->leftJoin('p.User u')
                ->orderBy('p.id DESC')
                ->offset($offset)
                ->limit($limit)
                ->fetchArray();

        if ($photos) {
            foreach ($photos AS $key => $p) {
                $photos[$key]['uid'] = $p['user_id'];
                $photos[$key]['speciality'] = $p['start_year'] && $p['speciality'] ? $p['start_year'] . '级' . $p['speciality'] : $p['speciality'];
                $photos[$key]['upload_date'] = $p['upload_at'];
                $photos[$key]['str_upload_date'] = Date::ueTime($p['upload_at']);
                $photos[$key]['hits'] = $photos[$key]['id'];
                $photos[$key]['comments_count'] = $photos[$key]['comments_num'];
                $photos[$key]['thumbnail_pic'] = $this->_siteurl . '/' . $p['img_path'];
                $photos[$key]['bmiddle_pic'] = $this->_siteurl . '/' . str_replace('resize/', '', $p['img_path']);
                $photos[$key]['original_pic'] = $photos[$key]['bmiddle_pic'];
                unset($photos[$key]['upload_at']);
                unset($photos[$key]['comments_num']);
                unset($photos[$key]['user_id']);
                unset($photos[$key]['img_path']);
                unset($photos[$key]['is_release']);
                unset($photos[$key]['start_year']);
            }
        }

        return $photos;
    }

    //活动报名
    function action_sign() {

        $this->checkLogin();

        $event_id = $this->getParameter('id');

        //创建报名模型
        $eventSign = new Model_Eventsign($event_id, $this->_uid, 'add');

        //传递表单数据
        $eventSign->postData['event_id'] = $event_id;
        $eventSign->postData['num'] = $this->getParameter('number_applicants', 1);
        $eventSign->postData['remarks'] = $this->getParameter('remarks', '来自' . $this->_clients);
        $eventSign->postData['clients'] = $this->_clients;

        //根据sign_action新添加或修改
        $post_status = $eventSign->signSub();

        // 执行报名或修改操作
        if ($post_status) {
            $back = array();
            $back['uid'] = $this->_uid;
            $back['event_id'] = $event_id;
            $back['sign_date'] = date('Y-m-d H:i:s');
            $this->response($back, 'success', 'success');
        }
        //报名或修改失败
        else {
            $this->error($eventSign->getError());
        }
    }

    //取消报名
    function action_cancelSign() {
        $this->checkLogin();
        $event_id = $this->getParameter('id');
        $sign = Doctrine_Query::create()
                ->from('EventSign s')
                ->where('s.event_id = ?', $event_id)
                ->addWhere('s.user_id=?', $this->_uid)
                ->fetchOne();
        if ($sign) {
            $sign->delete();
            $back = array();
            $back['uid'] = $this->_uid;
            $back['event_id'] = $event_id;
            $back['cancel_date'] = date('Y-m-d H:i:s');
            $this->response($back, 'success', 'success');
        } else {
            $this->error('尚未参加活动或已经取消活动报名');
        }
    }

    //判断活动状态
    function getStatuses($start_time, $finish_time) {
        $time = time();
        if ($time >= $start_time AND $time <= $finish_time) {
            return '进行中';
        } elseif ($time <= $start_time) {
            return '报名中';
        } else {
            return '已结束';
        }
    }

}

?>
