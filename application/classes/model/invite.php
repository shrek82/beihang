<?php

/**
  +-----------------------------------------------------------------
 * 名称：邀请模型
 * 版本：1.0
 * 版权：www.usho.cn
 * 作者：zhaojiangang
 * 最后更新日期：12-10-12 上午10:27
  +-----------------------------------------------------------------
 */

class Model_Invite {

        //返回所有邀请方式数组
        //name:邀请方式名称，适用于后台查看
        //function:邀请功能   适用给受邀请做一说明
        public static function getType() {
                return array(
                    'regLink' => '加入校友网公共邀请',
                    'regMail' => '加入校友网邮件邀请',
                );
        }

        //保存邀请记录
        public static function create($data) {
                $invite = new UserInvite();
                $error = null;
                if (!isset($data['type'])) {
                        $error['type'] = '没有推荐种类';
                }
                if (!isset($data['user_id'])) {
                        $error['user_id'] = '用户ID不能为空';
                }
                if (!isset($data['title'])) {
                        $error['title'] = '没有推荐名称';
                }
                foreach ($data AS $key => $value) {
                        $invite[$key] = $value;
                }
                $invite->create_date = isset($data['create_data']) ? $data['create_data'] : date('Y-m-d H:i:s');
                $invite->save();
                if ($invite->id) {
                        return $invite->id;
                } else {
                        return $error;
                }
        }

        //保存受邀记录
        public static function saveLog($data) {
            $base_config=  Kohana::config('config')->base;
            $site_name=$base_config['sitename'];

                if (isset($data['invite_id']) AND isset($data['receiver_user_id'])) {
                        $invite = self::loadModel($data['invite_id']);
                        if ($invite) {

                                //获取邀请人注册信息
                                $receiver_user = Model_User::getModel($data['receiver_user_id'], 'id,realname');

                                //自来加入校友网公共邀请
                                if ($invite['type'] == 'regLink') {
                                        $new_invite_id = self::create(array(
                                                    'user_id' => $invite['user_id'],
                                                    'title' => $invite['title'],
                                                    'type' => $invite['type'],
                                                    'receiver_name' => $receiver_user['realname'],
                                                    'receiver_user_id' => $data['receiver_user_id'],
                                                    'message' => $invite['message'],
                                                    'parent_invite_id' => $data['invite_id'],
                                                    'create_date' => $invite['create_date'],
                                                    'read_date' => date('Y-m-d H:i:s'),
                                                    'accept_date' => date('Y-m-d H:i:s'),
                                                    'is_read' => TRUE,
                                                    'is_accept' => TRUE,
                                                ));

                                        //发送站内信告诉邀请我的人
                                        if ($new_invite_id > 0) {
                                                Model_Msg::create(array(
                                                    'user_id' => $receiver_user['id'],
                                                    'send_to' => $invite['user_id'],
                                                    'content' => '感谢您邀请，我已经加入'.$site_name.'!',
                                                ));
                                        }
                                }

                                //来自要邮件邀请
                                if ($invite['type'] == 'regMail') {
                                        $invite->receiver_name = $receiver_user['realname'];
                                        $invite->receiver_user_id = $data['receiver_user_id'];
                                        $invite->accept_date = date('Y-m-d H:i:s');
                                        $invite->is_accept = TRUE;
                                        $invite->save();

                                        //发送站内信告诉邀请我的人
                                        if ($invite->id > 0) {
                                                Model_Msg::create(array(
                                                    'user_id' => $receiver_user['id'],
                                                    'send_to' => $invite['user_id'],
                                                    'content' => '感谢您邮件邀请，我已经加入'.$site_name.'!',
                                                ));
                                        }
                                }
                                //end type
                        }
                }
        }

        //获取记录
        public static function loadModel($id) {
                return Doctrine_Query::create()
                                ->from('UserInvite')
                                ->where('id = ?', $id)
                                ->fetchOne();
        }

}

?>