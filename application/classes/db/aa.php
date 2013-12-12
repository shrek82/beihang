<?php

/**
  +-----------------------------------------------------------------
 * 名称：校友模型
 * 版本：1.0
 * 版权：www.usho.cn
 * 作者：zhaojiangang
 * 最后更新日期：12-10-12 上午9:46
  +-----------------------------------------------------------------
 */
class Db_Aa {

    //校友会管理权限
    public static function getPermission($aa_id, $user_id = null) {

        $sess = Session::instance();
        $user_id = $sess->get('id');
        $user_role = $sess->get('role');

        //默认权限
        $permission = array();
        $permission['is_edit_permission'] = False;
        $permission['is_control_permission'] = False;
        $permission['is_system_permission'] = False;

        //活动不存在
        if (!$user_id) {
            return $permission;
        }

        if ($event) {
            //总会管理员
            if ($user_role == '管理员') {
                $permission['is_edit_permission'] = True;
                $permission['is_control_permission'] = True;
                $permission['is_system_permission'] = True;
            }
            //是否为地方校友会管理员
            elseif (self::isManager($aa_id, $user_id)) {
                $permission['is_edit_permission'] = True;
                $permission['is_control_permission'] = True;
            } else {
                return $permission;
            }
        }

        return $permission;
    }

    //根据id获取某一校友会信息
    public static function getInfoById($id) {
        $cache = Cache::instance(Layout_Db::CACHE_GROUP);
        $cache_varname = 'aa_info' . $id;
        $info = $cache->get($cache_varname);
        if (!$info) {
            $info = DB::select(DB::expr('a.*'))
                    ->select(DB::expr('(SELECT COUNT(m.id) FROM aa_member m WHERE a.id = m.aa_id) AS mcount'))
                    ->from(array('aa', 'a'))
                    ->where('a.id', '=', $id)
                    ->execute()
                    ->as_array();
            $info = $info ? $info[0] : false;
            $cache->set($cache_varname, $info, 1800);
        }
        return $info;
    }

    //删除校友会缓存
    public static function delteInfoCache($id) {
        if ($id) {
            Cache::instance(Layout_Db::CACHE_GROUP)->delete('aa_info' . $id);
        }
    }

    //推荐组织
    public static function topAa($type, $limit) {
        $aas = DB::select(DB::expr('a.id,a.name,a.ename,a.sname'))
                ->select(DB::expr('(SELECT COUNT(m.id) FROM aa_member m WHERE m.aa_id = a.id) AS mcount'))
                ->from(array('aa', 'a'))
                ->where('class', '=', $type)
                ->limit($limit)
                ->order_by('mcount', 'DESC')
                ->execute()
                ->as_array();
        return $aas;
        
    }

    //某成员信息
    public static function getMemberInfo($aa_id, $user_id) {
        if ($aa_id < 0 OR $user_id < 0) {
            return false;
        }
        $member = DB::select('m.*')
                ->from(array('aa_member', 'm'))
                ->where('aa_id', '=', $aa_id)
                ->where('user_id', '=', $user_id)
                ->execute()
                ->as_array();
        return $member ? $member[0] : false;
    }

    //是否为本会成员
    public static function isMember($aa_id, $user_id) {
        return (bool) self::getMemberInfo($aa_id, $user_id);
    }

    //是否为管理员
    public static function isManager($aa_id, $user_id) {
        $role = Session::instance()->get('role');
        if ($role == '管理员') {
            return True;
        }
        $member = self::getMemberInfo($aa_id, $user_id);
        if ($member) {
            return $member['chairman'] == 1 || $member['manager'] == 1 ? True : False;
        } else {
            return False;
        }
    }

}

?>
