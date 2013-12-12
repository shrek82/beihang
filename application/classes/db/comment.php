<?php

/**
  +-----------------------------------------------------------------
 * 名称：评论模型
 * 版本：1.0
 * 版权：www.usho.cn
 * 作者：zhaojiangang
 * 最后更新日期：12-10-12 上午9:46
  +-----------------------------------------------------------------
 */
class Db_Comment {

    //获取评论内容
    public static function get($condition) {

        $limit = isset($condition['limit']) ? $condition['limit'] : 20;
        $page = isset($condition['page']) ? $condition['page'] : 1;
        $offset = ($page - 1) * $limit;
        $order = isset($condition['order']) && strtolower($condition['order']) == 'desc' ? 'DESC' : 'ASC';

        //所有评论，用来显示楼层数
        $all_comments = DB::select('id')
                        ->from('comment')->order_by('id', 'ASC');

        //用户在线时间差
        $alive = time() - 900;

        $comment = DB::select(DB::expr('c.*,u.id AS uid,u.realname AS realname,u.sex AS sex,u.start_year AS start_year,u.speciality AS speciality'))
                ->select(DB::expr('(SELECT o.id FROM ol o WHERE o.uid=c.user_id AND o.time>' . $alive . ' ) AS online'))
                ->from(array('comment', 'c'))
                ->join(array('user', 'u'))
                ->on('u.id', '=', 'c.user_id')
                ->order_by('c.id', $order);

        if (isset($condition['news_id']) AND $condition['news_id']) {
            $all_comments = $all_comments->where('news_id', '=', $condition['news_id']);
            $comment = $comment->where('c.news_id', '=', $condition['news_id']);
        }
        elseif (isset($condition['event_id']) AND $condition['event_id']) {
            $all_comments = $all_comments->where('event_id', '=', $condition['event_id']);
            $comment = $comment->where('c.event_id', '=', $condition['event_id']);
        }
        elseif (isset($condition['bbs_unit_id']) AND $condition['bbs_unit_id']) {
            $all_comments = $all_comments->where('bbs_unit_id', '=', $condition['bbs_unit_id']);
            $comment = $comment->where('c.bbs_unit_id', '=', $condition['bbs_unit_id']);
        }
        elseif (isset($condition['class_room_id']) AND $condition['class_room_id']) {
            $all_comments = $all_comments->where('class_room_id', '=', $condition['class_room_id']);
            $comment = $comment->where('c.class_room_id', '=', $condition['class_room_id']);
        }
        elseif (isset($condition['class_unit_id']) AND $condition['class_unit_id']) {
            $all_comments = $all_comments->where('class_unit_id', '=', $condition['class_unit_id']);
            $comment = $comment->where('c.class_unit_id', '=', $condition['class_unit_id']);
        }
        elseif (isset($condition['pic_id']) AND $condition['pic_id']) {
            $all_comments = $all_comments->where('pic_id', '=', $condition['pic_id']);
            $comment = $comment->where('c.pic_id', '=', $condition['pic_id']);
        }
        elseif (isset($condition['vote_id']) AND $condition['vote_id']) {
            $all_comments = $all_comments->where('vote_id', '=', $condition['vote_id']);
            $comment = $comment->where('c.vote_id', '=', $condition['vote_id']);
        }
        elseif (isset($condition['album_id']) AND $condition['album_id']) {
            $all_comments = $all_comments->where('album_id', '=', $condition['album_id']);
            $comment = $comment->where('c.album_id', '=', $condition['album_id']);
        }
        elseif (isset($condition['weibo_id']) AND $condition['weibo_id']) {
            $all_comments = $all_comments->where('weibo_id', '=', $condition['weibo_id']);
            $comment = $comment->where('c.weibo_id', '=', $condition['weibo_id']);
        }
        else {
            return array('error' => '没有指定内容id', 'comments' => array(), 'total_items' => 0, 'floor' => array(), 'limit' => $limit, 'order' => $order);
        }

        //限制id范围
        if (isset($condition['max_id']) AND $condition['max_id']) {
            $all_comments = $all_comments->where('id', '<', $condition['max_id']);
            $comment = $comment->where('c.id', '< ', $condition['max_id']);
        }

        if (isset($condition['since_id']) AND $condition['since_id']) {
            $all_comments = $all_comments->where('id', '>', $condition['since_id']);
            $comment = $comment->where('c.id', '> ', $condition['since_id']);
        }

        //只显示某一条评论
        if (isset($condition['cmt_id']) AND $condition['cmt_id']) {
            $all_comments = $all_comments->where('id', '=', $condition['cmt_id']);
            $comment = $comment->where('c.id', '= ', $condition['cmt_id']);
            $limit = 1;
            $offset = 0;
            $total_items = 1;
        }

        //所有评论
        $all_comments = $all_comments->execute()->as_array();
        $total_items = count($all_comments);

        $floor = array();
        foreach ($all_comments AS $key => $c) {
            $floor['floor_' . $c['id']] = $key + 1;
        }

        $comments = $comment->offset($offset)
                ->limit($limit)
                ->execute()
                ->as_array();

        return array('comments' => $comments, 'total_items' => $total_items, 'floor' => $floor, 'limit' => $limit, 'order' => $order);
    }

