<?php

// layout:aa

class Layout_Weibo extends Layout_Main {

    public $template = 'layout/weibo';
    public $_aa_id;
    public $_aa;
    public $_member;
    public $_aa_manager;

    function before() {
        parent::before();

        $this->_aa_manager=False;
        $this->_aa_id = Arr::get($_GET, 'id', 0);

        $aa = Doctrine_Query::create()
                        ->select('a.*')
                        ->addSelect('(SELECT COUNT(m.id) FROM AaMember m WHERE a.id = m.aa_id) AS mcount')
                        ->from('Aa a')
                        ->where('a.id = ?', $this->_aa_id)
                        ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

        if (!$aa) {
            $this->request->redirect('main/notFound');
        }
        $this->_aa = $aa;
        View::set_global('_AA', $aa);
        View::set_global('_ID', $this->_aa_id);

        $user_id = $this->_sess->get('id', 0);

        //成员信息
        $member = Doctrine_Query::create()
                        ->from('AaMember')
                        ->where('aa_id = ?', $this->_aa_id)
                        ->andWhere('user_id = ?', $user_id)
                        ->fetchOne();

        if ($member) {
            // 更新最后访问时间
            if (time() - strtotime($member['visit_at']) > 60 * 5) {
                $member->visit_at = date('Y-m-d H:i:s');
                $member->save();
            }

           //校友会管理员
            if($member['manager']==True OR $member['chairman']==True){
                $this->_aa_manager=True;
            }
        }

        $this->_member = $member;
        View::set_global('_MEMBER', $member);

        if (strstr($this->request->controller, 'aa_admin')) {
            $this->_render('_body_top', null, 'inc/aa/admin_bar');
        } else {
            $this->_render('_body_top', compact('aa', 'member'), 'inc/aa/topbar');
        }

        //统计校友会温度
        View::set_global('aa', $this->_aa);
    }

}