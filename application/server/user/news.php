<?php

class Controller_User_News extends Layout_User {

    function before() {
        parent::before();

        $topbar_links = array(
            'user_news/index' => '我的新闻',
            'user_news/form' => '新闻投稿',
        );
        View::set_global('topbar_links', $topbar_links);
    }

    function action_index() {
        $news = Doctrine_Query::create()
                        ->from('News')
                        ->where('user_id = ?', $this->_user_id)
                        ->orderBy('update_at DESC');

        $total_news = $news->count();

        $pager = Pagination::factory(array(
                    'total_items' => $total_news,
                    'items_per_page' => 12,
                    'view' => 'pager/common',
                ));

        $view['pager'] = $pager;
        $view['news'] = $news->offset($pager->offset)
                        ->limit($pager->items_per_page)
                        ->fetchArray();

        $this->_title('我的新闻');
        $this->_render('_body', $view);
    }

    function action_form() {
        $this->userPermissions('newsPublish');
        $news_id = Arr::get($_GET, 'news_id', 0);

        $news = Doctrine_Query::create()
                        ->select('n.*,c.aa_id AS aa_id,c.id AS news_category_id')
                        ->from('News n')
                        ->leftJoin('n.NewsCategory c')
                        ->where('n.id = ?', $news_id)
                        ->fetchOne();

        // 权限检查
        if ($news) {
            if ($news['user_id'] != $this->_user_id
                    && $this->_role != '管理员') {
                Model_User::deny();
            }
        }

        $view['news'] = $news;
        $this->_title('新闻投稿');
        $this->_render('_body', $view);

    }

   

}