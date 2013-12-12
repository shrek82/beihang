<?php

/**
  +-----------------------------------------------------------------
 * 名称：活动模型
 * 版本：1.0
 * 版权：www.usho.cn
 * 作者：zhaojiangang
 * 最后更新日期：12-10-12 上午9:46
  +-----------------------------------------------------------------
 */
class Db_Event {

    //过滤一些分类标签
    public static function replaceTitle($title) {
        $title = preg_replace('/【.*?】/', '', $title);
        $title = preg_replace('/(\d+)-(\d+)-(\d+)/', '${2}月${3}日', $title);
        return $title;
    }

    //手机版活动分类
    public static function getMobileCategory() {
        $category = array();
        $category[] = array('name' => '所有', 'parameter' => 'all');
        $category[] = array('name' => '户外', 'parameter' => 'yd');
        $category[] = array('name' => '沙龙', 'parameter' => 'sl');
        $category[] = array('name' => '娱乐', 'parameter' => 'yl');
        $category[] = array('name' => '创业', 'parameter' => 'cy');
        $category[] = array('name' => '艺术', 'parameter' => 'ys');
        $category[] = array('name' => '旅游', 'parameter' => 'ly');
        $category[] = array('name' => '爱心', 'parameter' => 'ax');
        $category[] = array('name' => '聚会', 'parameter' => 'jh');
        $category[] = array('name' => '其他', 'parameter' => 'qt');
        return $category;
    }

    //手机版推荐图片活动
    public static function getRecommended($condition) {

        $uid = isset($condition['uid']) ? $condition['uid'] : null;
        $cat = isset($condition['cat']) ? $condition['cat'] : 'all';
        $limit = isset($condition['limit']) ? $condition['limit'] : 3;

        $query = DB::select(DB::expr('e.id,e.title,e.poster_path'))
                ->from(array('event', 'e'));

        //我加入的组织
        if ($cat == 'all' OR empty($cat)) {
            if ($uid) {
                $query = $query->where('e.aa_id', 'IN', Model_User::aaIds($uid));
            }
            $query = $query->where('e.is_recommended', '=', 1)
                    ->where('e.poster_path', 'IS NOT', NULL)
                    ->where('e.poster_path', '<>', '')
                    //->where('e.start', '>=', date('Y-m-d H:i:s'))
                    ->where('e.is_closed', '=', 0)
                    ->order_by('e.id', 'DESC')
                    ->limit($limit);
            $event = $query->execute()->as_array();
        } else {
            $event = array();
        }

        return $event;
    }

    //活动链接
    public static function getLink($eid, $aa_id, $club_id) {
        if ($club_id > 0) {
            $link = '/club_home/eventview?id=' . $club_id . '&eid=' . $eid;
        } elseif ($aa_id > 0) {
            $link = '/aa_home/eventview?id=' . $aa_id . '&eid=' . $eid;
        } else {
            $link = '/event/view?id=' . $eid;
        }
        return $link;
    }

