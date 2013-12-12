<?php

/**
  +-----------------------------------------------------------------
 * 名称：论坛模型
 * 版本：1.0
 * 版权：www.usho.cn
 * 作者：zhaojiangang
 * 最后更新日期：12-10-12 上午10:27
  +-----------------------------------------------------------------
 */
class Model_Bbs extends Layout_Db {

    const FOCUS_PATH = 'static/upload/bbs_focus/';
    const USE_CACHE = True;

    static $up_rule = array(
        'Upload::valid' => array(),
        'Upload::not_empty' => array(),
        'Upload::type' => array('Upload::type' => array('gif', 'jpg', 'jpeg', 'png')),
        'Upload::size' => array('1M')
    );

    //获取论坛首页列表缓存(aid=0为总会0，空全部)
    public static function getListCache($aa_id = null) {
        return Cache::instance(Layout_Db::CACHE_GROUP)->get('bbs_unit_recordst_aid' . $aa_id);
    }

    //获取论坛首页列表缓存(aid=0为总会0，空全部)
    public static function getListTotalCache($aa_id = null) {
        return Cache::instance(Layout_Db::CACHE_GROUP)->get('bbs_unit_total_itmes_aid' . $aa_id);
    }

    //查询话题
    public static function getUnits($condition) {

        $aa_id = isset($condition['aa_id']) ? $condition['aa_id'] : null;
        $bbs_id = isset($condition['bbs_id']) ? $condition['bbs_id'] : null;
        $club_id = isset($condition['club_id']) ? $condition['club_id'] : null;
        $show = isset($condition['show']) ? $condition['show'] : null;
        $sid = isset($condition['sid']) ? $condition['sid'] : null;
        $page = isset($condition['page']) ? $condition['page'] : 1;
        $q = isset($condition['q']) ? $condition['q'] : null;
        $page_size = isset($condition['page_size']) ? $condition['page_size'] : null;
        $offset = ($page - 1) * $page_size;
        $get_sql = isset($condition['get_sql']) ? $condition['get_sql'] : false;
        $orderby = '';

        //查询主题
        $unit = Doctrine_Query::create()
                ->select('u.id,u.aa_id,u.club_id,u.title,u.type,u.subject,u.user_id,u.is_good,u.is_fixed,u.is_pic,u.comment_at,u.create_at,u.reply_num,u.new_replyid,u.hit,u.title_color,u.event_id')
                ->addSelect('user.id,user.realname')
                ->addSelect('ru.realname AS replyname')
                ->from('BbsUnit u')
                ->where('u.is_closed = 0')
                ->leftJoin('u.User user')
                ->leftJoin('u.Ruser ru');

        //总会和地方校友会
        if ($aa_id == '0' OR $aa_id > 0) {
            $unit->addWhere('u.aa_id=' . $aa_id);
            $orderby = 'u.is_fixed DESC,case when u.comment_at IS NOT NULL then u.comment_at when u.comment_at IS NULL then u.create_at end DESC';
        }
        //所有校友会
        else {
            $unit = $unit->addSelect('a.sname AS aa_sname')
                    ->addSelect('IF(u.is_fixed = 1 AND u.aa_id=0,1,0) AS public_fixed')
                    ->leftJoin('u.Aa a');
            $orderby = 'public_fixed DESC,case when u.comment_at IS NOT NULL then u.comment_at when u.comment_at IS NULL then u.create_at end DESC';
        }

        //按版块
        if ($bbs_id) {
            $unit->addWhere('u.bbs_id=' . $bbs_id);
        }

        //按俱乐部
        if ($club_id) {
            $unit->addWhere('u.club_id=' . $club_id);
        }

        //关键字搜索
        if ($q) {
            $unit->andWhere('u.title LIKE ?', '%' . $q . '%');
        }

        //按主题分类
        if ($sid) {
            $unit->andWhere('u.subject=?', $sid);
        }

        //筛选
        if ($show == 'today') {
            $unit->addWhere('TIMESTAMPDIFF(DAY, now(), u.create_at)=0');
        } elseif ($show == 'week') {
            $time_span = time() - Date::WEEK;
            $unit->addWhere('UNIX_TIMESTAMP(u.create_at)>=' . $time_span);
        } elseif ($show == 'good') {
            $unit->addWhere('u.is_good=1');
        } elseif ($show == 'mytopic') {
            $unit->addWhere('u.user_id=?', $user_id);
        } elseif ($show == 'mycomment') {
            $my_comment_ids = $ids = Doctrine_Query::create()
                    ->select('bbs_unit_id')
                    ->from('Comment c')
                    ->where('c.user_id = ?', $user_id)
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
            $unit->andWhereIn('u.id', $bbs_unit_ids);
        } elseif ($show == 'event') {
            $unit->addWhere('u.event_id>0');
        } else {
            //...
        }

        $total_itmes = $unit->count();

        $units = $unit->offset($offset)
                ->orderBy($orderby)
                ->limit($page_size)
                ->fetchArray();

        //是列表首页和地方首页保存缓存
        if ($page == 1 AND empty($bbs_id) AND empty($sid) AND empty($club_id) AND empty($show) AND (empty($q))) {
            $cache = Cache::instance(Layout_Db::CACHE_GROUP);
            $cache->set('bbs_unit_recordst_aid' . $aa_id, $units, 84600);
            $cache->set('bbs_unit_total_itmes_aid' . $aa_id, $total_itmes, 84600);
        }

        return array('units' => $units, 'total_items' => $total_itmes);
    }

