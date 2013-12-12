<?php

class Controller_Aa_Admin_Album extends Layout_Aa {

    function before() {
        parent::before();

        // 管理组成员
        if (!$this->_aa_manager) {
            Request::instance()->redirect('main/deny?reason=' . urlencode('很抱歉，您没有管理权限'));
            exit;
        }

        $actions = array(
            'aa_admin_album/index' => '所有相册',
            'aa_admin_album/new' => '最近更新',
            'aa_admin_album/event' => '活动相册',
            'aa_admin_album/club' => '俱乐部相册',
            'aa_admin_album/aa' => '公共相册',
            'aa_admin_album/category' => '创建相册',
        );
        $this->_render('_body_action', compact('actions'), 'aa_global/admin_action');
    }

    # 所有相册

    function action_index($list = 'all') {
        $page = Arr::get($_GET, 'page', 1);
        $q = Arr::get($_POST, 'q');
        $view['q'] = $q;
        $condition = array('empty_albums' => True, 'aa_id' => $this->_id, 'page_size' => 15, 'count' => true, 'page' => $page, 'list' => $list, 'q' => $q);
        $album_data = Db_Album::getAlbums($condition);
        $view['albums'] = $album_data['albums'];
        $view['pager'] = $album_data['pager'];
        $this->_title('所有相册');
        $this->_render('_body', $view, 'aa_admin_album/index');
    }

    # 最新相册

    function action_new($list = 'new') {
        $page = Arr::get($_GET, 'page', 1);
        $q = Arr::get($_POST, 'q');
        $view['q'] = $q;
        $condition = array('empty_albums' => False, 'aa_id' => $this->_id, 'page_size' => 15, 'count' => true, 'page' => $page, 'list' => $list, 'q' => $q);
        $album_data = Db_Album::getAlbums($condition);
        $view['albums'] = $album_data['albums'];
        $view['pager'] = $album_data['pager'];
        $this->_title('最近更新相册');
        $this->_render('_body', $view, 'aa_admin_album/index');
    }

    # 活动相册

    function action_event($list = 'event') {
        $page = Arr::get($_GET, 'page', 1);
        $q = Arr::get($_POST, 'q');
        $view['q'] = $q;
        $condition = array('empty_albums' => True, 'aa_id' => $this->_id, 'page_size' => 15, 'count' => true, 'page' => $page, 'list' => $list, 'q' => $q);
        $album_data = Db_Album::getAlbums($condition);
        $view['albums'] = $album_data['albums'];
        $view['pager'] = $album_data['pager'];
        $this->_title('活动相册');
        $this->_render('_body', $view, 'aa_admin_album/index');
    }

    # 俱乐部相册

    function action_club($list = 'club') {
        $page = Arr::get($_GET, 'page', 1);
        $q = Arr::get($_POST, 'q');
        $view['q'] = $q;
        $condition = array('empty_albums' => True, 'aa_id' => $this->_id, 'page_size' => 15, 'count' => true, 'page' => $page, 'list' => $list, 'q' => $q);
        $album_data = Db_Album::getAlbums($condition);
        $view['albums'] = $album_data['albums'];
        $view['pager'] = $album_data['pager'];
        $this->_title('俱乐部相册');
        $this->_render('_body', $view, 'aa_admin_album/index');
    }

    #单独相册

    function action_aa($list = 'aa') {
        $page = Arr::get($_GET, 'page', 1);
        $q = Arr::get($_POST, 'q');
        $view['q'] = $q;
        $condition = array('empty_albums' => True, 'aa_id' => $this->_id, 'page_size' => 15, 'count' => true, 'page' => $page, 'list' => $list, 'q' => $q);
        $album_data = Db_Album::getAlbums($condition);
        $view['albums'] = $album_data['albums'];
        $view['pager'] = $album_data['pager'];
        $this->_title('自建相册');
        $this->_render('_body', $view, 'aa_admin_album/index');
    }

    //自建相册
    function action_category() {
        $album_id = Arr::get($_GET, 'album_id');

        $album = Doctrine_Query::create()
                ->from('Album a')
                ->where('a.aa_id = ?', $this->_id)
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
                $album->aa_id = $this->_id;
                $album->name = Arr::get($_POST, 'name');
                $album->order_num = Arr::get($_POST, 'order_num');
                $album->create_at = date('Y-n-d H:i:s');
            }
            $album->save();
            $this->_redirect(URL::site('aa_admin_album/category?id=' . $this->_id));
        }

        $this->_title('创建相册');
        $this->_render('_body', $view);
    }

    //修改相册
    function action_editForm() {
        $this->auto_render = FALSE;
        $album_id = Arr::get($_GET, 'album_id');
        $album=Doctrine_Query::create()
                ->from('Album')
                ->where('id=?', $album_id)
                ->addWhere('aa_id=?', $this->_id)
                ->fetchOne();

        if($_POST){

            if (empty($_POST['name'])) {
                echo 'err#相册名称不能为空';
                exit;
            }
            $album->synchronizeWithArray($_POST);
            $album->save();
            echo $album->id;
            exit;
        }
        $view['album']=$album;
        $view['action_url']='/aa_admin_album/editForm?id='.$this->_id.'&album_id='.$album_id;
        echo View::factory('album/editAlbum',$view);
    }

    //删除相册
    function action_del() {
        $album_id = Arr::get($_GET, 'album_id');
        $condition = array('id' => $album_id);
        Db_Album::delete($condition);
    }

}