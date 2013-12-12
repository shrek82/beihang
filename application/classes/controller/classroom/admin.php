<?php

class Controller_Classroom_Admin extends Layout_Class {

    function before() {
        parent::before();
        if (!$this->_is_manager) {
            $this->_redirect('classroom_home?id=' . $this->_id);
        }
    }

    # 成员管理

    function action_members() {
        $q = Arr::get($_GET, 'q');

        $member = Doctrine_Query::create()
                ->select('cm.*, u.realname')
                ->from('ClassMember cm')
                ->leftJoin('cm.User u')
                ->where('cm.class_room_id = ?', $this->_id)
                ->orderBy('cm.visit_at DESC');

        if ($q) {
            $member->andWhere('u.realname LIKE ?', '%' . $q . '%');
        }

        $pager = Pagination::factory(array(
                    'total_items' => count($member),
                    'items_per_page' => 12,
                    'view' => 'pager/common'
                ));

        $view['pager'] = $pager;

        $members = $member->offset($pager->offset)
                ->limit($pager->items_per_page)
                ->fetchArray();

        $this->_title('成员管理列表');
        $this->_render('_body', compact('members', 'q', 'pager'));
    }

    function action_member($field) {
        $this->auto_render = FALSE;

        $mid = Arr::get($_GET, 'mid', 0);
        $member = Doctrine_Query::create()
                ->from('ClassMember cm')
                ->where('cm.id = ?', $mid)
                ->fetchOne();

        if ($field == 'kout') {
            if ($member['user_id'] == $this->_user_id) {
                echo '不能踢出自己';
            } else {
                $member->delete();
                exit;
            }
            exit;
        }

        if ($member) {
            $member->$field = Arr::get($_POST, 'val');
            $member->save();
        }
    }

    # 班级基础信息设置

    function action_base() {
        if ($_POST) {
            $v = Validate::setRules($_POST, 'classroom');
            $post = $v->getData();
            $classroom = Doctrine_Query::create()
                    ->from('ClassRoom cr')
                    ->where('cr.id = ?', $this->_id)
                    ->fetchOne();
            $classroom->synchronizeWithArray($post);
            $classroom->save();
            echo $classroom->id;
            exit;
        }

        $this->_title('班级基础信息设置');
        $this->_render('_body');
    }

    # 处理加入申请

    function action_apply($handler) {
        $id = Arr::get($_GET, 'apply_id');
        $cr_id = $this->_id;

        $apply = Doctrine_Query::create()
                ->from('JoinApply')
                ->where('class_room_id = ?', $cr_id)
                ->andWhere('id = ?', $id)
                ->fetchOne();

        if (!$apply)
            exit;

        if ($handler == 'accept') {
            $user_id = $apply['user_id'];
            $apply->delete();
            if (!Model_Classroom::isMember($cr_id, $user_id)) {
                Model_Classroom::join($cr_id, $user_id);
            }
        }

        // 拒绝申请处理
        if ($handler == 'reject') {
            $reason = trim(Arr::get($_POST, 'reject_reason'));
            if (!$reason) {
                $view['reason'] = $apply['reject_reason'];
                $view['action'] = URL::site('classroom_admin/apply/reject') . URL::query();
                echo View::factory('inc/reject_reason', $view);
            } else {
                $apply['reject_reason'] = $reason;
                $apply['is_reject'] = TRUE;
                $apply->save();
            }
        }
    }

    # 审批

    function action_index() {
        $apply = Doctrine_Query::create()
                ->select('jp.*,u.id,u.realname AS realname,u.sex,u.role')
                ->from('JoinApply jp')
                ->leftJoin('jp.User u')
                ->addSelect('(SELECT ab.id FROM ClassAbook ab WHERE ab.user_id = jp.user_id) AS has_abook')
                ->where('class_room_id = ?', $this->_id)
                ->orderby('jp.apply_at DESC, jp.is_reject ASC')
                ->fetchArray();

        $view['apply'] = $apply;
        $this->_render('_body', $view);
    }

    //置顶话题
    function action_setFix() {

        $this->auto_render = FALSE;
        $cid = Arr::get($_GET, 'cid');
        $unit = Doctrine_Query::create()
                ->from('ClassBbsUnit')
                ->where('id = ?', $cid)
                ->fetchOne();

        if ($unit['is_fixed'] == True) {
            $post['is_fixed'] = False;
        } else {
            $post['is_fixed'] = True;
        }
        $unit->fromArray($post);
        $unit->save();
    }

    //设置优秀话题
    function action_setGood() {

        $this->auto_render = FALSE;
        $cid = Arr::get($_GET, 'cid');
        $unit = Doctrine_Query::create()
                ->from('ClassBbsUnit')
                ->where('id = ?', $cid)
                ->fetchOne();

        if ($unit['is_good'] == True) {
            $post['is_good'] = False;
        } else {
            $post['is_good'] = True;
        }
        $unit->fromArray($post);
        $unit->save();
    }

    //删除话题
    function action_unitDel() {

        $this->auto_render = FALSE;
        $cid = Arr::get($_GET, 'cid');
        $unit = Doctrine_Query::create()
                ->from('ClassBbsUnit u')
                ->where('id =?', $cid)
                ->fetchOne();
        if ($unit AND ($unit['classroom_id'] == $this->_id)) {
            $unit->delete();
            Db_Comment::delete(array('class_unit_id' => $cid));
        }
    }

    //相册管理
    function action_album() {

        $album_id = Arr::get($_GET, 'album_id');

        $action_url = array(
            '/classroom_admin/album?id=' . $this->_id => '所有相册',
            '/classroom_admin/newalbum?id=' . $this->_id => '创建相册',
        );
        //View::set_global('action_url', $action_url);
        //删除相册
        if (Arr::get($_GET, 'del') == 'yes') {

            $album = Doctrine_Query::create()
                    ->from('Album a')
                    ->where('a.classroom_id = ?', $this->_id)
                    ->addWhere('a.id = ?', $album_id)
                    ->fetchOne();
            if ($album) {
                $album->delete();
                Doctrine_Query::create()
                        ->delete('Pic p')
                        ->where('p.album_id = ?', $album_id)
                        ->execute();
            }
            $this->_redirect(URL::site('classroom_admin/album?id=' . $this->_id));
        }

        $album = Doctrine_Query::create()
                ->from('Album a')
                ->where('a.id = ?', $album_id)
                ->fetchOne();

        $view['album'] = $album;

        if ($_POST) {
            if ($album) {
                $_POST['name'] = Arr::get($_POST, 'name', '班级相册');
                $album->synchronizeWithArray($_POST);
            } else {
                $album = new Album();
                $album->classroom_id = $this->_id;
                $album->name = Arr::get($_POST, 'name', '班级相册');
                $album->create_at = date('Y-n-d H:i:s');
            }
            $album->save();
            $this->_redirect(URL::site('classroom_admin/album?id=' . $this->_id));
        }

        $all_album = Doctrine_Query::create()
                ->from('Album a')
                ->where('a.classroom_id = ?', $this->_id)
                ->fetchArray();

        $view['all_album'] = $all_album;

        $this->_render('_body', $view);
    }

}