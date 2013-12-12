<?php
//移动端网页
class Layout_NewMobile extends Layout_Main {

    public $template = 'layout/mobile2';

    //预设变量
    function before() {
        parent::before();
        $this->_siteurl = 'http://' . $_SERVER['HTTP_HOST'];

        //全局模板渲染
        $this->_render('_header', null, 'mobile/empty_header');
        $this->_render('_footer', null, 'mobile/footer');
    }


}

?>
