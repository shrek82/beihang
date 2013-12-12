<?php

/**
 * 全局搜索组件，ajax调用
 */
class Controller_Search extends Layout_Main {

    function before() {
        $this->template = 'layout/search';
        parent::before();
    }

    # 分配搜索关键字的显示页面

    function action_index() {
        $view['q'] = urldecode(Arr::get($_GET, 'q'));
        $view['for'] = Arr::get($_GET, 'for', 'news');
        $view['user_id'] = Arr::get($_GET, 'user_id');
        $from = Arr::get($_GET, 'from');

        //原有电子信息报自动跳转
        if ($from == 'eleReport') {
            $this->eleReport($view['q'], $view['for']);
        } else {
            $this->_title('全站搜索');
            $this->_render('_body', $view);
        }
    }

    function eleReport($q, $for) {
        if ($for == 'news') {
            $news = Doctrine_Query::create()
                    ->from('News')
                    ->where('title LIKE ?', '%' . $q . '%')
                    ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
            if (!$news) {
                $this->request->redirect('search?q=' . urlencode($q) . '&for=news');
            } else {
                $this->request->redirect(URL::site('news/view?id=' . $news['id']));
            }
        }

        if ($for == 'bbs') {
            $bbs = Doctrine_Query::create()
                    ->from('BbsUnit')
                    ->where('title LIKE ?', '%' . $q . '%')
                    ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

            if (!$bbs) {
                $this->request->redirect('search?q=' . urlencode($q) . '&for=bbs');
            } else {
                $this->request->redirect(URL::site('bbs/viewPost?id=' . $bbs['id']));
            }
        }
    }

    function action_org() {
        $view['q'] = urldecode(Arr::get($_POST, 'q'));

        $view['for'] = Arr::get($_GET, 'for', 'org');
        if ($view['q']) {
            $view['org'] = Doctrine_Query::create()
                    ->select('a.id,a.name,a.sname,a.ename,(SELECT COUNT(m.id) FROM AaMember m WHERE a.id = m.aa_id) AS mcount')
                    ->from('Aa a')
                    ->where('a.name LIKE ?', '%' . $view['q'] . '%')
                    ->limit(15)
                    ->fetchArray();
        } else {
            $view['org'] = null;
        }

        echo View::factory('search/org', $view);
    }

    function action_event() {
        # 活动关键字搜索
        $q = urldecode(Arr::get($_POST, 'q'));
        $view['q'] = $q;

        $page = Arr::get($_POST, 'page', 1);
        $view['page'] = $page;

        # 活动分类
        $type = urldecode(Arr::get($_POST, 'type'));
        $view['type'] = $type;

        # 内容过滤 {sign:可以报名,start:未开始的,finish:结束的}
        $filter = Arr::get($_POST, 'filter');

        # 顺序
        $order = Arr::get($_POST, 'order', 'ASC');

        if ($q) {

            # 校友自主活动列表
            $event = Doctrine_Query::create()
                    ->select('e.id,e.title,e.sign_limit,e.start,e.finish,e.address,e.type,e.aa_id,e.club_id,e.is_fixed,e.publish_at,e.tags, a.name, c.name')
                    ->addSelect('(SELECT SUM(ss.num) FROM EventSign ss WHERE ss.event_id = e.id) AS sign_num')
                    ->from('Event e')
                    ->leftJoin('e.Aa a')
                    ->leftJoin('e.Club c')
                    ->where('e.is_closed = ? OR e.is_closed IS NULL', FALSE);

            if ($type) {
                $event->andWhere('e.type = ?', $type);
            }

            if ($q) {
                $event->andWhere('e.title LIKE ?', '%' . $q . '%');
            }

            switch ($filter) {
                case 'sign':
                    $event->addWhere('e.sign_finish > curdate()');
                    $event->orderBy('e.sign_start ' . $order);
                    break;
                case 'start':
                    $event->addWhere('e.finish > curdate()');
                    $event->orderBy('e.start ' . $order);
                    break;
                case 'finish':
                    $event->addWhere('e.finish < curdate()');
                    $order = $order == 'ASC' ? 'DESC' : 'ASC';
                    $event->orderBy('e.finish ' . $order);
                    break;
                default:
                    $event->orderBy('IF (e.sign_start < curdate() and e.sign_finish > curdate(),
                                   TIMESTAMPDIFF(MINUTE, curdate(), e.sign_finish),
                                   TIMESTAMPDIFF(MINUTE, e.start, curdate())) ASC');
                    break;
            }

            $pager = Pagination::factory(array(
                        'type' => 'event',
                        'items_per_page' => 10,
                        'total_items' => $event->count(),
                        'view' => 'inc/pager/search',
                    ));

            $pager->set_current_page($page);


            $view['pager'] = $pager;

            $view['events'] = $event->offset(($page - 1) * 10)
                    ->limit($pager->items_per_page)
                    ->fetchArray();
        } else {
            $view['events'] = null;
            $view['pager'] = null;
        }

        echo View::factory('search/event', $view);
    }

