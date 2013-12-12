<?php

class Controller_Aa_Admin_Event extends Layout_Aa {

    function before() {
        parent::before();
        // 管理组成员
        if(!$this->_aa_manager){
            Request::instance()->redirect('main/deny?reason=' . urlencode('很抱歉，您没有管理权限'));
            exit;
        }

        $actions = array(
            'event/form' => '发布活动',
            'aa_admin_event/index' => '管理活动',
        );
        $this->_render('_body_action', compact('actions'), 'aa_global/admin_action');
    }

    function action_index() {
        $q = urldecode(Arr::get($_GET, 'q'));

        $event = Doctrine_Query::create()
                        ->select('e.*')
                        ->addSelect('u.realname AS realname')
                       ->addSelect('IF(e.finish >= now(),TIMESTAMPDIFF(MINUTE, now(), e.start),900000) AS can_sign')
                        ->from('Event e')
                        ->where('aa_id=?', Arr::get($_GET, 'id'))
                        ->leftJoin('e.User u')
                        ->orderBy('is_fixed DESC,can_sign ASC,e.start DESC');;

        if ($q) {
            $event->where('e.title LIKE ?', '%' . $q . '%');
        }

        $total_events = $event->count();

        $pager = Pagination::factory(array(
                    'total_items' => $total_events,
                    'items_per_page' => 10,
                    'view' => 'pager/common',
                ));

        $events = $event->offset($pager->offset)
                        ->limit($pager->items_per_page)
                        ->fetchArray();

        $this->_title($this->_aa['name'] . '的活动');
        $this->_render('_body', compact('events', 'pager'));
    }

    //修改某些值
    //aa_id=$this->id
    function action_set() {
        if ($_POST) {
            $cid = Arr::get($_POST, 'cid');
            $field = Arr::get($_POST, 'field');
            $bool_field = Arr::get($_POST, 'bool_field');
            $field_value = Arr::get($_POST, 'field_value');

            $event = Doctrine_Query::create()
                    ->from('Event e')
                    ->where('e.aa_id = ?', $this->_id)
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