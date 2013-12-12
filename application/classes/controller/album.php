<?php

class Controller_Album extends Layout_Main {

    function before() {
        $this->template = 'layout/album';
        parent::before();
    }

    //照片列表
    function action_picIndex() {
        $this->userPermissions('picView');
        $album_id = Arr::get($_GET, 'id', 0);
        $upload = Arr::get($_GET, 'upload');
        $sess_user = $this->_uid;
        $album_category = null;
        $banned_upload = null;
        $must_release = False;

        $album = Doctrine_Query::create()
                ->from('Album ab')
                ->where('ab.id = ?', $album_id);

        //不存在相册
        if ($album->count() == 0) { // no album
            echo View::factory('main/notFound');
        }

        //存在相册
        else {
            $view['album'] = $album->fetchOne(array(), 3); // get album

            $url = ''; // return url
            $url_name = ''; //return name
            //
            //活动相册
           if ($view['album']['event_id']) { // event
                $event = Db_Event::getEventById($view['album']['event_id']);
                $view['event'] = $event;
                $url = Db_Event::getLink($event['id'], $event['aa_id'], $event['club_id']);
                $url_name = $event['title'] . '活动首页';
                $album_category = '活动相册';
                $banned_upload = '您还没有参加该活动，暂时不能上传照片！';
                $view['is_allowed_upload'] = Model_Event::isJoined($view['album']['event_id'], $sess_user);
                //没有上传权限考虑是否是管理员
                if (!$view['is_allowed_upload']) {
                    $permission = Db_Event::getPermission($event['id'], $this->_uid, $event);
                    if ($permission['is_edit_permission']) {
                        $view['is_allowed_upload'] = True;
                    }
                }
            }
            //俱乐部相册
            elseif($view['album']['club_id']) { // club
                $club = Doctrine_Query::create()
                        ->select('id,aa_id,name')
                        ->from('Club')
                        ->where('id = ?', $view['album']['club_id'])
                        ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

                $url = URL::site('club_home/album?id=' . $club['id']);
                $url_name = $club['name'];
                $banned_upload = '您还不是该俱乐部成员，暂时不能上传照片！';
                $album_category = '俱乐部相册';
                $view['is_allowed_upload'] = DB_Club::isMember($club['id'], $this->_uid);
                if (!$view['is_allowed_upload']) {
                    $view['is_allowed_upload']=DB_Aa::isManager($club['aa_id'], $this->_uid);
                }

            }
            //校友会小册
            elseif ($view['album']['aa_id']) { // aa
                $aa = Doctrine_Query::create()
                        ->select('id,name,sname')
                        ->from('Aa')
                        ->where('id = ?', $view['album']['aa_id'])
                        ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

                $url = URL::site('aa_home/album?id=' . $view['album']['aa_id']);
                $url_name = $aa['sname'] . '校友会';
                $album_category = '校友会相册';
                $banned_upload = '您还不是该校友会成员，暂时不能上传照片！';
                $view['is_allowed_upload'] = DB_Aa::isMember($view['album']['aa_id'], $sess_user);
            }
            //会员相册
            elseif ($view['album']['user_id']) { //user
                $user = Doctrine_Query::create()
                        ->select('id,realname')
                        ->from('User')
                        ->where('id = ?', $view['album']['user_id'])
                        ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

                $url = URL::site('user_album/index?id=' . $view['album']['user_id']);
                $url_name = $user['realname'] . '主页';
                $album_category = '个人相册';
                $banned_upload = '您不是本人，暂时不能上传照片！';
                $view['is_allowed_upload'] = ($view['album']['user_id'] == $sess_user) ? true : false;
            }
            //专题相册
            elseif ($view['album']['special_id']) { //user
                $special = Doctrine_Query::create()
                        ->select('*')
                        ->from('NewsSpecial')
                        ->where('id = ?', $view['album']['special_id'])
                        ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

                $url = URL::site('main');
                $url_name = '专题图片';
                if ($view['album']['is_allow_upload'] OR $this->_sess->get('role') == '管理员') {
                    $view['is_allowed_upload'] = true;
                } else {
                    $view['is_allowed_upload'] = false;
                }
                $banned_upload = '您暂时没有权限上传照片！';
                $album_category = '专题相册';
            }
           //班级相册
            elseif ($view['album']['classroom_id']) {
                $url = URL::site('classroom_home/album?id=' . $view['album']['classroom_id']);
                $classroom = Doctrine_Query::create()
                        ->select('id,name,speciality,start_year')
                        ->from('ClassRoom')
                        ->where('id = ?', $view['album']['classroom_id'])
                        ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
                $classroom_name = $classroom['speciality'] ? $classroom['speciality'] : $classroom['name'];
                $url_name = $classroom['start_year'] . '级' . $classroom_name;
                $view['is_allowed_upload'] = Model_Classroom::isMember($view['album']['classroom_id'], $sess_user);
                $banned_upload = '您还不是班级成员，暂时不能上传照片！';
                $album_category = '班级相册';
            }
            //总会相册
            elseif ($view['album']['aa_id'] === '0') {
                $url = URL::site('aa');
                $url_name = '校友总会';
                $view['is_allowed_upload'] = $this->userPermissions('uploadpic', true);
                $banned_upload = '您暂时没有权限上传照片！';
                $album_category = '总会相册';
                $must_release = True;
            }
            else{

            }

            $view['url'] = $url;
            $view['url_name'] = $url_name;

            $pic = Doctrine_Query::create()
                    ->select('p.*,u.realname AS realname')
                    ->from('Pic p')
                    ->leftJoin('p.User u')
                    ->where('p.album_id = ?', $album_id);

            //总会求是追忆还需为审核
            if ($must_release AND $album_id == 59) {
                $pic->addWhere('is_release=?', True);
            }

            $pic->orderBy('p.id DESC');

            $total_pics = $pic->count();
            $view['total_pics'] = $total_pics;

            $pager = Pagination::factory(array(
                        'total_items' => $total_pics,
                        'items_per_page' => 24,
                        'view' => 'pager/common'
            ));
            $view['pager'] = $pager->render();
            $view['upload'] = $upload;

            $view['album_category'] = $album_category;
            $view['banned_upload'] = $banned_upload;

            $view['pics'] = $pic->offset($pager->offset) // get pics
                    ->limit($pager->items_per_page)
                    ->fetchArray();

            $this->_render('_body', $view);
        }
    }

