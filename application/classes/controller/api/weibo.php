<?php

//新闻
class Controller_Api_Weibo extends Layout_Mobile {

    function before() {
        parent::before();
    }

    //获取话题分类
    function action_category() {
        $data = array();
        $data[] = array(
            'name' => '原创的',
            'parameter' => 'original',
        );
        $data[] = array(
            'name' => '活动相关',
            'parameter' => 'event',
        );
        $data[] = array(
            'name' => '我发布的',
            'parameter' => 'my',
        );
        $data[] = array(
            'name' => '评论过的',
            'parameter' => 'mycomments',
        );
        $this->response($data, 'list', 'list');
    }

    function action_index() {

        $q = $this->getParameter('q');
        $limit = $this->getParameter('limit', 15);
        $page = $this->getParameter('page', 1);
        $cat = $this->getParameter('cat', 'is_original');
        $offset = ($page - 1) * $limit;

        //我加入的校友会
        $weibo = Doctrine_Query::create()
                ->select('c.*,u.realname,u.sex,u.speciality,u.start_year')
                ->from('WeiboContent c')
                ->leftJoin('c.User u');

        //原创的
        if ($cat == 'original') {
            $weibo->where('c.is_original=1');
        }
        //活动相关
        elseif ($cat == 'event') {
            $weibo->where('c.is_original=0');
        }
        //我自己的
        elseif ($cat == 'my') {
            $weibo->where('c.user_id=' . $this->_uid);
        }
        //我评论过的
        elseif ($cat == 'mycomments') {
            $my_comment_ids = $ids = Doctrine_Query::create()
                    ->select('c.weibo_id')
                    ->from('Comment c')
                    ->where('c.user_id ='.$this->_uid)
                    ->addWhere('c.weibo_id >0')
                    ->groupBy('c.weibo_id')
                    ->orderBy('c.id DESC')
                    ->limit(50)
                    ->execute(array(), Doctrine_Core::HYDRATE_SINGLE_SCALAR);

            if (!$my_comment_ids) {
                $weibo_ids = array(-1);
            }
            elseif (is_string($my_comment_ids)) {
                $weibo_ids = array($my_comment_ids);
            }
            else {
                $weibo_ids = $my_comment_ids;
            }
            $weibo->whereIn('c.id',$weibo_ids);
        }

        //默认显示原创的
        else {
            $weibo->where('c.is_original=1');
        }

        $weibo = $weibo->offset($offset)
                ->orderBy('c.id DESC')
                ->limit($limit)
                ->fetchArray();

        $data = array();
        if (count($weibo) > 0) {
            foreach ($weibo AS $key => $c) {
                $data[$key]['id'] = $c['id'];
                $data[$key]['uid'] = $c['user_id'];
                $data[$key]['comments_count'] = $c['reply_num'] ? $c['reply_num'] : 0;
                $data[$key]['create_date'] = $c['post_at'];
                $data[$key]['str_create_date'] = Date::ueTime($c['post_at']);
                $data[$key]['allow_comment'] = 'true';
                $data[$key]['clients'] = $c['clients'] ? $c['clients'] : '网页版';
                $data[$key]['is_original'] = $c['is_original'] ? 'true' : 'false';
                $data[$key]['content'] = Common_Global::sinatext($c['content']);
                if ($c['img_path']) {
                    $data[$key]['thumbnail_pic'] = $this->_siteurl . '/' . $c['img_path'];
                    $image_file = Image::factory($c['img_path']);
                    $data[$key]['thumbnail_pic_width'] = $image_file->width;
                    $data[$key]['thumbnail_pic_height'] = $image_file->height;
                    $pics[0]['nid'] = 1;
                    $pics[0]['pic_id'] = '';
                    $pics[0]['title'] = '新鲜事';
                    $pics[0]['intro'] = '暂无描述';
                    $pics[0]['thumbnail_pic'] = $this->_siteurl . '/' . $c['img_path'];
                    $pics[0]['bmiddle_pic'] = str_replace('_mini', '_bmiddle', $pics[0]['thumbnail_pic']);
                    $pics[0]['original_pic'] = $pics[0]['bmiddle_pic'];
                    $data[$key]['pics'] = $pics;
                } else {
                    $data[$key]['thumbnail_pic'] = '';
                    $data[$key]['thumbnail_pic_width'] = '';
                    $data[$key]['thumbnail_pic_height'] = '';
                    $data[$key]['pics'] = "";
                }
                $data[$key]['user']['id'] = $c['user_id'];
                $data[$key]['user']['realname'] = $c['User']['realname'];
                $data[$key]['user']['speciality'] = $c['User']['start_year'] && $c['User']['speciality'] ? $c['User']['start_year'] . '级' . $c['User']['speciality'] : $c['User']['speciality'];
                $data[$key]['user']['sex'] = $c['User']['sex'];
                $data[$key]['user']['profile_image_url'] = $this->_siteurl . Model_User::avatar($c['user_id'], 48, $c['User']['sex']);
                $data[$key]['user']['avatar_large'] = $this->_siteurl . Model_User::avatar($c['user_id'], 128, $c['User']['sex']);
            }
        }
        $this->response($data, 'list', null);
    }

