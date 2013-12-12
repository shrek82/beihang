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
class Model_Bbs{

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
                    ->where('b.aa_id','= ', $aa_id)
                    ->where('b.parent_id','<>',-1)
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

    # 获取BBS ID 数组

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
    public static function updateCommentNum($unit_id, $new_replyid) {
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
        $unit['comment_at'] = date('Y-m-d H:i:s');
        $unit['new_replyid'] = $new_replyid;
        $unit->save();
        return $total_comments;
    }

}