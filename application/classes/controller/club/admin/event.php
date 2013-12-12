<?php

class Controller_Club_Admin_Event extends Layout_Clubadmin {

    function before() {
        parent::before();

        $actions = array(
            'event/form' => '发布活动',
            'club_admin_event/index' => '管理活动',
        );
        $this->_render('_body_action', compact('actions'), 'club_global/admin_action');
    }

    //俱乐部活动列表
    function action_index() {
        $event = Doctrine_Query::create()
                ->select('e.*')
                ->addSelect('u.realname AS realname,a.name AS aa_name')
                ->addSelect('IF(e.start >= now(),TIMESTAMPDIFF(DAY, now(), e.start),10000) AS can_sign')
                ->from('Event e')
                ->leftJoin('e.Aa a')
                ->leftJoin('e.User u')
                ->where('e.club_id = ?', $this->_id)
                ->orderBy('is_club_fixed DESC,can_sign ASC,e.start DESC');

        $total_events = $event->count();

        $pager = Pagination::factory(array(
                    'total_items' => $total_events,
                    'items_per_page' => 10,
                    'view' => 'pager/common',
                ));

        $events = $event->offset($pager->offset)
                ->limit($pager->items_per_page)
                ->fetchArray();

        $this->_title($this->_club['name'] . '的活动');
        $this->_render('_body', compact('events', 'pager'));
    }

    //修改某些值
    //club_id=$this->id
    function action_set() {
        if ($_POST) {
            $cid = Arr::get($_POST, 'cid');
            $field = Arr::get($_POST, 'field');
            $bool_field = Arr::get($_POST, 'bool_field');
            $field_value = Arr::get($_POST, 'field_value');

            $event = Doctrine_Query::create()
                    ->from('Event e')
                    ->where('e.club_id = ?', $this->_id)
                    ->andWhere('e.id = ?', $cid)
                    ->fetchOne();

            //修改bool值
            if ($event && $bool_field && isset($event[$bool_field])) {
                $event[$bool_field] = $event[$bool_field] ? FALSE : TRUE;
                $event->save();
            }
            //修改其他字段
            elseif ($event && $field) {
                $event[$field] = $field_value;
                $event->save();
            } else {
                return false;
            }
        }
    }



}