    /**
      +------------------------------------------------------------------------------
     * 查看详情
      +------------------------------------------------------------------------------
     */
    function action_view() {
        $id = $this->getParameter('id');
        $exparray = Kohana::config('expression.default');
        $weibo = Doctrine_Query::create()
                ->select('c.*,u.realname,u.sex,u.speciality as speciality ,u.start_year as start_year,u.city')
                ->from('WeiboContent c')
                ->leftJoin('c.User u')
                ->where('c.id=' . $id)
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

        if ($weibo) {

            //转换表情
            if (preg_match('/\[.*\]/U', $weibo['content'])) {
                foreach ($exparray as $name => $path) {
                    $weibo['content'] = str_replace('[' . $name . ']', '<img src="http://zuaa.zju.edu.cn/static/aa/expression/' . $path . '"  style="margin-right:3px; vertical-align: middle">', $weibo['content']);
                }
            }

            //生成图片位置
            if ($weibo['img_paths']) {
                $img_path_array = explode('||', $weibo['img_paths']);
                $imgHtml = '';
                foreach ($img_path_array AS $key => $path) {
                    $imgHtml = $imgHtml . '<img src="' . str_replace('_mini', '_bmiddle', $path) . '">';
                }
                $weibo['content'] = $imgHtml . $weibo['content'];
            }

            $data = array();
            $data['id'] = $weibo['id'];
            $data['uid'] = $weibo['user_id'];
            $data['comments_count'] = $weibo['reply_num'] ? $weibo['reply_num'] : 0;
            $data['create_data'] = $weibo['post_at'];
            $data['str_create_data'] = Date::ueTime($weibo['post_at']);
            $data['allow_comment'] = 'true';
            $data['content'] = '';
            $data['clients'] = $weibo['clients'] ? $weibo['clients'] : '网页版';
            $data['pics'] = '';
            $data['user']['id'] = $weibo['user_id'];
            $data['user']['realname'] = $weibo['User']['realname'];
            $data['user']['speciality'] = $weibo['User']['start_year'] && $weibo['User']['speciality'] ? $weibo['User']['start_year'] . '级' . $weibo['User']['speciality'] : $weibo['User']['speciality'];
            $data['user']['sex'] = $weibo['User']['sex'];
            $data['user']['profile_image_url'] = $this->_siteurl . Model_User::avatar($weibo['user_id'], 48, $weibo['User']['sex']);
            $data['user']['avatar_large'] = $this->_siteurl . Model_User::avatar($weibo['user_id'], 128, $weibo['User']['sex']);
            $htmlAndPics = Common_Global::standardHtmlAndPics($weibo['content'], '新鲜分享');
            $weibo['content'] = $htmlAndPics['html'];
            $data['content'] = View::factory('api/weibo/view', array('weibo' => $weibo));
            $data['pics'] = $htmlAndPics['pics'];
            ;
            $this->response($data);
        } else {
            $this->error('新鲜事不存在或已被删除');
        }
    }

    //发布
    function action_post() {
        $this->checkLogin();
        $content = $this->getParameter('content');
        $upload_file = $this->getParameter('upload_file');

        //存在上传图片
        $img_path = false;
        if ($_FILES AND $_FILES['upload_file']['size'] > 0) {
            $upload_images = Common_Upload::pic($_FILES['upload_file'], $this->_uid);
            $img_path = isset($upload_images['images']['mini']) ? $upload_images['images']['mini'] : false;
        }

        //创建微博内容
        $weiboPost = array();
        $weiboPost['user_id'] = $this->_uid;
        $weiboPost['img_paths'] = $img_path;
        $weiboPost['content'] = $content;
        $weiboPost['is_original'] = true;
        $weiboPost['about_org'] = false;
        $weiboPost['clients'] = $this->_clients;
        $weiboPost['from_aa'] = 1;
        $backData = Model_weibo::post($weiboPost);
        //错误提示
        if (is_array($backData)) {
            $this->error($backData['msg']);
        }
        //添加成功
        else {
            $back = array();
            $back['id'] = $backData;
            $back['uid'] = $this->_uid;
            $back['content'] = $content;
            $back['clients'] = $this->_clients;
            $this->response($back, 'success', 'success');
        }
    }

}

?>