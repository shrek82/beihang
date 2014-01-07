<?php

/**
  +-----------------------------------------------------------------
 * 名称：活动控制器
  +-----------------------------------------------------------------
 */
class Controller_Wedding extends Layout_Main {

    function before() {
        //$this->template = 'layout/wedding';
       // parent::before();
    }

    /**
      +------------------------------------------------------------------------------
     * 活动首页
      +------------------------------------------------------------------------------
     */
    function action_index() {
        
    }

    /**
      +------------------------------------------------------------------------------
     * 添加或修改报名信息
      +------------------------------------------------------------------------------
     */
    function action_sign() {

        $this->auto_render = FALSE;
        $event_id = Arr::get($_POST, 'event_id');
        $sign_action = Arr::get($_POST, 'sign_action');
        $eventSign = new Model_Eventsign($event_id, $this->_uid, $sign_action);

        //传递表单数据
        $eventSign->postData = $_POST;

        //提交添加或修改信息
        $post_status = $eventSign->signSub();

        // 执行报名或修改操作
        if ($post_status) {
            echo $post_status;
        }
        //报名或修改失败
        else {
            $error = $eventSign->getError();
            echo 'err#' . $error;
            exit;
        }
    }

}