    //获取某一校友会或俱乐部版块信息
    public static function getBbsByAid($aa_id) {
        $bbs = null;
        $cache = Cache::instance(Layout_Db::CACHE_GROUP);
        if (self::USE_CACHE) {
            $bbs = $cache->get('bbs_aid_cid_' . $aa_id);
        }

        if (!$bbs) {
            $bbs = DB::select(DB::expr('b.id,b.name,b.club_id'))
                    ->select(DB::expr('(SELECT c.name FROM club c WHERE c.id = b.club_id) AS club_name'))
                    ->from(array('bbs', 'b'))
                    ->where('b.aa_id', '= ', $aa_id)
                    ->where('b.parent_id', '<>', -1)
                    ->order_by(DB::expr('b.club_id ASC,b.order_num ASC,b.id ASC'))
                    ->execute()
                    ->as_array();

            $cache->set('bbs_aid_cid_' . $aa_id, $bbs, 3600);
        }
        return $bbs;
    }

    //根据话题id更新论坛相应列表缓存
    public static function updateListCacheById($bbs_unit_id) {
        $unit = Doctrine_Query::create()
                ->select('id,bbs_id,aa_id,club_id')
                ->from('BbsUnit')
                ->where('id = ?', $bbs_unit_id)
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
        if ($unit) {
            $cache = Cache::instance(Layout_Db::CACHE_GROUP);
            $cache->delete('bbs_unit_recordst_aid');
            $cache->delete('bbs_unit_recordst_aid' . $unit['aa_id']);
        }
    }

    //根据aid更新论坛相应列表缓存
    public static function updateListCacheByAid($aid = null) {
        $cache = Cache::instance(Layout_Db::CACHE_GROUP);
        //话题首页
        $cache->delete('bbs_unit_recordst_aid');
        //地方校友会首页
        $cache->delete('bbs_unit_recordst_aid' . $aid);
    }

    //获取某一版块信息
    public static function getBbsByid($bbs_id) {
        $bbs = Doctrine_Query::create()
                ->select('id,name,aa_id,club_id')
                ->from('Bbs')
                ->where('id = ?', $bbs_id)
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
        return $bbs;
    }

    public static function limitCheck($unit) {
        $sess = Session::instance();

        // 没有限制或者身份为(管理员)则直接返回T
        // $sess->get('role') == '管理员'
        if ($unit['is_limit'] == false) {
            return true;
        }

        $club_id = $unit['Bbs']['club_id'];

        if ($club_id && !Model_Club::isMember($club_id, $sess->get('id'))) {
            Model_User::deny('您不是该帖子所在俱乐部的成员，无法浏览。');
        }

        $aa_id = $unit['Bbs']['aa_id'];

        if ($aa_id && !Model_Aa::isMember($aa_id, $sess->get('id'))) {
            Model_User::deny('您不是该帖子所在校友会的成员，无法浏览。');
        }

        return true;
    }

    public static function isExist($params) {
        $bbs = Doctrine_Query::create()->from('Bbs b');

        foreach ($params as $field => $value) {
            $bbs->where('b.' . $field . ' = ?', $value);
        }

        return (bool) $bbs->count();
    }

