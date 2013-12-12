<?php

//移动客户端
class Controller_Mobile_Home extends Layout_Mobile2 {

    public $_id;
    public $_aa;

    public function before() {
        parent::before();
        $this->_id = Arr::get($_GET, 'aid',1);
        View::set_global('_ID',$this->_id);
    }

    //初始校友有页面
    function action_index() {
        $view = array();
        $condition['aa_id'] = $this->_id;
        $condition['limit'] = 10;
        $event = Db_Event::getEvents($condition);
        if ($event) {
            foreach ($event as $key => $e) {
                $event[$key]['title'] = Db_Event::replaceTitle($e['title']);
            }
        }
        $view['event']=$event;
        $this->_title('移动客户端-北航校友网');
        $this->_render('_body', $view);
    }

    //活动页面
    function action_event() {
        $view = array();
        $this->_aid = Arr::get($_GET, 'aid');
        $this->_title('移动客户端-北航校友网');
        $this->_render('_body', $view);
    }

    function action_eview2() {
        $this->auto_render=false;
        echo View::factory('mobile_home/inuitcss',null);

    }
    //活动展示页面
    function action_eview() {
        $eid = Arr::get($_GET, 'eid');

        # 活动详细说明
        $event = Doctrine_Query::create()
                ->select('e.*')
                ->addSelect('a.id AS aa_id,a.name AS aa_name')
                ->addSelect('c.id AS club_id,c.name AS club_name')
                ->addSelect('u.id as uid,u.realname AS realname')
                ->addSelect('(SELECT SUM(ss.num) FROM EventSign ss WHERE ss.event_id = e.id) AS sign_num')
                ->from('Event e')
                ->leftJoin('e.Aa a')
                ->leftJoin('e.Club c')
                ->leftJoin('e.User u')
                ->where('e.id=?', $eid)
                ->addWhere('e.is_closed = ? OR e.is_closed IS NULL', FALSE)
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

        $this->_title('活动详情-北航校友网');
        $htmlAndPics = Common_Global::standardHtmlAndPics($event['content']);
        $event['content'] = $htmlAndPics['html'];
        $view['pic']=$htmlAndPics['pics'];
        $view['event']=$event;
        $this->_render('_header', null,'mobile_home/empty_header');
        $this->_render('_body', $view);
    }

}

?>
