<?php

//服务器定时执行的任务
class Controller_Timedtask extends Layout_Main {

    function action_index() {
        $this->auto_render=False;
        //发送激活邮件给那些没有发送过激活的的
        //$this->sendActiveMail();
    }

    //发送未激活的邮箱
    public function sendActiveMail() {
        //今天的
        //->where(DB::expr('TIMESTAMPDIFF(DAY, now(), u.reg_at)'), '=', 0)

        $user = DB::select(DB::expr('u.id,u.realname,u.account'))
                ->from(array('user', 'u'))
                ->where('u.actived', '=', 0)
                ->where('u.is_sended_active', '=',0)
                ->order_by('u.id','DESC')
                ->limit(30)
                ->execute()
                ->as_array();

        $mailer = new Model_Mailer('first');
        if(count($user)>0){
            foreach($user AS $u){
                $mailer->userActive($u['realname'],$u['account']);
                DB::update('user')->set(array('is_sended_active'=>1))->where('id', '=',$u['id']) ->execute();
                echo '已发送&nbsp;'.$u['realname'].'&nbsp;'.$u['account'].'&nbsp;激活邮件<br>';
            }
        }
        else{
            echo '暂时还没有可发送的邮件！';
        }
    }
}
?>