<?php

/**
  +-----------------------------------------------------------------
 * 名称：班级主页控制器
 * 版本：1.0
 * 版权：www.usho.cn
 * 作者：zhaojiangang
 * 最后更新日期：12-10-15 下午1:55
 * 相同指数:100%
  +-----------------------------------------------------------------
 */
class Controller_Classroom_Home extends Layout_Class {
    # 首页

    function action_index() {

        // 图片链接展示
        $view['banner'] = DB::select()
                ->from(array('banner', 'b'))
                ->where('b.classroom_id', '=', $this->_id)
                ->where('b.format', '=', 'banner')
                ->where('b.is_display', '=', 1)
                ->order_by('b.order_num', 'ASC')
                ->limit($this->_theme['banner_limit'])
                ->execute()
                ->as_array();

        // 班级所有成员的user_id
        $member_ids = Doctrine_Query::create()
                ->select('user_id')
                ->from('ClassMember cm')
                ->where('cm.class_room_id = ?', $this->_id)
                ->execute(array(), 6);

        $units = Doctrine_Query::create()
                ->select('bu.id,bu.title,bu.user_id,bu.update_at,bu.hit,bu.create_at,bu.is_fixed,bu.is_good,bu.comment_at,bu.reply_num,u.realname AS realname')
                ->from('ClassBbsUnit bu')
                ->leftJoin('bu.ClassBbs b')
                ->leftJoin('bu.User u')
                ->where('b.classroom_id=?', $this->_id)
                ->orderBy('bu.is_fixed DESC,
            case when bu.comment_at IS NOT NULL then comment_at
                 when comment_at IS NULL then bu.update_at
            end DESC')
                ->limit(10)
                ->fetchArray();
        $view['units'] = $units;

        // 最近更新的相册
        if (count($member_ids) > 0) {
            $photos = Doctrine_Query::create()
                    ->select('p.upload_at, p.name,p.album_id,p.img_path, ab.*, p.user_id')
                    ->addSelect('(SELECT u.realname FROM User u WHERE u.id = p.user_id) AS realname')
                    ->from('Pic p')
                    ->leftJoin('p.Album ab')
                    ->whereIn('p.user_id', $member_ids)
                    ->orderBy('p.upload_at DESC')
                    ->limit(8)
                    ->fetchArray();
        } else {
            $photos = array();
        }

        $view['photos'] = $photos;

        //最近访问
        $visit_members = Doctrine_Query::create()
                ->select('cm.user_id,cm.visit_at,u.realname AS realname,u.sex AS sex')
                ->from('ClassMember cm')
                ->leftJoin('cm.User u')
                ->where('cm.class_room_id = ?', $this->_id)
                ->orderBy('cm.visit_at DESC')
                ->limit(9)
                ->fetchArray();
        $view['visit_members'] = $visit_members;

        //管理员
        $managers = Doctrine_Query::create()
                ->select('cm.user_id,cm.visit_at,u.realname AS realname,u.sex AS sex')
                ->from('ClassMember cm')
                ->leftJoin('cm.User u')
                ->where('cm.class_room_id = ?', $this->_id)
                ->addWhere('cm.is_manager = ?', True)
                ->orderBy('cm.visit_at DESC')
                ->limit(9)
                ->fetchArray();
        $view['managers'] = $managers;

        $this->_title($this->_classroom['name']);
        $this->_render('_body', $view);
    }

    #通信录填写页面

    function action_setAbook() {
        $setAbook = False;

        $abook = Doctrine_Query::create()
                ->from('ClassAbook ca')
                ->where('ca.user_id = ?', $this->_uid)
                ->fetchOne();

        if ($_POST) {
            $v = Validate::setRules($_POST, 'abook');
            $post = $v->getData();
            if (!$v->check()) {
                echo Candy::MARK_ERR . $v->outputMsg($v->errors('validate'));
            } else {
                if (!$abook) {
                    $abook = new ClassAbook();
                    $post['user_id'] = $this->_uid;
                    $abook->fromArray($post);
                } else {
                    $abook->synchronizeWithArray($post);
                }
                $abook->save();
            }
            exit;
        } else {
            //自动获取注册联系信息
            if (!$abook) {
                $abook = Doctrine_Query::create()
                        ->from('UserContact')
                        ->where('user_id=?', $this->_uid)
                        ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
                $abook['msn'] = null;
                $setAbook = True;
            }
        }

        $view = compact('abook', 'setAbook');
        $this->_title('设置个人班级通讯录信息');
        $this->_render('_body', $view);
    }

    # 通讯录列表

    function action_addressbook() {

        // 全部成员id
        $member = Doctrine_Query::create()
                ->select('cm.user_id')
                ->from('ClassMember cm')
                ->where('cm.class_room_id = ?', $this->_id)
                ->execute(array(), 6);

        $member = count($member) > 0 ? $member : array(0);

        $pager = Pagination::factory(array(
                    'total_items' => count($member),
                    'items_per_page' => 12,
                    'view' => 'pager/common'
                ));

        $addressbook = Doctrine_Query::create()
                ->select('u.id,u.account,u.realname,uc.*,u.sex')
                ->from('User u')
                ->leftJoin('u.Contact uc')
                ->whereIn('u.id', $member)
                ->offset($pager->offset)
                ->limit($pager->items_per_page)
                ->fetchArray();

        $view = compact('addressbook', 'pager');
        $this->_title('班级同学录');
        $this->_render('_body', $view);
    }

    # 提交留言本处理

    function action_guestbook() {
        $this->_title('班级留言板');
        $this->_render('_body');
    }

    # 成员列表

    function action_members() {
        $member = Doctrine_Query::create()
                ->from('ClassMember cm')
                ->leftJoin('cm.User u')
                ->where('class_room_id = ?', $this->_id);

        $pager = Pagination::factory(array(
                    'total_items' => $member->count(),
                    'items_per_page' => 36,
                    'view' => 'pager/common'
                ));
        $view['pager'] = $pager;

        $members = $member->offset($pager->offset)
                ->orderBy('cm.visit_at DESC')
                ->limit($pager->items_per_page)
                ->fetchArray();

        $view['members'] = $members;

        //管理员
        $managers = Doctrine_Query::create()
                ->select('cm.user_id,cm.visit_at,u.realname AS realname,u.sex AS sex')
                ->from('ClassMember cm')
                ->leftJoin('cm.User u')
                ->where('cm.class_room_id = ?', $this->_id)
                ->addWhere('cm.is_manager = ?', True)
                ->orderBy('cm.visit_at DESC')
                ->limit(9)
                ->fetchArray();
        $view['managers'] = $managers;

        $this->_title('班级成员');
        $this->_render('_body', $view);
    }

    //申请为管理员
    function action_applyManager() {

        if (!$this->_uid) {
            echo '您还没有登录，请先登录网站后再进行申请操作。';
        } else {
            if (!$this->_classroom['is_member']) {
                echo '您还不是本班级成员，请先加入。';
            } else {

                // 已经为班级管理员
                $apply = Doctrine_Query::create()
                        ->from('JoinApply')
                        ->where('class_room_id = ?', $this->_id)
                        ->andWhere('user_id = ?', $this->_uid)
                        ->fetchOne();

                echo View::factory('classroom_home/applyManager', compact('apply', 'class_room_id'));
            }
        }
    }

    //直接加入班级，班级没有管理员自动成为管理员
    function action_join() {

        $this->auto_render = False;

        //管理员总数
        $manager_count = Doctrine_Query::create()
                ->from('ClassMember')
                ->where('class_room_id = ?', $this->_id)
                ->addWhere('is_manager = ?', True)
                ->count();

        if (!Model_Classroom::isMember($this->_id, $this->_uid)) {
            Model_Classroom::join($this->_id, $this->_uid);
            if ($manager_count < 1) {
                Model_Classroom::setManager($this->_id, $this->_uid);
            }
        }
    }

    //申请加入班级
    function action_joinApply() {

        if ($this->_classroom['is_member']) {
            echo '您已经是班级成员，不需要再申请。';
        } else {

            //查找班级是否有成员
            $total_member = Doctrine_Query::create()
                    ->from('ClassMember')
                    ->where('class_room_id = ?', $this->_id)
                    ->count();

            // 已经申请了加入
            $apply = Doctrine_Query::create()
                    ->from('JoinApply')
                    ->where('class_room_id = ?', $this->_id)
                    ->andWhere('user_id = ?', $this->_uid)
                    ->fetchOne();

            //已经有成员则直接加入
            echo View::factory('inc/join_apply', compact('apply', 'class_room_id'));
        }
    }

    //退出班级
    function action_exitclass() {
        $this->auto_render = FALSE;
        $del = Doctrine_Query::create()
                ->delete('ClassMember')
                ->where('class_room_id =?', $this->_id)
                ->addWhere('user_id=?', $this->_uid)
                ->execute();

        Model_Classroom::updateMemberNum($this->_id);
    }

    //班级相册
    function action_album() {

        //创建一个新相册
        if (isset($_POST['new_album'])) {
            $this->auto_render = false;
            $album = new Album();
            $album->classroom_id = $this->_id;
            $album->name = Arr::get($_POST, 'name', '班级相册');
            $album->create_at = date('Y-n-d H:i:s');
            $album->save();
            echo $album->id;
            exit;
        }

        $condition = array('classroom_id' => $this->_id, 'page_size' => 16, 'empty_albums' => true);
        $album_data = Db_Album::getAlbums($condition);
        $view['albums'] = $album_data['albums'];
        $view['pager'] = $album_data['pager'];

        $this->_title($this->_classroom['name'] . '班级相册');
        $this->_render('_body', $view);
    }

    //成员相册
    function action_photos() {
        // 班级所有成员的user_id
        $member_ids = Doctrine_Query::create()
                ->select('user_id')
                ->from('ClassMember cm')
                ->where('cm.class_room_id = ?', $this->_id)
                ->execute(array(), 6);

        $member_ids = count($member_ids) > 0 ? $member_ids : array(0);

        // 最近更新的相册
        $photos = Doctrine_Query::create()
                ->select('p.upload_at, p.name,p.img_path, ab.*, p.user_id')
                ->addSelect('(SELECT u.realname FROM User u WHERE u.id = p.user_id) AS realname')
                ->from('Pic p')
                ->leftJoin('p.Album ab')
                ->whereIn('p.user_id', $member_ids)
                ->andWhere('p.upload_at > ?', date('Y-m-d', time() - Date::DAY * 10))
                ->orderBy('p.upload_at DESC');

        $pager = Pagination::factory(array(
                    'total_items' => $photos->count(),
                    'items_per_page' => 12,
                    'view' => 'pager/common'
                ));

        $photos = $photos->offset($pager->offset)
                ->limit($pager->items_per_page)
                ->fetchArray();

        $view = compact('photos', 'pager');
        $this->_title('班级相册');
        $this->_render('_body', $view);
    }

    //班级交流区
    function action_bbs() {
        $bbs_id = Arr::get($_GET, 'bbs_id', 0);

        $unit = Doctrine_Query::create()
                ->select('bu.id,bu.title,bu.user_id,bu.update_at,bu.hit,bu.create_at,bu.is_fixed,bu.is_good,bu.comment_at,bu.reply_num,u.realname')
                ->from('ClassBbsUnit bu')
                ->leftJoin('bu.ClassBbs b')
                ->leftJoin('bu.User u')
                ->where('b.classroom_id=?', $this->_id)
                ->orderBy('bu.is_fixed DESC,
            case when bu.comment_at IS NOT NULL then comment_at
                 when comment_at IS NULL then bu.update_at
            end DESC');

        if ($bbs_id > 0) {
            $unit->andWhere('u.bbs_id = ?', $bbs_id);
        }

        $total_unit = $unit->count();

        $pager = Pagination::factory(array(
                    'total_items' => $total_unit,
                    'items_per_page' => 15,
                    'view' => 'pager/common',
                ));

        $unit = $unit->offset($pager->offset)
                ->limit($pager->items_per_page)
                ->fetchArray();

        //echo Kohana::debug($unit);
        $this->_title('班级论坛');
        $this->_render('_body', compact('unit', 'pager', 'bbs_id'));
    }

    //查看话题
    function action_bbsUnit() {
        $unit_id = Arr::get($_GET, 'unit_id');
        $unit = Doctrine_Query::create()
                ->select('u.*,m.realname AS realname,m.sex AS sex')
                ->from('ClassBbsUnit u')
                ->leftJoin('u.User m')
                ->where('u.id=?', $unit_id)
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);


        $update_hits = Doctrine_Query::create()
                ->update('ClassBbsUnit')
                ->set('hit', 'hit +1')
                ->where('id = ?', $unit_id)
                ->execute();

        $view['unit'] = $unit;
        $this->_title($unit['title']);
        $this->_render('_body', $view);
    }

    function action_bbsPost() {
        $unit_id = Arr::get($_GET, 'unit_id');
        $bbs_id = Arr::get($_GET, 'bbs_id');

        //没有版块自动创建
        $class_bbs_count = Doctrine_Query::create()
                ->from('ClassBbs b')
                ->where('b.classroom_id=?', $this->_id)
                ->count();

        if (!$class_bbs_count) {
            $bbs = new ClassBbs();
            $class_bbs['name'] = '默认版块';
            $class_bbs['classroom_id'] = $this->_id;
            $class_bbs['order_num'] = 1;
            $class_bbs['intro'] = '系统自动创建的版块';
            $bbs->fromArray($class_bbs);
            $bbs->save();
        }

        $class_bbs = Doctrine_Query::create()
                ->select('b.id,b.name')
                ->from('ClassBbs b')
                ->where('b.classroom_id=?', $this->_id)
                ->orderBy('order_num ASC')
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

        //提交信息
        if ($_POST) {

            $valid = Validate::setRules($_POST, 'bbs_post');
            $post = Validate::getData();

            if (!$valid->check()) {
                echo Candy::MARK_ERR .
                $valid->outputMsg($valid->errors('validate'));
            } else {
                $content = Arr::get($_POST, 'content');
                preg_match_all("'src[\s\r\n]?=[\s\r\n]?[\\\]?[\'|\"]?(.*?\.(jpg|gif|png))[\\\]?[\'\"]?'si", $content, $imgArray);
                if ($imgArray[1]) {
                    $post['is_pic'] = 1;
                }

                //修改话题
                if ((int) $post['unit_id'] > 0) {

                    $unit = Doctrine_Query::create()
                            ->from('ClassBbsUnit')
                            ->where('id = ?', $post['unit_id'])
                            ->addWhere('user_id=?', $this->_uid)
                            ->fetchOne();

                    if ($unit) {
                        $post['update_at'] = date('Y-m-d H:i:s');
                        $unit->synchronizeWithArray($post);
                        $unit->save();
                    }
                    echo $post['unit_id'];
                } else {
                    unset($post['id']);
                    $post['user_id'] = $this->_uid;
                    $post['create_at'] = date('Y-m-d H:i:s');
                    $post['update_at'] = date('Y-m-d H:i:s');
                    $unit = new ClassBbsUnit();
                    $unit->fromArray($post);
                    $unit->save();
                    echo $unit->id;
                }
            }
        }
        $unit = Doctrine_Query::create()
                ->select('u.*')
                ->from('ClassBbsUnit u')
                ->where('u.id=?', $unit_id)
                ->fetchOne();

        $this->_title('发表话题');
        $this->_render('_body', compact('unit', 'class_bbs', 'bbs_id'));
    }

    //下载成员通讯录
    function action_export() {
        $this->auto_render = FALSE;

        if (!$this->_classroom['is_member']) {
            echo '很抱歉，您还不是班级成员！请先申请加入到班级，谢谢!';
            eixt;
        }

        $this->request->headers['Content-Type'] = 'application/ms-excel';

        Candy::import('excelMaker');

        $id = Arr::get($_GET, 'id');

        // 全部成员id
        $member = Doctrine_Query::create()
                ->select('cm.user_id')
                ->from('ClassMember cm')
                ->where('cm.class_room_id = ?', $this->_id)
                ->execute(array(), 6);

        $addressbook = Doctrine_Query::create()
                ->select('u.id,u.account,u.realname,uc.*')
                ->from('User u')
                ->leftJoin('u.Contact uc')
                ->whereIn('u.id', $member)
                ->fetchArray();

        $xls[1] = array('姓名', '手机', 'QQ', '邮箱', '详细地址');

        foreach ($addressbook as $i => $a) {
            $xls[$i + 2][] = $a['realname'];
            $xls[$i + 2][] = $a['Contact']['mobile'];
            $xls[$i + 2][] = $a['Contact']['qq'];
            $xls[$i + 2][] = $a['account'];
            $xls[$i + 2][] = $a['Contact']['address'];
        }

        $excel = new Excel_Xml('UTF-8');
        $excel->addArray($xls);
        $excel->generateXML('ClassAddressbook');
    }

    //成员最新动态
    function action_update() {

    }

}