    # unit访问记录

    public static function unitHit($unit_id) {
        Doctrine_Query::create()
                ->update('BbsUnit')
                ->set('hit', 'hit +1')
                ->where('id = ?', $unit_id)
                ->execute();
    }

    # unit是否回复过

    public static function isReply($unit_id, $user_id) {
        return (bool) Doctrine_Query::create()
                        ->from('BbsUnit u')
                        ->leftJoin('u.Comments c')
                        ->where('u.id = ? AND (c.user_id = ? OR u.user_id = ?) ', array($unit_id, $user_id, $user_id))
                        ->count();
    }

    # 获取bbs id 数组

    public static function getIDs($params) {
        $aa_id = Arr::get($params, 'aa_id');
        $club_id = Arr::get($params, 'club_id');

        $bbs = Doctrine_Query::create()
                ->select('b.id,b.name,a.name,c.name')
                ->from('Bbs b')
                ->leftJoin('b.Aa a')
                ->leftJoin('b.Club c')
                ->where('b.parent_id >=0 OR b.parent_id is NULL')
                ->addWhere('b.aa_id = ?', $aa_id);

        if ($club_id) {
            $bbs->where('b.club_id = ?', $club_id);
        } else {
            $bbs->andWhere('b.club_id = ?', 0);
        }

        $result = $bbs->fetchArray();
        $ids = array();
        if (count($result) > 0) {
            foreach ($result as $r) {
                $name = ($r['Aa']['name'] || $r['Club']['name']) ? $r['Aa']['name'] . $r['Club']['name'] : '公共论坛';
                //$ids[$r['id']] = $r['name'] . "({$name})";
                $ids[$r['id']] = $r['name'];
            }
        }
        return $ids;
    }

    //发布或修改帖子
    public static function postUnit($post, $bbs_unit_id) {
        if (!$post['title'] OR !$post['content'] OR !$post['user_id'] OR !$post['bbs_id']) {
            return False;
        }

        if (@$bbs_unit_id) {
            $unit = Doctrine_Query::create()
                    ->from('BbsUnit')
                    ->where('id = ?', $bbs_unit_id)
                    ->fetchOne();
            if (!$unit) {
                $unit = new BbsUnit();
            }
        }

        $unit->fromArray($post);
        $unit->save();
        return $unit->id;
    }

    //更新评论总数
    public static function updateCommentNum($unit_id, $new_replyid,$new_cmtid=null) {
        $total_comments = Doctrine_Query::create()
                ->from('Comment')
                ->where('bbs_unit_id = ?', $unit_id)
                ->count();

        $unit = Doctrine_Query::create()
                ->select('id,reply_num,comment_at,new_replyid')
                ->from('BbsUnit')
                ->where('id = ?', $unit_id)
                ->fetchOne();

        $unit['reply_num'] = $total_comments;
        $unit['new_cmtid'] = $new_cmtid;
        $unit['comment_at'] = date('Y-m-d H:i:s');
        $unit['new_replyid'] = $new_replyid;
        $unit->save();
        return $total_comments;
    }

    //手机端话题分类浏览参数
    public static function getMobielCategory() {
        $data = array();
        $data[] = array(
            'name' => '公共',
            'parameter' => 'all',
        );
        $data[] = array(
            'name' => '加入组织的',
            'parameter' => 'joinaa',
        );
        $data[] = array(
            'name' => '我发布的',
            'parameter' => 'mytopic',
        );
        $data[] = array(
            'name' => '评论过的',
            'parameter' => 'mycomments',
        );

        return $data;
    }

