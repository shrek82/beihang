<?php

//新浪微博
header("content-type:text/html; charset=utf-8");

class Controller_Api_Sina extends Layout_Main {

        public $_user_id;
        public $_config;

        function before() {
                parent::before();
                $this->auto_render = false;
                $this->_user_id=$this->_sess->get('id');
                $this->_config = Kohana::config('app');
        }

        function action_index() {
                
        }
        function action_post() {

                $binding = Model_Binding::getBinding(array(
                            'user_id' => $this->_user_id,
                            'service' => 'sina',
                        ));
                $view['binding'] = $binding;
                
                echo Kohana::debug($binding);

                //if ($_POST) {
                        Candy::import('sinaWeiboApi');
                        $c = new SaeTClientV2($this->_config->sina['WB_AKEY'], $this->_config->sina['WB_SKEY'], $binding['access_token']);
                        $content = Arr::get($_POST, 'content', '出来露露脸，增加下人气～～');
                        $ret = $c->update($content); //发送微博
                        if (isset($ret['error_code']) && $ret['error_code'] > 0) {
                                echo "发送失败，错误：{$ret['error_code']}:{$ret['error']}";
                        } else {
                                exit;
                        }
                        
                        echo Kohana::debug($ret);
              //  }
        }

}