<?php
/**
  +-----------------------------------------------------------------
 * 名称：新鲜事控制器
 * 版本：1.0
 * 版权：www.usho.cn
 * 作者：zhaojiangang
 * 最后更新日期：12-10-12 上午9:29
 * 相同指数:100%
  +-----------------------------------------------------------------
 */
class Controller_Weibo extends Layout_Aa {

    public $pagesize = 15;

    function before() {
        parent::before();
    }

    //微博首页
    function action_index() {

        $view = array();
        $uid = Arr::get($_GET, 'uid');
        $v = Arr::get($_GET, 'v');
        $page = Arr::get($_GET, 'page', 1);
        $topic = Arr::get($_GET, 'topic');

        // 图片链接展示
        $banner = Doctrine_Query::create()
                ->from('Banner s')
                ->where('s.aa_id = ?', $this->_id)
                ->addWhere('format="banner"')
                ->orderBy('s.order_num ASC')
                ->limit(3)
                ->fetchArray();

        $logo = Doctrine_Query::create()
                ->from('Banner s')
                ->where('s.aa_id = ?', $this->_id)
                ->addWhere('format="logo"')
                ->orderBy('s.order_num ASC')
                ->limit(10)
                ->fetchArray();

        //默认banner
        if (count($banner) == 0) {
            $banner[] = array(
                'filename' => '/static/images/default_banner.jpg',
                'title' => '欢迎回来！',
                'url' => '',
            );
        }

        $view['banner'] = $banner;
        $view['logo'] = $logo;

        $view['show_src'] = URL::base() . Model_Aa::SHOW_PATH; // 图片显示路径

        //新鲜事
        $weibo = Doctrine_Query::create()
                ->select('c.*,u.realname AS realname,u.sex AS sex,u.speciality as speciality ,u.start_year as start_year')
                ->from('WeiboContent c')
                ->leftJoin('c.User u');

        //某一个人的
        if ($uid) {
            $view['user_info'] = Doctrine_Query::create()
                    ->select('u.*')
                    ->from('User u')
                    ->where('u.id=?', $uid)
                    ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
            $weibo->where('c.user_id=?', $uid);
        }
        //所有地方校友的
        else {
            $weibo->whereIn('c.aa_id',array(0,$this->_id));
           // $weibo->where('c.user_id IN (SELECT m.user_id from AaMember m where m.aa_id=?)', $this->_id);
        }

        //原创
        if ($v == 'original') {
            $weibo->addWhere('c.is_original=?', true);
        }
        //有关校友会的(非个人)
        elseif ($v == 'aa') {
            $weibo->addWhere('c.about_org=?', true);
        }
        //提到我的
        elseif ($v == 'atme') {
            $search_srt = '%[u=' . $this->_uid . ']%';
            $weibo->addWhere('c.content LIKE ?', $search_srt);
        }
        //我关注的
        elseif ($v == 'mark') {
            $mark_user_array = Model_User::markArr($this->_uid);
            $weibo->andWhereIn('c.user_id', $mark_user_array);
        }
        //我评论过的
        elseif ($v == 'cmt') {
            $cmtForWeiboids_array = Model_Comment::cmtWeibo($this->_uid);
            $weibo->andWhereIn('c.id', $cmtForWeiboids_array);
        }
        elseif ($uid) {
        }
        else {
            $weibo->addWhere('c.is_original=?', true);
        }

        //相关话题
        if ($topic) {
            $weibo->andWhere('c.content LIKE ?', '%'.$topic.'%');
        }

        $total_weibo = $weibo->count();

        $pager = Pagination::factory(array(
                    'total_items' => $total_weibo,
                    'items_per_page' => $this->pagesize,
                    'view' => 'pager/weibo',
                ));

        $view['pager'] = $pager;

        $view['weibo'] = $weibo->offset($pager->offset)
                ->orderBy('c.id DESC')
                ->limit($pager->items_per_page)
                ->fetchArray();

        //热门话题
        $view['hot_topics'] = Doctrine_Query::create()
                ->select('*')
                ->from('WeiboTopics')
                ->orderBy('num DESC')
                ->limit(10)
                ->fetchArray();

        //更新最后一条新鲜事查询日期
        $lastQueryTime = $view['weibo'] ? $view['weibo'][0]['post_at'] : null;
        $this->updateLastTime($lastQueryTime);

        $view['uid'] = $uid;
        $view['v'] = $v;
        $view['page'] = $page;
        $view['pagesize'] = $this->pagesize;
        $view['total_weibo'] = $total_weibo;
        $view['topic'] = $topic;
        $view['total_page'] = ceil($total_weibo / $this->pagesize);
        $this->_title('新鲜事');
        $this->_render('_body', $view);
    }

