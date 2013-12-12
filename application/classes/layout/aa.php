<?php

/**
  +-----------------------------------------------------------------
 * 名称：地方校友会底层
 * 版本：1.0
 * 版权：www.usho.cn
 * 作者：zhaojiangang
 * 最后更新日期：12-10-12 上午9:17
  +-----------------------------------------------------------------
 */
class Layout_Aa extends Layout_Main {

    public $template = 'layout/homepage';
    public $_id;
    public $_aa;
    public $_member;
    public $_aa_manager;
    public $_theme;

    function before() {
        parent::before();

       $qid=$this->request->param('id');

        $this->_aa_manager = False;

       $this->_id=  is_numeric($qid)?$qid:(int)Arr::get($_GET, 'id', 0);

        $aa = DB_Aa::getInfoById($this->_id);

        if (!$aa OR $this->_id == 0) {
            Request::instance()->redirect('aa');
            exit;
        }

        //主题风格
        $this->_theme = Db_Theme::getTheme(array('aa_id' => $this->_id));

        $this->_aa = $aa;
        $member = null;

        //当前成员信息
        if ($this->_uid) {
            $member = Doctrine_Query::create()
                    ->from('AaMember')
                    ->where('aa_id = ?', $this->_id)
                    ->andWhere('user_id = ?', $this->_uid)
                    ->fetchOne();

            if ($member) {
                // 更新最后访问时间
                if (time() - strtotime($member['visit_at']) > 60 * 5) {
                    $member->visit_at = date('Y-m-d H:i:s');
                    $member->save();
                }

                //校友会管理员
                if ($member['manager'] OR $member['chairman']) {
                    $this->_aa_manager = True;
                }
            }

            if ($this->_role == '管理员') {
                $this->_aa_manager = True;
            }
        }



        $this->_member = $member;
        View::set_global('_MEMBER', $member);
        View::set_global('_THEME', $this->_theme);
        View::set_global('_AA', $aa);
        View::set_global('_ID', $this->_id);

        //导航
        if (strstr($this->request->controller, 'aa_admin')) {
            $this->_render('_body_top', null, 'aa_global/admin_topbar');
        } else {
            $this->_render('_body_top', compact('aa', 'member'), 'aa_global/topbar');
        }
    }

}