<?php

/**
  +------------------------------------------------------------------------------
 * 新闻模块
  +------------------------------------------------------------------------------
 */
class Controller_News extends Layout_Main {

    function before() {
        $this->template = 'layout/news';
        parent::before();
    }

    /**
      +------------------------------------------------------------------------------
     * 新闻首页
      +------------------------------------------------------------------------------
     */
    function action_index() {

        //echo  Common_Global::getImageBysuffix('static/upload/attached/201206/2012064110352575.jpg', 'mini');
        //exit;
        //网站公告(原系统消息)
        $view['sys_msg'] = Doctrine_Query::create()
                ->select('id,title')
                ->from('SysMessage')
                ->orderBy('id desc')
                ->fetchArray();

        // 新闻一周点击排行
        $view['hit_news'] = Model_News::hitRank(9);

        // 新闻一周推荐排行
        //$view['dig_news'] = Model_News::digRank(8);
        // 总会新闻
        $view['main_news'] = Model_News::get(array('aa_id' => 'main', 'limit' => 15));

        //新闻分类
        $view['category'] = Doctrine_Query::create()
                ->select('id,name')
                ->from('NewsCategory')
                ->where('aa_id=?', 0)
                ->orderBy('order_num ASC')
                ->fetchArray();

        // 地方会新闻
        $view['aa_news'] = Doctrine_Query::create()
                ->select('n.title,n.create_at,n.is_pic,n.is_top')
                ->addSelect('a.id AS aa_id,a.sname as aa_name,a.ename as aa_ename')
                ->from('News n')
                ->leftJoin('n.NewsCategory c')
                ->leftJoin('c.Aa a')
                ->where('c.is_public = ?', TRUE)
                ->andWhere('n.is_release = ?', TRUE)
                ->andWhere('n.is_draft = ?', FALSE)
                ->andWhere('c.aa_id > 0')
                ->orderBy('n.create_at DESC')
                ->limit(10)
                ->fetchArray();

        //投票调查
        $view['vote'] = Doctrine_Query::create()
                ->from('Vote')
                ->where('is_closed=?', False)
                ->addWhere('bbs_unit_id=?', False)
                ->orderBy('id DESC')
                ->limit(5)
                ->fetchArray();

        $this->_title('新闻中心');
        $this->_render('_body', $view);
    }

    /**
      +------------------------------------------------------------------------------
     * 新闻分类列表
      +------------------------------------------------------------------------------
     */
    function action_list() {

        // 关键字过滤
        $q = urldecode(Arr::get($_GET, 'q'));
        $view['q'] = $q;

        // 所在校友会过滤
        $aa_id = Arr::get($_GET, 'aa_id', 0);
        $view['aa_id'] = $aa_id;

        // 具体分类
        $cid = Arr::get($_GET, 'cid');
        $view['cid'] = $cid;

        // 新闻一周点击排行
        $view['hit_news'] = Model_News::hitRank(8);

        // 新闻一周推荐排行
        $view['dig_news'] = Model_News::digRank(8);

        // 总会新闻的固定新闻
        $fixed_news = Doctrine_Query::create()
                ->select('n.title, n.create_at, n.hit, n.title_color,n.is_pic')
                ->from('News n')
                ->leftJoin('n.NewsCategory c')
                ->where('n.is_release = ?', TRUE)
                ->andWhere('c.aa_id = 0 ')
                ->orderBy('n.create_at DESC')
                ->fetchArray();

        $view['fixed_news'] = $fixed_news;

        // 其他新闻列表
        $news = Doctrine_Query::create()
                ->select('n.title, n.create_at, c.name, c.id, c.aa_id,n.title_color,n.is_pic')
                ->from('News n')
                ->where('n.is_release = ?', TRUE)
                ->leftJoin('n.NewsCategory c');

        if ($aa_id > 0) {
            $news->addSelect('a.sname as aa_name,a.ename as aa_ename')
                    ->leftJoin('c.Aa a')
                    ->andWhere('c.aa_id > 0');
        } else {
            $news->andWhere('c.aa_id = 0 AND c.id<>5');
            //总会新闻分类
            $view['all_category'] = Doctrine_Query::create()
                    ->select('id,name')
                    ->from('NewsCategory')
                    ->where('aa_id=0  AND id<>5')
                    ->orderBy('order_num ASC')
                    ->fetchArray();
        }

        if ($cid) {
            $news->andWhere('c.id = ?', $cid);
            if ($aa_id == 0) {
                // 总会新闻分类
                $view['category'] = Doctrine_Query::create()
                        ->select('id,name')
                        ->from('NewsCategory')
                        ->where('aa_id=? AND id = ?', array(0, $cid))
                        ->orderBy('order_num ASC')
                        ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
            }
        }

        $total_news = $news->count();

        $pager = Pagination::factory(array(
                    'total_items' => $total_news,
                    'items_per_page' => 25,
                    'view' => 'pager/common'
        ));

        $view['news'] = $news->offset($pager->offset)
                ->limit($pager->items_per_page)
                ->orderBy('n.create_at DESC')
                ->fetchArray();

        $view['pager'] = $pager;
        $this->_title('新闻列表');
        $this->_render('_body', $view);
    }

