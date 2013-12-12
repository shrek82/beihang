<?php

class Controller_Aa_Admin_Member extends Layout_Aa {

    function before() {
        parent::before();

        // 管理组成员
        if(!$this->_aa_manager){
            Request::instance()->redirect('main/deny?reason=' . urlencode('很抱歉，您没有管理权限'));
            exit;
        }

        $actions = array(
            'aa_admin/index' => '成员申请',
            'aa_admin_member/index' => '正式成员',
        );
        $this->_render('_body_action', compact('actions'), 'aa_global/admin_action');
    }

    //成员列表
    function action_index() {
        $q = trim(Arr::get($_POST, 'q'));
        $view['q'] = urldecode($q);

        $member = Doctrine_Query::create()
                ->select('u.realname,m.*,u.sex')
                ->from('AaMember m')
                ->leftJoin('m.User u')
                ->where('m.aa_id = ?', $this->_id);

        if ($q) {
            $member->addWhere('u.realname LIKE ?', '%' . $q . '%');
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
                ->orderBy('m.manager DESC,m.visit_at DESC,m.id DESC')
                ->fetchArray();

        $view['resources'] = Kohana::config('acl.AaResources');

        $this->_title('成员管理');
        $this->_render('_body', $view);
    }

    //下载成员名单
    function action_export() {
        $this->auto_render = FALSE;

        $this->request->headers['Content-Type'] = 'application/ms-excel';

        Candy::import('excelMaker');

        $id = Arr::get($_GET, 'id');

        $members = Doctrine_Query::create()
                ->select('a.*,u.realname AS realname,u.point AS point,u.sex AS sex,u.start_year AS start_year,u.speciality AS speciality,u.account AS email,c.tel AS tel,c.mobile AS mobile,c.address AS address,w.company AS company,w.job AS job')
                ->from('AaMember a')
                ->leftJoin('a.User u')
                ->leftJoin('u.Contact c')
                ->leftJoin('u.Works w')
                ->where('a.aa_id = ?', $id)
                ->fetchArray();

        $xls[1] = array('姓名', '性别', '入学年份', '毕业专业', '电话', '邮箱', '手机', '地址', '单位', '职务', '注册日期', '最后来访日期','积分');

        foreach ($members as $i => $m) {
            $xls[$i + 2][] = $m['realname'];
            $xls[$i + 2][] = $m['sex'];
            $xls[$i + 2][] = $m['start_year'];
            $xls[$i + 2][] = $m['speciality'];
            $xls[$i + 2][] = $m['tel'];
            $xls[$i + 2][] = $m['email'];
            $xls[$i + 2][] = $m['mobile'];
            $xls[$i + 2][] = $m['address'];
            $xls[$i + 2][] = $m['company'];
            $xls[$i + 2][] = $m['job'];
            $xls[$i + 2][] = $m['join_at'];
            $xls[$i + 2][] = $m['visit_at'];
            $xls[$i + 2][] = $m['point'];
        }

        $excel = new Excel_Xml('UTF-8');
        $excel->addArray($xls);
        $excel->generateXML('Alumni-member-' . $id);
    }

    //设置管理员
    function action_setManager() {
        $this->auto_render = FALSE;
        $cid = Arr::get($_POST, 'cid');
        $member = Doctrine_Query::create()
                ->from('AaMember')
                ->where('aa_id = ?', $this->_id)
                ->addWhere('user_id = ?', $cid)
                ->fetchOne();

        if ($member) {
            if ($member['manager']) {
                $member->manager = FALSE;
                $member->permissions = FALSE;
                $member->title = FALSE;
            } else {
                $member->manager = TRUE;
                $member->permissions = implode(' ', Kohana::config('acl.AaResources'));
            }
            $member->save();
        }
    }

    //修改成员某项信息
    function action_set() {
        $cid = Arr::get($_GET, 'cid');

        $member = Doctrine_Query::create()
                ->from('AaMember m')
                ->where('aa_id = ?', $this->_id)
                ->addWhere('m.user_id = ?', $cid)
                ->fetchOne();

        if ($_POST) {
            $member->synchronizeWithArray($_POST);
            $member->save();
            exit;
        }
    }

    //移除校友会
    function action_remove(){
        $this->auto_render=false;
        $cid= Arr::get($_GET,'cid');
        Doctrine_Query::create()
                ->delete('AaMember')
                ->where('user_id = ?',$cid)
                ->addWhere('aa_id = ?',$this->_id)
                ->execute();
    }

}