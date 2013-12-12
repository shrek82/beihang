<?php
/**
  +-----------------------------------------------------------------
 * 名称：俱乐部主页
 * 版本：1.1
 * 版权：www.usho.cn
 * 作者：zhaojiangang
 * 最后更新日期：12-10-25 上午11:10
 * 相同指数:100%
  +-----------------------------------------------------------------
 */
class Controller_Club_Home extends Layout_Club {

    function action_index() {

        $this->_title($this->_club['name']);

        // 图片链接展示
        $view['banner'] = DB::select()
                ->from(array('banner', 'b'))
                ->where('b.club_id', '=', $this->_id)
                ->where('b.format', '=', 'banner')
                ->where('b.is_display', '=', 1)
                ->order_by('b.order_num', 'ASC')
                ->limit(5)
                ->execute()
                ->as_array();

        //新闻列表
        $view['news'] = Model_News::get(array('club_id'=>$this->_id,'limit'=>$this->_theme['news_limit']));

        //友情链接logo
        $view['logo'] = DB::select()
                ->from(array('banner', 'b'))
                ->where('b.club_id', '=', $this->_id)
                ->where('b.format', '=', 'logo')
                ->where('b.is_display', '=', 1)
                ->order_by('b.order_num', 'ASC')
                ->limit(10)
                ->execute()
                ->as_array();

        // 最新话题
        $condition = array(
            'aa_id' => $this->_club['aa_id'],
            'club_id' => $this->_id,
            'page_size' => 10,
            'replyname' => false,
            'count_total' => false,
            'admin_mode' => false,
        );

        $query_data = Db_Bbs::getUnits($condition);
        $view['units'] = $query_data['units'];

        // 管理组成员
        $view['manager'] = Doctrine_Query::create()
                ->select('cm.user_id, cm.join_at,u.realname AS realname,u.sex AS sex')
                ->from('ClubMember cm')
                ->leftJoin('cm.User u')
                ->where('cm.club_id = ?', $this->_id)
                ->andWhere('cm.manager = ?', TRUE)
                ->orderBy('cm.join_at DESC')
                ->fetchArray();

        // 最近加入的成员
        $view['member'] = Doctrine_Query::create()
                ->select('cm.user_id, cm.join_at,u.realname AS realname,u.sex AS sex')
                ->from('ClubMember cm')
                ->leftJoin('cm.User u')
                ->where('cm.club_id = ?', $this->_id)
                ->orderBy('cm.join_at DESC')
                ->limit(9)
                ->fetchArray();

        // 最近访问的
        $view['visitor'] = Doctrine_Query::create()
                ->select('cm.user_id, cm.visit_at,u.realname AS realname,u.sex AS sex')
                ->from('ClubMember cm')
                ->leftJoin('cm.User u')
                ->where('cm.club_id = ?', $this->_id)
                ->orderBy('cm.visit_at DESC')
                ->limit(9)
                ->fetchArray();

        // 最新俱乐部相片
        $view['photo'] = Doctrine_Query::create()
                ->from('Pic p')
                ->leftJoin('p.Album a')
                ->where('a.club_id = ?', $this->_id)
                ->orderBy('p.upload_at DESC')
                ->limit(4)
                ->fetchArray();

        // 其他信息列表
        $view['infos'] = Doctrine_Query::create()
                ->select('title,aa_id,club_id')
                ->from('AaInfo')
                ->where('club_id = ?', $this->_id)
                ->addWhere('is_public = ?', True)
                ->orderBy('order_num ASC')
                ->useResultCache(true, 600, 'club_home_infos_' . $this->_id)
                ->fetchArray();

        // 最新活动
        $condition = array('aa_id' => $this->_club['aa_id'], 'club_id' => $this->_id, 'limit' => 10);
        $view['event'] = Db_Event::getEvents($condition);

        //最新照片
        $condition = array('club_id' => $this->_id, 'page_size' => 4);
        $album_data = Db_Album::getAlbums($condition);
        $view['albums'] = $album_data['albums'];

        $this->_render('_body', $view);
    }

