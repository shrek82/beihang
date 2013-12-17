<?php

/**
  +-----------------------------------------------------------------
 * 名称：管理首页
 * 版本：1.0
 * 版权：www.usho.cn
 * 作者：zhaojiangang
 * 最后更新日期：12-10-12 上午10:56
  +-----------------------------------------------------------------
 */
class Controller_Admin extends Layout_Admin {

    function before() {
        parent::before();

        $leftbar_links = array(
            'admin/index' => '最新数据',
        );

        $this->_render('_body_left', compact('leftbar_links'), 'admin/leftbar');
    }

    //后台首页(框架)
    function action_index() {
        $this->auto_render = FALSE;
        echo View::factory('admin/frame');
    }

    //信息统计
    function action_count() {

        //所有新闻总数
        $view['news_count']['all'] = Doctrine_Query::create()
                ->from('News n')
                ->count();
        //已审核新闻
        $view['news_count']['isRelease'] = Doctrine_Query::create()
                ->from('News n')
                ->where('is_release=? AND is_draft=?', array(1, 0))
                ->count();
        //待审核新闻
        $view['news_count']['notRelease'] = Doctrine_Query::create()
                ->from('News n')
                ->where('is_release=? AND is_draft=?', array(0, 0))
                ->count();

        //今日发布新闻
        $view['news_count']['today'] = Doctrine_Query::create()
                ->from('News n')
                ->where('create_at=curdate() AND is_draft=?', 0)
                ->count();

        //注册校友
        $view['user_count']['all'] = Doctrine_Query::create()
                ->select('id')
                ->from('User')
                ->count();
        //本月
        $view['user_count']['benyue'] = Doctrine_Query::create()
                ->select('id')
                ->from('User')
                ->where("DATE_FORMAT(reg_at,'%Y%m' ) = DATE_FORMAT( CURDATE() , '%Y%m' )")
                ->count();

        //上月
        $view['user_count']['shangyue'] = Doctrine_Query::create()
                ->select('id')
                ->from('User')
                ->where("PERIOD_DIFF( date_format( now( ) , '%Y%m' ) , date_format(reg_at, '%Y%m' ) ) =1 ")
                ->count();

        //今日注册
        $view['user_count']['today'] = Doctrine_Query::create()
                ->select('id')
                ->from('User')
                ->where("to_days(reg_at) = to_days(now())")
                ->count();

        $this->_render('_body', $view);
    }

    //词语过滤
    function action_filter() {

        $id = Arr::get($_GET, 'id');
        $filter = Doctrine_Query::create()
                ->from('Filter')
                ->where('id = ?', $id)
                ->fetchOne();

        if ($_POST) {
            $post['string'] = Arr::get($_POST, 'string');
            if (!$filter) {
                $filter = new Filter();
                $filter->fromArray($post);
                $filter->save();
            } else {
                $filter->fromArray($post);
                $filter->save();
            }
        }
        $view['filter'] = $filter;
        $view['filters'] = Doctrine_Query::create()
                ->from('Filter')
                ->fetchArray();

        $this->_render('_body', $view);
    }

    //删除非法关键词
    function action_delFilter() {
        $id = Arr::get($_GET, 'id');
        $del = Doctrine_Query::create()
                ->delete('Filter')
                ->where('id = ?', $id)
                ->execute();
        $this->_redirect('admin/filter');
    }

    //顶部导航
    function action_topbar() {
        $this->auto_render = FALSE;
        echo View::factory('admin/topbar');
    }

    //左侧导航
    function action_leftbar() {
        $this->auto_render = FALSE;
        //新闻分类 
        $view['news_categorys'] = Doctrine_Query::create()
                ->from('NewsCategory')
                ->where('aa_id=0')
                ->orderBy('order_num ASC')
                ->fetchArray();
        echo View::factory('admin/leftbar', $view);
    }

}