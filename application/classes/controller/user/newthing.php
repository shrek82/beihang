<?php

class Controller_User_Newthing extends Layout_User {

    function before() {
        parent::before();
    }

    //我的记录
    function action_index() {

        $display = Arr::get($_GET, 'display', 'self');
        //我自己的
        if ($display == 'self') {
            $newthing = Doctrine_Query::create()
                    ->select('c.*,u.realname AS realname')
                    ->from('WeiboContent c')
                    ->leftJoin('c.User u')
                    ->where('c.user_id = ?', $this->_user_id)
                    ->addWhere('c.is_original = ?', True)
                    ->orderBy('c.post_at DESC');
        }
        //关注的校友的
        elseif ($display == 'mark') {
            $mark_user_array = Model_User::markArr($this->_uid);
            $newthing = Doctrine_Query::create()
                    ->select('c.*,u.realname AS realname')
                    ->from('WeiboContent c')
                    ->leftJoin('c.User u')
                    ->whereIn('c.user_id', $mark_user_array)
                    ->addWhere('c.is_original = ?', True)
                    ->orderBy('c.post_at DESC');
        }
        //评论过的
        elseif ($display == 'cmted') {
            $cmtForWeiboids_array = Model_Comment::cmtWeibo($this->_uid);
            $newthing = Doctrine_Query::create()
                    ->select('c.*,u.realname AS realname')
                    ->from('WeiboContent c')
                    ->leftJoin('c.User u')
                    ->whereIn('c.user_id', $cmtForWeiboids_array)
                    ->addWhere('c.is_original = ?', True)
                    ->orderBy('c.post_at DESC');
        }


        $pager = Pagination::factory(array(
                    'total_items' => $newthing->count(),
                    'items_per_page' => 10,
                    'view' => 'pager/common',
        ));

        $view['pager'] = $pager;
        $newthing = $newthing->offset($pager->offset)
                ->limit($pager->items_per_page)
                ->fetchArray();

        //新鲜事最新评论
        foreach ($newthing as $key => $b) {
            $newthing_comment = Doctrine_Query::create()
                    ->select('c.id,c.user_id,c.content,c.post_at,u.realname AS realname,u.sex AS sex')
                    ->from('Comment c')
                    ->leftJoin('c.User u')
                    ->where('c.weibo_id=?', $b['id'])
                    ->orderBy('c.id DESC')
                    ->fetchArray();

            if ($newthing_comment) {
                $newthing[$key]['comments'] = array_reverse($newthing_comment, TRUE);
            } else {
                $newthing[$key]['comments'] = null;
            }
        }
        $view['newthings'] = $newthing;
        $view['display'] = $display;
        $this->_title('我的状态');
        $this->_render('_body', $view);
    }

    //删除新鲜事
    function action_del() {
        $this->auto_render = FALSE;
        $cid = Arr::get($_GET, 'cid');
        $del = Doctrine_Query::create()
                ->delete('WeiboContent')
                ->where('id =?', $cid)
                ->andwhere('user_id = ?', $this->_user_id)
                ->execute();

        Db_Comment::delete(array('weibo_id' => $cid));
    }

    //提交新鲜事
    function action_post() {

        if (!$this->userPermissions('weibo', True)) {
            echo Candy::MARK_ERR . '很抱歉，您未登录或尚未通过审核，暂时还不能发布内容！';
            exit;
        }

        $weiboPost = array();
        $weiboPost['user_id'] = $this->_sess->get('id');
        $weiboPost['content'] = Arr::get($_POST, 'content');
        $weiboPost['is_original'] = true;
        Db_User::updatePoint('weibo');
        $backData = Model_weibo::post($weiboPost);
        //错误提示
        if (is_array($backData)) {
            echo Candy::MARK_ERR . $backData['msg'];
            exit;
        } else {
            echo $backData;
        }
    }

}