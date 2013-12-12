<?php

/**
  +-----------------------------------------------------------------
 * 名称：班级底层
 * 版本：1.0
 * 版权：www.usho.cn
 * 作者：zhaojiangang
 * 最后更新日期：12-10-12 上午9:43
  +-----------------------------------------------------------------
 */
class Layout_Class extends Layout_Main {

    public $template = 'layout/homepage';
    public $_id;
    public $_classroom;
    public $_member;
    public $_is_manager;
    public $_theme;


    function before() {
        parent::before();

        $this->_id = (int)Arr::get($_GET, 'id');

        //主题风格
        $this->_theme = Db_Theme::getTheme(array('classroom_id' => $this->_id));

        //默认班级相关权限
        $this->_is_manager = FALSE;
        $this->_member = FALSE;
        $member=False;

        // 当前班级信息
        $classroom = Db_Classroom::getInfoById($this->_id);


        // 不是有效的班级则跳转
        if (!$classroom) {
            $this->_redirect('classroom');
        }

        //班级名称
        if(!$classroom['name']){
            $classroom['name']=$classroom['start_year']?$classroom['start_year'].'级'.$classroom['speciality']:$classroom['speciality'];
        }

        $this->_classroom = $classroom;

        //当前成员信息
        if ($this->_uid) {
            $member = Doctrine_Query::create()
                    ->from('ClassMember')
                    ->where('class_room_id = ?', $this->_id)
                    ->andWhere('user_id = ?', $this->_uid)
                    ->fetchOne();

            if ($member) {
                // 更新最后访问时间
                if (time() - strtotime($member['visit_at']) > 60 * 5) {
                    $member->visit_at = date('Y-m-d H:i:s');
                    $member->save();
                }

                //班级管理员
                if ($member['is_manager']) {
                    $this->_is_manager = True;
                }
            }
        }

        //成员信息
        $this->_member = $member;

        //总会管理员也是管理员
        if ($this->_role == '管理员') {
            $this->_is_manager = True;
        }

        View::set_global('_MEMBER', $this->_member);
        View::set_global('_IS_MANAGER', $this->_is_manager);
        View::set_global('_THEME', $this->_theme);
        View::set_global('_ID', $this->_id);
        View::set_global('_CLASSROOM', $this->_classroom);

        //导航
        if (strstr($this->request->controller, 'classroom_admin')) {
            $this->_render('_body_top', null, 'classroom_global/admin_topbar');
        } else {
            $this->_render('_body_top', compact('classroom', 'member'), 'classroom_global/topbar');
        }

    }

    //非班级成员和不是管理员不能访问
    function after() {
        parent::after();
        if (!$this->_member AND !$this->_is_manager) {
            $this->template->_body = View::factory('classroom_home/deny');
        }
        //Model_User::deny();
    }

}