    //活动列表
    public static function getEvents($condition) {
        
        //不查询
        if(isset($condition['limit'])&&$condition['limit']==0){
            return array();
        }

        $aa_id = isset($condition['aa_id']) ? $condition['aa_id'] : null;
        $club_id = isset($condition['club_id']) ? $condition['club_id'] : null;
        $page = isset($condition['page'])&&$condition['page'] ? $condition['page'] : 15;
        $limit = isset($condition['limit'])&&$condition['limit'] ? $condition['limit'] : 15;
        $page_size = isset($condition['page_size']) ? $condition['page_size'] : 25;
        $start_date = isset($condition['start_date']) ? $condition['start_date'] : null;
        $offset = ($page - 1) * $page_size;
        $max_id = isset($condition['max_id']) ? $condition['max_id'] : null;
        $since_id = isset($condition['since_id']) ? $condition['since_id'] : null;
        $q = isset($condition['q']) ? $condition['q'] : null;
        $cat = isset($condition['cat']) ? $condition['cat'] : null;
        $aa_info = isset($condition['aa_info']) ? $condition['aa_info'] : false;
        $user_info = isset($condition['user_info']) ? $condition['user_info'] : false;
        $get_sql = isset($condition['get_sql']) ? $condition['get_sql'] : false;
        $is_recommend = isset($condition['is_recommend']) ? $condition['is_recommend'] : false;
        $uid = isset($condition['uid']) && $condition['uid'] ? $condition['uid'] : false;
        
        //默认排序方式
        $orderby = 'is_start_fixed DESC,can_sign ASC,e.start DESC';

        //默认字段
        //
        //查询语句
        $query = DB::select(DB::expr('e.id,e.title,e.start,e.user_id,e.finish,e.address,e.type,e.aa_id,e.club_id,e.is_fixed,e.is_club_fixed,e.sign_limit,e.small_img_path,e.comments_num AS reply_num,e.is_vcert,e.is_fixed,e.tags,e.publish_at'))
                ->select(DB::expr('(SELECT SUM(ss.num) FROM event_sign ss WHERE ss.event_id = e.id) AS sign_num'))
                ->select(DB::expr('e.comments_num AS reply_num'))
                ->select(DB::expr('IF(e.finish >= now()&&e.is_suspend=0,TIMESTAMPDIFF(MINUTE, now(), e.start),900000) AS can_sign'))
                ->select(DB::expr('IF(e.is_fixed = 1 && e.finish >= now(),1,0) AS is_start_fixed'))
                ->select(DB::expr('IF(e.is_club_fixed=1 && e.finish >= now(),1,0) AS is_club_start_fixed'))
                ->from(array('event', 'e'))
                ->where('e.is_closed', '=', 0);

        //校友会信息
        if ($aa_info) {
            $query = $query->select(DB::expr('a.name AS aa_name'))->join(array('aa', 'a'))->on('e.aa_id', '=', 'a.id');
        }

        //发起人信息
        if ($user_info) {
            $query = $query->select(DB::expr('ur.realname AS realname'))->join(array('user', 'ur'))->on('e.user_id', '=', 'ur.id');
        }

        //校友会的
        if (is_numeric($aa_id)) {
            $query = $query->where('e.aa_id', '=', (int) $aa_id);
        }

        //俱乐部的
        if ($club_id) {
            $query = $query->where('e.club_id', '=', (int) $club_id);
            $orderby = 'is_club_start_fixed DESC,can_sign ASC,e.start DESC';
        }

        //某时间之后的
        if ($start_date) {
            $query = $query->where('e.start', '>=', (int) $start_date);
        }

        //某eid之后的
        if ($since_id) {
            $query = $query->where('e.id', '>', $since_id);
        }

        //某eid之前的
        if ($max_id) {
            $query = $query->where('e.id', '<', $max_id);
        }

        //相关关键字
        if ($q) {
            $query = $query->where('e.title', 'LIKE', '%' . $q . '%');
        }

        //手机推荐的活动
        if ($is_recommend) {
            $query = $query->select('e.poster_path')->where('e.is_recommended', '=', 1)
                    ->where('e.poster_path', 'IS NOT', NULL)
                    ->where('e.poster_path', '<>', '');
        }


        //根据分类筛选
        if ($cat) {
            //我加入的组织
            if ($cat == 'joinaa' && $uid) {
                $query = $query->where('e.aa_id', 'IN', Model_User::aaIds($uid));
            }
            //我参加过的
            elseif ($cat == 'joined' && $uid) {
                $joinIds = Model_Event::joinIDs($uid);
                $query = $query->where('e.id', 'IN', $joinIds);
            }
            //按活动形式分类
            elseif ($cat == 'yd') {
                $query = $query->where('e.type', '=', '运动健身');
            } elseif ($cat == 'cf') {
                $query = $query->where('e.type', '=', '吃饭逛街');
            } elseif ($cat == 'sl') {
                $query = $query->where('e.type', '=', '座谈沙龙');
            } elseif ($cat == 'yl') {
                $query = $query->where('e.type', '=', '室内娱乐');
            } elseif ($cat == 'ly') {
                $query = $query->where('e.type', '=', '户外旅游');
            } elseif ($cat == 'jh') {
                $query = $query->where('e.type', '=', '周年聚会');
            } elseif ($cat == 'ys') {
                $query = $query->where('e.type', '=', '艺术欣赏');
            } elseif ($cat == 'ax') {
                $query = $query->where('e.type', '=', '爱心互助');
            } elseif ($cat == 'cy') {
                $query = $query->where('e.type', '=', '创业大赛');
            } elseif ($cat == 'qt') {
                $query = $query->where('e.type', '=', '其他');
            }
            //某一段时间内容
            elseif ($cat == 'week_' OR $cat == 'today_' OR $cat == 'weeken_') {
                switch ($cat) {
                    case 'week':
                        $time_span = time() + Date::WEEK;
                        $event->addWhere('UNIX_TIMESTAMP(e.start)>UNIX_TIMESTAMP() AND UNIX_TIMESTAMP(e.start)<=' . $time_span);
                        break;
                    case 'today':
                        $event->addWhere('TIMESTAMPDIFF(DAY, now(), e.start)=0');
                        break;
                    case 'weeken':
                        $event->addWhere('DAYOFWEEK(e.start)=1 OR DAYOFWEEK(e.start)=7');
                        break;
                    default :
                        break;
                }
            } else {

            }
        }

        if ($limit) {
            $query = $query->limit($limit);
        }

        $query = $query->order_by(DB::expr($orderby))->offset($offset);

        //返回sql语句
        if ($get_sql) {
            return $query;
        }

        //执行查询
        $events = $query->execute()->as_array();

        return $events;
    }

