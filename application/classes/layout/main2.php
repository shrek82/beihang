<?php
class Layout_Main extends Candy_Controller {

    protected $_acl;
    protected $_role;
    public $template = 'layout/main';
    public $_uid;

    function before() {
        parent::before();

        // acl
        $this->_acl = new Acl();
        foreach ($this->_conf('acl.Roles') as $r) {
            $this->_acl->add_role($r);
        }

        //用户自动登录
        Model_user::userInfo();

        //用户信息
        $this->_uid = $this->_sess->get('id');
        $this->_role = $this->_sess->get('role', '游客');

        //全局模板变量
        View::set_global('_UID', $this->_uid);
        View::set_global('_ROLE', $this->_role);

        //全局模板渲染
        $this->_render('_header_top', null, 'global/header_top');
        $this->_render('_footer_bottom', null, 'global/footer_bottom');
    }

    //操作用户积分(统计或奖励的内容,是否奖励)
    function updatePoint($info = 'all', $rewards = False, $user_id = null) {

    }

    //生成和删除需更新session的用户文件
    function updateSession($uid, $action = null) {

    }

    //发送到新浪微博
    function sinaWeiboUpdate($data) {
        $user_id = $this->_uid;
        $appconfig = Kohana::config('app');
        $text = isset($data['text']) ? $data['text'] : null;
        $pic_url = isset($data['pic_url']) ? $data['pic_url'] : null;

        //帐号绑定信息
        //校友会的
        if (isset($data['aa_id']) AND $data['aa_id'] >= 0) {
            $binding = Model_Binding::getBinding(array(
                        'aa_id' => $data['aa_id'],
                        'service' => 'sina',
                    ));
        }
        //个人的
        else {
            $binding = Model_Binding::getBinding(array(
                        'user_id' => $this->_uid,
                        'service' => 'sina',
                    ));
        }

        if (!$binding) {
            return $back['error'] = '没有绑定帐号';
        }

        //导入新浪微博接口
        Candy::import('sinaWeiboApi');
        $c = new SaeTClientV2($appconfig->sina['WB_AKEY'], $appconfig->sina['WB_SKEY'], $binding['access_token']);

        //内容转换
        $text = $text ? Common_Global::sinatext($text) : null;

        //内容链接
        $text = isset($data['link']) ? $text . ' ' . $data['link'] : $text;

        //发布微博内容
        if ($text AND $pic_url) {
            $ret = $c->upload($text, $pic_url);
        } else {
            $ret = $ret = $c->update($text);
        }

        //返回发送结果
        if (isset($ret['error_code']) && $ret['error_code'] > 0) {
            return $back['error'] = "发送失败，错误：{$ret['error_code']}:{$ret['error']}";
        } else {
            return $ret;
        }
    }

}