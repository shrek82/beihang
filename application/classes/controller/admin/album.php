<?php

class Controller_Admin_Album extends Layout_Admin {

    function before() {
        parent::before();
        $leftbar_links = array(
            'admin_album/index' => '组织、活动、个人照片',
            'admin_album/old_photos' => '岁月流金',
        );

        $this->_render('_body_left', compact('leftbar_links'), 'admin/leftbar');
    }

    #文字链接

    function action_index() {

        $type = Arr::get($_GET, 'type');
        
        $count=Doctrine_Query::create()
                ->select('p.id')
                ->from('Pic p')
                ->leftJoin('p.Album a');
        
        $pics = Doctrine_Query::create()
                ->select('p.id,p.user_id,p.upload_at,p.name,p.img_path,sf.id AS is_home,u.realname AS realname')
                ->from('Pic p')
                ->leftJoin('p.Album a')
                ->leftJoin('p.User u')
                ->leftJoin('p.SysFilter sf');

        if ($type == 'aa') {
            $count->where('a.aa_id>0');
            $pics->where('a.aa_id>0');
        }

        if ($type == 'classroom') {
            $count->where('a.classroom_id>0');
            $pics->where('a.classroom_id>0');
        }

        if ($type == 'evnet') {
            $count->where('a.event_id>0');
            $pics->where('a.event_id>0');
        }

        if ($type == 'club') {
            $count->where('a.classroom_id>0');
            $pics->where('a.club_id>0');
        }

        if ($type == 'user') {
            $count->where('a.classroom_id>0');
            $pics->where('a.user_id>0');
        }

        $pics->orderBy('id DESC');


        $total_pics = $count->count();

        $pager = Pagination::factory(array(
                    'total_items' => $total_pics,
                    'items_per_page' => 20,
                    'view' => 'pager/common',
        ));

        $view['type'] = $type;
        $view['pager'] = $pager;
        $view['pics'] = $pics->offset($pager->offset)
                ->limit($pager->items_per_page)
                ->fetchArray();

        $this->_title('文字链接');
        $this->_render('_body', $view);
    }

    #删除照片以及评论

    function action_del() {
        $this->auto_render = FALSE;
        $cid = Arr::get($_GET, 'cid');
        $pic = Doctrine_Query::create()
                ->from('Pic')
                ->where('id =?', $cid)
                ->fetchOne();

        if ($pic) {
            Db_Comment::delete(array('pic_id' => $pic->id));
            @unlink($pic['img_path']);
            @unlink(str_replace('resize/', '', $pic['img_path']));
            $pic->delete();
        }
    }

    //老照片
    function action_old_photos() {
        $album_id = Arr::get($_GET, 'album_id');

        $albums = Doctrine_Query::create()
                ->from('Album')
                ->where('aa_id=?', 0)
                ->orderBy('id ASC')
                ->fetchArray();

        $view['albums'] = $albums;

        $pics = Doctrine_Query::create()
                ->select('p.id,p.name,p.is_release,p.img_path,sf.id AS is_home')
                ->from('Pic p')
                ->leftJoin('p.Album a')
                ->leftJoin('p.SysFilter sf')
                ->where('a.aa_id=0');

        if ($album_id) {
            $pics->addWhere('p.album_id=?', $album_id);
        }

        $pics->orderBy('p.id DESC');

        $total_pics = $pics->count();

        $pager = Pagination::factory(array(
                    'total_items' => $total_pics,
                    'items_per_page' => 20,
                    'view' => 'pager/common',
        ));

        $view['album_id'] = $album_id;
        $view['pager'] = $pager;
        $view['pics'] = $pics->offset($pager->offset)
                ->limit($pager->items_per_page)
                ->fetchArray();

        $this->_title('文字链接');
        $this->_render('_body', $view);
    }

    function action_homepage() {
        $id = Arr::get($_GET, 'id');
		$id =$id?$id:Arr::get($_POST, 'id');

        $filter = Doctrine_Query::create()
                ->from('SysFilter sf')
                ->where('sf.pic_id = ?', $id)
                ->fetchOne();

        if ($filter) {
            $filter->delete();
        } else { // 新加入
            $new_sf = new SysFilter();
            $new_sf->pic_id = $id;
            $new_sf->save();
        }
    }

    function action_release() {
        $id = Arr::get($_GET, 'id');
        $pic = Doctrine_Query::create()
                ->from('Pic')
                ->where('id = ?', $id)
                ->fetchOne();

        if ($pic['is_release'] == TRUE) {
            $pic['is_release'] = FALSE;
        } else {
            $pic['is_release'] = TRUE;
        }

        $pic->save();
    }

}

?>