    //新鲜事正文
    function action_content() {
        $wid = Arr::get($_GET, 'wid');
        $user_id = $this->_uid;
        $weibo = Doctrine_Query::create()
                ->select('c.*,aa.sname,u.realname AS realname,u.sex AS sex,u.speciality as speciality ,u.start_year as start_year')
                ->from('WeiboContent c')
                ->leftJoin('c.User u')
                ->where('c.id=?', $wid)
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
        $view['w'] = $weibo;

        $view['user_topics'] = Doctrine_Query::create()
                ->select('*')
                ->from('WeiboTopics')
                ->addWhere('user_id=?', $weibo['user_id'])
                ->orderBy('num DESC')
                ->limit(10)
                ->fetchArray();

        //关注状态
        if ($user_id AND $user_id != $weibo['user_id']) {
            //是否已关注作者
            $is_marked = Model_Mark::is_mark($user_id, $weibo['user_id']);
            //是否被关注
            $is_markMe = Model_Mark::is_mark($weibo['user_id'], $user_id);
            $view['mark']['is_marked'] = $is_marked;
            $view['mark']['is_markMe'] = $is_markMe;
        }

        $this->_title($weibo['realname']);
        $this->_render('_body', $view);
    }

    //获取某一条微博信息
    function action_getWeiboForId() {
        $wid = Arr::get($_GET, 'wid');
        $template = Arr::get($_GET, 'tpl', 'full_content_list');
        $fadeIn = Arr::get($_GET, 'fadeIn', null);
        $this->auto_render = FALSE;
        $weibo = Doctrine_Query::create()
                ->select('c.*,aa.sname,u.realname AS realname,u.sex AS sex')
                ->from('WeiboContent c')
                ->leftJoin('c.User u')
                ->where('c.id=?', $wid)
                ->addWhere('c.aa_id = ?', $this->_id)
                ->limit(1)
                ->fetchArray();

        $view['weibo'] = $weibo;
        $view['fadeIn'] = $fadeIn;

        //更新最后查询时间
        $lastQueryTime = $view['weibo'] ? $view['weibo'][0]['post_at'] : null;
        $this->updateLastTime($lastQueryTime);

        echo View::factory('weibo/' . $template, $view);
    }

    //按条件获取
    function action_getWeibo() {
        $page = Arr::get($_GET, 'page', 1);
        $v = Arr::get($_GET, 'v');
        $getLast = Arr::get($_GET, 'getLast');
        $this->auto_render = FALSE;
        $weibo = Doctrine_Query::create()
                ->select('c.*,aa.sname,u.realname AS realname,u.sex AS sex')
                ->from('WeiboContent c')
                ->leftJoin('c.User u')
                ->where('c.id IS NOT NULL');

        //有关校友会的(非个人)
        if ($v == 'aa') {
            $weibo->addWhere('c.about_org=?', true);
        }
        //原创
        elseif ($v == 'original') {
            $weibo->addWhere('c.is_original=?', true);
        }
        //活动相关的
        elseif ($v == 'event') {
            $weibo->addWhere('c.is_original=?', false);
        }
        //校友的
        elseif ($v == 'alumnus') {
            $weibo->addWhere('c.about_org=?', false);
        }
        //提到我的
        elseif ($v == 'atme') {
            $search_srt = '%[u=' . $this->_uid . ']%';
            $weibo->addWhere('c.content LIKE ?', $search_srt);
        }
        //我关注的
        elseif ($v == 'mark') {
            $mark_user_array = Model_User::markArr($this->_uid);
            $weibo->andWhereIn('c.user_id', $mark_user_array);
        } else {
            $weibo->addWhere('c.is_original=?', true);
        }

        //某时间段之后的
        if ($getLast == 'yes') {
            $lastQueryTime = $this->_sess->get('lastQueryTime');
            $weibo->addWhere('c.post_at>?', $lastQueryTime);
        }

        $view['weibo'] = $weibo->orderBy('c.post_at DESC')
                ->offset($this->pagesize * ($page - 1))
                ->limit($this->pagesize)
                ->fetchArray();

        $view['page'] = $page;

        //上次最后查询新鲜事id
        $lastQueryTime = $view['weibo'] ? $view['weibo'][0]['post_at'] : null;
        $this->updateLastTime($lastQueryTime);

        echo View::factory('weibo/full_content_list', $view);
    }