    //手机端信息接口
    public static function getMobileList($condition = array()) {

        $limit = isset($condition['limit']) && $condition['limit'] ? $condition['limit'] : 10;
        $page = isset($condition['page']) && $condition['page'] ? $condition['page'] : 1;
        $cat = isset($condition['cat']) && $condition['cat'] ? $condition['cat'] : 'all';
        $aa_id = isset($condition['aa_id']) && $condition['aa_id'] ? $condition['aa_id'] : null;
        $_uid = isset($condition['_uid']) && $condition['_uid'] ? $condition['_uid'] : null;
        $offset = ($page - 1) * $limit;
        $q = isset($condition['q']) ? $condition['q'] : null;
        $is_display_event = isset($condition['is_display_event']) ? $condition['is_display_event'] : false;

        //置顶主题(仅在浏览全部或加入的校友会第一页时才显示)
        $fixed = Doctrine_Query::create()
                ->select('u.id,u.aa_id,u.subject,u.bbs_id,u.title,u.user_id,u.comment_at,u.create_at,u.new_replyid,u.reply_num,u.hit,u.is_good,u.is_fixed')
                ->addSelect('a.name AS aa_name')
                ->addSelect('user.id,user.realname,user.sex,user.speciality,user.start_year')
                ->addSelect('ru.realname AS replyname,ru.sex AS replysex')
                ->addSelect('IF(u.is_fixed = 1 AND u.aa_id=0,1,0) AS public_fixed')
                ->from('BbsUnit u')
                ->leftJoin('u.User user')
                ->leftJoin('u.Ruser ru')
                ->leftJoin('u.Aa a')
                ->where('u.is_closed=0');

        //最新主题
        $unit = Doctrine_Query::create()
                ->select('u.id,u.aa_id,u.subject,u.bbs_id,u.title,u.user_id,u.comment_at,u.create_at,u.new_replyid,u.reply_num,u.hit,u.is_good,u.is_fixed')
                ->addSelect('a.name AS aa_name')
                ->addSelect('user.id,user.realname,user.sex,user.speciality,user.start_year')
                ->addSelect('ru.realname AS replyname,ru.sex AS replysex')
                ->from('BbsUnit u')
                ->leftJoin('u.User user')
                ->leftJoin('u.Ruser ru')
                ->leftJoin('u.Aa a')
                ->where('u.is_closed=0');

        //只有在第一页时查询置顶话题
        $display_fixed = $page == 1 ? true : false;

        //1、设置排序
        //获取普通校友会置顶话题
        $fixed_order_by = 'is_fixed DESC,case when u.comment_at IS NOT NULL then u.comment_at when u.comment_at IS NULL then u.create_at end DESC';
        //指定总会时地方校友会置顶忽略
        if (!$aa_id) {
            $fixed_order_by = 'public_fixed DESC,case when u.comment_at IS NOT NULL then u.comment_at when u.comment_at IS NULL then u.create_at end DESC';
        }

        $order_by = 'case when u.comment_at IS NOT NULL then u.comment_at when u.comment_at IS NULL then u.create_at end DESC';


        //指定校友会
        if (is_numeric($aa_id)) {
            $fixed->addWhere('u.aa_id=' . $aa_id);
            $unit->addWhere('u.aa_id=' . $aa_id);
        }

        //是否展示活动
        if (!$is_display_event) {
            $fixed->addWhere('u.event_id IS NULL');
            $unit->addWhere('u.event_id IS NULL');
        }

        //2、cat快速分类查找
        //加入的校友会
        if ($cat == 'joinaa') {
            if (!$_uid) {
                $fixed->addWhere('u.aa_id=', 0);
                $unit->addWhere('u.aa_id=', 0);
            } else {
                $joinaa_ids = Model_User::aaIds($_uid);
                $fixed->andWhereIn('u.aa_id', $joinaa_ids);
                $unit->andWhereIn('u.aa_id', $joinaa_ids);
            }
        }
        //其他校友会的
        elseif ($cat == 'otheraa') {
            if ($_uid) {
                $aa_ids = Model_User::aaIds($_uid);
                $aa_ids[] = 0;
            } else {
                $aa_ids[] = 0;
            }
            $fixed->andWhereNotIn('u.aa_id', $aa_ids);
            $unit->andWhereNotIn('u.aa_id', $aa_ids);
        }
        //我的主题
        elseif ($cat == 'mytopic') {
            if (!$_uid) {
                $unit->addWhere('u.user_id=?', 0);
            } else {
                $unit->addWhere('u.user_id=?', $_uid);
            }
        }
        //我评论过的
        elseif ($cat == 'mycomments') {
            if (!$_uid) {
                $unit->addWhere('u.user_id=?', 0);
            } else {
                $my_comment_ids = $ids = Doctrine_Query::create()
                        ->select('c.bbs_unit_id')
                        ->from('Comment c')
                        ->where('c.user_id = ?', $_uid)
                        ->addWhere('c.bbs_unit_id >0')
                        ->groupBy('c.bbs_unit_id')
                        ->orderBy('c.id DESC')
                        ->limit(60)
                        ->execute(array(), Doctrine_Core::HYDRATE_SINGLE_SCALAR);

                if (!$my_comment_ids) {
                    $bbs_unit_ids = array(0);
                } elseif (is_string($my_comment_ids)) {
                    $bbs_unit_ids = array($ids);
                } else {
                    $bbs_unit_ids = $my_comment_ids;
                }
                $unit->andWhereIn('u.id', $bbs_unit_ids);
            }
        } else {

        }

        //相关关键字
        if ($q) {
            $fixed->addWhere('u.title LIKE ?', '%' . $q . '%');
            $unit->addWhere('u.title LIKE ?', '%' . $q . '%');
        }

        //3、查询置顶话题
        $fixed_list = array();
        //只有第1页显示
        if ($display_fixed) {
            $fixed_limit = $cat == 'all' ? 1 : 3;
            $fixed_list = $fixed->addWhere('u.is_fixed = ?', TRUE)
                            ->limit($fixed_limit)
                            ->orderBy($fixed_order_by)->fetchArray();
        }

        //4、查询最新话题
        $last_list = $unit->offset($offset)
                        ->limit($limit)
                        ->orderBy($order_by)->fetchArray();

        $back = array();
        $back['fixed'] = $fixed_list;
        $back['list'] = $last_list;
        return $back;
    }

