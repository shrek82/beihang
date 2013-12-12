<?php
/**
  +-----------------------------------------------------------------
 * 名称：后台管理控制器
 * 版本：1.0
 * 版权：www.usho.cn
 * 作者：zhaojiangang
 * 最后更新日期：12-10-12 上午9:42
  +-----------------------------------------------------------------
 */
class Layout_Admin extends Layout_Main {

    public $template = 'layout/admin';

    function before() {
        parent::before();
        //非管理员
        if (($this->_role != '管理员') OR (!$this->_sess->get('id'))) {
            Request::instance()->redirect('main/deny?reason=' . urlencode('很抱歉，您没有权限访问该页！'));
            exit;
        }
    }

}