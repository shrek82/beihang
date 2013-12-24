<?php

class Controller_Service extends Layout_Main {

    function before() {
        parent::before();
    }

    function action_index() {
        $id = Arr::get($_GET, 'id');

        $categorys = Doctrine_Query::create()
                ->select('c.id,c.name')
                ->from('ContentCategory c')
                ->where('c.name like "%校友服务-%"')
                ->addWhere('c.name!="校友服务-介绍"')
                ->orderBy('c.order_num ASC')
                ->fetchArray();
        
        foreach ($categorys as $key => $cat) {
            $categorys[$key]['items'] = Doctrine_Query::create()
                    ->select('c.id,c.title,c.redirect')
                    ->from('Content c')
                    ->where('c.type=?', $cat['id'])
                    ->orderBy('c.id ASC')
                    ->fetchArray();
        }
        $view['categorys'] = $categorys;

        //展现内容
        if ($id) {
            $content = Doctrine_Query::create()
                    ->from('Content c')
                    ->leftJoin('c.ContentCategory cat')
                    ->where('cat.name like "%校友服务-%"')
                    ->addWhere('c.id=?', $id)
                    ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
        } else {
            $content = Doctrine_Query::create()
                    ->select('c.*')
                    ->from('Content c')
                    ->leftJoin('c.ContentCategory cat')
                    ->where('cat.name="校友服务-介绍"')
                    ->orderBy('c.id ASC')
                    ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
        }
        $view['content'] = $content;
        
       $this->_title('校友服务');
        $this->_render('_body', $view);
    }

}

?>
