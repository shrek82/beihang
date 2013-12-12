<?php

class Controller_User_Event extends Layout_User {

    function before() {
        parent::before();

        $topbar_links = array(
            'user_event/index' => '我发起的活动',
            'user_event/join' => '参加的活动',
            'user_event/form' => '发起活动',
        );
        View::set_global('topbar_links', $topbar_links);
    }

    function action_index() {
        $event = Doctrine_Query::create()
                        ->select('e.*')
                        ->from('Event e')
                        ->where('e.user_id = ?', $this->_user_id)
                        ->orderBy('e.publish_at DESC');

        $total_events = $event->count();

        $pager = Pagination::factory(array(
                    'total_items' => $total_events,
                    'items_per_page' => 6,
                    'view' => 'pager/common',
                ));

        $events = $event->offset($pager->offset)
                        ->limit($pager->items_per_page)
                        ->fetchArray();

        $this->_title('我发起的活动');
        $this->_render('_body', compact('events', 'pager'));
    }

    function action_join() {
        $ids = Model_Event::joinIDs($this->_user_id);

        $event = Doctrine_Query::create()
                        ->select('e.*')
                        ->from('Event e')
                        ->whereIn('e.id', $ids)
                        ->orderBy('e.publish_at DESC');

        $total_events = $event->count();

        $pager = Pagination::factory(array(
                    'total_items' => $total_events,
                    'items_per_page' => 10,
                    'view' => 'pager/common',
                ));

        $events = $event->offset($pager->offset)
                        ->limit($pager->items_per_page)
                        ->fetchArray();

        $this->_title('我参加的活动');
        $this->_render('_body', compact('events', 'pager'));
    }
}