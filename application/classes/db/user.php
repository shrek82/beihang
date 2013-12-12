<?php
/**
  +-----------------------------------------------------------------
 * 名称：用户模型
 * 版本：1.0
 * 版权：www.usho.cn
 * 作者：zhaojiangang
 * 最后更新日期：12-10-12 上午9:46
  +-----------------------------------------------------------------
 */
class Db_User {

    //根据id获取某一校友信息
    public static function getInfoById($uid = 0) {
        $user = DB::select(DB::expr('u.*'))
                ->from(array('user', 'u'))
                ->where('u.id', '=', $uid)
                ->execute()
                ->as_array();
        $user = $user ? $user[0] : false;
        return $user;
    }

    //获取用户积分
    public static function getPoint($uid) {
        $data = DB::select("*")
                ->from('user_point')
                ->where('user_id', '=', $uid)
                ->execute()
                ->as_array();
        $data = $data ? $data[0] : false;
        return $data;
    }

    //论坛话题总数
    public static function getBbsUnitCount($uid) {
        $data = DB::select('id')
                ->from('bbs_unit')
                ->where('user_id', '=', $uid)
                ->where('is_closed', '=', 0)
                ->execute()
                ->count();
        return $data;
    }

    //班级话题总数
    public static function getClassBbsUnitCount($uid) {
        $data = DB::select('id')
                ->from('class_bbs_unit')
                ->where('user_id', '=', $uid)
                ->where('is_closed', '=', 0)
                ->execute()
                ->count();
        return $data;
    }

    //评论总数
    public static function getCommentCount($uid) {
        $data = DB::select('id')
                ->from('comment')
                ->where('user_id', '=', $uid)
                ->where('is_closed', '=', 0)
                ->execute()
                ->count();
        return $data;
    }

    //照片总数
    public static function getPicsCount($uid) {
        $data = DB::select('id')
                ->from('pic')
                ->where('user_id', '=', $uid)
                ->execute()
                ->count();
        return $data;
    }

    //发起的活动总数
    public static function getEventCount($uid) {
        $data = DB::select('id')
                ->from('event')
                ->where('user_id', '=', $uid)
                ->execute()
                ->count();
        return $data;
    }

    //参加的的活动总数
    public static function getSignEventCount($uid) {
        $data = DB::select('id')
                ->from('event_sign')
                ->where('user_id', '=', $uid)
                ->where('is_present', '=', '是')
                ->execute()
                ->count();
        return $data;
    }

    //邀请他人注册
    public static function getRegInvitesCount($uid) {
        $data = Doctrine_Query::create()
                ->select('i.id,u.id AS uid')
                ->from('UserInvite i')
                ->leftJoin('i.RUser u')
                ->where('i.user_id = ?', $uid)
                ->addWhere('i.receiver_user_id is not null')
                ->addWhere('(i.type="regLink" OR type="regMail")')
                ->addWhere('i.is_accept=?', TRUE)
                ->addWhere('u.role=?', '校友(已认证)')
                ->count();
        return $data;
    }

    //邀请他人参加活动
    public static function getEventsignInvitesCount($uid) {
        $data = DB::select('id')
                ->from('user_invite')
                ->where('user_id', '=', $uid)
                ->where('receiver_user_id', 'IS NOT', NULL)
                ->where('type', '=', 'inviteSignEvent')
                ->where('is_accept', '=', 1)
                ->execute()
                ->count();
        return $data;
    }

    //原创的新鲜事
    public static function getWeiboCount($uid) {
        $data = DB::select('id')
                ->from('weibo_content')
                ->where('user_id', '=', $uid)
                ->where('is_original', '=', 1)
                ->execute()
                ->count();
        return $data;
    }

    //绑定的微博
    public static function getWeiboBinding($uid) {
        $data = DB::select('id')
                ->from('weibo_binding')
                ->where('user_id', '=', $uid)
                ->execute()
                ->count();
        return $data;
    }

    //创建用户积分
    public static function createPoint($array = array()) {
        if (isset($array['user_id'])) {
            $fields = array();
            $values = array();
            foreach ($array AS $key => $value) {
                $fields[] = $key;
                $values[] = $value;
            }
        }

        $point = DB::insert('user_point', $fields)
                ->values($values)
                ->execute();
        return $point;
    }

    //更新用户某一字段
    public static function updateField($uid, $array) {

        if (count($array) > 0 AND $uid) {
            $update = DB::update('user')
                    ->set($array)
                    ->where('id', '=', $uid)
                    ->execute();
            return $update;
        }
        return FALSE;
    }