    //删除某项内容评论
    public static function delete($condition) {
        $query = DB::delete('comment');
        $op = '=';
        if (isset($condition['id']) AND $condition['id']) {
            $op = is_array($condition['id']) ? 'IN' : '=';
            $query = $query->where('id', $op, $condition['id']);
        }
        elseif (isset($condition['user_id']) AND $condition['user_id']) {
            $op = is_array($condition['user_id']) ? 'IN' : '=';
            $query = $query->where('user_id', $op, $condition['user_id']);
        }
        elseif (isset($condition['event_id']) AND $condition['event_id']) {
            $op = is_array($condition['event_id']) ? 'IN' : '=';
            $query = $query->where('event_id', $op, $condition['event_id']);
        }
        elseif (isset($condition['news_id']) AND $condition['news_id']) {
            $op = is_array($condition['news_id']) ? 'IN' : '=';
            $query = $query->where('news_id', $op, $condition['news_id']);
        }
        elseif (isset($condition['bbs_unit_id']) AND $condition['bbs_unit_id']) {
            $op = is_array($condition['bbs_unit_id']) ? 'IN' : '=';
            $query = $query->where('bbs_unit_id', $op, $condition['bbs_unit_id']);
        }
        elseif (isset($condition['class_room_id']) AND $condition['class_room_id']) {
            $op = is_array($condition['class_room_id']) ? 'IN' : '=';
            $query = $query->where('class_room_id', $op, $condition['class_room_id']);
        }
        elseif (isset($condition['classroom_id']) AND $condition['classroom_id']) {
            $op = is_array($condition['classroom_id']) ? 'IN' : '=';
            $query = $query->where('classroom_id', $op, $condition['classroom_id']);
        }
        elseif (isset($condition['class_unit_id']) AND $condition['class_unit_id']) {
            $op = is_array($condition['class_unit_id']) ? 'IN' : '=';
            $query = $query->where('class_unit_id', $op, $condition['class_unit_id']);
        }
        elseif (isset($condition['bubble_id']) AND $condition['bubble_id']) {
            $op = is_array($condition['bubble_id']) ? 'IN' : '=';
            $query = $query->where('bubble_id', $op, $condition['bubble_id']);
        }
        elseif (isset($condition['weibo_id']) AND $condition['weibo_id']) {
            $op = is_array($condition['weibo_id']) ? 'IN' : '=';
            $query = $query->where('weibo_id', $op, $condition['weibo_id']);
        }
        elseif (isset($condition['pic_id']) AND $condition['pic_id']) {
            $op = is_array($condition['pic_id']) ? 'IN' : '=';
            $query = $query->where('pic_id', $op, $condition['pic_id']);
        }
        elseif (isset($condition['vote_id']) AND $condition['vote_id']) {
            $op = is_array($condition['vote_id']) ? 'IN' : '=';
            $query = $query->where('vote_id', $op, $condition['vote_id']);
        }
        else {
            return False;
        }

        return $query->execute();
    }

}

?>
