<?php
/**
  +-----------------------------------------------------------------
 * 名称：个人主页控制器
 * 版本：1.0
 * 版权：www.usho.cn
 * 作者：zhaojiangang
 * 最后更新日期：12-10-12 上午9:45
  +-----------------------------------------------------------------
 */
class Layout_User extends Layout_Main {

    public $template = 'layout/user';
    protected $_user;
    protected $_user_id;
    protected $_id;

    function before() {
        parent::before();

        // 用户id
        $this->_user_id = $this->_sess->get('id');
        if (!$this->_user_id) {
            $this->request->redirect('user/login');
        }

        // 访问别人的就需要审核
        $this->_id = (int)Arr::get($_GET, 'id', $this->_user_id);
        View::set_global('_ID', $this->_id);

        $user = Doctrine_Query::create()
                        ->from('User u')
                        ->leftJoin('u.Contact')
                        ->leftJoin('u.Works')
                        ->where('u.id = ?', $this->_id)
                        ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

        if (!$user) {
            $this->request->redirect('main/notFound');
        }

        $this->_user = $user;
        View::set_global('_SEX', $user['sex']);

        // 是自己的主页
        $is_master = ($this->_id == $this->_user_id);

        // 访问别人的主页留下踪迹
        if (!$is_master) {
            $this->visit($this->_id);
        }
        //本人则即使更新Session和Cookis信息
        else {
            $sess = Session::instance();
            $sess->set('role', $user['role']);
            $sess->set('actived', $user['actived']);
        }

        View::set_global('_MASTER', $is_master);
        View::set_global('_USER', $user);

        $this->_render('_user_header', null, 'user_global/header');
        $this->_render('_user_nav', null, 'user_global/nav');
        $this->_render('_user_left', null, 'user_global/left');
        $this->_render('_user_footer', null, 'user_global/footer');
    }

    function visit($user_id) {
        $max_num = 18;
        $master_visitor = Doctrine_Query::create()
                        ->from('UserVisitor')
                        ->where('user_id = ?', $user_id);

        $num = $master_visitor->count();

        $has_me = (bool) $master_visitor
                        ->andWhere('visitor_id = ?', $this->_user_id)
                        ->count();

        if ($has_me == TRUE) {
            $me = $master_visitor->fetchOne();
            $me->visit_at = date('Y-m-d H:i:s');
            $me->save();
        } else {
            if ($max_num == $num) {
                Doctrine_Query::create()
                        ->delete('UserVisitor')
                        ->where('user_id = ?', $user_id)
                        ->orderBy('visit_at ASC')
                        ->limit(1)
                        ->execute();
            }
            $new_one = new UserVisitor();
            $new_one->user_id = $user_id;
            $new_one->visitor_id = $this->_user_id;
            $new_one->visit_at = date('Y-m-d H:i:s');
            $new_one->save();
        }
    }

}