<?php

//名称：论坛模型
class Db_Bbs {

    const USE_CACHE = True;
    const PAGE_SIZE = 15;

    //更新文件缓存
    public static function updateUnitViewDataCache($bbs_unit_id) {
        $_filecache = Cache::instance(Layout_Db::CACHE_GROUP);
        $cache_key = 'bbs_unit_view_data_' . $bbs_unit_id;
        return $_filecache->delete($cache_key);
    }

    //话题基本信息
    public static function getUnitBaseViewData($id) {
        if (!$id) {
            return false;
        }
        $data = Doctrine_Query::create()
                ->select('u.*,p.*,ur.realname,ur.sex,ur.start_year,ur.city,ur.speciality')
                ->from('BbsUnit u')
                ->where('u.id = ?', $id)
                ->leftJoin('u.Post p')
                ->leftJoin('u.User ur')
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
        return $data;
    }

    //话题详细信息
    public static function getUnitViewData($condition) {

        $bbs_unit_id = isset($condition['bbs_unit_id']) ? $condition['bbs_unit_id'] : false;

        if (!$bbs_unit_id) {
            return false;
        }
        $alive = time() - 900;
        $data = Doctrine_Query::create()
                ->select('u.*,p.*,b.*,ur.realname,ur.sex,ur.start_year,ur.reg_at,ur.city,ur.speciality,ur.bbs_unit_num,ur.point,ur.homepage,ur.intro,a.id,a.sname,c.id,c.name,sf.id')
                ->addSelect('(SELECT bu.content FROM UserBubble bu WHERE bu.user_id=u.user_id ORDER BY bu.id DESC limit 1) AS bubble')
                ->addSelect('(SELECT o.id FROM Ol o WHERE o.uid=u.user_id AND o.time>' . $alive . ' ) AS online')
                ->from('BbsUnit u')
                ->where('u.id = ?', $bbs_unit_id)
                ->leftJoin('u.Post p')
                ->leftJoin('u.Bbs b')
                ->leftJoin('b.Aa a')
                ->leftJoin('u.SysFilter sf')
                ->leftJoin('b.Club c')
                ->leftJoin('u.User ur')
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
        return $data;
    }

