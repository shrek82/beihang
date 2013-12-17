<?php

class Controller_Aa_Home extends Layout_Aa {

    /**
      +------------------------------------------------------------------------------
     * 地方校友会首页
      +------------------------------------------------------------------------------
     */
    function action_index() {

        // 图片链接展示
        $view['banner'] = DB::select()
                ->from(array('banner', 'b'))
                ->where('b.aa_id', '=', $this->_id)
                ->where('b.format', '=', 'banner')
                ->where('b.is_display', '=', 1)
                ->order_by('b.order_num', 'ASC')
                ->limit($this->_theme['banner_limit'])
                ->execute()
                ->as_array();

        //友情链接logo
        $view['logo'] = DB::select()
                ->from(array('banner', 'b'))
                ->where('b.aa_id', '=', $this->_id)
                ->where('b.format', '=', 'logo')
                ->where('b.is_display', '=', 1)
                ->order_by('b.order_num', 'ASC')
                ->limit(10)
                ->execute()
                ->as_array();

        //推荐展示
        $view['sidebar_rec'] = DB::select()
                ->from(array('banner', 'b'))
                ->where('b.aa_id', '=', $this->_id)
                ->where('b.format', '=', 'rec')
                ->where('b.is_display', '=', 1)
                ->order_by('b.order_num', 'ASC')
                ->limit(5)
                ->execute()
                ->as_array();

        //新闻列表
        $view['news'] = Model_News::get(array('aa_id' => $this->_id, 'limit' => $this->_theme['news_limit']));

        //新鲜事
        $view['weibo'] = DB::select(DB::expr('c.*,u.realname AS realname,u.sex AS sex'))
                ->from(array('weibo_content', 'c'))
                ->join(array('user', 'u'))
                ->on('u.id', '=', 'c.user_id')
                ->where('c.aa_id', 'IN', array(0, $this->_id))
                ->order_by('c.id', 'DESC')
                ->limit($this->_theme['weibo_limit'])
                ->cached(300)
                ->execute()
                ->as_array();

        //微博热门话题
        $view['hot_topics'] = DB::select()
                ->from('weibo_topics')
                ->order_by('num', 'DESC')
                ->limit(6)
                ->execute()
                ->as_array();

        //热门活动标签
        $view['host_event_tags'] = DB::select()
                ->from('event_tags')
                ->order_by('num', 'DESC')
                ->limit(12)
                ->cached(300)
                ->execute()
                ->as_array();

        //主要校友会活动
        if ($this->_id == 1) {
            $view['clubs'] = DB::select(DB::expr('c.id,c.aa_id,c.name,c.logo_path'))
                            ->select(DB::expr('(SELECT COUNT(m.id) FROM club_member m WHERE m.club_id = c.id) AS members_num'))
                            ->from(array('club', 'c'))
                            ->where('c.aa_id', '=', $this->_id)
                            ->limit(9)
                            ->order_by('order_num', 'ASC')
                            ->execute()->as_array();
        }

        // 即将开始的活动
        $condition = array('aa_id' => $this->_id, 'limit' => $this->_theme['event_limit']);
        $view['event'] = Db_Event::getEvents($condition);
        //近期活动标签
        $near_event_tags = array();
        if ($view['event'] AND $this->_id == 1) {
            $all_tags = array();
            $all_tags_num = array();
            foreach ($view['event'] as $e) {
                if (strtotime($e['start']) >= time()) {
                    $tags = explode(',', str_replace(' ', ',', trim($e['tags'])));
                    if ($tags) {
                        foreach ($tags AS $key => $tag) {
                            if (trim($tag)) {
                                $is_has = array_search($tag, $all_tags);
                                if ($is_has === 0 OR $is_has > 0) {
                                    $all_tags_num[$is_has]+=1;
                                } elseif ($key == 0) {
                                    $all_tags[] = $tag;
                                    $all_tags_num[] = 1;
                                    break;
                                }
                            }
                        }
                    }
                }
            }
            $near_event_tags = array();
            foreach ($all_tags as $key => $t) {
                $near_event_tags[] = array('name' => $t, 'num' => $all_tags_num[$key]);
            }
        }
        $view['near_event_tags'] = $near_event_tags;

        //从论坛缓存获取该组织最新话题
        $condition = array('aa_id' => $this->_id, 'auto_save_cache' => false, 'page_size' => $this->_theme['bbsunit_limit'], 'replyname' => false, 'count_total' => false);
        $query_data = Db_Bbs::getUnits($condition);
        $units = $query_data['units'];

        $view['units'] = $units;

        // 其他信息列表
        $info = Doctrine_Query::create()
                ->select('title, aa_id')
                ->from('AaInfo')
                ->where('aa_id = ?', $this->_id)
                ->addWhere('is_public = ?', True)
                ->orderBy('order_num ASC');
        $view['infos'] = $info->useResultCache(true, 600, 'aa_home_infos_' . $this->_id)
                ->fetchArray();


        //最近访问
        $view['visit_members'] = DB::select(DB::expr('am.visit_at,am.user_id, am.join_at,u.realname AS realname,u.sex AS sex'))
                ->from(array('aa_member', 'am'))
                ->join(array('user', 'u'))
                ->on('u.id', '=', 'am.user_id')
                ->where('am.aa_id', '=', (int) $this->_id)
                ->order_by('am.visit_at', 'DESC')
                ->limit(9)
                ->execute()
                ->as_array();

        // 最近加入的成员
        $view['member'] = null;

        //最新照片
        $condition = array('aa_id' => $this->_id, 'page_size' => 4);
        $album_data = Db_Album::getAlbums($condition);
        $view['albums'] = $album_data['albums'];
        $this->_render('_body', $view);
    }

