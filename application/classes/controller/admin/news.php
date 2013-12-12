<?php

class Controller_Admin_News extends Layout_Admin {

    function before() {
        parent::before();

        $leftbar_links = array(
            'admin_news/index' => '所有新闻',
            'admin_news/form' => '添加新闻',
            'admin_news/category' => '新闻分类',
        );

        $this->_render('_body_left', compact('leftbar_links'), 'admin/leftbar');
    }

    function action_index() {
        // 新闻所属筛选{main:总会,aa:地方校友会}
        $from = Arr::get($_GET, 'from');
        $release = Arr::get($_GET, 'release');
        $view['release'] = $release;
        $view['from'] = $from;
        $q = urldecode(Arr::get($_GET, 'q'));
        $search_type = urldecode(Arr::get($_GET, 'search_type'));
        $view['q'] = $q;
        $view['search_type'] = $search_type;

        $count = Doctrine_Query::create()
                ->select('n.id')
                ->from('News n');

        $news = Doctrine_Query::create()
                ->select('n.id,n.title,n.is_release,n.create_at,n.user_id,n.title_color,n.update_at,n.is_top,n.author_name,n.is_pic,n.is_focus')
                ->addSelect('sf.id AS is_home,c.name AS category_name')
                ->addSelect('u.realname AS realname')
                ->from('News n')
                ->leftJoin('n.NewsCategory c')
                ->leftJoin('n.SysFilter sf')
                ->leftJoin('n.User u')
                ->where('n.is_draft = ?', FALSE);

        //总会新闻
        if ($from == 'main') {
            $count->leftJoin('n.NewsCategory c')->andWhere('c.aa_id = ?', 0);
            $news->andWhere('c.aa_id = ?', 0)
                    ->orderBy('n.create_at DESC');
        }
        //地方校友会
        elseif ($from == 'aa') {
            $count->leftJoin('n.NewsCategory c')->andWhere('c.aa_id > ?', 0);
            $news->andWhere('c.aa_id > ?', 0)
                    ->addSelect('aa.id AS aa_id,aa.sname AS aa_name')
                    ->leftJoin('c.Aa aa')
                    ->orderBy('n.create_at DESC');
        }
        //俱乐部
        elseif ($from == 'club') {
            $count->leftJoin('n.NewsCategory c')->andWhere('c.club_id > ?', 0);
            $news->andWhere('c.club_id > ?', 0)
                    ->addSelect('club.id AS club_id,club.name AS club_name')
                    ->leftJoin('c.Club club')
                    ->orderBy('n.create_at DESC');
        }
        //首页显示
        elseif ($from == 'home') {
            $news->andWhere('sf.news_id > 0')
                    ->orderBy('n.create_at DESC');
        }
        //默认
        else {
            $news->orderBy('n.create_at DESC');
        }

        if ($release) {
            $count->andWhere('is_release =1');
            $news->andWhere('is_release=1')
                    ->orderBy('n.create_at DESC');
        }

        if ($release === '0') {
            $count->andWhere('is_release =0');
            $news->andWhere('is_release=0')
                    ->orderBy('n.create_at DESC');
        }

        if ($q) {
            if ($search_type == 'title') {
                $count->andWhere('n.title LIKE ?', '%' . $q . '%');
                $news->andWhere('n.title LIKE ?', '%' . $q . '%');
            } else {
                $count->andWhere('n.author_name LIKE ?', '%' . $q . '%');
                $news->andWhere('n.author_name LIKE ?', '%' . $q . '%');
            }
        }

        if (!$_GET) {
            $news->orderBy('n.is_top DESC,n.create_at DESC');
        }


        $total_items = $count->count();

        $pager = Pagination::factory(array(
                    'total_items' => $total_items,
                    'items_per_page' => 15,
                    'view' => 'pager/common',
        ));

        $view['pager'] = $pager;

        $view['news'] = $news->offset($pager->offset)
                ->limit($pager->items_per_page)
                ->fetchArray();

        $this->_title('新闻列表');
        $this->_render('_body', $view);
    }

    function action_homepage() {
        $id = Arr::get($_POST, 'id');

        $filter = Doctrine_Query::create()
                ->from('SysFilter sf')
                ->where('sf.news_id = ?', $id)
                ->fetchOne();

        if ($filter) {
            $filter->delete();
        } else { // 新加入
            $new_sf = new SysFilter();
            $new_sf->news_id = $id;
            $new_sf->save();
        }
    }