    //发布微博
    function action_post() {
        $this->auto_render = False;

        if ($_POST) {
            //身份审核
            if (!$this->userPermissions('weibo', True)) {
                echo Candy::MARK_ERR . '很抱歉，您未登录或尚未通过审核，暂时还不能发布内容！';
                exit;
            }

            $share_sina = Arr::get($_POST, 'share_sina', false);
            $content = Arr::get($_POST, 'content', null);
            $img_paths = Arr::get($_POST, 'img_paths', null);

            //创建微博内容
            $weiboPost = array();
            $weiboPost['user_id'] = $this->_uid;
            $weiboPost['img_paths'] = $img_paths;
            $weiboPost['from_forward'] = Arr::get($_POST, 'from_forward', null);
            $weiboPost['forward_content'] = Arr::get($_POST, 'forward_content', null);

            //转发并评论这些微博
            $weiboPost['comment_weibos'] = Arr::get($_POST, 'comment_weibos');
            //新鲜事内容
            $weiboPost['content'] = $content;
            //是否为原创
            $weiboPost['is_original'] = true;
            //组织发布的
            $weiboPost['about_org'] = Arr::get($_POST, 'about_org', false);
            //发布出处
            $weiboPost['aa_id'] = $this->_id;
            $backData = Model_weibo::post($weiboPost);
            //错误提示
            if (is_array($backData)) {
                echo Candy::MARK_ERR . $backData['msg'];
                exit;
            }
            //添加成功
            else {
                //发布到新浪微博
                if ($share_sina == 'yes') {
                    //插入的图片
                    if ($img_paths) {
                        $img_paths_array = explode('||', trim($img_paths));
                        $pic_url = isset($img_paths_array[0]) ? URL::base(FALSE, TRUE) . $img_paths_array[0] : null;
                        $pic_url = isset($pic_url) ? preg_replace('/_mini\//', '', $pic_url) : null;
                    }
                    //提取活动链接
                    if (preg_match('/\[e=([\d]+)\]/is', $content)) {
                        preg_match_all('/\[e=([\d]+)\]/is', $content, $event_id);
                        $link = URL::base(FALSE, TRUE) . 'event?id=' . $event_id[1][0];
                    } else {
                        $link = null;
                    }

                    $this->sinaWeiboUpdate(array(
                        'text' => $content,
                        'pic_url' => isset($pic_url) ? $pic_url : null,
                        'link' => $link,
                            ));
                }

                Db_User::updatePoint('weibo');
                //backData 为 wid
                echo $backData;
            }
        }
    }

    //删除微博
    function action_del() {
        $this->auto_render = False;
        $cid = Arr::get($_GET, 'cid');
        $weibo = Doctrine_Query::create()
                ->from('WeiboContent')
                ->where('id = ?', $cid)
                ->fetchOne();

        if ($weibo AND $weibo['user_id'] == $this->_uid) {

            $weibo->delete();

            //更新话题总数
            Model_weibo::updateTopic($weibo['topic'],$weibo['user_id']);

            Db_Comment::delete(array('weibo_id' => $cid));
        }
    }