    //查询话题
    public static function getUnits($condition) {

        $sess = Session::instance();
        
        $aa_id = isset($condition['aa_id']) ? $condition['aa_id'] : null;
        $bbs_id = isset($condition['bbs_id']) ? $condition['bbs_id'] : null;
        $club_id = isset($condition['club_id']) ? $condition['club_id'] : null;
        $show = isset($condition['show']) ? $condition['show'] : null;
        $sid = isset($condition['sid']) ? $condition['sid'] : null;
        $page = isset($condition['page']) ? $condition['page'] : 1;
        $q = isset($condition['q']) ? $condition['q'] : null;
        $page_size = isset($condition['page_size']) ? $condition['page_size'] : 25;
        $offset = ($page - 1) * $page_size;

        //是否统计总数
        $count_total = isset($condition['count_total']) ? $condition['count_total'] : true;
        //是否查询回复者姓名
        $replyname = isset($condition['replyname']) ? $condition['replyname'] : true;
        //是否管理员查看模式
        $admin_mode = isset($condition['admin_mode']) ? $condition['admin_mode'] : false;
        $orderby = '';

        //查询总数
        $count = DB::select(DB::expr('u.id'))
                ->from(array('bbs_unit', 'u'));

        //非管理员模式不显示已经屏蔽的帖子
        if (!$admin_mode) {
            $count = $count->where('u.is_closed', '=', '0');
        }

        //基本字段
        $units = DB::select(DB::expr('u.id,u.aa_id,u.event_id,u.club_id,u.title,u.type,u.subject,u.user_id,u.is_good,u.is_fixed,u.is_club_fixed,u.is_closed,u.is_pic,u.comment_at,u.create_at,u.reply_num,u.new_replyid,u.hit,u.title_color,u.event_id'))
                ->select(DB::expr('u.create_at,u.reply_num,u.new_replyid,u.hit,u.title_color,u.event_id,user.realname AS realname'));

        //查询回复者信息
        if ($replyname) {
            $units = $units->select(DB::expr('(SELECT re.realname FROM user re WHERE re.id=u.new_replyid ) AS replyname'));
        }

        //继续查询
        $units = $units->from(array('bbs_unit', 'u'));

        //非管理员模式不显示已经屏蔽的帖子
        if (!$admin_mode) {
            $units = $units->where('u.is_closed', '=', '0');
        }

        //总会和地方校友会
        if ($aa_id == '0' OR $aa_id > 0) {
            $count = $count->where('u.aa_id', '=', $aa_id);
            $units = $units->where('u.aa_id', '=', $aa_id);
            $orderby = 'u.is_fixed DESC,case when u.comment_at IS NOT NULL then u.comment_at when u.comment_at IS NULL then u.create_at end DESC';
        }
        //所有校友会
        else {
            $units = $units->select(DB::expr('IF(u.is_fixed = 1 AND u.aa_id=0,1,0) AS public_fixed'));
            $orderby = 'public_fixed DESC,case when u.comment_at IS NOT NULL then u.comment_at when u.comment_at IS NULL then u.create_at end DESC';
        }

        //按版块
        if ($bbs_id) {
            $count = $count->where('u.bbs_id', '=', $bbs_id);
            $units = $units->where('u.bbs_id', '=', $bbs_id);
        }

        //按俱乐部
        if ($club_id) {
            $count = $count->where('u.club_id', '=', $club_id);
            $units = $units->where('u.club_id', '=', $club_id);
            $orderby = 'u.is_fixed DESC,u.is_club_fixed DESC,case when u.comment_at IS NOT NULL then u.comment_at when u.comment_at IS NULL then u.create_at end DESC';
        }


        //关键字搜索
        if ($q) {
            $count = $count->where('u.title', 'LIKE', '%' . $q . '%');
            $units = $units->where('u.title', 'LIKE', '%' . $q . '%');
        }

        //按主题分类
        if ($sid) {
            $count = $count->where('u.subject', '=', $sid);
            $units = $units->where('u.subject', '=', $sid);
        }


        //今天的
        if ($show == 'today') {
            $count = $count->where(DB::expr('TIMESTAMPDIFF(DAY, now(), u.create_at)'), '=', 0);
            $units = $units->where(DB::expr('TIMESTAMPDIFF(DAY, now(), u.create_at)'), '=', 0);
        }

        //最近7天
        elseif ($show == 'week') {
            $count = $count->where(DB::expr('DATE_SUB(CURDATE(), INTERVAL 7 DAY)'), '<=', DB::expr('date(u.create_at)'));
            $units = $units->where(DB::expr('DATE_SUB(CURDATE(), INTERVAL 7 DAY)'), '<=', DB::expr('date(u.create_at)'));
        }
        //精华
        elseif ($show == 'good') {
            $count = $count->where('u.is_good', '=', 1);
            $units = $units->where('u.is_good', '=', 1);
        }
        //自己的
        elseif ($show == 'mytopic') {
            $count = $count->where('u.user_id', '=', $sess->get('id', 0));
            $units = $units->where('u.user_id', '=', $sess->get('id', 0));
        }
        //最近回复
        elseif ($show == 'mycomment') {
            $my_comment_ids = $ids = Doctrine_Query::create()
                    ->select('bbs_unit_id')
                    ->from('Comment c')
                    ->where('c.user_id = ?', $sess->get('id', 0))
                    ->groupBy('c.bbs_unit_id')
                    ->limit(300)
                    ->orderBy('c.id DESC')
                    ->execute(array(), Doctrine_Core::HYDRATE_SINGLE_SCALAR);
            if (!$my_comment_ids) {
                $bbs_unit_ids = array(0);
            } elseif (is_string($my_comment_ids)) {
                $bbs_unit_ids = array($ids);
            } else {
                $bbs_unit_ids = $my_comment_ids;
            }
            $count = $count->where('u.id', 'in', $bbs_unit_ids);
            $units = $units->where('u.id', 'in', $bbs_unit_ids);
        }
        //活动相关的
        elseif ($show == 'event') {
            $count = $count->where('u.event_id', '>', 0);
            $units = $units->where('u.event_id', '>', 0);
        }
        //其他的
        else {
            //...
        }
        
        //话题总数
        if ($count_total) {
            $total_items = $count->execute()->count();
        } else {
            $total_items = null;
        }

        //话题
        $units = $units->select(DB::expr('aa.sname AS aa_sname'))
                ->join(array('user', 'user'))
                ->on('u.user_id', '=', 'user.id')
                ->join('aa')
                ->on('u.aa_id', '=', 'aa.id')
                ->offset($offset)
                ->limit($page_size)
                ->order_by(DB::expr($orderby));
        
        $units=$units->execute()
                ->as_array();

        return array('units' => $units, 'total_items' => $total_items);
    }

    public static function getMainFixed() {

        $sess = Session::instance();

        $units = DB::select(DB::expr('u.id,u.aa_id,u.club_id,u.title,u.type,u.subject,u.user_id,u.is_good,u.is_fixed,u.is_pic,u.comment_at,u.create_at,u.reply_num,u.new_replyid,u.hit,u.title_color,u.event_id'))
                ->select(DB::expr('u.create_at,u.reply_num,u.new_replyid,u.hit,u.title_color,u.event_id'))
                ->select(DB::expr('user.realname,reply.realname AS replyname'))
                ->from(array('bbs_unit', 'u'))
                ->join('user')
                ->on('u.user_id', '=', 'user.id')
                ->join(array('user', 'reply'))
                ->on('u.new_replyid', '=', 'reply.id')
                ->where('u.is_fixed', '=', 1)
                ->where('u.aa_id', '=', 0)
                ->limit(5)
                ->order_by('u.id', 'DESC')
                ->execute()
                ->as_array();

        return $units;
    }

