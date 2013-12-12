<?php

//论坛
//从网友版缓存获取
class Controller_Api_Bbs extends Layout_Mobile {

    function before() {
        parent::before();
    }

    //获取话题分类
    function action_category() {
        $data = Model_Bbs::getMobielCategory();
        $this->response($data, 'list', 'list');
    }

    //话题列表
    function action_index() {

        $condition['limit'] = $this->getParameter('limit', 15);
        $condition['page'] = $this->getParameter('page', 1);
        $condition['cat'] = $this->getParameter('cat', 'all');

        //置顶主题
        $units = Model_Bbs::getMobileList($condition);

        $back['fixed'] =Model_bbs::createXmlArray($units['fixed']);
        $back['list'] = Model_bbs::createXmlArray($units['list']);
        $this->response($back, null, null);
    }


    //查询数据
    function action_view() {
        $id = $this->getParameter('id');
        $subject = Kohana::config('bbs.subject');
        $u = Doctrine_Query::create()
                ->select('u.*,p.content AS content')
                ->addSelect('a.id,a.sname,b.id,b.name,b.aa_id,b.club_id')
                ->addSelect('c.id,c.name')
                ->addSelect('user.id,user.realname,user.sex,user.speciality,user.start_year,user.city')
                ->from('BbsUnit u')
                ->leftJoin('u.Post p')
                ->leftJoin('u.User user')
                ->leftJoin('u.Bbs b')
                ->leftJoin('b.Aa a')
                ->leftJoin('b.Club c')
                ->where('u.id=?', $id)
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

        if (!$u) {
            $this->error('话题不存在或已经被删除！');
        } else {
            Model_Bbs::unitHit($id);
        }

        $event = null;
        $signs = null;
        $total_sign = 0;
        if ($u['event_id']) {

            //活动信息
            $event = Db_Event::getEventById($u['event_id']);

            //所有报名记录
            $signs = Db_Event::getEventSigner($u['event_id']);

            if ($signs) {
                foreach ($signs AS $s) {
                    $total_sign+=$s['num'];
                }
            }
        }

        $data = array();
        $data['id'] = $u['id'];
        $data['title'] = $u['title'];
        $data['subject'] = $subject[$u['subject']];
        $data['aa_id'] = $u['Bbs']['aa_id'];
        $data['aa_name'] = $u['Bbs']['aa_id'] > 0 ? $u['Bbs']['Aa']['sname'] . '校友会' : '校友总会';
        $data['bbs_id'] = $u['bbs_id'];
        $data['bbs_name'] = $u['Bbs']['name'];
        $data['is_good'] = $u['is_good'] ? 'true' : 'false';
        $data['is_fixed'] = $u['is_fixed'] ? 'true' : 'false';
        $data['uid'] = $u['user_id'];
        $data['hits'] = $u['hit'] ? $u['hit'] : 0;
        $data['comments_count'] = $u['reply_num'] ? $u['reply_num'] : 0;
        $data['create_data'] = $u['create_at'];
        $data['str_create_data'] = Date::ueTime($u['create_at']);
        $data['update_data'] = $u['comment_at'] ? $u['comment_at'] : $data['create_data'];
        $data['str_update_data'] = Date::ueTime($data['update_data']);
        $data['allow_comment'] = 'true';
        $htmlAndPics = Common_Global::standardHtmlAndPics($u['content'], $u['title']);
        $u['content'] = $htmlAndPics['html'];
        $data['content'] = View::factory('api/bbs/view', array('u' => $u, 'event' => $event, 'total_sign' => $total_sign, 'signs' => $signs));
        $data['pics'] = $htmlAndPics['pics'];
        $data['user']['id'] = $u['user_id'];
        $data['user']['realname'] = $u['User']['realname'];
        $data['user']['speciality'] = $u['User']['start_year'] && $u['User']['speciality'] ? $u['User']['start_year'] . '级' . $u['User']['speciality'] : $u['User']['speciality'];
        $data['user']['sex'] = $u['User']['sex'];
        $data['user']['profile_image_url'] = $this->_siteurl . Model_User::avatar($u['user_id'], 48, $u['User']['sex']);
        $data['user']['avatar_large'] = $this->_siteurl . Model_User::avatar($u['user_id'], 128, $u['User']['sex']);

        $this->response($data);
    }

}

?>