    function action_pic() {
        $del = Arr::get($_GET, 'del');
        $news_id = Arr::get($_GET, 'news_id', 0);

        // 删除操作
        if ($del) {
            Doctrine_Query::create()
                    ->delete('NewsPic')
                    ->where('id = ?', $del)
                    ->execute();
        }

        if ($_POST) {
            $valid = Validate::factory($_FILES);
            $valid->rules('pic', Model_Album::$up_rule);
            if (!$valid->check()) {
                $view['err'] = $valid->outputMsg($valid->errors('validate'));
            } else {
                $valid = Validate::setRules($_POST, 'newspic');
                $post = $valid->getData();
                if (!$valid->check()) {
                    $view['err'] = $valid->outputMsg($valid->errors('validate'));
                } else {
                    $img = file_get_contents($_FILES['pic']['tmp_name']);
                    $newspic = new NewsPic();
                    $newspic->title = $post['title'];
                    $newspic->url = $post['url'];
                    $newspic->news_id = $post['news_id'];
                    $newspic->pic = Image::blob_resize($img, 663, 185);
                    $newspic->save();
                    $this->request->redirect('admin_news/pic');
                }
            }
        }

        $news = Doctrine_Query::create()
                ->from('News n')
                ->where('n.id = ?', $news_id)
                ->fetchOne();

        $view['news'] = $news;

        $newspic = Doctrine_Query::create()
                ->select('title, url')
                ->from('NewsPic')
                ->orderBy('id DESC')
                ->fetchArray();
        $view['newspics'] = $newspic;

        $this->_title('新闻图片');
        $this->_render('_body', $view);
    }

    //添加或修改内容
    function action_form() {
        $id = Arr::get($_GET, 'id', 0);
        $view['err'] = '';

        $news_special = Doctrine_Query::create()
                ->from('NewsSpecial')
                ->orderBy('id ASC')
                ->fetchArray();
        $view['news_special'] = $news_special;

        $news = Doctrine_Query::create()
                ->from('News n')
                ->select('n.*,(SELECT c.news_category_id FROM NewsCategorys c WHERE c.news_id=n.id LIMIT 1) AS news_category')
                ->where('n.id = ?', $id)
                ->fetchOne();

        $news_category = Doctrine_Query::create()
                ->from('NewsCategory')
                ->where('aa_id=?', 0)
                ->orderBy('order_num ASC')
                ->fetchArray();
        $view['news_category'] = $news_category;

        $view['aa_news_category'] = Doctrine_Query::create()
                ->select('c.*,a.id,a.sname,a.name')
                ->from('NewsCategory c')
                ->leftJoin('c.Aa a')
                ->where('c.id = ?', $news['category_id'])
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

        $view['news'] = $news;
        $this->_render('_body', $view);
    }

    //新闻分类
    function action_category() {

        if ($_POST) {
            $v = Validate::setRules($_POST, 'news_category');
            $post = $v->getData();
            if (!$v->check()) {
                echo Candy::MARK_ERR . $v->outputMsg($v->errors('validate'));
            } else {
                $category = Doctrine_Query::create()
                        ->from('NewsCategory')
                        ->where('id = ?', $post['id'])
                        ->fetchOne();

                if (!$category) {
                    $category = new NewsCategory();
                    $category->fromArray($post);
                    $category->save();
                } else {
                    $category->synchronizeWithArray($post);
                    $category->save();
                }
            }
            exit;
        }

        $view['category'] = Doctrine_Query::create()
                ->select('c.*')
                ->addSelect('(SELECT COUNT(cs.id) FROM NewsCategorys cs WHERE cs.news_category_id = c.id) AS news_count')
                ->from('NewsCategory c')
                ->where('c.aa_id = ?', 0)
                ->orderBy('c.order_num ASC')
                ->fetchArray();

        $this->_title('新闻分类');
        $this->_render('_body', $view);
    }

