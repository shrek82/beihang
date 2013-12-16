<?php

/**
  +------------------------------------------------------------------------------
 * 校友组织
  +------------------------------------------------------------------------------
 */
class Controller_Aa extends Layout_Main {

    function before() {
        $this->template = 'layout/aa';
        parent::before();
    }

    function action_info() {

        $this->auto_render = FALSE;
        $aa = Arr::get($_GET, 'aa', 0);
        $id = Arr::get($_GET, 'id');

        if ($aa == 0) {
            echo Doctrine_Query::create()
                    ->select('content')
                    ->from('MainInfo')
                    ->where('id = ?', $id)
                    ->execute(array(), Doctrine_Core::HYDRATE_SINGLE_SCALAR);
        } else {
            echo Doctrine_Query::create()
                    ->select('content')
                    ->from('AaInfo')
                    ->where('id = ? AND aa_id = ?', array($id, $aa))
                    ->execute(array(), Doctrine_Core::HYDRATE_SINGLE_SCALAR);
        }
    }

    /**
      +------------------------------------------------------------------------------
     * 校友总会
      +------------------------------------------------------------------------------
     */
    function action_index() {

        $main = Doctrine_Query::create()
                ->from('MainAa m')
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
        $view['main'] = $main;

        // 总会其他信息
        $view['other_info'] = Doctrine_Query::create()
                ->useQueryCache(false)
                ->select('title')
                ->from('MainInfo')
                ->where('type=?', '其他')
                ->orderBy('order_num ASC')
                ->fetchArray();

        $this->_title('北航校友会,北京航空航天大学校友总会,北航校友总会');
        $this->_render('_body', $view);
    }

    function action_zhuanti($id = null) {
        echo 'zhuanti';
        echo '<br>id=';
        echo $id;
    }

    function action_view($id = null) {
        echo 'view';
        echo '<br>id=';
        echo $id;
    }

    function action_hangzhou($id = null) {
        echo 'hz';
        echo '<br>id=';
        echo $id;
    }

    /**
      +------------------------------------------------------------------------------
     * 地方校友会
      +------------------------------------------------------------------------------
     */
    function action_branch() {

        $date = date('Y-m-d');

        $time_span = time() - Date::WEEK;

        $all_aa = Doctrine_Query::create()
                ->select('a.name, a.sname,a.ename,a.contacts,a.email,a.class, a.tel,a.fax,a.address,a.group,a.order_num')
                ->from('Aa a')
                ->where('a.class=?', '地方校友会')
                ->orderBy('a.order_num ASC')
                ->fetchArray();

        $view['all_aa'] = $all_aa; //带温度统计为$view_all_aa
        $this->_title('地方校友会');
        $this->_render('_body', $view);
    }

    /**
      +------------------------------------------------------------------------------
     * 学院分会
      +------------------------------------------------------------------------------
     */
    function action_institute() {
        $institution = Doctrine_Query::create()
                ->select('a.name, a.sname,a.ename,a.class,a.contacts,a.email, a.tel,a.fax,a.address,a.group,a.order_num')
                ->from('Aa a')
                ->where('a.class=?', '学院')
                ->orderBy('a.order_num ASC')
                ->fetchArray();

        $this->_title('学院分会');
        $view['institution'] = $institution;
        $this->_render('_body', $view);
    }

    /**
      +------------------------------------------------------------------------------
     * 行业分会
      +------------------------------------------------------------------------------
     */
    function action_industry() {
        $institution = Doctrine_Query::create()
                ->select('a.name, a.sname,a.ename,a.class,a.contacts,a.email, a.tel,a.fax,a.address,a.group,a.order_num')
                ->from('Aa a')
                ->where('a.class=?', '行业分会')
                ->orderBy('a.order_num ASC')
                ->fetchArray();

        $this->_title('学院分会');
        $view['institution'] = $institution;
        $this->_render('_body', $view);
    }

