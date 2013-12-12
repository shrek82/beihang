<?php
/**
  +-----------------------------------------------------------------
 * 名称：俱乐部底层
 * 版本：1.0
 * 版权：www.usho.cn
 * 作者：zhaojiangang
 * 最后更新日期：12-10-12 上午9:43
  +-----------------------------------------------------------------
 */

class Layout_Club extends Layout_Main {

    public $template = 'layout/homepage';
    public $_id;
    public $_club;
    public $_member;
    public $_is_manager = False;
    public $_theme;

    function before() {
        parent::before();

        //兼容之前俱乐部URL参数
        $this->_id = (int)Arr::get($_GET, 'club_id', 0);
        if (!$this->_id) {
            $this->_id = (int)Arr::get($_GET, 'id', 0);
        }
        //俱乐部信息
        $this->_club = Db_Club::getInfoById($this->_id);

        if (!$this->_club) {
            $this->request->redirect('main/notFound');
        }

        //页面配色
        $this->_theme = Db_Theme::getTheme(array('club_id' => $this->_id));

        //当前用户
        $member = null;
        if ($this->_uid) {

            $aa_member = Db_Aa::getMemberInfo($this->_club['aa_id'], $this->_uid);

            $member = Doctrine_Query::create()
                    ->from('ClubMember')
                    ->where('club_id = ?', $this->_id)
                    ->andWhere('user_id = ?', $this->_uid)
                    ->fetchOne();

            if ($member) {
                // 更新最后访问时间
                if (time() - strtotime($member['visit_at']) > 300) {
                    $member->visit_at = date('Y-m-d H:i:s');
                    $member->save();
                }
            }

            //校友会管理员
            if ($aa_member AND $aa_member['manager'] == True) {
                $this->_is_manager = True;
            }

            //俱乐部管理员
            if (($member['manager'] == True OR $member['chairman'] == True) OR $this->_role == '管理员') {
                $this->_is_manager = True;
            }
        }

        $this->_member = $member;

        //全局变量
        View::set_global('_ID', $this->_club['id']);
        View::set_global('_CLUB', $this->_club);
        View::set_global('_MEMBER', $member);
        View::set_global('_IS_MANAGER', $this->_is_manager);
        View::set_global('_THEME', $this->_theme);

        //默认导航菜单
        $this->_render('_body_top', compact('club', 'member'), 'club_global/topbar');
    }

}