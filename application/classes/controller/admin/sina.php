<?php

class Controller_Admin_Sina extends Layout_Admin {

    function before() {
        parent::before();
    }

    function action_index() {
        $q = urldecode(Arr::get($_GET, 'q'));
        $view['q'] = $q;

        $weibo = Doctrine_Query::create()
                ->select('*')
                ->from('SinaWeibo')
                ->where('is_delete=?', false);

        if ($q) {
                $weibo->addWhere('text LIKE ?', '%' . $q . '%');
        }

        $total_items = $weibo->count();

        $pager = Pagination::factory(array(
                    'total_items' => $total_items,
                    'items_per_page' => 20,
                    'view' => 'pager/common',
                ));

        $view['pager'] = $pager;

        // view
        $view['weibo'] = $weibo->offset($pager->offset)
                ->limit($pager->items_per_page)
                ->orderBy('id DESC')
                ->fetchArray();

        $this->_title('新浪微博');
        $this->_render('_body', $view);
    }

    function action_comments() {
        //
        $q = urldecode(Arr::get($_GET, 'q'));
        $search_type = urldecode(Arr::get($_GET, 'search_type'));
        $verify = Arr::get($_GET, 'verify');
        $view['q'] = $q;
        $view['search_type'] = $search_type;
        $view['verify'] = $verify;

        $comments = Doctrine_Query::create()
                ->select('*')
                ->from('SinaComments');

        if ($q) {
            if ($search_type == 'text') {
                $comments->where('text LIKE ?', '%' . $q . '%');
            }
            if ($search_type == 'username') {
                $comments->where('(cmt_name LIKE ? OR cmt_screen_name LIKE ?) ', array('%' . $q . '%', '%' . $q . '%'));
            }
        }

        if ($verify == 'yes') {
            $comments->where('is_verify=?', true);
        }
        if ($verify == 'no') {
            $comments->where('is_verify=?', false);
        }


        $total_items = $comments->count();

        $pager = Pagination::factory(array(
                    'total_items' => $total_items,
                    'items_per_page' => 20,
                    'view' => 'pager/common',
                ));

        $view['pager'] = $pager;

        // view
        $view['comments'] = $comments->offset($pager->offset)
                ->limit($pager->items_per_page)
                ->orderBy('id DESC')
                ->fetchArray();

        $this->_title('新浪微博评论');
        $this->_render('_body', $view);
    }

    function action_commentVerify() {
        $id = Arr::get($_POST, 'id');
        $comment = Doctrine_Query::create()
                ->from('SinaComments')
                ->where('id = ?', $id)
                ->fetchOne();

        if ($comment['is_verify']) {
            $comment->is_verify = false;
        } else { // 新加入
            $comment->is_verify = true;
        }
        $comment->save();
    }

    //发布微博
    function action_form() {

        if ($_POST) {
            $this->auto_render = False;
            $img_path = Arr::get($_POST, 'img_path');
            $content = Arr::get($_POST, 'text', '这家伙真懒，什么都没写～');

            //插入的图片
            if ($img_path) {
                $pic_url = 'http://zuaa.zju.edu.cn' . $img_path;
            }

            $post_data = array(
                'aa_id' => 0,
                'text' => $content,
                'pic_url' => isset($pic_url) ? $pic_url : null
            );

            $ret = $this->sinaWeiboUpdate($post_data);
            if (isset($ret['error'])) {
                echo Candy::MARK_ERR . $ret['error'];
            } elseif (isset($ret['idstr'])) {
                unset($ret['id']);
                $post = $ret;
                $post['uid'] = $ret['user']['id'];
                $post['user_id'] = null;
                $post['aa_id'] = 0;
                $post['wid'] = $ret['id'];
                $post['colletion_at'] = date("Y-m-d H:i:s");
                $post['created_at'] = date("Y-m-d H:i:s", strtotime($ret['created_at']));
                $sinaweibo = new SinaWeibo();
                $sinaweibo->fromArray($post);
                $sinaweibo->save();
                echo $ret['idstr'];
            } else {
                echo Candy::MARK_ERR . '很抱歉，发送超时，请重试！';
            }
            exit;
        }

        $view = array();
        $this->_render('_body', $view);
    }

    function action_delweibo() {
        $this->auto_render = FALSE;
        $cid = Arr::get($_GET, 'cid');
        $weibo = Doctrine_Query::create()
                ->from('SinaWeibo')
                ->where('id = ?', $cid)
                ->fetchOne();
        $weibo->is_delete = true;
        $weibo->save();
    }

}