    /**
      +------------------------------------------------------------------------------
     * 新闻浏览
      +------------------------------------------------------------------------------
     */
    function action_view() {
        $this->userPermissions('newsView');

        $id = Arr::get($_GET, 'id');
        $news_category = '';
        $aa_info = '';

        $news = Doctrine_Query::create()
                ->from('News')
                ->where('id = ?', $id)
                ->fetchOne();

        //未找到新闻
        if (!$news) {
            $this->request->redirect('main/notFound');
        }
        //新闻分类
        else {
            $news_category = Doctrine_Query::create()
                    ->select('c.*,c.aa_id AS aa_id')
                    ->from('NewsCategory c')
                    ->leftJoin('c.Aa a')
                    ->where('c.id = ?', $news['category_id'])
                    ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

            //地方校友会新闻
            if ($news_category['aa_id']) {
                $this->_redirect('aa_home/newsDetail?id=' . $news_category['aa_id'] . '&nid=' . $id);
            }
            //俱乐部新闻
            if ($news_category['club_id']) {
                $this->_redirect('club_home/newsDetail?id=' . $news_category['club_id'] . '&nid=' . $id);
            }
        }

        //增加浏览次数
        $news->hit = $news['hit'] + 1;
        $news->save();

        //如果需要跳转至其他网页
        if ($news['redirect']) {
            $this->_redirect($news['redirect']);
            exit;
        }
        //上一篇
        //$prev = Model_News::prev($id);
        //下一篇
        //$next = Model_News::next($id);
        //相关新闻
        $relate = Model_News::relate($news);
        // 一周推荐新闻
        $dig_news = Model_News::digRank(10);

        $this->_title($news['title']);

        $this->_render('_body', compact('news', 'news_category', 'aa_info', 'prev', 'next', 'relate', 'dig_news'));
    }

    function action_deny() {
        
    }

    /**
      +------------------------------------------------------------------------------
     * 新闻??
      +------------------------------------------------------------------------------
     */
    function action_digg() {
        $this->auto_render = FALSE;

        if ($_GET && Request::$is_ajax) {
            if ($_GET['id'] == $_GET['dig']) {
                Model_News::dig($_GET['id']);
            }

            echo Doctrine_Query::create()
                    ->select('dig')
                    ->from('News')
                    ->where('id = ?', $_GET['id'])
                    ->execute(array(), Doctrine_Core::HYDRATE_SINGLE_SCALAR);
        }
    }

    /**
      +------------------------------------------------------------------------------
     * 新闻审核
      +------------------------------------------------------------------------------
     */
    function action_release() {
        if ($_POST && Request::$is_ajax) {
            $news = Doctrine_Query::create()
                    ->from('News')
                    ->where('id = ?', Arr::get($_POST, 'id'))
                    ->fetchOne();

            if ($news['is_release'] == TRUE) {
                $news['is_release'] = FALSE;
            } else {
                $news['is_release'] = TRUE;
            }

            $news->save();
        }
    }

