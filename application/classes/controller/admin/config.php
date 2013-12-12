<?php

class Controller_Admin_Config extends Layout_Admin {

    function before() {
        parent::before();
    }

    #文字链接

    function action_index() {

        $view['configs'] = Doctrine_Query::create()
                ->from('Config')
                ->orderBy('id ASC')
                ->fetchArray();

        $this->_title('网站设置');
        $this->_render('_body', $view);
    }

    #添加或修改链接

    function action_form() {

        $this->auto_render = False;

        $configs = Doctrine_Query::create()
                ->from('Config')
                ->fetchArray();

        foreach ($configs as $key => $c) {
            $config = Doctrine_Query::create()
                    ->from('Config')
                    ->where('key=?', $c['key'])
                    ->fetchOne();
            if ($config AND isset($_POST[$c['key']])) {
                $config->value = $_POST[$c['key']];
                $config->save();
            } elseif ($config AND $config['is_boolean']) {
                $config->value = '0';
                $config->save();
            }
        }
        
        Model_Config::get(true);
       
        echo 1;
    }

}

?>
