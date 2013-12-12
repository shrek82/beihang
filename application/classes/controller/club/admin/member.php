<?php

class Controller_Club_Admin_Member extends Layout_Clubadmin {

    function before() {
        parent::before();
        $actions = array(
            'club_admin/index' => '加入申请',
            'club_admin_member/index' => '正式成员',
        );
        $this->_render('_body_action', compact('actions'), 'club_global/admin_action');
    }

    //成员列表
    function action_index() {

        $q = trim(Arr::get($_POST, 'q'));
        $view['q'] = urldecode($q);

        $member = Doctrine_Query::create()
                ->select('u.realname,m.*,u.sex')
                ->from('ClubMember m')
                ->leftJoin('m.User u')
                ->where('m.club_id = ?', $this->_id);

        if ($q) {
            $member->addWhere('u.realname LIKE ?', '%' . trim($q) . '%');
        }

        $total_members = $member->count();

        $pager = Pagination::factory(array(
                    'total_items' => $total_members,
                    'items_per_page' => 16,
                    'view' => 'pager/common'
                ));

        $view['pager'] = $pager;
        $view['members'] = $member->offset($pager->offset)
                ->limit($pager->items_per_page)
                ->fetchArray();

        $this->_title('成员管理');
        $this->_render('_body', $view);
    }

    //修改成员某项信息
    function action_set() {
        $cid = Arr::get($_GET, 'cid');

        $member = Doctrine_Query::create()
                ->from('ClubMember m')
                ->where('club_id = ?', $this->_id)
                ->addWhere('m.user_id = ?', $cid)
                ->fetchOne();

        if ($_POST) {
            $member->synchronizeWithArray($_POST);
            $member->save();
            exit;
        }
    }

    //设置为管理员
    function action_setManager() {
	$this->auto_render = FALSE;
	$cid = Arr::get($_GET, 'cid');
	$member = Doctrine_Query::create()
			->from('ClubMember')
			->where('club_id = ?', $this->_id)
			->addWhere('user_id = ?', $cid)
			->fetchOne();

	if ($member) {
	    if ($member['manager']) {
		$member->manager = FALSE;
		$member->title = FALSE;
	    } else {
                $member->title = empty($member['title'])?'管理员':$member['title'];
		$member->manager = TRUE;
	    }
	    $member->save();
	}
    }

    //请出俱乐部
    function action_remove(){
        $this->auto_render=false;
        $cid= Arr::get($_GET,'cid');
        Doctrine_Query::create()
                ->delete('ClubMember')
                ->where('user_id = ?',$cid)
                ->addWhere('club_id = ?',$this->_id)
                ->execute();
    }

}