    /**
      +------------------------------------------------------------------------------
     * 新闻置顶
      +------------------------------------------------------------------------------
     */
    function action_fix() {
        
    }

    function action_updateicon() {
        $id = Arr::get($_GET, 'id');
        $icon = Arr::get($_GET, 'icon');
        $news = Doctrine_Query::create()->from('News')
                ->where('id = ?', $id)
                ->fetchOne();

        if (!$news) {
            echo 'not have news';
            exit;
        }

        $images = Common_Global::getImages($news['content'], 'static/upload/');

        if ((!$images) OR count($images) == 0) {
            echo 'not images';
            $post['images'] = null;
            $post['small_img_path'] = null;
            $post['is_pic'] = false;
            $news->synchronizeWithArray($post);
            $news->save();
            exit;
        } elseif ($icon) {
            $post['is_pic'] = true;
            $post['images'] = serialize($images);
            $small_img_path = Common_Global::getImageBysuffix($icon, 'mini', true);
            $post['small_img_path'] = $small_img_path;
            $news->synchronizeWithArray($post);
            $news->save();
            echo '<img src="http://zuaa.zju.edu.cn/' . $small_img_path . '">';
        } else {
            
        }

        $view['news'] = $news;
        $view['images'] = $images;
        $this->_render('_body', $view);
    }

    /**
      +------------------------------------------------------------------------------
     * 保存发布
      +------------------------------------------------------------------------------
     */
    function action_create() {

        $create_from = Arr::get($_POST, 'create_from');
        if ($_POST) {

            // 没有分类
            if (!$_POST['category_id']) {
                echo Candy::MARK_ERR . '请选择新闻分类';
                exit;
            }

            $v = Validate::setRules($_POST, 'news');
            $post = $v->getData();
            if (!$v->check()) {
                echo Candy::MARK_ERR . $v->outputMsg($v->errors('validate'));
            } else {
                //是否包含非法关键词
                $filter_str = Model_Filter::check(array(Arr::get($_POST, 'title'), Arr::get($_POST, 'content')));
                if ($filter_str) {
                    echo Candy::MARK_ERR . '检测到含有非法关键词“' . $filter_str . '”，请在修改后重试，谢谢！';
                    exit;
                }

                $content = Arr::get($_POST, 'content');

                //获取本站图片
                $images = Common_Global::getImages($content, 'static/upload/');

                //生成迷你图
                if (count($images) > 0) {
                    $post['is_pic'] = true;
                    $post['images'] = serialize($images);
                    $post['small_img_path'] = Common_Global::getImageBysuffix($images[0], 'mini');
                }

                //焦点图片
                $post['img_path'] = isset($post['img_path']) ? $post['img_path'] : null;

                if (empty($post['intro'])) {
                    $post['intro'] = Text::limit_chars(trim(strip_tags($content), 200));
                }

                $post['update_at'] = date('Y-m-d H:i:s');
                $post['short_title'] = $post['short_title'] ? $post['short_title'] : $post['title'];
                $post['create_at'] = $post['create_at'] ? $post['create_at'] : date('Y-m-d H:i:s');
                $post['category_id'] = $post['category_id'];
                $post['is_release'] = Arr::get($_POST, 'is_release');
                $post['is_top'] = Arr::get($_POST, 'is_top', 0);
                $post['is_focus'] = Arr::get($_POST, 'is_focus');
                $post['is_comment'] = Arr::get($_POST, 'is_comment', 0);
                $post['special_id'] = Arr::get($_POST, 'special_id');
                $post['Tags'] = Model_News::tagArray($post['tag']);
                $news = new News();
                $news->fromArray($post);
                $news->save();

                //发布成功
                if ($news->id) {
                    echo $news->id;
                }

                //发布到北航校友新闻栏目
                if (isset($_POST['is_people_news']) AND $_POST['is_people_news'] == 1) {
                    $people_news['title'] = $post['title'];
                    $people_news['content'] = Arr::get($_POST, 'content');
                    $people_news['author_name'] = Arr::get($_POST, 'author_name');
                    Model_People::postnews($people_news);
                }


                //操作日志
                if ($this->_role == '管理员') {
                    $log_data = array();
                    $log_data['type'] = '发布新闻';
                    $log_data['news_id'] = $news->id;
                    $log_data['description'] = '发布了新闻“' . $post['title'] . '”';
                    Common_Log::add($log_data);
                }

                //从后台管理员发布
                if ($create_from == 'sys_admin') {
                    $this->_redirect('admin_news');
                }
            }
        }
    }

