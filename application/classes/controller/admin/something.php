<?php

class Controller_Admin_Something extends Layout_Admin {

    function before() {
        parent::before();
    }

    //话题管理
    function action_index() {
        $q = urldecode(Arr::get($_GET, 'q'));
        //搜索方式 按内容或按作者
        $search_type = Arr::get($_GET, 'search_type');
        //按所属浏览内容浏览
        $object = Arr::get($_GET, 'object');

        $something = Doctrine_Query::create()
                ->select('c.id,c.content,c.user_id,c.post_at,c.aa_id,u.realname as realname')
                ->from('WeiboContent c')
                ->leftJoin('c.User u')
                ->orderBy('c.id DESC');

        //分类
        if ($object == 'original') {
            $something->where('c.is_original=1');
        } elseif ($object == 'event') {
            $something->where('c.content like ?', '%[/e]%');
        }

        if ($q) {
            //按标题搜索
            if ($search_type == 'title') {
                $something->andWhere('c.content like ?', '%' . $q . '%');
            }
            //按作者
            else {
                $something->andWhere('u.realname=?', $q);
            }
        }

        $total_something = $something->count();

        $pager = Pagination::factory(array(
                    'total_items' => $total_something,
                    'items_per_page' => 20,
                    'view' => 'pager/common',
        ));

        $somethings = $something->limit($pager->items_per_page)
                ->offset($pager->offset)
                ->fetchArray();

        $this->_title('话题列表');
        $this->_render('_body', compact('somethings', 'pager', 'q', 'object', 'search_type'));
    }

    //删除话题
    function action_del() {
        $this->auto_render = FALSE;
        $cid = Arr::get($_GET, 'cid', -1);

        Doctrine_Query::create()
                ->delete('Comment')
                ->where('weibo_id =?', $cid)
                ->execute();
        Doctrine_Query::create()
                ->delete('WeiboContent')
                ->where('id =?', $cid)
                ->execute();
    }

}
