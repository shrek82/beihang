<?php

class Controller_Admin_Event extends Layout_Admin {

    function before() {
        parent::before();

        $leftbar_links = array(
            'admin_event/index' => '活动列表',
            'admin_event/static' => '专题活动',
        );

        $this->_render('_body_left', compact('leftbar_links'), 'admin/leftbar');
    }

    # 专题活动管理

    function action_static() {
        // 当前操作的活动（没有则显示新档）
        $id = Arr::get($_GET, 'id', 0);
        $order = Arr::get($_GET, 'order');
        $img_path = '';
        $event = Doctrine_Query::create()
                        ->from('EventStatic')
                        ->where('id = ?', $id)
                        ->fetchOne();
        $file_name = date("YmdHis");

        if ($order && $event) {
            $event['order_num'] = $order;
            $event->save();
            exit();
        }

        $view['event'] = $event;
        $view['err'] = '';

        if ($_POST) {

            if ($_FILES['banner']['size'] > 0) {
                // 上传的banner
                $valid = Validate::factory($_FILES);
                $valid->rules('banner', Model_Album::$up_rule);
                if (!$valid->check()) {
                    $view['err'] .= $valid->outputMsg($valid->errors('validate'));
                } else {
                    // 处理banner图片
                    $path = DOCROOT . Model_Event::BANNER_DIR;
                    $file_suffix = preg_replace('/image\//', '', $_FILES['banner']['type']);
                    Upload::save($_FILES['banner'], $file_name . '.' . $file_suffix, $path);
                    $img_path = URL::base() . Model_Event::BANNER_DIR . $file_name . '.' . $file_suffix;
                }
            }

            // 活动内容数据
            $valid = Validate::setRules($_POST, 'event_static');
            $post = $valid->getData();
            $post['is_closed'] = Arr::get($_POST, 'is_closed') ? TRUE : FALSE;
            if (!$valid->check()) {
                $view['err'] .= $valid->outputMsg($valid->errors('validate'));
            } else {

                if ($img_path) {
                    $post['img_path'] = $img_path;
                }
                // 更新活动 or 创建活动
                if ($event) {
                    unset($post['id']);
                    $event->synchronizeWithArray($post);
                    $event->save();
                } else {
                    $event = new EventStatic();
                    $event->fromArray($post);
                    $event->save();
                }

                // 处理完毕后刷新页面
                // $this->request->redirect('admin_event/static');
            }
        }

        // 获取现有专题列表
        $view['events'] = Doctrine_Query::create()
                        ->select('title, order_num, is_closed')
                        ->from('EventStatic')
                        ->orderBy('order_num ASC')
                        ->fetchArray();

        $this->_render('_body', $view);
    }

    function action_index() {
        $q = urldecode(Arr::get($_GET, 'q'));
        $state = Arr::get($_GET, 'state');
        $count = Doctrine_Query::create()
                        ->select('e.id')
                        ->from('Event e');
        
        $event = Doctrine_Query::create()
                        ->select('e.*')
                        ->addSelect('sf.id AS is_home,a.name AS aa_name,u.realname AS realname')
                        ->addSelect('IF(e.start >= now(),TIMESTAMPDIFF(DAY, now(), e.start),10000) AS can_sign')
                        ->from('Event e')
                        ->leftJoin('e.SysFilter sf')
                        ->leftJoin('e.Aa a')
                        ->leftJoin('e.User u')
                        ->orderBy('is_fixed DESC,can_sign ASC,e.start DESC');

        if ($q) {
            $count->where('e.title LIKE ?', '%' . $q . '%');
            $event->where('e.title LIKE ?', '%' . $q . '%');
        }

        $total_events = $count->count();

        $pager = Pagination::factory(array(
                    'total_items' => $total_events,
                    'items_per_page' => 10,
                    'view' => 'pager/common',
                ));

        $events = $event->offset($pager->offset)
                        ->limit($pager->items_per_page)
                        ->fetchArray();

        $this->_title('本站所有的活动');
        $this->_render('_body', compact('state', 'events', 'pager'));
    }

    //修改活动分类
    function action_setType() {
        $this->auto_render = FALSE;
        $val = Arr::get($_GET, 'val');

        $event = Doctrine_Query::create()
                        ->from('Event e')
                        ->where('e.id = ?', Arr::get($_GET, 'id'))
                        ->fetchOne();
        if ($event) {
            $event['type'] = $val;
            $event->save();
        }
    }


    function action_homepage() {
        $id = Arr::get($_POST, 'id');
        $num = Arr::get($_POST, 'n');

        $filter = Doctrine_Query::create()
                        ->from('SysFilter sf')
                        ->where('sf.event_id = ?', $id)
                        ->fetchOne();

        if ($filter) {
            $filter->delete();
        } else { // 新加入
            $new_sf = new SysFilter();
            $new_sf->event_id = $id;
            $new_sf->order_num = $num;
            $new_sf->save();
        }
    }

    function action_delStatic() {
        $id = Arr::get($_GET, 'cid');
        $static = Doctrine_Query::create()
                        ->from('EventStatic')
                        ->where('id = ?', $id)
                        ->fetchOne();

        if ($static) {
            $static->delete();
        }
        @unlink($static['img_path']);
    }

    //设置固定、推荐等
    function action_set() {
        $this->auto_render=False;
        $event_id = Arr::get($_POST, 'event_id');
        $field = Arr::get($_POST, 'field');
        if($event_id AND $field){
            Db_Event::setBoolValue($event_id,$field);
        }
    }

}