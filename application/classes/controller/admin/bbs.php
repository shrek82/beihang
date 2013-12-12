<?php

class Controller_Admin_Bbs extends Layout_Admin {

    function before() {
        parent::before();

        $leftbar_links = array(
            'admin_bbs/index' => '所有话题',
            'admin_bbs/category' => '公共分类管理',
            'admin_bbs/focus' => '幻灯片',
        );

        $this->_render('_body_left', compact('leftbar_links'), 'admin/leftbar');
    }

    //话题管理
    function action_index() {
        $q = urldecode(Arr::get($_GET, 'q'));
        $user_id = Arr::get($_GET, 'user_id');
        $search_type = Arr::get($_GET, 'search_type');

        $aa = Arr::get($_GET, 'aa');

        $count = Doctrine_Query::create()
                ->from('BbsUnit u')
                ->select('u.id')
                ->where('u.id >0');

        $bbs_unit = Doctrine_Query::create()
                ->select('u.id,u.title,u.title_color,u.reply_num,u.create_at,u.comment_at,u.user_id,u.type,u.is_fixed,u.is_closed,u.is_good,u.is_pic,u.hit,u.reply_num, b.name, s.realname')
                ->addSelect('sf.id AS is_home')
                ->addSelect('IF(u.is_fixed = 1 AND b.aa_id=0,1,0) AS public_fixed')
                ->from('BbsUnit u')
                ->leftJoin('u.Bbs b');
        if ($aa) {
            $count->addWhere('u.aa_id = ?', $aa);
            $bbs_unit->where('u.aa_id = ?', $aa);
        } elseif ($aa == '0') {
            $count->addWhere('u.aa_id = ?', 0);
            $bbs_unit->where('u.aa_id =0');
        }

        $bbs_unit->leftJoin('u.User s')
                ->leftJoin('b.Aa a')
                ->leftJoin('u.SysFilter sf')
                ->addSelect('a.sname as aa_name,a.ename as aa_ename')
                ->orderBy('public_fixed DESC,
            case when u.comment_at IS NOT NULL then u.comment_at
                 when u.comment_at IS NULL then u.create_at
            end DESC');

        if ($q) {
            //按标题搜索
            if ($search_type == 'title') {
                $count->andWhere('u.title like ?', '%' . $q . '%');
                $bbs_unit->andWhere('u.title like ?', '%' . $q . '%');
            }
            //按作者
            else {
                $bbs_unit->andWhere('s.realname=?', $q);
            }
        }

        if ($user_id) {
            $bbs_unit->andWhere('s.id=?', $user_id);
        }

        $total_units = $count->count();

        $pager = Pagination::factory(array(
                    'total_items' => $total_units,
                    'items_per_page' => 20,
                    'view' => 'pager/common',
        ));

        $units = $bbs_unit->limit($pager->items_per_page)
                ->offset($pager->offset)
                ->fetchArray();

        $bbs = Doctrine_Query::create()
                ->from('Bbs b')
                ->where('b.aa_id = 0 AND b.club_id = 0')
                ->fetchArray();

        $this->_title('论坛列表');
        $this->_render('_body', compact('units', 'bbs', 'pager', 'q', 'aa', 'search_type'));
    }

    function action_unit() {
        if ($_POST) {
            $unit_id = Arr::get($_POST, 'unit_id');
            $mv = Arr::get($_POST, 'mv');
            $bool = Arr::get($_POST, 'bool');

            $unit = Doctrine_Query::create()
                    ->from('BbsUnit u')
                    ->where('u.id = ?', $unit_id)
                    ->fetchOne();

            if ($unit && isset($unit[$bool])) {
                $cur_val = $unit[$bool];
                $set_val = ($cur_val == TRUE) ? FALSE : TRUE;
                $unit[$bool] = $set_val;
                $unit->save();
            }

            if ($unit && $mv) {
                $unit['bbs_id'] = $mv;
                $unit->save();
            }

            if ($bool == 'is_fixed') {
                $action_name = $set_val ? '置顶了话题' : '取消置顶';
            } elseif ($bool == 'is_closed') {
                $action_name = $set_val ? '屏蔽了话题' : '取消屏蔽';
            } else {
                
            }

            $log_data = array();
            $log_data['type'] = '话题管理';
            $log_data['bbs_unit_id'] = $unit_id;
            $log_data['description'] = $action_name . '“' . $unit['title'] . '”';
            Common_Log::add($log_data);
        }
    }

    function action_form() {
        $bbs_id = Arr::get($_GET, 'bbs_id');

        $bbs_parent = Doctrine_Query::create()
                ->from('Bbs')
                ->where('parent_id = -1')
                ->addWhere('aa_id = 0')
                ->orderBy('order_num ASC')
                ->fetchArray();

        $bbs = Doctrine_Query::create()
                ->from('Bbs b')
                ->where('b.id = ?', $bbs_id)
                ->fetchOne();

        if ($_POST) {

            if ($bbs != FALSE && ($bbs['name'] != $_POST['name'])) {
                if (Model_Bbs::isExist(array('aa_id' => 0, 'name' => $_POST['name']))) {
                    echo Candy::MARK_ERR . '已经有同名，请更换';
                    exit;
                }
            }

            if ($bbs) {
                $bbs->synchronizeWithArray($_POST);
            } else {
                $bbs = new Bbs();
                $bbs->name = Arr::get($_POST, 'name');
                $bbs->intro = Arr::get($_POST, 'intro');
                $bbs->order_num = Arr::get($_POST, 'order_num');
                $bbs->parent_id = Arr::get($_POST, 'parent_id');
            }
            $bbs->save();
            exit;
        }

        echo View::factory('admin_bbs/form', compact('bbs', 'bbs_parent'));
    }

    //评论管理
    function action_comment() {
        $q = urldecode(Arr::get($_GET, 'q'));
        $user_id = Arr::get($_GET, 'user_id');
        //搜索方式 按内容或按作者
        $search_type = Arr::get($_GET, 'search_type');
        //按所属浏览内容浏览
        $object = Arr::get($_GET, 'object');


        $aa = Arr::get($_GET, 'aa');

        $comment = Doctrine_Query::create()
                ->select('c.*,u.realname as realname')
                ->from('Comment c')
                ->leftJoin('c.User u')
                ->orderBy('c.post_at DESC');

        if ($q) {
            //按标题搜索
            if ($search_type == 'title') {
                $comment->andWhere('c.content like ?', '%' . $q . '%');
            }
            //按作者
            else {
                $comment->andWhere('u.realname=?', $q);
            }
        }

        if ($user_id) {
            $comment->where('c.user_id=?', $user_id);
        }

        if ($object) {
            switch ($object) {
                case 'news':
                    $comment->where('c.news_id>0');
                    break;
                case 'event':
                    $comment->where('c.event_id>0');
                    break;
                case 'bbs_unit':
                    $comment->where('c.bbs_unit_id>0');
                    break;
                case 'pic':
                    $comment->where('c.pic_id>0');
                    break;
                case 'vote':
                    $comment->where('c.vote_id>0');
                    break;
                case 'class_unit':
                    $comment->where('c.class_unit_id>0');
                    break;
                case 'class_room':
                    $comment->where('c.class_room_id>0');
                    break;
            }
        }

        $total_comment = $comment->count();

        $pager = Pagination::factory(array(
                    'total_items' => $total_comment,
                    'items_per_page' => 15,
                    'view' => 'pager/common',
        ));

        $comments = $comment->limit($pager->items_per_page)
                ->offset($pager->offset)
                ->fetchArray();

        $this->_title('话题列表');
        $this->_render('_body', compact('comments', 'pager', 'q', 'object', 'search_type'));
    }

    //删除评论
    function action_delComment() {
        $this->auto_render = FALSE;
        $cid = Arr::get($_GET, 'cid');
        Db_Comment::delete(array('id' => $cid));
    }

    //论坛首页幻灯片
    function action_focus() {
        $q = urldecode(Arr::get($_GET, 'q'));

        $bbs_unit = Doctrine_Query::create()
                ->select('u.id,u.title,u.create_at,u.is_focus,u.type')
                ->where('u.is_focus=?', True)
                ->from('BbsUnit u')
                ->orderBy('u.id DESC');

        if ($q) {
            $bbs_unit->addWhere('u.title like ?', '%' . $q . '%');
        }

        $total_units = $bbs_unit->count();

        $pager = Pagination::factory(array(
                    'total_items' => $total_units,
                    'items_per_page' => 20,
                    'view' => 'pager/common',
        ));

        $units = $bbs_unit->limit($pager->items_per_page)
                ->offset($pager->offset)
                ->fetchArray();


        $this->_title('论坛幻灯片管理列表');
        $this->_render('_body', compact('units', 'pager', 'q'));
    }

    //增加及修改论坛幻灯片
    function action_focusForm() {

        $id = Arr::get($_GET, 'id', 0);
        $view['err'] = '';
        $img_path = '';
        $file_name = date("YmdHis");
        $bbs_focus = Doctrine_Query::create()
                ->select('u.*,p.content AS content')
                ->from('BbsUnit u')
                ->leftJoin('u.Post p')
                ->where('u.id = ?', $id)
                ->fetchOne();

        $view['bbs_focus'] = $bbs_focus;

        preg_match_all("'src[\s\r\n]?=[\s\r\n]?[\\\]?[\'|\"]?(.*?\.(jpg|gif|png))[\\\]?[\'\"]?'si", $bbs_focus['content'], $imgArray);
        $view['img_path'] = $imgArray[1];

        if ($_POST) {
            //使用自定义上传图片
            if ($_FILES['file']['size'] > 0) {
                //上传的图片
                $valid = Validate::factory($_FILES);
                $valid->rules('file', Model_Bbs::$up_rule);
                if (!$valid->check()) {
                    $view['err'] .= $valid->outputMsg($valid->errors('validate'));
                } else {
                    // 处理图片
                    $path = DOCROOT . Model_Bbs::FOCUS_PATH;
                    Upload::save($_FILES['file'], $file_name, $path);
                    //原尺寸
                    Image::factory($path . $file_name)
                            ->resize(366, 244, Image::NONE)
                            ->save($path . $file_name . '.jpg');

                    $img_path = URL::base() . Model_Bbs::FOCUS_PATH . $file_name . '.jpg';
                    unlink($path . $file_name);
                    $post['img_path'] = $img_path;
                }
            }
            //使用话题内容图片
            elseif (Arr::get($_POST, 'img_path')) {

                $path = DOCROOT . Model_Bbs::FOCUS_PATH;
                Image::factory(DOCROOT . Arr::get($_POST, 'img_path'))
                        ->resize(366, 244, Image::NONE)
                        ->save($path . $file_name . '.jpg');

                $post['img_path'] = URL::base() . Model_Bbs::FOCUS_PATH . $file_name . '.jpg';
            } else {
                $view['err'] = '还没有选择图片';
            }

            // 保存修改
            if ($bbs_focus && isset($post['img_path'])) {
                $post['is_focus'] = True;
                $bbs_focus->synchronizeWithArray($post);
                $bbs_focus->save();

                $this->request->redirect('bbs/');
            }
        }

        $this->_render('_body', $view);
    }

    //删除幻灯片显示
    function action_delFocus() {
        $this->auto_render = FALSE;
        $cid = Arr::get($_GET, 'cid');
        $focus = Doctrine_Query::create()
                ->from('BbsUnit')
                ->where('id = ?', $cid)
                ->fetchOne();
        $post['is_focus'] = False;
        $focus->synchronizeWithArray($post);
        $focus->save();
    }

    //论坛分类
    function action_category() {
        $bbs = Doctrine_Query::create()
                ->from('Bbs b')
                ->where('b.aa_id = 0 AND b.club_id = 0')
                ->fetchArray();

        $view['bbs'] = $bbs;

        $this->_render('_body', $view);
    }

    //增加或删除首页显示
    function action_homepage() {
        $cid = Arr::get($_POST, 'cid');

        $filter = Doctrine_Query::create()
                ->from('SysFilter sf')
                ->where('sf.bbs_unit_id = ?', $cid)
                ->fetchOne();

        if ($filter) {
            $filter->delete();
        } else { // 新加入
            $new_sf = new SysFilter();
            $new_sf->bbs_unit_id = $cid;
            $new_sf->save();
        }
    }

    //删除话题
    function action_del() {
        $this->auto_render = FALSE;
        $cid = Arr::get($_GET, 'cid', -1);
        $del = Doctrine_Query::create()
                ->delete('BbsUnit')
                ->where('id =?', $cid)
                ->execute();

        Db_Comment::delete(array('bbs_unit_id' => $cid));
    }

}