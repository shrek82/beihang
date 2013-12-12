<?php

class Controller_Club_Admin extends Layout_ClubAdmin {

    function before() {
        parent::before();
        $actions = array(
            'club_admin/index' => '加入申请',
            'club_admin_member/index' => '正式成员',
        );
        $this->_render('_body_action', compact('actions'), 'club_global/admin_action');
    }

    // 待审核名单 ＋ 其他信息概要？
    function action_index() {
        $apply = Doctrine_Query::create()
                ->from('JoinApply jp')
                ->leftJoin('jp.User')
                ->where('club_id = ?', $this->_id)
                ->orderby('jp.apply_at DESC, jp.is_reject ASC')
                ->fetchArray();

        $view['apply'] = $apply;
        $this->_render('_body', $view);
    }

    # 处理加入申请

    function action_apply($handler) {
        $id = Arr::get($_GET, 'apply_id');
        $club_id = $this->_id;

        $apply = Doctrine_Query::create()
                ->from('JoinApply')
                ->where('club_id = ?', $club_id)
                ->andWhere('id = ?', $id)
                ->fetchOne();

        if (!$apply)
            exit;

        if ($handler == 'accept') {
            $user_id = $apply['user_id'];
            $apply->delete();
            if (!Model_Club::isMember($club_id, $user_id)) {
                $member = new ClubMember();
                $member->user_id = $user_id;
                $member->join_at = date('Y-m-d H:i:s');
                $member->club_id = $club_id;
                $member->save();
            }
        }

        // 拒绝申请处理
        if ($handler == 'reject') {
            $reason = trim(Arr::get($_POST, 'reject_reason'));
            if (!$reason) {
                $view['reason'] = $apply['reject_reason'];
                $view['action'] = URL::site('club_admin/apply/reject') . URL::query();
                echo View::factory('inc/reject_reason', $view);
            } else {
                $apply['reject_reason'] = $reason;
                $apply['is_reject'] = TRUE;
                $apply->save();
            }
        }
    }

}