    //新闻专题
    function action_special() {
        if ($_POST) {
            $v = Validate::setRules($_POST, 'news_special');
            $post = $v->getData();
            $special = Doctrine_Query::create()
                    ->from('NewsSpecial')
                    ->where('id = ?', Arr::get($_GET, 'special_id'))
                    ->fetchOne();
            if (!$special) {
                $special = new NewsSpecial();
                $special->fromArray($post);
                $special->save();
            } else {
                unset($post['id']);
                $post['is_displayweibo_on_home'] = isset($post['is_displayweibo_on_home']) ? True : False;
                $post['is_displaycomment_on_home'] = isset($post['is_displaycomment_on_home']) ? True : False;
                $special->synchronizeWithArray($post);
                $special->save();
            }
        }

        $view['all_special'] = Doctrine_Query::create()
                ->from('NewsSpecial s')
                ->select('s.*')
                ->addSelect('(SELECT a.id FROM Album a WHERE a.special_id = s.id ORDER BY a.id DESC LIMIT 1) AS album_id')
                ->orderBy('s.id DESC')
                ->fetchArray();

        $special_id = Arr::get($_GET, 'special_id', 0);
        $view['special'] = Doctrine_Query::create()
                ->from('NewsSpecial')
                ->where('id = ?', $special_id)
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

        if (!$view['special']) {
            $view['btn'] = '创建';
        } else {
            $view['btn'] = '保存修改';
        }

        $this->_title('新闻专题');
        $this->_render('_body', $view);
    }

    function action_getCategoryForm() {
        $this->auto_render = FALSE;
        $category_id = Arr::get($_GET, 'category_id', 0);

        $category = Doctrine_Query::create()
                ->from('NewsCategory')
                ->where('aa_id = ?', 0);

        $view['total'] = $category->count();

        $view['category'] = $category
                ->andWhere('id = ?', $category_id)
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

        if (!$view['category']) {
            $view['btn'] = '创建';
        } else {
            $view['btn'] = '保存修改';
        }

        echo View::factory('admin_news/category_form', $view);
    }

    //删除新闻
    function action_del() {
        $this->auto_render = FALSE;
        $cid = Arr::get($_GET, 'cid');

        $news = Doctrine_Query::create()
                ->select('*')
                ->from('News')
                ->where('id =?', $cid)
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

        if ($news['img_path']) {
            @unlink($news['img_path']);
        }

        //操作日志
        $log_data = array();
        $log_data['type'] = '删除新闻';
        $log_data['news_id'] = $cid;
        $log_data['description'] = '删除了新闻“' . $news['title'] . '”及其所有评论信息';
        Common_Log::add($log_data);


        $del = Doctrine_Query::create()
                ->delete('News')
                ->where('id =?', $cid)
                ->execute();

        $del = Doctrine_Query::create()
                ->delete('NewsCategorys')
                ->where('news_id =?', $cid)
                ->execute();

        $del = Doctrine_Query::create()
                ->delete('NewsTags')
                ->where('news_id =?', $cid)
                ->execute();

        $del = Doctrine_Query::create()
                ->delete('SysFilter')
                ->where('news_id =?', $cid)
                ->execute();
    }

    //删除分类
    function action_delcategory() {
        $this->auto_render = FALSE;
        $cid = Arr::get($_GET, 'cid');

        $category = Doctrine_Query::create()
                ->select('*')
                ->from('NewsCategory')
                ->where('id =?', $cid)
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

        if ($category) {
            $del = Doctrine_Query::create()
                    ->delete('News')
                    ->where('category_id=?', $cid)
                    ->execute();

            $del = Doctrine_Query::create()
                    ->delete('NewsCategory')
                    ->where('id =?', $cid)
                    ->execute();
        }

        //操作日志
        $log_data = array();
        $log_data['type'] = '删除新闻分类';
        $log_data['description'] = '删除了新闻分类“' . $category['name'] . '”，包含其中' . $news_count . '条新闻';
        Common_Log::add($log_data);
    }

    function action_fixed() {
        $this->auto_render = FALSE;
        $cid = Arr::get($_POST, 'cid');
        $news = Doctrine_Query::create()
                ->from('News')
                ->where('id = ?', $cid)
                ->fetchOne();
        if ($news['is_fixed'] == TRUE) {
            $news['is_fixed'] = FALSE;
        } else {
            $news['is_fixed'] = TRUE;
        }
        $news->save();
    }

    function action_focus() {
        $this->auto_render = FALSE;
        $cid = Arr::get($_POST, 'cid');
        $news = Doctrine_Query::create()
                ->from('News')
                ->where('id = ?', $cid)
                ->fetchOne();
        if ($news['is_focus'] == TRUE) {
            $news['is_focus'] = FALSE;
        } else {
            $news['is_focus'] = TRUE;
        }
        $news->save();
    }

    //设置为头条
    function action_top() {
        $this->auto_render = FALSE;
        $cid = Arr::get($_POST, 'cid');
        $news = Doctrine_Query::create()
                ->from('News')
                ->where('id = ?', $cid)
                ->fetchOne();
        if ($news['is_top'] == TRUE) {
            $news['is_top'] = FALSE;
        } else {
            $news['is_top'] = TRUE;
        }
        $news->save();
    }

}