    //获取活动基本介绍
    public static function getEventById($id) {
        $event = DB::select(DB::expr('e.*,(SELECT unit.id FROM bbs_unit unit WHERE unit.event_id = e.id limit 1) AS bbs_unit_id'))
                ->from(array('event', 'e'))
                ->where('e.id', '=', $id)
                ->execute()
                ->as_array();
        return $event ? $event[0] : array();
    }

    //获取话题id
    public static function getBbsUnitIdByEventId($id) {
        $bbs_unit = DB::select('id')
                ->from('bbs_unit')
                ->where('event_id', '=', $id)
                ->limit(1)
                ->execute()
                ->as_array();
        return $bbs_unit ? $bbs_unit[0]['id'] : NULL;
    }

    //获取活动报名信息
    public static function getEventSigner($id, $limit = null, $category_id = null) {
        $alive = time() - 900;
        $signer = Doctrine_Query::create()
                ->select('s.*, u.id, u.realname, u.account,u.sex')
                ->addSelect('(SELECT o.id FROM Ol o WHERE o.uid=s.user_id AND o.time>' . $alive . ' ) AS online')
                ->from('EventSign s')
                ->leftJoin('s.User u')
                ->where('event_id = ?', $id);

        if ((int) $category_id > 0) {
            $signer->addWhere('category_id=?', $category_id);
        }

        if ($limit) {
            $signer->limit($limit);
        }

        $signer = $signer->orderBy('sign_at ASC')
                ->fetchArray();
        return $signer;
    }

    //获取某一报名信息
    public static function getSignById($id) {
        $sign = DB::select()
                ->from('event_sign')
                ->where('id', '=', $id)
                ->execute()
                ->as_array();
        return $sign ? $sign[0] : array();
    }

    //修改活动某字段值
    public static function update($id, $fields) {
        return DB::update('event')->set($fields)->where('id', '=', $id)->execute();
    }

    //修改bool类型值
    public static function setBoolValue($id, $field) {
        $event = self::getEventById($id);
        if ($event AND isset($event[$field])) {
            $value = $event[$field] == 1 ? 0 : 1;
            $itmes = array();
            $itmes[$field] = $value;
            if ($field == 'is_fixed') {
                $itmes['is_club_fixed'] = $value;
            }
            return self::update($id, $itmes);
        } else {
            return false;
        }
    }

