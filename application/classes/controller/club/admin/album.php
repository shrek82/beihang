<?php

class Controller_Club_Admin_Album extends Layout_Clubadmin {

    function before() {
        parent::before();

        $actions = array(
            'club_admin_album/index' => '所有相册',
            'club_admin_album/new' => '最近更新',
            'club_admin_album/event' => '活动相册',
            'club_admin_album/club' => '单独相册',
            'club_admin_album/category' => '创建相册',
        );
        $this->_render('_body_action', compact('actions'), 'club_global/admin_action');
    }

    # 所有相册

    function action_index($list = 'all') {
        $page = Arr::get($_GET, 'page', 1);
        $q = Arr::get($_POST, 'q');
        $view['q'] = $q;
        $condition = array('empty_albums' => True, 'club_id' => $this->_id, 'page_size' => 15, 'count' => true, 'page' => $page, 'list' => $list, 'q' => $q);
        $album_data = Db_Album::getAlbums($condition);
        $view['albums'] = $album_data['albums'];
        $view['pager'] = $album_data['pager'];
        $this->_title('所有相册');
        $this->_render('_body', $view, 'club_admin_album/index');
    }

    # 所有相册

    function action_new($list = 'new') {
        $page = Arr::get($_GET, 'page', 1);
        $q = Arr::get($_POST, 'q');
        $view['q'] = $q;
        $condition = array('empty_albums' => False, 'club_id' => $this->_id, 'page_size' => 15, 'count' => true, 'page' => $page, 'list' => $list, 'q' => $q);
        $album_data = Db_Album::getAlbums($condition);
        $view['albums'] = $album_data['albums'];
        $view['pager'] = $album_data['pager'];
        $this->_title('最近更新相册');
        $this->_render('_body', $view, 'club_admin_album/index');
    }

    # 所有相册

    function action_event($list = 'event') {
        $page = Arr::get($_GET, 'page', 1);
        $q = Arr::get($_POST, 'q');
        $view['q'] = $q;
        $condition = array('empty_albums' => true, 'club_id' => $this->_id, 'page_size' => 15, 'count' => true, 'page' => $page, 'list' => $list, 'q' => $q);
        $album_data = Db_Album::getAlbums($condition);
        $view['albums'] = $album_data['albums'];
        $view['pager'] = $album_data['pager'];
        $this->_title('活动相册');
        $this->_render('_body', $view, 'club_admin_album/index');
    }

    # 所有相册

    function action_club($list = 'club') {

        $page = Arr::get($_GET, 'page', 1);
        $q = Arr::get($_POST, 'q');
        $view['q'] = $q;
        $condition = array('empty_albums' => true, 'club_id' => $this->_id, 'page_size' => 15, 'count' => true, 'page' => $page, 'list' => $list, 'q' => $q);
        $album_data = Db_Album::getAlbums($condition);
        $view['albums'] = $album_data['albums'];
        $view['pager'] = $album_data['pager'];
        $this->_title('俱乐部相册');
        $this->_render('_body', $view, 'club_admin_album/index');
    }

    //自建相册
    function action_category() {

        $album_id = Arr::get($_GET, 'album_id');
        $album = Doctrine_Query::create()
                ->from('Album a')
                ->where('a.club_id = ?', $this->_id)
                ->addWhere('a.id = ?', $album_id)
                ->fetchOne();
        $view['album'] = $album;

        if ($_POST) {
            $album_name = Arr::get($_POST, 'name');
            if (empty($album_name)) {
                echo 'err#相册名称不能为空';
                exit;
            }
            if ($album) {
                $album->synchronizeWithArray($_POST);
            } else {
                $album = new Album();
                $album->aa_id = $this->_club['aa_id'];
                $album->club_id = $this->_id;
                $album->name = Arr::get($_POST, 'name');
                $album->create_at = date('Y-n-d H:i:s');
            }
            $album->save();
            $this->_redirect(URL::site('club_admin_album/category?id=' . $this->_id));
        }

        $this->_title('创建相册');
        $this->_render('_body', $view);
    }

    //修改相册
    function action_editForm() {
        $this->auto_render = FALSE;
        $album_id = Arr::get($_GET, 'album_id');
        $album = Doctrine_Query::create()
                ->from('Album')
                ->where('id=?', $album_id)
                ->addWhere('club_id=?', $this->_id)
                ->fetchOne();

        if ($_POST) {

            if (empty($_POST['name'])) {
                echo 'err#相册名称不能为空';
                exit;
            }
            $album->synchronizeWithArray($_POST);
            $album->save();
            echo $album->id;
            exit;
        }
        $view['album'] = $album;
        $view['action_url'] = '/club_admin_album/editForm?id=' . $this->_id . '&album_id=' . $album_id;
        echo View::factory('album/editAlbum', $view);
    }

    //删除相册
    function action_del() {
        $album_id = Arr::get($_GET, 'album_id');
        $condition = array('id' => $album_id);
        Db_Album::delete($condition);
    }

}