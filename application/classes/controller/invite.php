<?php
/**
  +-----------------------------------------------------------------
 * 名称：邀请控制器
 * 版本：1.0
 * 版权：www.usho.cn
 * 作者：zhaojiangang
 * 最后更新日期：12-10-12 上午10:59
  +-----------------------------------------------------------------
 */
class Controller_Invite extends Layout_Main {

        //浏览邀请
        function action_index() {
                $code = Arr::get($_GET, 'code');
                $view = null;
                if ($code AND is_numeric(base64_decode($code))) {
                        $invite_id = base64_decode($code);
                        $invite = Doctrine_Query::create()
                                ->select('i.*,u.id AS user_id,u.realname AS realname,u.sex AS sex,u.start_year AS start_year,u.speciality AS speciality')
                                ->from('UserInvite i')
                                ->leftJoin('i.User u')
                                ->where('i.id=?', $invite_id)
                                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
                        $view['invite'] = $invite;

                        //暂存邀请信息，全站任意位置操作即为受邀
                        //来加入校友网公共邀请链接(编辑session，后续注册额外加分)
                        if ($invite['type'] == 'regLink' OR $invite['type'] == 'regMail') {
                                $this->_sess->set('reg_invite', $invite['id']);
                        }

                        //来自邮件邀请，更新阅读状态
                        if ($invite['type'] == 'regMail') {
                                $invite = Model_Invite::loadModel($invite_id);
                                $invite->read_date = date('Y-m-d H:i:s');
                                $invite->is_read = TRUE;
                                $invite->save();
                                $this->_sess->set('receiver_email', $invite['receiver_email']);
                                $this->_redirect('/user/register');
                                exit;
                        }
                }
                //无效的邀请码
                else {
                        $view['error'] = '很抱歉，邀请码不正确;请检查链接地址是否完整!';
                }
                $this->_render('_body', $view);
        }

        //test
        function action_saveLog() {
                //保存受邀记录
                $invite_id = $this->_sess->get('reg_invite');
                if ($invite_id) {
                        Model_Invite::saveLog(array(
                            'invite_id' => $invite_id,
                            'receiver_user_id' => 12336,
                        ));
                        //清空邀请session
                        $this->_sess->set('reg_invite', null);
                }
        }

        function action_testt() {

        }

}