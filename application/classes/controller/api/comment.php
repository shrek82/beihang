<?php

//评论
class Controller_Api_Comment extends Layout_Mobile {

    //话题列表
    function action_index() {

        $search = array();
        $search['limit'] = $this->getParameter('limit', 25);
        $search['page'] = $this->getParameter('page', 1);
        $search['max_id'] = $this->getParameter('max_id');
        $search['since_id'] = $this->getParameter('since_id');
        $search['news_id'] = $this->getParameter('news_id');
        $search['event_id'] = $this->getParameter('event_id');
        $search['bbs_unit_id'] = $this->getParameter('bbs_unit_id');
        $search['pic_id'] = $this->getParameter('pic_id');
        $search['weibo_id'] = $this->getParameter('weibo_id');
        $search['order'] = $this->getParameter('order', 'ASC');

        $data = DB_Comment::get($search);

        //错误提示
        if (isset($data['error'])) {
            $this->error($data['error']);
        } else {
            $comment = array();
            foreach ($data['comments'] AS $key => $c) {
                $comment[$key]['id'] = $c['id'];
                if (isset($c['bbs_unit_id'])) {
                    $comment[$key]['bbs_unit_id'] = $c['bbs_unit_id'];
                } elseif (isset($c['news_id'])) {
                    $comment[$key]['news_id'] = $c['news_id'];
                } elseif (isset($c['event_id'])) {
                    $comment[$key]['event_id'] = $c['event_id'];
                } elseif (isset($c['pic_id'])) {
                    $comment[$key]['pic_id'] = $c['pic_id'];
                } elseif (isset($c['weibo_id'])) {
                    $comment[$key]['weibo_id'] = $c['weibo_id'];
                } else {

                }
                if ($c['sex'] == '女') {
                    $online_icon = isset($c['online']) ? $this->_siteurl . '/static/images/online1.gif' : $this->_siteurl . '/static/images/online0.gif';
                } else {
                    $online_icon = isset($c['online']) ? $this->_siteurl . '/static/images/online1.gif' : $this->_siteurl . '/static/images/online0.gif';
                }
                $floor = ($search['page'] - 1) * $search['limit'] + $key + 1;
                $comment[$key]['uid'] = $c['user_id'];
                $comment[$key]['user']['id'] = $c['user_id'];
                $comment[$key]['user']['realname'] = $floor . '楼 ' . $c['realname'];
                $comment[$key]['user']['online'] = isset($c['online']) ? 'ture' : 'false';
                $comment[$key]['user']['online_icon'] = $online_icon;
                $comment[$key]['user']['speciality'] = $c['start_year'] && $c['speciality'] ? $c['start_year'] . '级' . $c['speciality'] : $c['speciality'];
                $comment[$key]['user']['profile_image_url'] = $this->_siteurl . Model_User::avatar($c['user_id'], 48, $c['sex']);
                $comment[$key]['user']['avatar_large'] = $this->_siteurl . Model_User::avatar($c['user_id'], 128, $c['sex']);
                unset($comment[$key]['user']['start_year']);
                $comment[$key]['create_date'] = Date::ueTime($c['post_at']);
                //$comment[$key]['str_create_date'] = Date::span_str(strtotime($c['post_at'])).'前';
                $comment[$key]['str_create_date'] = Date::ueTime($c['post_at']);
                $comment[$key]['clients'] = $c['clients'];
                $comment[$key]['content'] = Common_Global::mobileText($c['content']);
                if ($c['quote_ids']) {
                    $comment[$key]['quotes'] = Model_Comment::getQuotes($c['quote_ids']);
                } else {
                    $comment[$key]['quotes'] = array();
                }
                $comment[$key]['pics'] = '';
            }
            $this->response($comment, 'list', null);
        }
    }

    /**
      +------------------------------------------------------------------------------
     * 提交评论
      +------------------------------------------------------------------------------
     */
    function action_post() {
        $this->checkLogin();
        
        //用户没有通过审核
        if ($this->_user['role'] != '校友(已认证)' AND $this->_user['role'] != '管理员') {
            $this->error('很抱歉，您还没有通过审核，暂时发布评论；');
        }
        
        $news_id = $this->getParameter('news_id');
        $event_id = $this->getParameter('event_id');
        $bbs_unit_id = $this->getParameter('bbs_unit_id');
        $pic_id = $this->getParameter('pic_id');
        $weibo_id = $this->getParameter('weibo_id');
        $content = $this->getParameter('content');

        $post['post_at'] = date('Y-m-d H:i:s');
        if ($news_id) {
            $object_field = 'news_id';
            $post['news_id'] = $news_id;
        } elseif ($event_id) {
            $object_field = 'event_id';
            $post['event_id'] = $event_id;
            $post['bbs_unit_id'] = $bbs_unit_id ? $bbs_unit_id : Db_Event::getBbsUnitIdByEventId($event_id);
        } elseif ($bbs_unit_id) {
            $object_field = 'bbs_unit_id';
            $post['bbs_unit_id'] = $bbs_unit_id;
            $post['event_id'] = $event_id ? $event_id : Db_Bbs::getEventIdByBbsUnitId($event_id);
        } elseif ($pic_id) {
            $object_field = 'pic_id';
            $post['pic_id'] = $pic_id;
        } elseif ($weibo_id) {
            $object_field = 'weibo_id';
            $post['weibo_id'] = $weibo_id;
        } else {
            $this->error('评论发布失败，请指定评论对于内容id');
        }

        if (!$content) {
            $this->error('是不是忘了写内容呢？');
        }

        //存在上传图片
        $img_path = false;
        if ($_FILES AND $_FILES['upload_file']['size'] > 0) {
            $upload_images = Common_Upload::pic($_FILES['upload_file'], $this->_uid);
            $img_path = isset($upload_images['images']['bmiddle']) ? $upload_images['images']['bmiddle'] : false;
        }

        $post['content'] = $img_path ? '<p><img src="/' . $img_path . '"  border="0" /><br /></p>' . $content : $content;
        $post['clients'] = $this->_clients;
        $post['user_id'] = $this->_uid;

        $cid = Model_Comment::post($post);
        if ($cid) {
            $post['id'] = $cid;
            $post['uid'] = $this->_uid;
            $post['realname'] = $this->_user['realname'];
            $post['sex'] = $this->_user['sex'];
            $post['speciality'] = $this->_user['start_year'] && $this->_user['speciality'] ? $this->_user['start_year'] . '级' . $this->_user['speciality'] : $this->_user['speciality'];
            $post['profile_image_url'] = $this->_siteurl . Model_User::avatar($this->_uid, 48, $this->_user['sex']);
            $post['avatar_large'] = $this->_siteurl . Model_User::avatar($this->_uid, 128, $this->_user['sex']);
            unset($post['user_id']);
            $this->response($post, 'success', 'success');
        } else {
            $this->error('评论发布失败，请重试');
        }
    }

}

?>