    //获取论坛首页列表缓存(aid=0为总会0，空全部)
    public static function getListCache($aa_id = null) {
        return Cache::instance(Layout_Db::CACHE_GROUP)->get('bbs_unit_recordst_aid' . $aa_id);
    }

    //获取论坛首页列表缓存(aid=0为总会0，空全部)
    public static function getListTotalCache($aa_id = null) {
        return Cache::instance(Layout_Db::CACHE_GROUP)->get('bbs_unit_total_items_aid' . $aa_id);
    }

    //删除话题
    public static function delete($condition) {

        $query = DB::select()->from('bbs_unit');

        if (isset($condition['id'])) {
            $query = $query->where('id', '=', $condition['id']);
        } elseif (isset($condition['user_id'])) {
            $query = $query->where('user_id', '=', $condition['user_id']);
        } elseif (isset($condition['aa_id'])) {
            $query = $query->where('aa_id', '=', $condition['aa_id']);
        } elseif (isset($condition['club_id'])) {
            $query = $query->where('club_id', '=', $condition['club_id']);
        } elseif (isset($condition['bbs_id'])) {
            $query = $query->where('bbs_id', '=', $condition['bbs_id']);
        } elseif (isset($condition['event_id'])) {
            $query = $query->where('event_id', '=', $condition['event_id']);
        } else {
            return False;
        }

        $itmes = $query->execute()->as_array();
        if ($itmes) {
            $ids = array();
            foreach ($itmes as $i) {
                $ids[] = $i['id'];
            }
            if (count($ids) > 0) {
                DB::delete('bbs_unit')->where('id', 'IN', $ids)->execute();

                //删除详细内容
                DB::delete('bbs_post')->where('bbs_unit_id', 'IN', $ids)->execute();

                //删除评论
                Db_Comment::delete(array('bbs_unit_id' => $ids));
            }
        }
    }

    //返回当前用户对某一主题的控制权限
    public static function getPermission($id, $user_id = null, $unit = null) {
        $sess = Session::instance();
        $user_id = $sess->get('id');
        $user_role = $sess->get('role');

        //没有传递活动详情
        if (!$unit) {
            $unit = Doctrine_Query::create()
                    ->select('u.id,u.user_id,b.aa_id AS aa_id,b.club_id AS club_id')
                    ->from('BbsUnit u')
                    ->leftJoin('u.Bbs b')
                    ->where('u.id = ?', $id)
                    ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
        }

        $permission = array();
        $permission['is_edit_permission'] = False;
        $permission['is_control_permission'] = False;
        $permission['is_system_permission'] = False;

        if (!$unit) {
            return $permission;
        }

        if ($unit) {
            //总会管理员
            if ($user_role == '管理员') {
                $permission['is_edit_permission'] = True;
                $permission['is_control_permission'] = True;
                $permission['is_system_permission'] = True;
            }
            //地方校友会管理员
            elseif (DB_Aa::isManager($unit['aa_id'], $user_id)) {
                $permission['is_edit_permission'] = True;
                $permission['is_control_permission'] = True;
            }
            //俱乐部管理员
            elseif (DB_Club::isManager($unit['club_id'], $user_id)) {
                $permission['is_edit_permission'] = True;
                $permission['is_control_permission'] = True;
            }
            //作者本人
            elseif ($unit['user_id'] == $user_id) {
                $permission['is_edit_permission'] = True;
            } else {
                return $permission;
            }
        }
        return $permission;
    }

    //修改某一话题相关字段值
    public static function set($post) {

        if ($post) {
            $cid = Arr::get($post, 'cid');
            $field = Arr::get($post, 'field');
            $bool_field = Arr::get($post, 'bool_field');
            $field_value = Arr::get($post, 'field_value');

            $unit = Doctrine_Query::create()
                    ->from('BbsUnit u')
                    ->where('u.id = ?', $cid)
                    ->fetchOne();

            //修改bool值
            if ($unit && $bool_field && isset($unit[$bool_field])) {
                $unit[$bool_field] = $unit[$bool_field] ? FALSE : TRUE;
                $unit->save();
            }
            //修改其他字段
            elseif ($unit && $field) {
                $unit[$field] = $field_value;
                $unit->save();
            } else {
                return false;
            }
        }
    }

    //获取话题event_id
    public static function getEventIdByBbsUnitId($id) {
        $bbs_unit = DB::select('id', 'event_id')
                ->from('bbs_unit')
                ->where('id', '=', $id)
                ->limit(1)
                ->execute()
                ->as_array();
        return $bbs_unit ? $bbs_unit[0]['event_id'] : NULL;
    }

}

?>