    //用户是否已经参加某活动
    public static function isJoined($event_id, $user_id = 0) {
        $r = Doctrine_Query::create()
                ->select('id')
                ->from('EventSign')
                ->where('event_id = ? AND user_id = ?', array($event_id, $user_id))
                ->execute(array(), 6);
        return (bool) $r;
    }

    //获取活动相册
    public static function getAlbum($event_id) {
        $album = DB::select()
                ->from('album')
                ->where('event_id', '=', $event_id)
                ->limit(1)
                ->execute()
                ->as_array();
        return $album ? $album[0] : array();
    }

    //是否具有活动管理权限
    public static function isManager($event) {

        $sess = Session::instance();

        $user_id = $sess->get('id', 0);

        // 发起人
        if ($event['user_id'] == $user_id) {
            return TRUE;
        }
        // 网站管理员
        elseif ($sess->get('role') == '管理员') {
            return TRUE;
        }
        // 校友会负责人
        elseif ((int) $event['aa_id'] > 0) {
            if (DB_Aa::isManager($event['aa_id'], $user_id)) {
                return TRUE;
            }
        }
        // 俱乐部负责人
        elseif ((int) $event['club_id'] > 0) {
            return DB_Club::checkPow($event['club_id'], $user_id, false);
        } else {
            return FALSE;
        }

        return FALSE;
    }

    //是否可参加活动
    public static function canSign($user, $event, $all_signs, $is_signed) {
        $can_sign = False;
        $why_can_not = NULL;
        return array('can_sign' => $can_sign, 'error' => $why_can_not);
    }

    //删除活动
    public static function delete($id) {

        //删除报名信息
        DB::delete('event_sign')->where('event_id', '=', $id)->execute();

        //删除评论
        Db_Comment::delete(array('event_id' => $id));

        //删除相册及照片
        Db_Album::delete(array('event_id' => $id));

        //从话题删除
        Db_Bbs::delete(array('event_id' => $id));

        //从活动表删除
        DB::delete('event')->where('id', '=', $id)->execute();
    }

    //删除某一条报名信息
    public static function deleteSignById($id) {
        DB::delete('event_sign')->where('id', '=', $id)->execute();
    }

    //删除某一活动所有报名信息
    public static function deleteSignByEvent($event_id) {
        DB::delete('event_sign')->where('event_id', '=', $event_id)->execute();
    }

//返回当前用户对某一活动的控制权限
    public static function getPermission($id, $user_id = null, $event = null) {

        $sess = Session::instance();
        $user_id = $sess->get('id');
        $user_role = $sess->get('role');

        //没有传递活动详情
        if (!$event) {
            $event = self::getEventById($id);
        }

        //默认权限
        $permission = array();
        $permission['is_edit_permission'] = False;
        $permission['is_control_permission'] = False;
        $permission['is_system_permission'] = False;

        //活动不存在
        if (!$event OR !$user_id) {
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
            elseif (DB_Aa::isManager($event['aa_id'], $user_id)) {
                $permission['is_edit_permission'] = True;
                $permission['is_control_permission'] = True;
            }
            //是否为俱乐部管理员
            elseif (DB_Club::isManager($event['club_id'], $user_id)) {
                $permission['is_edit_permission'] = True;
                $permission['is_control_permission'] = True;
            }
            //作者本人
            elseif ($event['user_id'] == $user_id) {
                $permission['is_edit_permission'] = True;
            }
            //
            else {
                return $permission;
            }
        }

        return $permission;
    }