    /**
     *
      +------------------------------------------------------------------------------
     * 新闻更新
      +------------------------------------------------------------------------------
     */
    function action_update() {
        $create_from = Arr::get($_POST, 'create_from');

        if ($_POST) {

            $v = Validate::setRules($_POST, 'news');
            $post = $v->getData();

            // 没有分类
            if (!$_POST['category_id']) {
                echo Candy::MARK_ERR . '请选择新闻分类';
                exit;
            }

            if (!$v->check()) {
                echo Candy::MARK_ERR . $v->outputMsg($v->errors('validate'));
            } else {

                //返回包含的图片
                $images = Common_Global::getImages($post['content'], 'static/upload/');
                if (count($images) > 0) {
                    $post['is_pic'] = true;
                    $post['images'] = serialize($images);
                    $post['small_img_path'] = Common_Global::getImageBysuffix($images[0], 'mini');
                } else {
                    $post['is_pic'] = false;
                    $post['images'] = null;
                    $post['small_img_path'] = null;
                }

                $post['img_path'] = isset($post['img_path']) ? $post['img_path'] : null;
                $post['update_at'] = date('Y-m-d H:i:s');
                $post['category_id'] = $post['category_id'];
                $post['Tags'] = Model_News::tagArray($post['tag']);
                $post['is_release'] = Arr::get($_POST, 'is_release');
                $post['is_top'] = Arr::get($_POST, 'is_top', 0);
                $post['is_focus'] = Arr::get($_POST, 'is_focus');
                $post['is_comment'] = Arr::get($_POST, 'is_comment');
                $post['special_id'] = Arr::get($_POST, 'special_id');

                $news = Doctrine_Query::create()->from('News')
                        ->where('id = ?', $post['news_id'])
                        ->fetchOne();

                unset($post['news_id']);
                $news->synchronizeWithArray($post);
                $news->save();
                echo $news->id;

                //操作日志
                if ($this->_role == '管理员') {
                    $log_data = array();
                    $log_data['type'] = '修改新闻';
                    $log_data['news_id'] = $news->id;
                    $log_data['description'] = '修改了新闻“' . $post['title'] . '”';
                    Common_Log::add($log_data);
                }

                //从后台管理员发布
                if ($create_from == 'sys_admin') {
                    $this->_redirect('admin_news');
                }
            }
        }
    }

    /**
      +------------------------------------------------------------------------------
     * 新闻搜索
      +------------------------------------------------------------------------------
     */
    function action_search() {
        $q = urldecode(Arr::get($_GET, 'q'));

        $news = Doctrine_Query::create()
                ->from('News n')
                ->leftJoin('n.Tags t')
                ->where('is_release = ? AND is_draft = ?', array(TRUE, FALSE))
                ->andWhere('n.title LIKE ? OR t.name = ?', array('%' . $q . '%', $q));

        $pager = Pagination::factory(array(
                    'total_items' => $news->count(),
                    'items_per_page' => 10,
                    'view' => 'pager/common',
        ));

        $view['pager'] = $pager;
        $view['tag'] = $q;
        $view['news'] = $news->limit($pager->items_per_page)
                ->offset($pager->offset)
                ->fetchArray();

        $this->_title('有关"' . $q . '"的新闻');
        $this->_render('_body', $view);
    }

    //网站公告
    function action_notice() {
        $id = Arr::get($_GET, 'id');
        $notice = Doctrine_Query::create()
                ->from('SysMessage')
                ->where('id=' . $id)
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

        $more_notices = Doctrine_Query::create()
                ->select('id,title')
                ->from('SysMessage')
                ->where('id<>?', $id)
                ->orderBy('id desc')
                ->fetchArray();

        $this->_title($notice['title']);
        $this->_render('_body', compact('notice', 'more_notices'));
    }

}
