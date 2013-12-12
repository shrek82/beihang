<?php
/**
  +-----------------------------------------------------------------
 * 名称：俱乐部模型
 * 版本：1.0
 * 版权：www.usho.cn
 * 作者：zhaojiangang
 * 最后更新日期：12-10-12 上午9:46
  +-----------------------------------------------------------------
 */
class Db_Club {



    //推荐组织
    public static function topClub($limit,$aa_id=null) {
        $clubs = DB::select(DB::expr('c.id,c.name'))
                ->select(DB::expr('(SELECT COUNT(m.id) FROM club_member m WHERE m.club_id = c.id) AS mcount'))
                ->from(array('club','c'));

        if($aa_id){
            $clubs->where('c.aa_id','=',$aa_id);
        }
       $clubs=$clubs->limit($limit)
                ->order_by('mcount', 'DESC')
                ->execute()
                ->as_array();
        return $clubs;
    }

    //根据id获取某一校友会信息
    public static function getInfoById($id,$reset=false) {
        $cache = Cache::instance(Layout_Db::CACHE_GROUP);
        $cache_varname = 'club_info' . $id;
        $info = $cache->get($cache_varname);
        if (!$info OR $reset) {
            $info = DB::select(DB::expr('c.*,a.sname'))
                    ->select(DB::expr('(SELECT COUNT(m.id) FROM club_member m WHERE m.club_id = c.id) AS mcount'))
                    ->from(array('club', 'c'))
                    ->join(array('aa', 'a'))
                    ->on('a.id', '=', 'c.aa_id')
                    ->where('c.id', '=',$id)
                    ->execute()
                    ->as_array();
            if ($info) {
                $info = $info[0];
                $info['aa_name']=$info['sname'].'校友会';
                $cache->set($cache_varname, $info, 1800);
            }
        }
        return $info;
    }

    //删除俱乐部介绍缓存
    public static function delteInfoCache($id) {
        if ($id) {
            Cache::instance(Layout_Db::CACHE_GROUP)->delete('club_info' . $id);
        }
    }

    # 检查是否有res的管理权限
    public static function checkPow($club_id, $user_id, $redirect=true) {
        $has_pow = self::isManager($club_id, $user_id);

        if (!$has_pow && $redirect == true) {
            Model_User::deny('非该俱乐部管理员成员无法进行该操作');
            exit;
        }

        return $has_pow;
    }


    //某成员信息
    public static function getMemberInfo($club_id, $user_id) {
        if ($club_id < 0 OR $user_id < 0) {
            return false;
        }

        $member = DB::select()
                ->from(array('club_member', 'm'))
                ->where('club_id', '=', $club_id)
                ->where('user_id', '=', $user_id)
                ->execute()
                ->as_array();

        return $member ? $member[0] : false;
    }

    //是否为本会成员
    public static function isMember($club_id, $user_id) {
        return (bool)self::getMemberInfo($club_id, $user_id);
    }

    //是否为管理员
    public static function isManager($club_id, $user_id) {
        $member = self::getMemberInfo($club_id, $user_id);
        if ($member) {
            return $member['chairman']==1||$member['manager']==1?true:false;
        } else {
            return False;
        }
    }

}

?>
