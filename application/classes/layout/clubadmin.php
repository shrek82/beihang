<?php

class Layout_Clubadmin extends Layout_Club {

    function before() {
        parent::before();
        if (!$this->_is_manager) {
            Model_User::deny('不是俱乐部管理员，请离开。');
        }
        $this->_render('_body_top', null, 'club_global/admin_topbar');
    }

}