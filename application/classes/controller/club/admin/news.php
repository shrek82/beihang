<?php

class Controller_Club_Admin_News extends Layout_Clubadmin {

    function before() {
        parent::before();

        $actions = array(
            'club_admin_news/index' => '新闻列表',
            'club_admin_news/form' => '添加新闻',
            'club_admin_news/category' => '新闻分类',
        );
        $this->_render('_body_action', compact('actions'), 'club_global/admin_action');
    }

    function action_index() {
        $news = Doctrine_Query::create()
                ->select('n.id,n.hit,n.comments_num,n.title,n.is_release,n.is_top,n.create_at,n.is_fixed')
                ->addSelect('c.club_id,c.name AS category_name')
                ->from('News n')
                ->leftJoin('n.NewsCategory c')
                ->where('c.club_id = ?', $this->_id)
                ->orderBy('n.is_fixed DESC,n.update_at DESC');

        $total_items = $news->count();

        $pager = Pagination::factory(array(
                    'total_items' => $total_items,
                    'items_per_page' => 12,
                    'view' => 'pager/common',
                ));

        $view['pager'] = $pager;

        // view
        $view['news'] = $news->offset($pager->offset)
                ->limit($pager->items_per_page)
                ->fetchArray();

        $this->_title('新闻列表');
        $this->_render('_body', $view);
    }

    function action_form() {
        $news_id = Arr::get($_GET, 'news_id', 0);

        $news = Doctrine_Query::create()
                ->select('n.*,c.club_id AS club_id,c.id AS news_category_id')
                ->from('News n')
                ->leftJoin('n.NewsCategory c')
                ->where('n.id = ?', $news_id)
                ->fetchOne();

        $category = Doctrine_Query::create()
                ->from('NewsCategory')
                ->where('club_id = ?', $this->_id)
                ->addWhere('is_public=?', TRUE)
                ->orderBy('order_num ASC')
                ->fetchArray();

        $view['news'] = $news;
        $view['category'] = $category;
        $this->_title('发布新闻');
        $this->_render('_body', $view);
    }

    function action_category() {
        $view['cid'] = Arr::get($_GET, 'cid', 0);
        $cur_cat = Doctrine_Query::create()
                ->from('NewsCategory')
                ->where('id = ?', $view['cid']);

        // post处理
        if ($_POST) {
            // 重排列
            if (isset($_POST['sort_order'])) {
                $ids = explode('|', $_POST['sort_order']);
                foreach ($ids as $i => $id) {
                    if ($id != '') {
                        Doctrine_Query::create()
                                ->update('NewsCategory')
                                ->set('order_num', $i)
                                ->where('id = ?', $id)
                                ->execute();
                    }
                }
                exit();
            }

            $valid = Validate::setRules($_POST, 'category');
            $post = Validate::getData();
            if (!$valid->check()) {
                echo Candy::MARK_ERR . $valid->outputMsg($valid->errors('validate'));
            } else {
                if ($view['cid'] == 0) {
                    // 新分类
                    $cur_cat->removeDqlQueryPart('where')
                            ->where('club_id = ? AND name = ?', array($this->_id, $post['name']));
                    $exist = $cur_cat->count();
                    if ($exist > 0) {
                        echo Candy::MARK_ERR . '同样的名称已经存在，请更换';
                    } else {
                        $post['club_id'] = $this->_id;
                        $newscat = new NewsCategory();
                        $newscat->fromArray($post);
                        $newscat->save();
                    }
                } else {
                    // 更新
                    $c = $cur_cat->fetchOne();
                    $c->synchronizeWithArray($post);
                    $c->save();
                }
            }
            exit;
        }

        // 现有分类
        $category = Doctrine_Query::create()
                ->from('NewsCategory nc')
                ->select('nc.*')
                ->addSelect('(SELECT COUNT(cs.id) FROM NewsCategorys cs WHERE cs.news_category_id = nc.id) AS news_count')
                ->where('nc.club_id = ?', $this->_id)
                ->orderBy('order_num ASC')
                ->fetchArray();

        $view['cur_cat'] = $cur_cat->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
        $view['btn'] = $view['cid'] == 0 ? '创建' : '保存';
        $view['category'] = $category;
        $view['club_id'] = $this->_id;

        // view
        $this->_title('新闻分类');
        $this->_render('_body', $view);
    }

    //删除分会新闻分类
    function action_delcategory() {
        $this->auto_render = FALSE;
        $cid = Arr::get($_GET, 'cid');

        $category = Doctrine_Query::create()
                ->select('*')
                ->from('NewsCategory')
                ->where('id =?', $cid)
                ->addWhere('club_id =?', $this->_id)
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

        if ($category) {
            $del = Doctrine_Query::create()
                    ->delete('News')
                    ->where('category_id=?', $cid)
                    ->execute();
            $del = Doctrine_Query::create()
                    ->delete('NewsCategory')
                    ->where('id =?', $cid)
                    ->addWhere('club_id =?', $this->_id)
                    ->execute();
        }
    }

    //置顶新闻
    function action_top() {
        $this->auto_render = FALSE;
        $news = Doctrine_Query::create()
                ->from('News')
                ->where('id = ?', Arr::get($_POST, 'cid'))
                ->fetchOne();

        if ($news['is_fixed'] == TRUE) {
            $news['is_fixed'] = FALSE;
        } else {
            $news['is_fixed'] = TRUE;
        }

        $news->save();
    }

    //删除新闻
    function action_del() {
        $this->auto_render = FALSE;
        $cid = Arr::get($_GET, 'cid');
        $news = Doctrine_Query::create()
                ->select('n.*,c.club_id AS club_id')
                ->from('News n')
                ->leftJoin('n.NewsCategory c')
                ->where('n.id =?', $cid)
                ->fetchOne();

        if ($news['club_id'] == $this->_id) {
            $news->delete();
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

}