    //更新用户积分
    public static function updatePoint($info = 'all', $rewards = False, $user_id = null) {

        $_sess = Session::instance();

        //point总积分
        //count_point 发布的内容统计积分

        $user_id = empty($user_id) ? $_sess->get('id') : $user_id;

        if ((!$user_id) OR ($rewards AND !$user_id)) {
            return false;
        }

        //积分值
        $point = Kohana::config('point')->add;
        $msg = Kohana::config('point')->msg;

        //查找当前用户积分
        $user_point = self::getPoint($user_id);

        //如果是上次统计超过3天,重新统计一次积分(可能管理员从后台删除帖子等)
        if ($user_point) {
            if (strtotime('now') - strtotime($user_point['update_at']) > 3600 * 24 * 3) {
                $info = 'all';
            }
        }

        //所有内容积分统计
        $count_point = 0;

        //不存在用户统计结果或重新统计全部
        if (!$user_point OR $info == 'all') {

            //公共话题
            $bbs_unit = self::getBbsUnitCount($user_id);
            $count_point+=$bbs_unit * $point['bbsunit'];

            //班级话题
            $class_unit = self::getClassBbsUnitCount($user_id);
            $count_point+=$class_unit * $point['classunit'];

            //评论总数
            $comment = self::getCommentCount($user_id);
            $count_point+=$comment * $point['comment'];

            //照片总数
            $photo = self::getPicsCount($user_id);
            $count_point+=$photo * $point['photo'];

            //发起的活动
            $event = self::getEventCount($user_id);
            $count_point+=$event * $point['event'];

            //参加的活动
            $eventsign = self::getSignEventCount($user_id);
            $count_point+=$eventsign * $point['eventsign'];

            //邀请他人注册
            $reg_invites = self::getRegInvitesCount($user_id);
            $count_point+=$reg_invites * $point['invite_register'];

            //邀请他人参加活动
            $eventsign_invites = self::getEventsignInvitesCount($user_id);
            $count_point+=$eventsign_invites * $point['inviteSignEvent'];

            //原创新鲜事
            $original_weibos = self::getWeiboCount($user_id);
            $count_point+=$original_weibos * $point['weibo'];

            //绑定微博
            $bindings = self::getWeiboBinding($user_id);
            $count_point+=$bindings * $point['weibo_binding'];
        }

        $post = array();

        //不是新注册但之前没有统计过的，首次统计给予10点积分
        if (!$user_point) {
            $post['count_point'] = $count_point;
            $post['rewards_point'] = $rewards ? $rewards : 10;
            $post['point'] = $rewards ? $rewards + $count_point : $count_point + 10;
        }
        //存在用户，增加登录奖励
        elseif ($info == 'login') {
            $post['rewards_point'] = $user_point['rewards_point'] + $point['login'];
            $post['point'] = $user_point['count_point'] + $post['rewards_point'];
        }
        //存在用户，重新统计所有
        elseif ($info == 'all') {
            $post['count_point'] = $count_point;
            $post['point'] = $user_point['rewards_point'] + $count_point;
        }
        //上传头像，给予固定奖励
        elseif ($info == 'upload_profile') {
            $post['rewards_point'] = $user_point['rewards_point'] + $point[$info];
            $post['point'] = $user_point['point'] + $point[$info];
        }

        //存在用户，额外给予积分奖励
        elseif ($rewards AND isset($point[$info])) {
            $post['rewards_point'] = $user_point['rewards_point'] + $point[$info];
            $post['point'] = $user_point['point'] + $point[$info];
        }
        //存在用户，只统计某一项
        elseif (isset($point[$info])) {
            $post['count_point'] = $user_point['count_point'] + $point[$info];
            $post['point'] = $user_point['point'] + $point[$info];
        } else {
            return false;
        }

        $post['update_at'] = date('Y-m-d H:i:s');

        //保存入库
        if ($user_point) {
            self::updatePointTable($user_id, $post);
        } else {
            $post['user_id'] = $user_id;
            self::createPoint($post);
        }

        //同时更新主表积分值
        self::updateField($user_id, array('point' => $post['point']));

        //仅对自己的积分操作做前台提示
        if ($user_id == $_sess->get('id') AND isset($msg[$info]) AND isset($point[$info])) {
            //这些内容在跳转后才显示出来，所以需要flash,其他在js中处理
            if ($info == 'login' OR $info == 'bbsunit' OR $info == 'event' OR $info == 'weibo_binding' OR $info == 'upload_profile' OR $info == 'add_works') {
                if ($point[$info] > 0) {
                    //弹出积分flash
                    $_sess->set('prompt',$msg[$info] . ' 积分+' . $point[$info]);
                }
            }
        }
    }

    //更新用户积分表
    public static function updatePointTable($uid, $array = array()) {
        if (count($array) > 0 AND $uid) {
            $update = DB::update('user_point')
                    ->set($array)
                    ->where('user_id', '=', $uid)
                    ->execute();
            return $update;
        }
        return FALSE;
    }

}

?>