    #俱乐部介绍

    function action_info() {
        $info_id = Arr::get($_GET, 'info_id');

        // 其他信息列表
        $info = Doctrine_Query::create()
                ->select('title,club_id')
                ->from('AaInfo')
                ->where('club_id = ?', $this->_id)
                ->orderBy('order_num ASC');
        $view['infos'] = $info->fetchArray();

        if (!$info_id) { // 显示简介
            $view['info']['title'] = $this->_club['name'] . '简介';
            $view['info']['content'] = $this->_club['intro'];
        } else {
            $view['info'] = Doctrine_Query::create()
                    ->from('AaInfo inf')
                    ->where('inf.club_id = ?', $this->_id)
                    ->addWhere('id=?', $info_id)
                    ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
        }

        if (!$view['info']) {
            $this->request->redirect('club_home?id=' . $this->_id);
        }

        $this->_title($view['info']['title']);
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
            'permission'=>True,
            'sign_category'=>True
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

    # 俱乐部积分（未确定模式）

    function action_point() {
        $this->_title('俱乐部积分');
        $this->_render('_body');
    }

    # 退出俱乐部

    function action_leave() {
        if ($this->_member) {
            $this->_member->delete();
            echo '成功退出';
            exit;
        }
    }

    //直接加入俱乐部
    function action_join() {
        $user_id = $this->_sess->get('id');
        $club_id = $this->_id;
        if (!Model_Club::isMember($club_id, $user_id)) {
            $member = new ClubMember();
            $member->user_id = $user_id;
            $member->join_at = date('Y-m-d H:i:s');
            $member->club_id = $club_id;
            $member->save();
        }
    }

    # 申请加入俱乐部

    function action_applyjoin() {
        $user_id = $this->_sess->get('id');
        $club_id = $this->_id;

        if (!$user_id) {
            echo '烦请先登录网站后再进行申请操作。';
        } else {
            if ($this->_member) {
                echo '您已经是本俱乐部成员，不需要再申请。';
            } else {

                // 已经申请了加入
                $apply = Doctrine_Query::create()
                        ->from('JoinApply')
                        ->where('club_id = ?', $club_id)
                        ->andWhere('user_id = ?', $user_id)
                        ->fetchOne();

                echo View::factory('inc/join_apply', compact('apply', 'club_id'));
            }
        }
    }

    function action_member() {
        $orderby = Arr::get($_GET, 'orderby', 'visit');
        $q = Arr::get($_POST, 'q');

        $member = Doctrine_Query::create()
                ->select('m.*,u.realname,u.sex')
                ->from('ClubMember m')
                ->leftJoin('m.User u')
                ->where('m.club_id = ?', $this->_id)
                ->orderBy('m.' . $orderby . '_at DESC');

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
                ->select('m.user_id, m.join_at,u.realname AS realname,u.sex AS sex')
                ->from('clubMember m')
                ->leftJoin('m.User u')
                ->where('m.club_id = ?', $this->_id)
                ->orderBy('m.join_at DESC')
                ->limit(9)
                ->fetchArray();

        // 管理组成员
        $view['manager'] = Doctrine_Query::create()
                ->select('m.title, m.user_id,u.realname AS realname,u.sex AS sex')
                ->from('clubMember m')
                ->leftJoin('m.User u')
                ->where('m.club_id = ?', $this->_id)
                ->andWhere('m.manager=?', True)
                ->fetchArray();

        $this->_title($this->_club['name'] . '成员');
        $this->_render('_body', $view);
    }

    // 俱乐部相册
    function action_album() {
        $list = Arr::get($_GET, 'list', 'new');
        $empty_albums = $list == 'all' ? True : False;
        $view['list'] = $list;
        $page = Arr::get($_GET, 'page', 1);
        $q = Arr::get($_POST, 'q');
        $view['q'] = $q;
        $condition = array('empty_albums' => $empty_albums, 'club_id' => $this->_id, 'page_size' => 16, 'count' => true, 'page' => $page, 'list' => $list, 'q' => $q);
        $album_data = Db_Album::getAlbums($condition);
        $view['albums'] = $album_data['albums'];
        $view['pager'] = $album_data['pager'];
        $this->_title('相册');
        $this->_render('_body', $view);
    }

    function action_event() {
        $type = Arr::get($_GET, 'type');
        $list = Arr::get($_GET, 'list');

        # tag
        $tag = urldecode(Arr::get($_GET, 'tag'));
        $view['tag'] = $tag;

        //我参加的活动
        $join = Arr::get($_GET, 'join');
        $view['join'] = $join;

        //我参加的活动
        if ($join AND $this->_uid) {
            $event_signed_ids = Model_Event::joinIDs($this->_sess->get('id'));
            $club_event_signed_ids = Doctrine_Query::create()
                    ->select('e.id')
                    ->from('Event e')
                    ->whereIn('e.id', $event_signed_ids)
                    ->addWhere('e.club_id = ?', $this->_id)
                    ->limit(200)
                    ->orderBy('e.start DESC')
                    ->execute(array(), Doctrine_Core::HYDRATE_SINGLE_SCALAR);

            if (count($club_event_signed_ids) == 0) {
                $club_event_signed_ids = array(0);
            }
        }

        //活动
        $event = Doctrine_Query::create()
                ->select('e.id,e.title,e.start,e.finish,e.address,e.type,e.aa_id,e.club_id,e.is_fixed,e.is_club_fixed,e.small_img_path,e.custom_icon')
                ->addSelect('(SELECT SUM(ss.num) FROM EventSign ss WHERE ss.event_id = e.id) AS sign_num')
                ->addSelect('e.comments_num AS cmt_num')
                ->addSelect('IF(e.finish >= now(),TIMESTAMPDIFF(MINUTE, now(), e.start),900000) AS can_sign')
                ->from('Event e')
                ->where('e.club_id = ?', $this->_id)
                ->andWhere('e.is_closed = ? OR e.is_closed IS NULL', FALSE);

        if ($join AND $this->_uid) {
            $event->whereIn('e.id', $club_event_signed_ids);
        }

        if ($tag) {
            $event->andWhere('(e.title LIKE ? OR e.tags LIKE ?)', array('%' . $tag . '%', '%' . $tag . '%'));
        }

        if ($list == 'aa' AND $this->_uid>0) {
            $event->whereIn('e.aa_id', Model_User::aaIds($this->_uid));
        }

        if ($list == 'joined' AND $this->_uid>0) {
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

        $event->orderBy('is_club_fixed DESC,can_sign ASC,e.start DESC');

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

        $this->_title('校友会的活动');
        $this->_render('_body', $view);
    }

    // 最新的一些话题
    function action_bbs() {
        $bbs_ids = Model_Bbs::getIDs(array('club_id' => $this->_id));
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
                ->orderBy('u.is_fixed DESC, u.comment_at DESC, u.create_at DESC');

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

    //校友会新闻
    function action_news() {
        $category_id = Arr::get($_GET, 'category', 0);

        $news = Doctrine_Query::create()
                ->select('n.title,n.create_at,n.hit,n.category_id,n.author_name,n.user_id,c.name,u.realname,n.is_fixed,c.name AS category_name')
                ->from('News n')
                ->leftJoin('n.NewsCategory c')
                ->leftJoin('n.User u')
                ->where('n.is_release = ?', TRUE)
                ->andWhere('c.club_id = ?', $this->_id)
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
                ->where('club_id = ?', $this->_id)
                ->andWhere('is_public = ?', TRUE)
                ->orderBy('order_num ASC,id ASC')
                ->fetchArray();

        $this->_title('俱乐部新闻');
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

}