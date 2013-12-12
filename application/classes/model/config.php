<?php

//网站配置
class Model_Config extends Layout_Db {

    public static function get($reset = false) {

        $cache = Cache::instance(self::CACHE_GROUP);
        $setting = $cache->get('site_setting');

        if (!$setting OR $reset) {
            $setting = array();
            $configs = Doctrine_Query::create()
                    ->from('Config')
                    ->fetchArray();
            foreach ($configs as $c) {
                $value=$c['is_boolean']?(bool)(int)$c['value']:$c['value'];
                $setting[$c['key']] = $value;
            }
            $cache->set('site_setting', $setting, 604800);
        }

        return $setting;
    }

}

?>