    # 校友会相关信息

    function action_info() {
        $info_id = Arr::get($_GET, 'info_id');

        // 其他信息列表
        $info = Doctrine_Query::create()
                ->select('title, aa_id')
                ->from('AaInfo')
                ->where('aa_id = ?', $this->_id)
                ->orderBy('order_num ASC');
        $view['infos'] = $info->fetchArray();

        if (!$info_id) { // 显示简介
            $view['info']['title'] = $this->_aa['name'] . '简介';
            $view['info']['content'] = $this->_aa['intro'];
        } else {
            $view['info'] = Doctrine_Query::create()
                    ->from('AaInfo inf')
                    ->where('inf.aa_id = ?', $this->_id)
                    ->addWhere('id=?', $info_id)
                    ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
        }

        if (!$view['info']) {
            $this->request->redirect('aa_home?id=' . $this->_id);
        }

        $this->_title($view['info']['title']);
        $this->_render('_body', $view);
    }

    # 退出校友会

    function action_leave() {
        if ($this->_member) {
            $this->_member->delete();
            echo '成功退出';
            exit;
        }
    }

    # 加入校友会

    function action_join() {
        $user_id = $this->_sess->get('id');
        $aa_id = $this->_id;

        if (!$user_id) {
            echo '先登录网站后再进行申请操作。';
        } else {
            if ($this->_member) {
                echo '您已经是本会成员，不需要再申请了。';
                exit;
            } else {

                // 已经申请了加入
                $apply = Doctrine_Query::create()
                        ->from('JoinApply')
                        ->where('aa_id = ?', $aa_id)
                        ->andWhere('user_id = ?', $user_id)
                        ->fetchOne();

                echo View::factory('aa_home/join_apply', compact('apply', 'aa_id'));
            }
        }
    }

