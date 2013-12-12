<?php
/**
  +-----------------------------------------------------------------
 * 名称：发送站内信模型
 * 版本：1.0
 * 版权：www.usho.cn
 * 作者：zhaojiangang
 * 最后更新日期：12-10-12 上午10:27
  +-----------------------------------------------------------------
 */
class Model_Msg {
        //发送站内信
        public static function create($post) {
                if (isset($post['user_id']) AND isset($post['send_to']) AND isset($post['content'])) {
                        $post['send_at'] = isset($post['send_at']) ? $post['send_at'] : date('Y-m-d H:i:s');
                        $post['update_at'] = isset($post['update_at']) ? $post['update_at'] : date('Y-m-d H:i:s');
                        $post['sort_in'] = isset($post['sort_in']) ? $post['sort_in'] : 0;
                        $msg = new UserMsg();
                        $msg->fromArray($post);
                        $msg->save();
                        if ($msg->id) {
                                return $msg->id;
                        }
                }
                return false;
        }

}

?>
