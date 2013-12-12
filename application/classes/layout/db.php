<?php

class Layout_Db {

    const CACHE_GROUP = 'xcache';

    public $_siteurl;

    public function __construct() {
        $this->_siteurl = 'http://' . $_SERVER['HTTP_HOST'];
    }

}

?>
