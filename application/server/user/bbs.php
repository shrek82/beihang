<?php

class Controller_User_Bbs extends Layout_User
{
    function before()
    {
        parent::before();

        $topbar_links = array(
            //'user_bbs/post' => '发帖',
            'user_bbs/index' => '我的帖子',
            'user_bbs/reply' => '我的回帖',
        );
        View::set_global('topbar_links', $topbar_links);
    }

    function action_reply()
    {
        $unit = Doctrine_Query::create()
                ->distinct('bu.id')
                ->from('BbsUnit bu')
                ->leftJoin('bu.Comments c')
                ->where('c.user_id = ?', $this->_user_id)
                ->orderBy('c.post_at DESC');

        $total_unit = $unit->count();

        $pager = Pagination::factory(array(
            'total_items' => $total_unit,
            'items_per_page' => 15,
            'view' => 'pager/common',
        ));
        $view['pager'] = $pager;

        $view['units'] = $unit->offset($pager->offset)
                              ->limit($pager->items_per_page)
                              ->fetchArray();

        $this->_title('我的回帖');
        $this->_render('_body', $view);
    }

    function action_index()
    {
        $q = urldecode(trim(Arr::get($_GET, 'q')));
        $unit = Doctrine_Query::create()
                    ->from('BbsUnit u')
                    ->where('u.user_id = ?', $this->_user_id)
                    ->orderBy('u.create_at DESC');

        $total_unit = $unit->count();

        $pager = Pagination::factory(array(
            'total_items' => $total_unit,
            'items_per_page' => 15,
            'view' => 'pager/common',
        ));
        $view['pager'] = $pager;

        $view['units'] = $unit->offset($pager->offset)
                              ->limit($pager->items_per_page)
                              ->fetchArray();

        $this->_title('我的帖子');
        $this->_render('_body', $view);
    }
}