    //相册首页
    function action_index() {

        $this->auto_render = false;
        $user_id = Arr::get($_GET, 'user');
        $aa_id = Arr::get($_GET, 'aa');
        $club_id = Arr::get($_GET, 'club');
        $event_id = Arr::get($_GET, 'event');
        $classroom_id = Arr::get($_GET, 'classroom_id');

        $album = Doctrine_Query::create()
                ->select('ab.*,
					(SELECT p.img_path FROM Pic p
						WHERE p.album_id = ab.id ORDER BY p.upload_at DESC LIMIT 1) AS snapshot')
                ->addSelect('(SELECT COUNT(pc.id) FROM Pic pc
						WHERE pc.album_id = ab.id) AS total_pics')
                ->from('Album ab')
                ->orderBy('ab.update_at DESC, ab.id DESC');

        if ($user_id) { // personal album
            $view['album'] = $album->where('user_id = ?', $user_id)
                    ->fetchArray();
        }

        if ($event_id) {
            $view['album'] = $album->where('event_id = ?', $event_id)
                    ->fetchArray();
        }

        if ($aa_id) {
            $view['album'] = $album->where('ab.aa_id = ?', $aa_id)
                    ->fetchArray();
        }

        if ($club_id) {
            $view['album'] = $album->where('club_id = ?', $club_id)
                    ->fetchArray();
        }

        if ($classroom_id) {
            $view['album'] = $album->where('classroom_id = ?', $classroom_id)
                    ->fetchArray();
        }

        echo View::factory('album/index', $view);
    }

    function action_render($id) {
        $this->_debug = false;
        $this->_dev = false;
        $this->auto_render = false;
        $this->request->headers['Content-Type'] = 'image/jpeg';

        $path = DOCROOT . Model_Album::PICS_DIR;
        $file = Image::factory($path . $id . '.jpg');
        if ($file->width > 800) {
            $file->resize(800, null);
        }
        echo $file->render('jpg');
    }

    function action_picView() {
        //$this->userPermissions('picView');
        $id = Arr::get($_GET, 'id');
        $view['mod'] = Arr::get($_GET, 'mod', 'v');

        $view['pic'] = Doctrine_Query::create()
                ->from('Pic')
                ->where('id = ?', $id)
                ->fetchOne();

        if ($view['pic']['user_id'] == $this->_uid) {

            $view['is_allowed_modify'] = true;
        } else {
            $view['is_allowed_modify'] = false;
        }

        $view['prev'] = Doctrine_Query::create()
                ->select('id,img_path')
                ->from('Pic')
                ->where('id > ?', $id)
                ->andWhere('album_id = ?', $view['pic']['album_id'])
                ->orderBy('id ASC')
                ->limit(1)
                ->execute(array(), 6);

        $view['next'] = Doctrine_Query::create()
                ->select('id,img_path')
                ->from('Pic')
                ->where('id < ?', $id)
                ->andWhere('album_id = ?', $view['pic']['album_id'])
                ->orderBy('id DESC')
                ->limit(1)
                ->execute(array(), 6);

        // no.x pic
        $view['total_num'] = Doctrine_Query::create()
                ->select('COUNT(pc.id) AS num')
                ->from('Pic pc')
                ->where('pc.album_id = ?', $view['pic']['album_id'])
                ->execute(array(), 6);

        $view['num'] = Doctrine_Query::create()
                ->select('COUNT(pc.id) AS num')
                ->from('Pic pc')
                ->where('pc.album_id = ?', $view['pic']['album_id'])
                ->andWhere('pc.id >= ?', $id)
                ->execute(array(), 6);

        // modify permission

        $this->_title($view['pic']['name'] . ' - 相册 ');
        $this->_render('_body', $view);
    }

    function action_picSave() {
        $id = Arr::get($_POST, 'id');

        $pic = Doctrine_Query::create()
                ->from('Pic')
                ->where('id = ?', $id)
                ->fetchOne();

        if ($_POST['name']) {
            $pattern = array('/\.jpg/i', '/\.gif/i', '/\.png/i', '/\.bmp/i');
            $replacement = '';
            $pic_name = preg_replace($pattern, $replacement, Arr::get($_POST, 'name'));
            $pic['name'] = $pic_name;
            $pic['intro'] = Arr::get($_POST, 'intro');
            $pic->save();
        }
    }

    function action_uploadPic() {

        $this->userPermissions('uploadpic');

        $user_id = $this->_uid;
        $view['id'] = Arr::get($_GET, 'id');

        if (base64_decode(Arr::get($_GET, 'enc')) != date('d')) {
            exit;
        }

        $view['album'] = Doctrine_Query::create()
                ->from('Album')
                ->where('id = ?', $view['id'])
                ->fetchOne(array(), 3);

        if (!$view['album']) {
            $this->request->redirect('/main');
        }
        $this->_title('上传照片');
        $this->_render('_body', $view, 'album/upload');
    }

    //批量编辑照片名称
    function action_picEdit() {
        $pic_ids = explode(',', Arr::get($_GET, 'uploaded_id'));
        $id = Arr::get($_GET, 'id');
        $view['id'] = $id;

        if ($_POST) {
            $pic_id = Arr::get($_POST, 'pic_id');
            $name = Arr::get($_POST, 'name');
            $intro = Arr::get($_POST, 'intro');

            foreach ($pic_id as $i => $p) {
                if ($pic_id[$i] AND $name[$i]) {
                    $pic = Doctrine_Query::create()
                            ->from('Pic')
                            ->where('id = ?', $pic_id[$i])
                            ->fetchOne();
                    if ($pic) {
                        $pic['name'] = $name[$i];
                        $pic['intro'] = $intro[$i];
                        $pic->save();
                    }
                }
            }
            $this->_redirect('album/picIndex?upload=new&id=' . $id);
        } else {

            $view['photo'] = Doctrine_Query::create()
                    ->from('Pic p')
                    ->whereIn('id', $pic_ids)
                    ->orderBy('p.id')
                    ->fetchArray();

            $this->_title('批量编辑照片');
            $this->_render('_body', $view);
        }
    }

    //删除照片
    function action_del() {
        $this->auto_render = FALSE;
        $cid = Arr::get($_GET, 'cid');

        $pic = Doctrine_Query::create()
                ->from('Pic')
                ->where('id =?', $cid)
                ->fetchOne();

        if ($pic AND $pic['user_id'] == $this->_uid) {
            Db_Comment::delete(array('pic_id' => $cid));
            @unlink($pic['img_path']);
            @unlink(str_replace('resize/', '', $pic['img_path']));
            $pic->delete();
            Model_Album::updatePicNum($pic['album_id']);
        }
    }

    //通过编辑器上传照片附件自动保存到相册
    function action_uploadAttached() {

        $this->auto_render = false;

        if (!$this->_uid) {
            exit;
        }

        $myAlbum = Doctrine_Query::create()
                ->from('Album')
                ->where('user_id = ?', $this->_uid)
                ->addWhere('name="我上传的照片"')
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

        if ($myAlbum) {
            $album_id = $myAlbum['id'];
        } else {
            $myAlbum = new Album();
            $myAlbum->user_id = $this->_uid;
            $myAlbum->name = '我上传的照片';
            $myAlbum->create_at = date('Y-m-d H:i:s');
            $myAlbum->save();
            $album_id = $myAlbum->id;
        }


        if ($_FILES) {
            $v = Validate::factory($_FILES);
            $v->rules('imgFile', Model_Album::$up_rule);

            //自动创建目录
            if (!is_dir('static/upload/attached/' . date('Ym'))) {
                mkdir('static/upload/attached/' . date('Ym'), 0777);
            }

            //大图目录
            if (!is_dir('static/upload/attached/' . date('Ym') . '/' . date('j'))) {
                mkdir('static/upload/attached/' . date('Ym') . '/' . date('j'), 0777);
            }

            //缩略图
            if (!is_dir('static/upload/attached/' . date('Ym') . '/' . date('j') . '/resize')) {
                mkdir('static/upload/attached/' . date('Ym') . '/' . date('j') . '/resize', 0777);
            }

            //正常显示大小
            if (!is_dir('static/upload/attached/' . date('Ym') . '/' . date('j') . '/normal')) {
                mkdir('static/upload/attached/' . date('Ym') . '/' . date('j') . '/normal', 0777);
            }

            //通过验证
            if (!$v->check()) {
                $err = $v->outputMsg($v->errors('validate'));
                echo json_encode(array('status' => 0, 'error' => $err));
            } else {
                $path = 'static/upload/attached/' . date('Ym') . '/' . date('j') . '/';
                $path_small = 'static/upload/attached/' . date('Ym') . '/' . date('j') . '/resize/';
                $path_normal = 'static/upload/attached/' . date('Ym') . '/' . date('j') . '/normal/';
                $file_name = date("YmjHis") . rand(10, 1000);

                //保存到临时目录
                Upload::save($_FILES['imgFile'], $this->_uid, $path);

                $pattern = array('/\.jpg/i', '/\.gif/i', '/\.png/i', '/\.bmp/i');
                $replacement = '';
                $pic = new Pic();
                $pic->user_id = $this->_uid;
                $pic->album_id = $album_id;
                $pic->name = $file_name;
                $pic->intro = '新闻、话题或评论中上传的照片';
                $pic->img_path = $path_small . $file_name . '.jpg';
                $pic->upload_at = date('Y-m-d H:i:s');
                $pic->Album['update_at'] = date('Y-m-d H:i:s');
                $pic->save();

                Model_Album::updatePicNum($album_id);

                //原尺寸
                $file = Image::factory($path . $this->_uid);
                $w = $file->width;
                $h = $file->height;

                //裁剪大图
                if ($w > 800) {
                    Image::factory($path . $this->_uid)
                            ->resize(800, 600)
                            ->save($path . $file_name . '.jpg');
                }
                //不裁剪大图
                else {
                    $file->save($path . $file_name . '.jpg');
                }

                //一般大小图片
                Image::factory($path . $this->_uid)
                        ->resize(500, 600)
                        ->save($path_normal . $file_name . '.jpg');

                //缩略图
                Image::factory($path . $this->_uid)
                        ->resize(150, 110)
                        ->save($path_small . $file_name . '.jpg');

                //删除临时文件,名称为用户ID
                unlink($path . $this->_uid);

                echo $path_small . $file_name . '.jpg';
                //返回json
                //echo json_encode(array('status' => 1,'big_path'=>$path. $file_name . '.jpg','smail_path'=>$path_small . $file_name . '.jpg', 'width' => $w, 'height' => $h, 'pic_id' => $pic->id));
            }
            exit;
        } else {
            //echo json_encode(array('status' => 0, 'error' => '没有选择任何图片!'));
        }
    }

    //获取相册ids
    function action_getAlbumIds() {
        $this->auto_render = false;
        $cat = Arr::get($_GET, 'cat');
        $aa_id = Arr::get($_GET, 'aa_id');
        $club_id = Arr::get($_GET, 'club_id');
        $classroom_id = Arr::get($_GET, 'classroom_id');
        $event_id = Arr::get($_GET, 'event_id');
        $user_id = $this->_uid;
        $album = Doctrine_Query::create()
                ->select('id,name,pic_num')
                ->from('Album');

        //我的相册
        if ($cat == 'my_album') {
            $html = '<select onchange="change_album_id(this.options[this.selectedIndex].value)">';
            $html.='<option>选择相册...</option>';
            $album = $album->where('user_id = ?', $this->_uid)
                    ->limit(10)
                    ->orderBy('id ASC')
                    ->fetchArray();
            if (count($album) > 0) {
                foreach ($album AS $key => $a) {
                    $html.='<option value="' . $a['id'] . '">' . $a['name'] . '</option>';
                }
            } else {
                $new_album = new Album();
                $new_album->user_id = $user_id;
                $new_album->name = '精彩分享';
                $new_album->create_at = date('Y-m-d H:i:s');
                $new_album->save();
                $html.='<option value="' . $new_album->id . '">精彩分享(自动创建)</option>';
            }
            $html.='</select>';
            echo $html;
        }
        //校友会相册
        elseif ($aa_id) {
            $html = '<select onchange="change_album_id(this.options[this.selectedIndex].value)">';
            $html.='<option>选择相册...</option>';
            $album = $album->where('aa_id = ?', $aa_id)
                    ->limit(15)
                    ->orderBy('id ASC')
                    ->fetchArray();
            if (count($album) > 0) {
                foreach ($album AS $key => $a) {
                    $html.='<option value="' . $a['id'] . '">' . Text::limit_chars($a['name'], 15, '...') . '</option>';
                }
            } else {
                $new_album = new Album();
                $new_album->aa_id = $aa_id;
                $new_album->name = '精彩分享';
                $new_album->create_at = date('Y-m-d H:i:s');
                $new_album->save();
                $html.='<option value="' . $new_album->id . '">精彩分享</option>';
            }
            $html.='</select>';
            echo $html;
        }
        //俱乐部相册
        elseif ($club_id) {
            $html = '<select onchange="change_album_id(this.options[this.selectedIndex].value)">';
            $html.='<option>选择相册...</option>';
            $album = $album->where('club_id = ?', $club_id)
                    ->limit(10)
                    ->orderBy('id ASC')
                    ->fetchArray();
            if (count($album) > 0) {
                foreach ($album AS $key => $a) {
                    $html.='<option value="' . $a['id'] . '">' . Text::limit_chars($a['name'], 15, '...') . '</option>';
                }
            } else {
                $new_album = new Album();
                $new_album->club_id = $club_id;
                $new_album->name = '精彩分享';
                $new_album->create_at = date('Y-m-d H:i:s');
                $new_album->save();
                $html.='<option value="' . $new_album->id . '">精彩分享</option>';
            }
            $html.='</select>';
            echo $html;
        }
        //班级相册
        elseif ($classroom_id) {
            $html = '<select onchange="change_album_id(this.options[this.selectedIndex].value)">';
            $html.='<option>选择相册...</option>';
            $album = $album->where('classroom_id = ?', $classroom_id)
                    ->limit(10)
                    ->orderBy('id ASC')
                    ->fetchArray();
            if (count($album) > 0) {
                foreach ($album AS $key => $a) {
                    $html.='<option value="' . $a['id'] . '">' . Text::limit_chars($a['name'], 15, '...') . '</option>';
                }
            } else {
                $new_album = new Album();
                $new_album->classroom_id = $club_id;
                $new_album->name = '精彩分享';
                $new_album->create_at = date('Y-m-d H:i:s');
                $new_album->save();
                $html.='<option value="' . $new_album->id . '">精彩分享</option>';
            }
            $html.='</select>';
            echo $html;
        }
        //近期参加的活动
        elseif ($cat == 'sign_event') {

            $html = '<select onchange="change_album_id(this.options[this.selectedIndex].value)">';
            $html.='<option>选择活动...</option>';

            $event = Doctrine_Query::create()
                    ->from('Event e')
                    ->select('e.id,e.title')
                    ->addSelect('(SELECT a.id FROM Album a WHERE a.event_id = e.id) AS album_id')
                    ->whereIn('id', Model_Event::joinIDs($user_id))
                    ->addWhere('e.is_closed = ? OR e.is_closed IS NULL', FALSE)
                    ->limit(15)
                    ->orderBy('e.ID DESC')
                    ->fetchArray();

            if (count($event) > 0) {
                foreach ($event AS $key => $e) {
                    if ($e['album_id']) {
                        $html.='<option value="' . $e['album_id'] . '">' . Text::limit_chars($e['title'], 28, '...') . '</option>';
                    }
                }
            } else {
                $html.='<option style="color:#999">暂无参加过活动</option>';
            }
            $html.='</select>';
            echo $html;
        }
        //我加入的校友会
        elseif ($cat == 'joined_aa') {
            $join_aa = Doctrine_Query::create()
                    ->from('Aa a')
                    ->select('a.id,a.sname,a.name')
                    ->whereIn('a.id', Model_User::aaIds($user_id))
                    ->orderBy('a.id ASC')
                    ->fetchArray();
            if (count($join_aa) > 0) {
                $org_field = "'aa_id'";
                $html = '<select onchange="change_org(' . $org_field . ',this.options[this.selectedIndex].value)">';
                $html.='<option>选择校友会...</option>';
                foreach ($join_aa AS $key => $a) {
                    $html.='<option value="' . $a['id'] . '">' . $a['sname'] . '校友会</option>';
                }
            } else {
                $html = '<select>';
                $html.='<option style="color:#999">暂无加入任何校友会</option>';
            }
            $html.='</select>';
            echo $html;
        }
        //加入的俱乐部
        elseif ($cat == 'joined_club') {
            $club = Doctrine_Query::create()
                    ->from('Club c')
                    ->whereIn('c.id', Model_User::clubIds($user_id))
                    ->fetchArray();
            if (count($club) > 0) {
                $org_field = "'club_id'";
                $html = '<select onchange="change_org(' . $org_field . ',this.options[this.selectedIndex].value)">';
                $html.='<option>选择俱乐部...</option>';
                foreach ($club AS $key => $c) {
                    $html.='<option value="' . $c['id'] . '">' . $c['name'] . '</option>';
                }
            } else {
                $html = '<select>';
                $html.='<option style="color:#999">暂无加入任何俱乐部</option>';
            }
            $html.='</select>';
            echo $html;
        }
        //我加入的班级
        elseif ($cat == 'joined_classroom') {
            $classroom = Doctrine_Query::create()
                    ->select('c.id,c.start_year,c.speciality')
                    ->from('ClassRoom c')
                    ->whereIn('c.id', Model_User::classroomIds($user_id))
                    ->fetchArray();
            if (count($classroom) > 0) {
                $org_field = "'classroom_id'";
                $html = '<select onchange="change_org(' . $org_field . ',this.options[this.selectedIndex].value)">';
                $html.='<option>选择班级...</option>';
                foreach ($classroom AS $key => $c) {
                    $html.='<option value="' . $c['id'] . '">' . $c['start_year'] . '级' . $c['speciality'] . '班</option>';
                }
            } else {
                $html = '<select>';
                $html.='<option style="color:#999">暂无加入任何班级</option>';
            }
            $html.='</select>';
            echo $html;
        }
        //校友总会
        elseif ($cat == 'main_aa') {
            $html = '<select onchange="change_album_id(this.options[this.selectedIndex].value)">';
            $album = $album->where('aa_id =0')
                    ->limit(15)
                    ->orderBy('id ASC')
                    ->fetchArray();
            if (count($album) > 0) {
                $html.='<option>选择相册...</option>';
                foreach ($album AS $key => $a) {
                    $html.='<option value="' . $a['id'] . '">' . Text::limit_chars($a['name'], 15, '...') . '</option>';
                }
            } else {
                $html.='<option style="color:#999">暂无相册</option>';
            }
            $html.='</select>';
            echo $html;
        } else {
            $html = '<select>">';
            $html.='<option style="color:#999">暂无相册</option>';
            echo $html;
            exit;
        }
    }

}