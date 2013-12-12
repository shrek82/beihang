<?php

class Controller_Admin_Club extends Layout_Admin {

    function before() {
        parent::before();
    }

    function action_index() {

        $q = urldecode(Arr::get($_GET, 'q'));
        $view['q'] = $q;

        $club = Doctrine_Query::create()
                ->select('c.id,c.aa_id,c.name,c.type,a.name AS aa_name')
                ->addSelect('(SELECT COUNT(t.id) FROM ClubMember t WHERE t.club_id = c.id) AS total_member')
                ->addSelect('(SELECT COUNT(j.id) FROM JoinApply j WHERE j.club_id = c.id AND j.is_reject=False) AS total_join')
                ->from('Club c')
                ->leftJoin('c.Aa a');

        //搜索关键字
        if ($q) {
            $club->addWhere('c.name LIKE ?', '%' . $q . '%');
        }

        $club->orderBy('c.id DESC');
        $total_club = $club->count();

        $pager = Pagination::factory(array(
                    'total_items' => $total_club,
                    'items_per_page' => 15,
                    'view' => 'pager/common',
                ));

        $view['pager'] = $pager;
        $view['club'] = $club->offset($pager->offset)
                ->limit($pager->items_per_page)
                ->fetchArray();

        $this->_title('俱乐部列表');
        $this->_render('_body', $view);
    }

    //删除俱乐部
    function action_del(){
        $cid=  Arr::get($_GET,'cid');
        $action=Model_Club::del($cid);
    }

}