    function action_news() {
        $q = urldecode(Arr::get($_POST, 'q'));
        $page = Arr::get($_POST, 'page', 1);

        if ($q) {
            $news = Doctrine_Query::create()
                    ->from('News n')
                    ->leftJoin('n.Tags t')
                    ->where('is_release = ? AND is_draft = ?', array(TRUE, FALSE))
                    ->andWhere('n.title LIKE ? OR t.name = ?', array('%' . $q . '%', $q))
                    ->orderBy('n.create_at DESC');

            $pager = Pagination::factory(array(
                        'type' => 'news',
                        'total_items' => $news->count(),
                        'items_per_page' => 10,
                        'view' => 'inc/pager/search',
                    ));

            $pager->set_current_page($page);

            $view['pager'] = $pager;
            $view['tag'] = $q;
            $view['news'] = $news->limit($pager->items_per_page)
                    ->offset($pager->offset)
                    ->fetchArray();
        } else {
            $view['news'] = null;
            $view['pager'] = null;
        }

        echo View::factory('search/news', $view);
    }

    function action_user() {
        $name = Arr::get($_POST, 'q');
        $view['name'] = $name;
        $city = urldecode(Arr::get($_POST, 'city'));
        $view['city'] = $city;
        $page = Arr::get($_POST, 'page', 1);

        if ($name) {


            // 现有注册校友城市列表
            $user_city = Doctrine_Query::create()
                    ->distinct()
                    ->select('u.city,u.sex')
                    ->from('User u');

            $view['cities'] = $user_city->useResultCache(true, 3600, 'search_u_city')
                    ->execute(array(), 6);

            // 校友列表
            $user = Doctrine_Query::create()
                    ->select('u.realname, u.sex,u.city,u.sex')
                    ->from('User u');

            if ($name) {
                $user->andWhere('u.realname LIKE ?', '%' . $name . '%');
            }

            if ($city) {
                $user->andWhere('u.city = ?', $city);
            }

            $pager = Pagination::factory(array(
                        'type' => 'user',
                        'total_items' => $user->count(),
                        'items_per_page' => 40,
                        'view' => 'inc/pager/search',
                    ));

            $pager->set_current_page($page);

            $view['pager'] = $pager;
            $view['users'] = $user->offset($pager->offset)
                    ->limit($pager->items_per_page)
                    ->fetchArray();
        } else {
            $view['users'] = null;
        }

        echo View::factory('search/user', $view);
    }

    //班级
    function action_classroom() {
        $name = Arr::get($_POST, 'q');

        $page = Arr::get($_POST, 'page');
        $view['name'] = $name;

        if ($name) {

            $classroom = Doctrine_Query::create()
                    ->select('cr.*')
                    ->addSelect('(SELECT COUNT(cm.id) FROM ClassMember cm WHERE cm.class_room_id=cr.id) AS membercount')
                    ->addSelect('m.user_id AS user_id')
                    ->addSelect('u.realname AS realname')
                    ->from('ClassRoom cr')
                    ->leftJoin('cr.Members m')
                    ->leftJoin('m.User u')
                    ->where('cr.create_at IS NOT NULL'); // 只显示激活的班级

            if ($name) {
                $classroom->addWhere('cr.speciality LIKE ?', '%' . $name . '%');
            }

            $pager = Pagination::factory(array(
                        'type' => 'classroom',
                        'total_items' => $classroom->count(),
                        'items_per_page' => 10,
                        'view' => 'inc/pager/search',
                    ));

            $view['pager'] = $pager;
            $view['classroom'] = $classroom->orderBy('create_at DESC')
                    ->offset(($page - 1) * 10)
                    ->limit($pager->items_per_page)
                    ->fetchArray();
        } else {
            $view['classroom'] = null;
        }

        echo View::factory('search/classroom', $view);
    }

    function action_bbs() {
        $q = urldecode(Arr::get($_POST, 'q'));
        $page = Arr::get($_POST, 'page', 1);
        $user_id = Arr::get($_POST, 'user_id');
        $user_id = $user_id ? $user_id : Arr::get($_GET, 'user_id');
        $view['config']['type'] = 'bbs';

        if ($q OR $user_id) {
            $bbs_unit = Doctrine_Query::create()
                    ->select('u.id,u.title,u.reply_num,u.create_at,u.user_id,u.type,u.hit,u.reply_num,s.realname AS realname')
                    ->from('BbsUnit u')
                    ->leftJoin('u.User s');

            if ($q) {
                $bbs_unit->andWhere('u.title like ?', '%' . $q . '%');
            }

            if ($user_id) {
                $bbs_unit->andWhere('u.user_id=?', $user_id);
            }

            $pager = Pagination::factory(array(
                        'type' => 'bbs',
                        'total_items' => $bbs_unit->count(),
                        'items_per_page' => 10,
                        'view' => 'inc/pager/search',
                    ));

            $pager->set_current_page($page);

            $view['pager'] = $pager;
            $view['tag'] = $q;
            $view['unit'] = $bbs_unit->limit($pager->items_per_page)
                    ->offset($pager->offset)
                    ->orderBy('u.id DESC')
                    ->fetchArray();
        } else {
            $view['unit'] = null;
            $view['pager'] = null;
        }

        echo View::factory('search/bbs', $view);
    }

}