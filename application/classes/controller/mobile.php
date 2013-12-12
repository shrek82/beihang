<?php

//移动客户端
class Controller_Mobile extends Layout_Main {

    public $template = 'layout/mobile2';
    //校友会ID
    public $_aid;
    public $_aidstr;
    //来源微信用户openId
    private $_weixin_openid;

    public function before() {

        parent::before();
        $this->_siteurl = 'http://' . $_SERVER['HTTP_HOST'];

        $this->_weixin_openid = Arr::get($_GET, 'weixin_openid');

        $this->_aidstr = 'aid=all';
        if (isset($_GET['aid']) && is_numeric($_GET['aid'])) {
            $this->_aid = $_GET['aid'];
            $this->_aidstr = 'aid=' . $_GET['aid'];
        }

        View::set_global('_AID', $this->_aid);
        View::set_global('_AIDSTR', $this->_aidstr);
        View::set_global('_WEIXINOPENID', $this->_weixin_openid);

        if ($this->request->action != 'login' AND $this->request->action != 'reg' AND $this->request->action != 'help') {
            $this->_sess->set('logined_redirect', $_SERVER['REQUEST_URI']);
        }

        //全局模板渲染
        $this->_render('_header', null, 'mobile/empty_header');
        $this->_render('_footer', null, 'mobile/footer');
    }

