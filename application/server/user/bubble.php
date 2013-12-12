<?php

class Controller_User_Bubble extends Layout_User {

    function before() {
        parent::before();

        $topbar_links = array(
            'user_bubble/index' => '我的状态',
            'user_bubble/others' => '其他人的',
        );
        View::set_global('topbar_links', $topbar_links);
    }

//我的记录
    function action_index() {
        $bubble = Doctrine_Query::create()
                ->select('b.*,u.sex AS sex')
                ->from('UserBubble b')
                ->leftJoin('b.User u')
                ->where('b.user_id = ?', $this->_user_id)
                ->orderBy('b.blow_at DESC');

        $pager = Pagination::factory(array(
                    'total_items' => $bubble->count(),
                    'items_per_page' => 10,
                    'view' => 'pager/common',
        ));

        $view['pager'] = $pager;
        $bubble = $bubble->offset($pager->offset)
                ->limit($pager->items_per_page)
                ->fetchArray();

        //记录最新评论
        foreach ($bubble as $key => $b) {
            $bubble_comment = Doctrine_Query::create()
                    ->select('c.id,c.user_id,c.content,c.post_at,u.realname AS realname,u.sex AS sex')
                    ->from('Comment c')
                    ->leftJoin('c.User u')
                    ->where('c.bubble_id=?', $b['id'])
                    ->orderBy('c.id DESC')
                    ->fetchArray();

            if ($bubble_comment) {
                $bubble[$key]['comments'] = array_reverse($bubble_comment, TRUE);
            } else {
                $bubble[$key]['comments'] = null;
            }
        }
        $view['bubbles'] = $bubble;
        $this->_title('我的状态');
        $this->_render('_body', $view);
    }

    //我关注的人的记录
    function action_others() {
        $users = Model_Mark::arr('user', $this->_user_id);

        $bubble = new Doctrine_RawSql();
        $bubble->addComponent('ub', 'UserBubble ub')
                ->select('{ub.*},{u.*}')
                ->from('(SELECT * FROM user_bubble ORDER BY blow_at DESC) ub
                        LEFT JOIN user u ON u.id = ub.user_id')
                ->addComponent('u', 'ub.User u')
                ->whereIn('ub.user_id', $users)
                ->groupBy('ub.user_id');

        $pager = Pagination::factory(array(
                    'total_items' => $bubble->count(),
                    'items_per_page' => 16,
                    'view' => 'pager/common',
        ));

        $view['pager'] = $pager;
        $bubble = $bubble->offset($pager->offset)
                ->limit($pager->items_per_page)
                ->execute(array(), Doctrine_Core::HYDRATE_ARRAY);

        //记录最新评论
        foreach ($bubble as $key => $b) {
            $bubble_comment = Doctrine_Query::create()
                    ->select('c.id,c.user_id,c.content,c.post_at,u.realname AS realname,u.sex AS sex')
                    ->from('Comment c')
                    ->leftJoin('c.User u')
                    ->where('c.bubble_id=?', $b['id'])
                    ->limit(5)
                    ->orderBy('c.id DESC')
                    ->fetchArray();

            if ($bubble_comment) {
                $bubble[$key]['comments'] = array_reverse($bubble_comment, TRUE);
            } else {
                $bubble[$key]['comments'] = null;
            }
        }

        $view['bubbles'] = $bubble;
        $this->_title('其他人的状态');
        $this->_render('_body', $view);
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

    //提交记录
    function action_blow() {
        if ($_POST) {
            $v = Validate::setRules($_POST, 'bubble');
            $post = $v->getData();
            if (!$v->check()) {
                echo Candy::MARK_ERR . $v->outputMsg($v->errors('validate'));
            } else {
                //是否包含非法关键词
                $filter_str = Model_Filter::check(Arr::get($_POST, 'content'));
                if ($filter_str) {
                    echo Candy::MARK_ERR . '检测到含有非法关键词“' . $filter_str . '”，请在修改后重试，谢谢！';
                    exit;
                }

                $post['user_id'] = $this->_user_id;
                $post['blow_at'] = date('Y-m-d H:i:s');
                $bubble = new UserBubble();
                $bubble->fromArray($post);
                $bubble->save();
            }
            exit;
        }
    }

    function action_del() {
        $this->auto_render = FALSE;
        $cid = Arr::get($_GET, 'cid');
        $del = Doctrine_Query::create()
                ->delete('UserBubble')
                ->where('id =?', $cid)
                ->andwhere('user_id = ?', $this->_user_id)
                ->execute();

        $del = Doctrine_Query::create()
                ->delete('Comment')
                ->where('bubble_id =?', $cid)
                ->addWhere('bubble_id>0')
                ->execute();
    }

}