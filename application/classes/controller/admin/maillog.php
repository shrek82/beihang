<?php
//校友邮箱注册记录

class Controller_Admin_Maillog extends Layout_Admin {

    function before() {
        parent::before();       
    }

    //内容管理首页
    function action_index() {

        $mail = Doctrine_Query::create()
                        ->from('ZuaaMail m')
			->select('m.id,m.user_id,m.create_at,m.username,u.realname AS realname')
			->leftJoin('m.User u')
                        ->orderBy('m.id DESC');

        $total_mail = $mail->count();

        $pager = Pagination::factory(array(
                    'total_items' => $total_mail,
                    'items_per_page' => 30,
                    'view' => 'pager/common',
                ));

        $view['pager'] = $pager;
        $view['mail'] = $mail->offset($pager->offset)
                        ->limit($pager->items_per_page)
                        ->fetchArray();

        $this->_title('邮箱注册记录');
       $this->_render('_body', $view);
    }

    #删除内容
    function action_del() {
        $this->auto_render = FALSE;
        $cid = Arr::get($_GET, 'cid');
        $del = Doctrine_Query::create()
                        ->delete('Zuaamail')
                        ->where('id =?', $cid)
                        ->execute();
    }

}