    //将话题数组转换成手机数组
    public static function createXmlArray($units) {

        $_siteurl = 'http://' . $_SERVER['HTTP_HOST'];
        $data = array();
        if (count($units) > 0) {
            $subject = Kohana::config('bbs.subject');
            foreach ($units AS $key => $u) {
                $data[$key]['id'] = $u['id'];
                $data[$key]['title'] = $u['title'];
                $data[$key]['subject'] = $subject[$u['subject']];
                $data[$key]['aa_id'] = $u['aa_id'];
                $data[$key]['aa_name'] = $u['aa_name'];
                $data[$key]['bbs_id'] = $u['bbs_id'];
                $data[$key]['bbs_name'] = '交流论坛';
                $data[$key]['is_good'] = $u['is_good'] ? 'true' : 'false';
                $data[$key]['is_fixed'] = $u['is_fixed'] ? 'true' : 'false';
                $data[$key]['uid'] = $u['user_id'];
                $data[$key]['reply_uid'] = $u['new_replyid'];
                $data[$key]['hits'] = $u['hit'] ? $u['hit'] : 0;
                $data[$key]['comments_count'] = $u['reply_num'] ? $u['reply_num'] : 0;
                $data[$key]['create_date'] = $u['create_at'];
                $data[$key]['str_create_date'] = Date::ueTime($u['create_at']);
                $data[$key]['update_date'] = $u['comment_at'] ? $u['comment_at'] : '';
                $data[$key]['str_update_date'] = $u['comment_at'] ? Date::ueTime($u['comment_at']) : '';
                $data[$key]['allow_comment'] = 'true';
                $data[$key]['statuses'] = $u['new_replyid'] ? $u['replyname'] . $data[$key]['str_update_date'] . '回复' : $u['User']['realname'] . $data[$key]['str_create_date'] . '发表';
                $avatar_id = $u['new_replyid'] ? $u['new_replyid'] : $u['user_id'];
                $avatar_sex = $u['new_replyid'] ? $u['replysex'] : $u['User']['sex'];
                $data[$key]['updater_avatar'] = $_siteurl . Model_User::avatar($avatar_id, 48, $avatar_sex);
                $data[$key]['updater_avatar_large'] = $_siteurl . Model_User::avatar($avatar_id, 128, $avatar_sex);
                $data[$key]['user']['id'] = $u['user_id'];
                $data[$key]['user']['realname'] = $u['User']['realname'];
                $data[$key]['user']['speciality'] = $u['User']['start_year'] && $u['User']['speciality'] ? $u['User']['start_year'] . '级' . $u['User']['speciality'] : $u['User']['speciality'];
                $data[$key]['user']['sex'] = $u['User']['sex'];
                $data[$key]['user']['profile_image_url'] = $_siteurl . Model_User::avatar($u['User']['id'], 48, $u['User']['sex']);
                $data[$key]['user']['avatar_large'] = $_siteurl . Model_User::avatar($u['User']['id'], 128, $u['User']['sex']);
            }
        } else {
            return '';
        }
        return $data;
    }

}