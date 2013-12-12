<?php

class Controller_Modulejs extends Layout_Main {

    public $template = 'layout/xhtml_v2';

    public function before() {
        parent::before();
    }

    function action_index() {
        $view[]=1;
        $this->_title('前端重构');
        $this->_render('_body', $view);
    }

    function action_seajs(){

    }


}