    //表情控制
    function action_expression() {
        $this->auto_render = FALSE;
        echo View::factory('weibo/insert/expression');
    }

    //获取评论表单及列表
    function action_comments() {
        $this->auto_render = FALSE;
        $wid = Arr::get($_GET, 'wid');
        $comments = Doctrine_Query::create()
                ->select('c.id,c.weibo_id,c.content,c.post_at,c.user_id,u.realname AS realname,u.sex AS sex')
                ->from('Comment c')
                ->leftJoin('c.User u')
                ->where('c.weibo_id = ?', $wid)
                ->orderBy('c.id DESC')
                ->limit(10)
                ->fetchArray();
        $view['wid'] = $wid;
        $view['comments'] = $comments;
        echo View::factory('weibo/comments', $view);
    }

    //获取最后一条评论内容
    function action_lastcomment() {
        $this->auto_render = FALSE;
        $wid = Arr::get($_GET, 'wid');
        $comments = Doctrine_Query::create()
                ->select('c.id,c.content,c.weibo_id,c.post_at,c.user_id,u.realname AS realname,u.sex AS sex')
                ->from('Comment c')
                ->leftJoin('c.User u')
                ->where('c.weibo_id = ?', $wid)
                ->orderBy('c.id DESC')
                ->limit(10)
                ->fetchArray();
        $view['comments'] = $comments;
        echo View::factory('weibo/comment_list', $view);
    }

//回复评论
    function action_replycmt() {
        $this->auto_render = FALSE;
        $cid = Arr::get($_GET, 'cid');
        $comments = Doctrine_Query::create()
                ->select('c.id,c.content,c.weibo_id,c.post_at,c.user_id,u.realname AS realname,u.sex AS sex')
                ->from('Comment c')
                ->leftJoin('c.User u')
                ->where('c.id = ?', $cid)
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

        $view['comments'] = $comments;
        echo View::factory('weibo/replycmt', $view);
    }

    //打开插入校友提示框
    function action_insertuser() {
        $this->auto_render = False;
        //所有我关注的人
        $mark = Doctrine_Query::create()
                ->select('m.user,u.realname AS realname')
                ->from('UserMark m')
                ->leftJoin('m.MUser u')
                ->where('m.user_id = ?', $this->_uid)
                ->limit(30)
                ->fetchArray();
        echo View::factory('weibo/insert/user', array('mark' => $mark));
    }

    //查找用户
    function action_searchuser() {
        $this->auto_render = FALSE;
        $namekeys = Arr::get($_GET, 'namekeys');
        $namekeys = preg_replace('/(^\s*)|(\s*$)/', '', $namekeys); //去除首尾空格;
        $namekeys = preg_replace('/,|，|(\s+)|(　+)+/', ' ', $namekeys); //替代空格,换行,tab,中文空格
        $namekeys = preg_replace('/(\s+)/', ' ', $namekeys); //替换多个空格为一个空格
        $name_array = explode(' ', trim($namekeys));
        $name_array_bak = $name_array;
        $view['users'] = '';
        if (count($name_array) > 0) {
            $users = Doctrine_Query::create()
                    ->select('id,realname,speciality,start_year,login_time')
                    ->from('User')
                    ->where('realname=?', $name_array[0]);
            unset($name_array[0]);
            foreach ($name_array as $name) {
                $users->orWhere('realname=?', $name);
            }
            $search = $users->fetchArray();
        }
        //按输入顺序重新排序
        $view['users'] = array();
        foreach ($name_array_bak as $inputname) {
            foreach ($search as $s) {
                if ($inputname == $s['realname']) {
                    $view['users'][] = $s;
                }
            }
        }
        echo View::factory('weibo/insert/userlist', $view);
    }

//插入图片
    function action_insertpic() {
        echo View::factory('weibo/insert/pic');
    }

    //打开活动插入框
    function action_insertevent() {
        echo View::factory('weibo/insert/event');
    }