    //初始校友有页面
    function action_index() {

        # 活动关键字搜索
        $q = Arr::get($_GET, 'q');
        $view['q'] = $q;

        # tag
        $tag = urldecode(Arr::get($_GET, 'tag'));
        $view['tag'] = $tag;

        # 活动分类
        $type = urldecode(Arr::get($_GET, 'type'));
        $view['type'] = $type;

        #特定范围
        $list = Arr::get($_GET, 'list');
        $view['list'] = $list;

        # 校友自主活动列表
        $event = Doctrine_Query::create()
                ->select('e.id,e.title,e.start,e.small_img_path,e.comments_num AS cmt_num,e.custom_icon,e.finish,e.address,e.type,e.aa_id,e.club_id,e.is_fixed,e.publish_at,e.tags,e.votes,e.score, a.name, c.name')
                ->addSelect('(SELECT SUM(ss.num) FROM EventSign ss WHERE ss.event_id = e.id) AS sign_num')
                ->addSelect('IF(e.finish >= now(),TIMESTAMPDIFF(MINUTE, now(), e.start),900000) AS can_sign')
                ->addSelect('IF(e.is_fixed = 1 && e.start >= now(),1,0) AS is_start_fixed')
                ->from('Event e')
                ->leftJoin('e.Aa a')
                ->leftJoin('e.Club c')
                ->where('e.is_closed = ? OR e.is_closed IS NULL', FALSE)
                ->orderBy('is_start_fixed DESC,can_sign ASC,e.start DESC');

        if ($type) {
            $event->andWhere('e.type = ?', $type);
        }

        if ($q) {
            $event->andWhere('e.title LIKE ?', '%' . $q . '%');
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

        $pager = Pagination::factory(array(
                    'total_items' => 2000,
                    'items_per_page' => 10,
                    'view' => 'pager/mobile'
        ));
        $view['pager'] = $pager;

        $view['events'] = $event->offset($pager->offset)
                ->limit($pager->items_per_page)
                ->fetchArray();

        $this->_title('活动');
        $this->_render('_header', null, 'mobile/header');
        $this->_render('_body', $view);
    }

    //活动展示页面
    function action_eview() {
        $eid = Arr::get($_GET, 'id');

        //活动详细页面数据
        $condition = array(
            'event_id' => $eid,
            'aa_info' => True,
            'club_info' => True,
            'organiger_info' => True,
            'album' => false,
            'photos' => false,
            'signs' => true,
            'total_sign' => true,
            'user_sign_status' => true,
            'bbs_unit_id' => true,
            'permission' => false,
            'sign_category' => false
        );

        $event_data = Db_Event::getEventViewData($condition);

        if (!$event_data) {
            $this->_redirect('/mobile/notfound?' . $this->_aidstr);
            exit;
        }

        $view['params']['event_id'] = $eid;
        $view['params']['bbs_unit_id'] = $event_data['bbs_unit_id'];


        $view['event_data'] = Db_Event::getEventViewData($condition);
        $this->_title($view['event_data']['event']['title']);
        $htmlAndPics = Common_Global::standardHtmlAndPics($view['event_data']['event']['content']);
        $view['event_data']['event']['content'] = $htmlAndPics['html'];
        $this->_render('_body', $view);
    }

    //活动报名
    public function action_signform() {
        $view = array();
        $this->_render('_body', $view);
    }

    //活动报名
    public function action_eventsign() {
        $this->auto_render = false;
        echo '111';
    }

    //初始校友有页面
    function action_bbs() {
        $condition['limit'] = 10;
        $condition['page'] = Arr::get($_GET, 'page', 1);
        $condition['cat'] = Arr::get($_GET, 'cat', 1);
        $condition['aa_id'] = Arr::get($_GET, 'aa_id', 1);
        //置顶主题
        $units = Model_Bbs::getMobileList($condition);

        $view['fixed'] = Model_bbs::createXmlArray($units['fixed']);
        $view['list'] = Model_bbs::createXmlArray($units['list']);

        $view['pager'] = Pagination::factory(array(
                    'total_items' => 6000,
                    'items_per_page' => 10,
                    'view' => 'pager/mobile',
        ));
        $this->_title('论坛');
        $this->_render('_header', null, 'mobile/header');
        $this->_render('_body', $view);
    }

    //话题详情
    function action_bbsview() {
        $id = Arr::get($_GET, 'id', 15);
        $u = Doctrine_Query::create()
                ->select('u.*,p.content AS content')
                ->addSelect('a.id,a.sname,b.id,b.name,b.aa_id,b.club_id')
                ->addSelect('c.id,c.name')
                ->addSelect('user.id,user.realname,user.sex,user.speciality,user.start_year,user.city')
                ->from('BbsUnit u')
                ->leftJoin('u.Post p')
                ->leftJoin('u.User user')
                ->leftJoin('u.Bbs b')
                ->leftJoin('b.Aa a')
                ->leftJoin('b.Club c')
                ->where('u.id=?', $id)
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

        $htmlAndPics = Common_Global::standardHtmlAndPics($u['content'], $u['title']);
        $u['content'] = $htmlAndPics['html'];

        if (!$u) {
            $this->error('话题不存在或已经被删除！');
        } else {
            Model_Bbs::unitHit($id);
        }

        $event = null;
        $signs = null;
        $total_sign = 0;
        if ($u['event_id']) {
            //活动信息
            $event = Db_Event::getEventById($u['event_id']);
            //所有报名记录
            $signs = Db_Event::getEventSigner($u['event_id']);
            if ($signs) {
                foreach ($signs AS $s) {
                    $total_sign+=$s['num'];
                }
            }
        }

        $view['u'] = $u;

        $this->_render('_body', $view);
    }

    public function action_news() {
        $view['news'] = Model_News::get(array('aa_id' => $this->_aid, 'limit' => 10));
        $view['pager'] = Pagination::factory(array(
                    'total_items' => 800,
                    'items_per_page' => 10,
                    'view' => 'pager/mobile',
        ));
        $this->_render('_header', null, 'mobile/header');
        $this->_render('_body', $view);
    }

    function action_newsview() {

        $id = Arr::get($_GET, 'id');
        $news = Doctrine_Query::create()
                ->from('News')
                ->where('id = ?', $id)
                ->fetchOne();

        //未找到新闻
        if (!$news) {
            $this->_redirect('/mobile/notfound?' . $this->_aidstr);
            exit;
        }
        $view['news'] = $news;
        Model_News::hit($id);
        //如果需要跳转至其他网页
        if ($news['redirect']) {
            $this->_redirect($news['redirect']);
            exit;
        }

        $htmlAndPics = Common_Global::standardHtmlAndPics($news['content'], $news['title']);
        $news['content'] = $htmlAndPics['html'];

        $this->_title($news['title']);
        $this->_render('_body', $view);
    }

    public function action_help() {
        $view = array();
        $this->_render('_header', null, 'mobile/header');
        $this->_render('_body', $view);
    }

    public function action_reg() {
        $this->_render('_body');
    }

    public function action_login() {
        if ($this->_uid) {
            $this->_redirect('/mobile');
        }
        $this->_render('_body');
    }

    //没有找到相关内容
    public function action_notfound() {
        $this->_render('_header', null, 'mobile/header');
        $this->_render('_body');
    }

}

?>
