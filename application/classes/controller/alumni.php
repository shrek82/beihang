<?php
/**
  +-----------------------------------------------------------------
 * 名称：档案管理器
 * 版本：1.0
 * 版权：www.usho.cn
 * 作者：zhaojiangang
 * 最后更新日期：12-10-12 上午10:53
 * 相同指数:100%
  +-----------------------------------------------------------------
 */
class Controller_Alumni extends Layout_Main
{

    function action_index()
    {
        $name = Arr::get($_GET, 'name');
        $view['name'] = $name;
        $city = urldecode(Arr::get($_GET, 'city'));
        $view['city'] = $city;

        if($name || $city){
            $view['query'] = '找到以下姓名相符的校友：';
        }

        // 目前在线校友（文字链接）
        $online_ids = array_keys(Model_User::online());
        $user_on = Doctrine_Query::create()
                    ->select('u.realname')
                    ->from('User u')
                    ->whereIn('u.id', $online_ids)
                    ->orderBy('u.login_time DESC')
                    ->useResultCache(true, 300, 'user_online')
                    ->fetchArray();
        $view['online'] = $user_on;
        $view['pager'] ='';
        $view['users'] = array();

        if($name){

        // 校友列表
        $user = Doctrine_Query::create()
                    ->select('u.realname, u.sex,u.city')
                    ->from('User u');

        if($name){
            $user->andWhere('u.realname=?',$name);
        }

        $pager = Pagination::factory(array(
            'total_items' => $user->count(),
            'items_per_page' => 30,
            'view' => 'pager/common',
        ));

        $view['pager'] = $pager;
        $view['users'] = $user->offset($pager->offset)
                              ->limit($pager->items_per_page)
                              ->fetchArray();

        }

        // 最近注册的校友
        $user_reg = Doctrine_Query::create()
                    ->select('u.realname')
                    ->from('User u')
                    ->orderBy('u.reg_at DESC')
                    ->limit(6)
                    ->useResultCache(true, 300, 'user_register')
                    ->fetchArray();
        $view['regs'] = $user_reg;

        // 推荐同城校友
        if($this->_sess->get('city')){
            $scity = Doctrine_Query::create()
                        ->select('u.realname, u.id, RANDOM() AS rand')
                        ->from('User u')
                        ->where('u.city = ?', $this->_sess->get('city'))
                        ->andWhere('u.id != ?', $this->_sess->get('id'))
                        ->orderBy('rand DESC')
                        ->limit(6)
                        ->useResultCache(true, 300, 'user_one_city')
                        ->fetchArray();
            $view['scity'] = $scity;
        }

        // 推荐同入年入学???认识的(使用接口？)
        if($this->_sess->get('begin_date')){

        }

        $this->_title($this->_config->base['sitename']);
        $this->_render('_body', $view);
    }

    // 更新档案数据
    function action_update(){
        header("Content-Type:text/html; charset=utf-8");
        $this->auto_render=False;
        if($this->_sess->get('role') == '管理员'){
            echo '您没有权限!';
            exit;
        }

    }
}