    //活动列表
    function action_event() {
        $type = Arr::get($_GET, 'type');
        $list = Arr::get($_GET, 'list');

        # tag
        $tag = urldecode(Arr::get($_GET, 'tag'));
        $view['tag'] = $tag;

        //我参加的活动
        $join = Arr::get($_GET, 'join');
        $view['join'] = $join;

        //我加入的校友会ids
        if ($join AND $this->_uid) {
            $event_signed_ids = Model_Event::joinIDs($this->_uid);
            $aa_event_signed_ids = Doctrine_Query::create()
                    ->select('e.id')
                    ->from('Event e')
                    ->whereIn('e.id', $event_signed_ids)
                    ->addWhere('e.aa_id = ?', $this->_id)
                    ->limit(150)
                    ->orderBy('e.start DESC')
                    ->execute(array(), Doctrine_Core::HYDRATE_SINGLE_SCALAR);

            if (count($aa_event_signed_ids) == 0) {
                $aa_event_signed_ids = array(0);
            }
        }

        //活动
        $event = Doctrine_Query::create()
                ->select('e.id,e.title,e.start,e.finish,e.address,e.type,e.aa_id,e.club_id,e.is_fixed,e.small_img_path,e.custom_icon')
                ->addSelect('(SELECT SUM(ss.num) FROM EventSign ss WHERE ss.event_id = e.id) AS sign_num')
                ->addSelect('e.comments_num AS cmt_num')
                ->addSelect('IF(e.finish >= now(),TIMESTAMPDIFF(MINUTE, now(), e.start),900000) AS can_sign')
                ->from('Event e')
                ->where('e.aa_id =' . $this->_id)
                ->andWhere('e.is_closed = ? OR e.is_closed IS NULL', FALSE);

        if ($join AND $this->_uid) {
            $event->whereIn('e.id', $aa_event_signed_ids);
        }

        if ($tag) {
            $event->andWhere('(e.title LIKE ? OR e.tags LIKE ?)', array('%' . $tag . '%', '%' . $tag . '%'));
        }

        if ($list == 'aa' AND $this->_uid > 0) {
            $event->whereIn('e.aa_id', Model_User::aaIds($this->_uid));
        }

        if ($list == 'joined' AND $this->_uid > 0) {
            $event->whereIn('e.id', Model_Event::joinIDs($this->_uid));
        }

        if ($list) {
            switch ($list) {
                case 'week':
                    $time_span = time() + Date::WEEK;
                    $event->addWhere('UNIX_TIMESTAMP(e.start)>UNIX_TIMESTAMP() AND UNIX_TIMESTAMP(e.start)<=' . $time_span);
                    break;
                case 'today':
                    $event->addWhere('TIMESTAMPDIFF(DAY, now(), e.start)=0');
                    break;
                case 'weeken':
                    $event->addWhere('DAYOFWEEK(e.start)=1 OR DAYOFWEEK(e.start)=7');
                    break;
                default :
                    break;
            }
        }


        $event->orderBy('is_fixed DESC,can_sign ASC,e.start DESC');

        $total_events = $event->count();

        $pager = Pagination::factory(array(
                    'total_items' => $total_events,
                    'items_per_page' => 10,
                    'view' => 'pager/common',
        ));

        $events = $event->offset($pager->offset)
                ->limit($pager->items_per_page)
                ->fetchArray();

        $view['type'] = $type;
        $view['list'] = $list;
        $view['events'] = $events;
        $view['pager'] = $pager;

        #热门标签
        $view['tags'] = Doctrine_Query::create()
                ->select('id,name,num, RANDOM() AS rand')
                ->from('EventTags')
                ->limit('30')
                ->orderBy('num DESC')
                ->fetchArray();

        $this->_title('校友会的活动');
        $this->_render('_body', $view);
    }

    //活动详细页面
    function action_eventview() {

        $this->_custom_media('<script type="text/javascript" src="/static/My97DatePicker/WdatePicker.js"></script><script type="text/javascript" src="/static/js/event.js"></script>');

        $eid = Arr::get($_GET, 'eid');

        //活动详细页面数据
        $condition = array(
            'event_id' => $eid,
            'aa_info' => True,
            'club_info' => True,
            'album' => True,
            'photos' => True,
            'signs' => True,
            'organiger_info' => True,
            'user_sign_status' => True,
            'bbs_unit_id' => True,
            'permission' => True,
            'sign_category' => True
        );

        $view['event_data'] = Db_Event::getEventViewData($condition);

        //活动不存在
        if (!$view['event_data']) {
            $this->request->redirect('main/notFound');
            exit;
        }
        $this->_title($view['event_data']['event']['title']);
        $this->_render('_body', $view);
    }

