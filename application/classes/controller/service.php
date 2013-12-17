<?php

class Controller_Service extends Layout_Main {

    function before() {
        parent::before();
        $service = Doctrine_Query::create()
                ->select('c.id,c.title,c.redirect')
                ->from('Content c')
                ->leftJoin('c.ContentCategory cat')
                ->where('cat.name="为您服务"')
                ->orderBy('c.order_num ASC')
                ->fetchArray();

        $jobs = Doctrine_Query::create()
                ->select('c.id,c.title,c.redirect')
                ->from('Content c')
                ->leftJoin('c.ContentCategory cat')
                ->where('cat.name="招生就业"')
                ->orderBy('c.order_num ASC')
                ->fetchArray();

        View::set_global('service', $service);
        View::set_global('jobs', $jobs);
    }

    function action_index() {
        $id = Arr::get($_GET, 'id');

        //展现内容
        if ($id) {
            $content = Doctrine_Query::create()
                    ->from('Content')
                    ->where('id=?', $id)
                    ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
        } else {
            $content = Doctrine_Query::create()
                    ->from('Content')
                    ->where('title="走进校园"')
                    ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
        }

        $this->_title('为您服务');
        $this->_render('_body', compact('content'));
    }

    //校区地图
    function action_map() {
        $id = Arr::get($_GET, 'id');
        $school = Doctrine_Query::create()
                ->select('c.id,c.title,c.redirect')
                ->from('Content c')
                ->leftJoin('c.ContentCategory cat')
                ->where('cat.name="为您服务-校区介绍"')
                ->orderBy('c.order_num ASC')
                ->fetchArray();

        $content = Doctrine_Query::create()
                ->from('Content')
                ->where('id=?', $id)
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);


        $this->_title($content['title']);
        $this->_render('_body', compact('id', 'school', 'content'));
    }

    //衣食住行
    function action_guide() {
        $id = Arr::get($_GET, 'id');
        $school = Doctrine_Query::create()
                ->select('c.id,c.title,c.redirect')
                ->from('Content c')
                ->leftJoin('c.ContentCategory cat')
                ->where('cat.name="为您服务-吃住行指南"')
                ->orderBy('c.order_num ASC')
                ->fetchArray();

        $content = Doctrine_Query::create()
                ->from('Content')
                ->where('id=?', $id)
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);


        $this->_title($content['title']);
        $this->_render('_body', compact('id', 'school', 'content'));
    }

}

?>
