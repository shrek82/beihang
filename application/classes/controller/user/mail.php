<?php

class Controller_User_Mail extends Layout_User {

    function action_index() {
        //是否已经申请了邮箱
        $view['mail'] = Doctrine_Query::create()
                        ->from('ZuaaMail')
                        ->where('user_id=?', $this->_sess->get('id'))
                        ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
        $this->_render('_body',$view);
    }
}