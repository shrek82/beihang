<?php

//校友报道及新闻
class Controller_People extends Layout_Main {

    function action_index() {
        $view['news'] = Doctrine_Query::create()
                ->select('n.title,n.create_at')
                ->from('News  n')
                ->where('n.category_id=?', 5)
                ->orderBy('create_at DESC,id DESC')
                ->limit(10)
                ->fetchArray();
        $this->_title('北航校友');
        $this->_render('_body', $view);
    }

    //历任校长
    function action_president() {
        $president_period = array(
            '1' => '所有时期'
        );

        $view['president_period'] = $president_period;

        foreach ($president_period as $key => $value) {
            $view['president'][$key] = Doctrine_Query::create()
                    ->from('President')
                    ->where('period=?', $key)
                    ->orderBy('order_num ASC,id ASC')
                    ->fetchArray();
        }

        $this->_render('_body', $view);
    }

    # 院士风采

    function action_academician() {
        $abc = Arr::get($_GET, 'abc');

        $people = Doctrine_Query::create()
                ->from('People p')
                ->orderBy('p.abc ASC');

        if ($abc) {
            $people->where('p.abc = ?', $abc);
        }

        $view['people'] = $people->fetchArray();
        $this->_title('院士风采');
        $this->_render('_body', $view);
    }

    function action_aView() {
        // 具体某人
        $id = Arr::get($_GET, 'id');
        $people = Doctrine_Query::create()
                ->from('People p')
                ->where('p.id = ?', $id)
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

        $view['people'] = $people;
        $this->_render('_body', $view);
    }

    //校友风采
    function action_news() {

        // 关键字过滤
        $q = urldecode(Arr::get($_GET, 'q'));
        $view['q'] = $q;

        $news = Doctrine_Query::create()
                ->select('n.title, n.create_at')
                ->where('n.category_id=?', 5)
                ->from('News n');

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
        $this->_title('北航校友');
        $this->_render('_body', $view);
    }

    function action_newsView() {

        $id = Arr::get($_GET, 'id');

        $news = Doctrine_Query::create()
                ->from('News')
                ->where('id = ?', $id)
                ->addWhere('category_id=5')
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

        if (!$news) {
            $this->request->redirect('main/notFound');
        }

        $relate = Doctrine_Query::create()
                ->from('News')
                ->where('id = ?', $id)
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

        $this->_title($news['title']);
        $this->_render('_body', compact('news', 'relate'));
    }

    //校友企业
    function action_enterprise() {

        // 关键字过滤
        $q = urldecode(Arr::get($_GET, 'q'));
        $view['q'] = $q;

        $news = Doctrine_Query::create()
                ->select('n.title, n.create_at')
                ->from('Content n')
                ->where('n.type=?', 12);

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
        $this->_title('校友企业');
        $this->_render('_body', $view);
    }

    function action_eView() {

        $id = Arr::get($_GET, 'id');

        $news = Doctrine_Query::create()
                ->from('Content')
                ->where('id = ?', $id)
                ->addWhere('type=12')
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

        if (!$news) {
            $this->request->redirect('main/notFound');
        }

        $relate = Doctrine_Query::create()
                ->from('Content')
                ->where('id = ?', $id)
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

        $this->_title($news['title']);
        $this->_render('_body', compact('news', 'relate'));
    }

}

?>
