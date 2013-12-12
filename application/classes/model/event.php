<?php
/**
  +-----------------------------------------------------------------
 * 名称：活动模型
 * 版本：1.0
 * 版权：www.usho.cn
 * 作者：zhaojiangang
 * 最后更新日期：12-10-11 下午3:39
  +-----------------------------------------------------------------
 */
class Model_Event {
    const BANNER_DIR = 'static/upload/event_static/';

    //活动标签
    public static function tagLinks($tag,$url=null){
return $tag;
    }

    public static function picSet($eids, $limit=4) {
        if (is_string($eids)) {
            $eids = array($eids);
        }

        if ($eids) {
            return Doctrine_Query::create()
                            ->select('ab.*, (SELECT p.img_path FROM Pic p
                        WHERE p.album_id = ab.id ORDER BY p.id DESC LIMIT 1) AS img_path')
                            ->from('Album ab')
                            ->whereIn('ab.event_id', $eids)
                            //->groupBy('pid')
                            //->having('pid > ?', 0)
                            ->limit($limit)
                            ->fetchArray();
        }

        return false;
    }

    public static function get($state='start', $limit=10) {
        if ($state == 'start') {
            return Doctrine_Query::create()
                            ->from('Event e')
                            ->where('e.is_closed = ?', FALSE)
                            ->andWhere('e.start > curdate()')
                            ->orderBy('e.start ASC')
                            ->limit($limit)->fetchArray();
        } else {
            return Doctrine_Query::create()
                            ->from('Event e')
                            ->where('e.is_closed = ?', FALSE)
                            ->andWhere('e.finish < curdate()')
                            ->orderBy('e.finish DESC')
                            ->limit($limit)->fetchArray();
        }
    }

    public static function isJoined($event_id, $user_id=0) {
        $r = Doctrine_Query::create()
                ->select('id')
                ->from('EventSign')
                ->where('event_id = ? AND user_id = ?', array($event_id, $user_id))
                ->execute(array(), 6);
        return (bool) $r;
    }

    // 用户参加的所有活动
    public static function joinIDs($user_id) {
        $sign = Doctrine_Query::create()
                ->distinct('event_id')
                ->select('event_id')
                ->from('EventSign')
                ->where('user_id = ?', $user_id)
                ->orderBy('sign_at DESC')
                ->execute(array(), Doctrine_Core::HYDRATE_SINGLE_SCALAR);

        $ids = array();
        if (count($sign) == 0) {
            $ids = array(0);
        } else {
            $ids = $sign;
        }
        return $ids;
    }


    public static function signStatus($event, $bool=false, $color=false) {
        if (strtotime($event['sign_start']) > time()) {
            if ($bool == TRUE)
                return FALSE;
            if ($color == TRUE)
                return '#f60';
            return '距离报名开始还有' . Date::span_str(time(), strtotime($event['sign_start']));
        }

        if (strtotime($event['sign_start']) < time() && time() < strtotime($event['sign_finish'])) {
            if ($bool == TRUE || $color == TRUE) {
                if ($event['sign_limit'] > 0 && $event['sign_limit'] <= $event['sign_num']) {
                    if ($bool == TRUE)
                        return FALSE;
                    if ($color == TRUE)
                        return '#fefee5';
                } else {
                    if ($bool == TRUE)
                        return TRUE;
                    if ($color == TRUE)
                        return '#efedff';
                }
            }

            return '距离报名结束还有' . Date::span_str(time(), strtotime($event['sign_finish']));
        }

        if (time() > strtotime($event['sign_finish'])) {
            if ($bool == TRUE)
                return FALSE;
            if ($color == TRUE)
                return '#ffedee';
            return '<span style="color:#f30">已截止报名</span>';
        }
    }

    //开始结束时间
    public static function SatusFinish($start, $finish) {
        if (time() >= strtotime($start) AND time() <= strtotime($finish)) {
            return date('m月d日', strtotime($start)) . ' ' . Date::getWeek($start) . ' ' . date('H:i', strtotime($start)) . '~' . date('H:i', strtotime($finish));
        } elseif (time() <= strtotime($start)) {
            return date('m月d日', strtotime($start)) . ' ' . Date::getWeek($start) . ' ' . date('H:i', strtotime($start)) . '~' . date('H:i', strtotime($finish));
        } else {
            return date('m月d日', strtotime($start)) . ' ' . Date::getWeek($start) . ' ' . date('H:i', strtotime($start)) . '~' . date('H:i', strtotime($finish));
        }
    }

    //开始结束时间
    public static function SatusFinish2($start, $finish) {
        if (time() >= strtotime($start) AND time() <= strtotime($finish)) {
            return date('m月d日', strtotime($start)) . ' ' . Date::getWeek($start) . ' ' . date('H:i', strtotime($start));
        } elseif (time() <= strtotime($start)) {
            return date('m月d日', strtotime($start)) . ' ' . Date::getWeek($start) . ' ' . date('H:i', strtotime($start));
        } else {
            return date('m月d日', strtotime($start)) . ' ' . Date::getWeek($start) . ' ' . date('H:i', strtotime($start));
        }
    }

    //活动状态
    public static function status($event) {
         if (isset($event['is_suspend']) AND $event['is_suspend']){
             return '<span style="color:#FF3300">活动暂停</span>';
         }
         elseif (isset($event['is_stop_sign']) AND $event['is_stop_sign']){
             return '<span style="color:#FF3300">暂停报名</span>';
         }
        elseif (strtotime($event['start']) > time()) {
            return '<span style="color:#FF6600">距离开始还有' . Date::span_str(time(), strtotime($event['start'])) . '</span>';
        }
        elseif (strtotime($event['start']) < time() && time() < strtotime($event['finish'])) {
            return '<span style="color:#0C820C">正在进行中，预计' . Date::span_str(strtotime($event['finish']), time()) . '后结束</span>';
        }
        elseif (time() > strtotime($event['finish'])) {
            return '<span style="color:#999">活动已结束，欢迎下次参加～</span>';
        }
    }

    //更新评论总数
    public static function updateCommentNum($event_id) {
        $total_comments = Doctrine_Query::create()
                ->from('Comment')
                ->where('event_id = ?', $event_id)
                ->count();

        $event = Doctrine_Query::create()
                ->select('id,comments_num')
                ->from('Event')
                ->where('id = ?', $event_id)
                ->fetchOne();
        $event['comments_num'] = $total_comments;
        $event->save();
        return $total_comments;
    }

    //活动详细信息
    public static function getOne($id) {
        $event = Doctrine_Query::create()
                ->select('e.*')
                ->from('Event e')
                ->where('e.id=?', $id)
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

        return $event;
    }

    //获取某一用户报名信息
    public static function getUserSign($event_id,$user_id) {
        $sign = Doctrine_Query::create()
                ->from('EventSign')
                ->where('event_id=?', $event_id)
                ->addWhere('user_id=?',$user_id)
                ->fetchOne();
        return $sign;
    }
}