    //活动详细信息
    public static function getEventViewData($condition) {

        $data = array();
        $user_id = Session::instance()->get('id');

        //活动id
        $event_id = isset($condition['event_id']) ? $condition['event_id'] : null;
        if (!$event_id) {
            return false;
        }

        //活动基本介绍
        $event = self::getEventById($event_id);
        if (!$event) {
            return false;
        }

        $data['event'] = $event;

        //发起人信息
        if (isset($condition['organiger_info']) AND $condition['organiger_info']) {
            $data['organiger'] = Db_User::getInfoById($event['user_id']);
        }

        //所属校友会信息
        if (isset($condition['aa_info']) AND $condition['aa_info']) {
            $data['aa'] = Db_Aa::getInfoById($event['aa_id']);
        }

        //所属俱乐部信息
        if (isset($condition['club_info']) AND $condition['club_info']) {
            $data['club'] = Db_Club::getInfoById($event['club_id']);
        }

        //查询相册信息
        if (isset($condition['album']) AND $condition['album']) {
            $data['album'] = self::getAlbum($event_id);
            $data['album_id'] = isset($data['album']['id']) ? $data['album']['id'] : False;
        }

        //相册详情
        if (isset($condition['album_id']) AND $condition['album_id']) {
            //未查询过相册
            if (!isset($data['album'])) {
                $data['album'] = self::getAlbum($event_id);
            }
            $data['album_id'] = isset($data['album']['id']) ? $data['album']['id'] : False;
        }

        //活动照片
        if (isset($condition['photos']) AND $condition['photos']) {
            //不知道相册id
            if (!isset($data['album_id'])) {
                $data['album'] = self::getAlbum($event_id);
                $data['album_id'] = isset($data['album']['id']) ? $data['album']['id'] : False;
            }

            //有相册
            if ($data['album_id']) {
                $photos_limit = isset($condition['photos_limit']) ? $condition['photos_limit'] : 6;
                $data['photos'] = Db_Album::getPics($data['album_id'], $photos_limit);
            }
            //没有相册
            else {
                $data['photos'] = False;
            }
        }

        //论坛话题id
        if (isset($condition['bbs_unit_id']) AND $condition['bbs_unit_id']) {
            $data['bbs_unit_id'] = $event['bbs_unit_id'];
        }

        //对报名信息进行分组
        if (isset($condition['sign_category']) AND $condition['sign_category']) {
            $data['sign_category'] = self::getSignCategory($event_id);
        }

        //当前活动报名用户信息
        if (isset($condition['signs']) AND $condition['signs']) {
            $data['signs'] = self::getEventSigner($event_id);
            //计算已经报名人数
            $total_sign = 0;
            foreach ($data['signs'] AS $s) {
                $total_sign = $total_sign + $s['num'];
            }
            $data['total_sign'] = $total_sign;
        }

        //只统计总报名人数
        if (isset($condition['total_sign']) AND $condition['total_sign']) {
            $total_sign = Doctrine_Query::create()
                    ->from('Event e')
                    ->select('e.id')
                    ->addSelect('(SELECT SUM(ss.num) FROM EventSign ss WHERE ss.event_id = e.id) AS sign_num')
                    ->where('e.id=?', $event_id)
                    ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
            $data['total_sign'] = $total_sign['sign_num'];
        }



        //当前用户报名状况
        if (isset($condition['user_sign_status']) AND $condition['user_sign_status']) {
            $is_signed = self::isJoined($event_id, $user_id);
            $data['user_sign_status']['is_signed'] = $is_signed;
        }

        //标签
        if (isset($condition['tags']) AND $condition['tags']) {
            $tags = '';
            if (count(explode(' ', $event['tags'])) > 0) {
                $tags = explode(' ', $event['tags']);
            }
            $data['tags'] = $tags;
        }

        //当前用户编辑、推荐、置顶、删除等权限
        if (isset($condition['permission']) AND $condition['permission']) {
            $permission = self::getPermission($event_id, $user_id, $event);
            $data['permission'] = $permission;
        }

        return $data;
    }

    //获取某活动分类信息
    public static function getSignCategory($event_id) {
        $categorys = Doctrine_Query::create()
                ->select('c.*')
                ->addSelect('(SELECT SUM(s.num) FROM EventSign s WHERE s.event_id = c.event_id AND s.category_id=c.id) AS sign_num')
                ->from('EventSignCategorys c')
                ->where('c.event_id=?', $event_id)
                ->orderBy('c.id ASC')
                ->fetchArray();

        return $categorys;
    }

}

?>
