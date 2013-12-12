<?php

class Controller_User_Album extends Layout_User {

    function action_index() {
        if ($_POST) {
            $album_name = Arr::get($_POST, 'name');
            if (!$album_name) {
                echo 'err#很抱歉，相册名称不能为空！';
                exit;
            } else {
                $album = new Album();
                $album->user_id = $this->_user_id;
                $album->name = $album_name;
                $album->create_at = date('Y-m-d H:i:s');
                $album->save();
                echo $album->id;
                exit;
            }
            exit;
        }

        $condition = array('user_id' => $this->_id, 'empty_albums' => true, 'list' => 'all', 'page_size' => 16);
        $album_data = Db_Album::getAlbums($condition);
        $view['albums'] = $album_data['albums'];

        $this->_title($this->_user['realname'] . '的相册');
        $this->_render('_body', $view);
    }

}