    //搜索活动
    function action_searchevent() {
        $this->auto_render = FALSE;
        $keyword = Arr::get($_GET, 'keyword');
        $view['events'] = Doctrine_Query::create()
                ->select('e.id,e.title,e.type,e.start,e.finish')
                ->addSelect('(SELECT COUNT(ss.id) FROM EventSign ss WHERE ss.num = e.num AND ss.event_id = e.id) AS sign_num')
                ->addSelect('(SELECT a.sname FROM Aa a WHERE a.id = e.aa_id) AS aa_name')
                ->addSelect('IF(e.start >= now(),TIMESTAMPDIFF(DAY, now(), e.start),10000) AS can_sign')
                ->from('Event e')
                ->where('e.title LIKE ?', '%' . $keyword . '%')
                ->orderBy('is_fixed DESC,can_sign ASC,e.start DESC,e.id DESC')
                ->limit(10)
                ->fetchArray();

        echo View::factory('weibo/insert/eventlist', $view);
    }

    //转发
    function action_retweet() {
        $this->auto_render = FALSE;
        $wid = Arr::get($_GET, 'wid');
        //当前博文
        $weibo = Doctrine_Query::create()
                ->select('c.*,u.realname AS realname,u.sex AS sex,u.speciality as speciality ,u.start_year as start_year')
                ->from('WeiboContent c')
                ->leftJoin('c.User u')
                ->where('c.id=?', $wid)
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
        $view['weibo'] = $weibo;

        //来自更早的微博
        $forward = null;
        if ($weibo['from_forward']) {
            $forward = Doctrine_Query::create()
                    ->select('c.*,u.realname AS realname,u.sex AS sex,u.speciality as speciality ,u.start_year as start_year')
                    ->from('WeiboContent c')
                    ->leftJoin('c.User u')
                    ->where('c.id=?', $weibo['from_forward'])
                    ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
        }
        $view['forward'] = $forward;

        echo View::factory('weibo/retweet', $view);
    }

    //获取正文带分页的评论
    function action_contentComments() {
        $this->auto_render = FALSE;
        $wid = Arr::get($_GET, 'wid');
        $page = Arr::get($_GET, 'page');
        $view['wid'] = $wid;

        $total_comments = Doctrine_Query::create()
                ->select('c.id')
                ->from('Comment c')
                ->where('c.weibo_id = ?', $wid)
                ->count();

        $pager = Pagination::factory(array(
                    'total_items' => $total_comments,
                    'items_per_page' => 20,
                    'view' => 'inc/pager/comment',
                ));

        $view['pager'] = $pager;

        $page = $page ? $page : $pager->total_pages;

        //自动跳转到最后一页
        if (Arr::get($_GET, 'page') == 'last') {
            $pager->set_current_page($pager->total_pages);
        }

        $comments = Doctrine_Query::create()
                ->select('c.id,c.weibo_id,c.content,c.post_at,c.user_id,u.realname AS realname,u.sex AS sex')
                ->from('Comment c')
                ->leftJoin('c.User u')
                ->where('c.weibo_id = ?', $wid)
                ->orderBy('c.id DESC')
                ->offset($pager->offset)
                ->limit($pager->items_per_page)
                ->fetchArray();
        $view['comments'] = $comments;
        echo View::factory('weibo/contentComments', $view);
    }

    //自动检查最新条数
    function action_checkLatestNum() {
        $this->auto_render = FALSE;
        $lastQueryTime = $this->_sess->get('lastQueryTime');

        $weibo = Doctrine_Query::create()
                ->select('a.aa_id,a.content_id,c.post_at as post_at')
                ->from('AaWeibo a')
                ->leftJoin('a.WeiboContent c')
                ->where('a.aa_id = ' . $this->_id);
        if ($lastQueryTime) {
            $weibo->addWhere('c.post_at>?', $lastQueryTime);
        }

        $count = $weibo->count();
        echo $count;
    }

    //更新最新新鲜事id
    function updateLastTime($time) {
        $sess_time = $this->_sess->get('lastQueryTime');
        $sess_time = $sess_time ? $sess_time : 0;
        if ($time AND strtotime($time) > strtotime($sess_time)) {
            $this->_sess->set('lastQueryTime', $time);
        }
    }

}

?>