    /**
      +------------------------------------------------------------------------------
     * 俱乐部
      +------------------------------------------------------------------------------
     */
    function action_club() {
        $view['club'] = Doctrine_Query::create()
                ->select('c.id,c.name,c.aa_id,c.member_num, (SELECT COUNT(m.id) FROM ClubMember m WHERE c.id = m.club_id) AS mcount,(SELECT COUNT(e.id) FROM Event e WHERE c.id = e.club_id) AS ecount')
                ->addSelect('(SELECT a.sname FROM Aa a WHERE a.id = c.aa_id) AS aa_name')
                ->from('Club c')
                ->where('c.aa_id=0')
                ->orderBy('mcount DESC, ecount DESC')
                ->limit(50)
                ->fetchArray();
        $this->_title('俱乐部');
        $this->_render('_body', $view);
    }

    /**
      +------------------------------------------------------------------------------
     * 总会章程
      +------------------------------------------------------------------------------
     */
    function action_constitution() {
        $main = Doctrine_Query::create()
                ->where('type=?', '章程')
                ->from('MainInfo')
                ->orderBy('order_num ASC,id DESC')
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

        $view['main'] = $main;

        // 总会其他信息
        $view['other_info'] = Doctrine_Query::create()
                ->useQueryCache(false)
                ->select('title')
                ->from('MainInfo')
                ->where('type=?', '其他')
                ->orderBy('order_num ASC')
                ->fetchArray();

        $this->_title($main['title']);
        $this->_render('_body', $view);
    }

    /**
      +------------------------------------------------------------------------------
     * 总会机构
      +------------------------------------------------------------------------------
     */
    function action_organization() {
        $id = Arr::get($_GET, 'id');
        $view['id'] = $id;

        $main = Doctrine_Query::create()
                ->from('MainInfo');

        if ($id > 0) {
            $main->where('id=?', $id);
        } else {
            $main->where('type=?', '机构')
                    ->orderBy('id DESC');
        }

        $main = $main->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

        $all_info = Doctrine_Query::create()
                ->select('title')
                ->where('type=?', '机构')
                ->from('MainInfo')
                ->orderBy('order_num ASC')
                ->fetchArray();

        // 总会其他信息
        $view['other_info'] = Doctrine_Query::create()
                ->useQueryCache(false)
                ->select('title')
                ->from('MainInfo')
                ->where('type=?', '其他')
                ->orderBy('order_num ASC')
                ->fetchArray();

        $view['all_info'] = $all_info;
        $view['main'] = $main;
        $view['main'] = $main;

        $this->_title($main['title']);
        $this->_render('_body', $view);
    }

    /**
      +------------------------------------------------------------------------------
     * 总会大事记
      +------------------------------------------------------------------------------
     */
    function action_memorabilia() {

        // 总会相关信息
        $info = Doctrine_Query::create()
                ->useQueryCache(false)
                ->select('title')
                ->from('MainInfo')
                ->orderBy('order_num ASC')
                ->fetchArray();
        $view['info'] = $info;

        $view['memorabilia'] = Doctrine_Query::create()
                ->from('Content c')
                ->where('c.type=9')
                ->orderBy('c.create_at DESC')
                ->fetchArray();
        // 总会其他信息
        $view['other_info'] = Doctrine_Query::create()
                ->useQueryCache(false)
                ->select('title')
                ->from('MainInfo')
                ->where('type=?', '其他')
                ->orderBy('order_num ASC')
                ->fetchArray();

        $this->_render('_body', $view);
    }

    /**
      +------------------------------------------------------------------------------
     * 总会其他信息
      +------------------------------------------------------------------------------
     */
    function action_other() {
        $id = Arr::get($_GET, 'id');
        $view['id'] = $id;

        $main = Doctrine_Query::create()
                ->from('MainInfo')
                ->where('id=?', $id)
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

        $view['other_info'] = Doctrine_Query::create()
                ->useQueryCache(false)
                ->select('title')
                ->from('MainInfo')
                ->where('type=?', '其他')
                ->orderBy('order_num ASC')
                ->fetchArray();

        $view['main'] = $main;

        $this->_title($main['title']);
        $this->_render('_body', $view);
    }

}