    // 最新的一些话题
    function action_bbs() {
        $bbs_ids = Model_Bbs::getIDs(array('aa_id' => $this->_id));
        $bbs_id = Arr::get($_GET, 'bbs_id');
        $q = Arr::get($_GET, 'q');

        $ids = array_keys($bbs_ids);
        if (count($ids) == 0) {
            $ids = array(0);
        }

        $bbs_unit = Doctrine_Query::create()
                ->select('u.*, b.name, s.realname')
                ->from('BbsUnit u')
                ->leftJoin('u.Bbs b')
                ->leftJoin('u.User s')
                ->whereIn('u.bbs_id', $ids)
                ->orderBy('u.is_fixed DESC,
                                    case when u.comment_at IS NOT NULL then u.comment_at
                                         when u.comment_at IS NULL then u.create_at
                                    end DESC');

        if ($q) {
            $bbs_unit->andWhere('u.title LIKE ?', '%' . $q . '%');
        }

        if ($bbs_id) {
            $bbs_unit->andWhere('u.bbs_id = ?', $bbs_id);
        }

        $total_units = $bbs_unit->count();

        $pager = Pagination::factory(array(
                    'total_items' => $total_units,
                    'items_per_page' => 20,
                    'view' => 'pager/common',
        ));

        $units = $bbs_unit->limit($pager->items_per_page)
                ->offset($pager->offset)
                ->fetchArray();

        $unit_list = View::factory('inc/bbs/unit_list', compact('units'));

        $this->_title('交流区');
        $this->_render('_body', compact('unit_list', 'bbs_ids', 'pager', 'q', 'bbs_id'));
    }

    // 校友会相册
    function action_album() {
        $list = Arr::get($_GET, 'list', 'new');
        $empty_albums = $list == 'all' ? True : False;
        $view['list'] = $list;
        $page = Arr::get($_GET, 'page', 1);
        $q = Arr::get($_POST, 'q');
        $view['q'] = $q;
        $condition = array('empty_albums' => $empty_albums, 'aa_id' => $this->_id, 'page_size' => 16, 'count' => true, 'page' => $page, 'list' => $list, 'q' => $q);
        $album_data = Db_Album::getAlbums($condition);
        $view['albums'] = $album_data['albums'];
        $view['pager'] = $album_data['pager'];
        $this->_title('相册');
        $this->_render('_body', $view);
    }

    //校友会新闻
    function action_news() {
        $category_id = Arr::get($_GET, 'category', 0);

        $news = Doctrine_Query::create()
                ->select('n.title,n.create_at,n.hit,n.category_id,n.author_name,n.user_id,c.name,u.realname,n.is_fixed,c.name AS category_name')
                ->from('News n')
                ->leftJoin('n.NewsCategory c')
                ->leftJoin('n.User u')
                ->where('n.is_release = ?', TRUE)
                ->andWhere('c.aa_id = ?', $this->_id)
                ->orderBy('n.is_fixed DESC, n.create_at DESC');

        if ($category_id > 0) {
            $news->andWhere('c.id = ?', $category_id);
        }

        $total_news = $news->count();

        $pager = Pagination::factory(array(
                    'total_items' => $total_news,
                    'view' => 'pager/common',
        ));

        $news = $news->offset($pager->offset)
                ->limit(15)
                ->fetchArray();

        $category = Doctrine_Query::create()
                ->from('NewsCategory')
                ->where('aa_id = ?', $this->_id)
                ->andWhere('is_public = ?', TRUE)
                ->orderBy('order_num ASC,id ASC')
                ->fetchArray();

        $this->_title('新闻');
        $this->_render('_body', compact('news', 'pager', 'category_id', 'category'));
    }

    /**
      +------------------------------------------------------------------------------
     * 新闻浏览
      +------------------------------------------------------------------------------
     */
    function action_newsDetail() {
        $this->userPermissions('newsView');
        $nid = Arr::get($_GET, 'nid');
        $news_category = '';
        $aa_info = '';

        $news = Doctrine_Query::create()
                ->from('News')
                ->where('id = ?', $nid)
                ->fetchOne();

        //未找到新闻
        if (!$news) {
            $this->request->redirect('main/notFound');
        }

        $view['news'] = $news;

        Model_News::hit($nid);

        //如果需要跳转至其他网页
        if ($news['redirect']) {
            $this->_redirect($news['redirect']);
            exit;
        }

        //相关
        $view['relate'] = Model_News::relate($news, 10, $this->_id);

        $this->_title($news['title']);
        $this->_render('_body', $view);
    }

    function action_member() {
        // 排序参数
        $orderby = Arr::get($_GET, 'orderby', 'visit');
        $q = Arr::get($_POST, 'q');

        $member = Doctrine_Query::create()
                ->select('am.user_id,am.user_id,am.join_at,am.visit_at,am.join_at,u.realname,u.sex')
                ->from('AaMember am')
                ->leftJoin('am.User u')
                ->where('am.aa_id = ?', $this->_id)
                ->orderBy('am.' . $orderby . '_at DESC');

        if ($q) {
            $member->andWhere('u.realname LIKE ?', '%' . $q . '%');
        }

        $total_members = $member->count();

        $pager = Pagination::factory(array(
                    'total_items' => $total_members,
                    'items_per_page' => 32,
                    'view' => 'pager/common',
        ));

        $view['orderby'] = $orderby;
        $view['q'] = $q;
        $view['pager'] = $pager;
        $view['members'] = $member->offset($pager->offset)
                ->limit($pager->items_per_page)
                ->fetchArray();

        // 最近加入的成员
        $view['joinmember'] = Doctrine_Query::create()
                ->select('am.user_id, am.join_at,u.realname AS realname,u.sex AS sex')
                ->from('AaMember am')
                ->leftJoin('am.User u')
                ->where('am.aa_id = ?', $this->_id)
                ->orderBy('am.join_at DESC')
                ->limit(9)
                ->fetchArray();

        // 管理组成员
        $view['manager'] = Doctrine_Query::create()
                ->select('am.title, am.user_id,u.realname AS realname,u.sex AS sex')
                ->from('AaMember am')
                ->leftJoin('am.User u')
                ->where('am.aa_id = ?', $this->_id)
                ->andWhere('am.manager=?', True)
                ->fetchArray();

        $this->_title($this->_aa['name'] . '成员');
        $this->_render('_body', $view);
    }

    function action_club() {
        $type = Arr::get($_GET, 'type');

        $club = Doctrine_Query::create()
                ->select('b.*, (SELECT COUNT(m.id) FROM ClubMember m WHERE m.club_id = b.id) AS member_num')
                ->from('Club b')
                ->where('b.aa_id = ?', $this->_id);

        if ($type) {
            $club->andWhere('b.type = ?', $type);
        }

        $total_club = $club->count();

        $pager = Pagination::factory(array(
                    'total_items' => $total_club,
                    'items_per_page' => 40,
                    'view' => 'pager/common',
        ));

        $view['pager'] = $pager;
        $view['clubs'] = $club->offset($pager->offset)
                ->limit($pager->items_per_page)
                ->orderBy('b.order_num ASC')
                ->fetchArray();

        $club_ids = array();
        foreach ($view['clubs'] AS $c) {
            $club_ids[] = $c['id'];
        }
        if (count($club_ids) == 0) {
            $club_ids = array(0);
        }

        //最新活动
        $event = Doctrine_Query::create()
                ->select('e.id,e.title,e.start,e.finish,e.address,e.type,e.aa_id,e.club_id,e.is_fixed,e.sign_limit,e.small_img_path')
                ->addSelect('(SELECT COUNT(ss.id) FROM EventSign ss WHERE ss.event_id = e.id) AS sign_num')
                ->addSelect('(SELECT COUNT(c.id) FROM Comment c WHERE c.event_id = e.id) AS reply_num')
                ->from('Event e')
                ->addSelect('IF(e.start >= now(),TIMESTAMPDIFF(DAY, now(), e.start),10000) AS can_sign')
                ->whereIn('e.club_id', $club_ids)
                ->andWhere('e.is_closed = ? OR e.is_closed IS NULL', FALSE)
                ->orderBy('is_fixed DESC,can_sign ASC,e.start DESC')
                ->limit(10);
        $view['event'] = $event->fetchArray();

        //最新加入
        $view['joinmember'] = Doctrine_Query::create()
                ->select('cm.user_id, cm.join_at,
                (SELECT u.realname FROM User u WHERE u.id = cm.user_id) AS realname')
                ->from('ClubMember cm')
                ->whereIn('cm.club_id', $club_ids)
                ->orderBy('cm.join_at DESC')
                ->limit(9)
                ->fetchArray();

        $this->_title($this->_aa['name'] . '俱乐部');
        $this->_render('_body', $view);
    }

}
