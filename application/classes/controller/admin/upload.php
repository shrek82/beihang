<?php

class Controller_Admin_Upload extends Layout_Admin {

        function before() {
                parent::before();
        }

        //管理员上传附件
        function action_index() {
                $this->auto_render = False;
                $view['error'] = '';
                $view['file_path'] = '';
                $view['file_extend'] = '';
                $file_name = date('YmdHis') . rand(100, 1000);

                //自动创建目录
                if (!is_dir('static/upload/attached/' . date('Ym'))) {
                        mkdir('static/upload/attached/' . date('Ym'), 0777);
                }

                if ($_POST) {

                        //如果上传了附件
                        if ($_FILES['file']['size'] > 0) {

                                $valid = Validate::factory($_FILES);
                                $valid->rules('file', Model_Content::$attached_rule);
                                if (!$valid->check()) {
                                        $view['err'] .= $valid->outputMsg($valid->errors('validate'));
                                } else {
                                        // 处理附件
                                        $file_extend = strtolower(trim(substr(strrchr($_FILES['file']['name'], '.'), 1)));
                                        $view['file_extend'] = $file_extend;
                                        $replace = '/\.' . $file_extend . '/';
                                        $view['old_file_name'] = preg_replace($replace, '', $_FILES['file']['name']);
                                        $path = DOCROOT . Model_Content::ATTACHED_PATH . date('Ym') . '/';
                                        $view['file_path'] = URL::base() . Model_Content::ATTACHED_PATH . date('Ym') . '/' . $file_name . '.' . $file_extend;
                                        Upload::save($_FILES['file'], $file_name . '.' . $file_extend, $path);
                                }
                        }
                        // 处理完毕后刷新页面
                }
                echo View::factory('admin_upload/index', $view);
        }

}

?>