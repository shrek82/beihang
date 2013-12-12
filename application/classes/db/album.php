<?php

/**
  +-----------------------------------------------------------------
 * 名称：相册模型
 * 版本：1.0
 * 版权：www.usho.cn
 * 作者：zhaojiangang
 * 最后更新日期：12-10-12 上午9:46
  +-----------------------------------------------------------------
 */
class Db_Album {

    //获取相册
    public static function getAlbums($condition) {

        $page = isset($condition['page']) ? $condition['page'] : 1;

        $q = isset($condition['q']) ? $condition['q'] : null;

        $page_size = isset($condition['page_size']) ? $condition['page_size'] : 25;

        $offset = ($page - 1) * $page_size;

        //是否显示空相册
        $empty_albums = isset($condition['empty_albums']) ? $condition['empty_albums'] : false;

        //筛选
        $list = isset($condition['list']) ? $condition['list'] : 'new';

        //是否分页统计
        $count = isset($condition['count']) ? $condition['count'] : false;

        // 校友相册
        $album = Doctrine_Query::create()
                ->select('a.*')
                ->addSelect('(SELECT p.img_path FROM Pic p WHERE p.album_id = a.id ORDER BY p.upload_at DESC LIMIT 1) AS img_path')
                ->addSelect(DB::expr('IF(a.order_num >0,a.order_num,100) AS sys_order_num'))
                ->from('Album a');

        //显示所有相册，包含空相册
        if ($empty_albums OR $q) {
            $album->where('a.id>0');
        } else {
            $album->where('a.pic_num>0');
        }

        //地方校友会的
        if (isset($condition['aa_id'])) {
            $album->addWhere('a.aa_id = ' . $condition['aa_id']);
        }

        //俱乐部的
        if (isset($condition['club_id'])) {
            $album->addWhere('a.club_id = ' . $condition['club_id']);
        }

        //用户的
        if (isset($condition['user_id'])) {
            $album->addWhere('a.user_id = ' . $condition['user_id']);
        }

        //班级的
        if (isset($condition['classroom_id'])) {
            $album->addWhere('a.classroom_id = ' . $condition['classroom_id']);
        }

        //活动的
        if (isset($condition['event_id'])) {
            $album->addWhere('a.event_id = ' . $condition['event_id']);
        }

        //搜索
        if ($q) {
            $album->andWhere('a.name LIKE ?', '%' . $q . '%');
        }

        //再次筛选
        if ($list == 'aa') {
            $album->andWhere('a.event_id<=0')->andWhere('a.club_id<=0');
        }
        //活动相册
        elseif ($list == 'event') {
            $album->andWhere('a.event_id>0');
        }
        //俱乐部相册
        elseif ($list == 'club') {
            $album->andWhere('a.club_id>=0')->andWhere('a.event_id=0');
        }
        //最新
        elseif ($list == 'new') {

        }
        //精选
        elseif ($list == 'all') {

        } else {

        }

        $pager = null;
        $total_num = null;
        if ($count) {
            $total_num = $album->count();
            $pager = Pagination::factory(array(
                        'total_items' => $total_num,
                        'items_per_page' => $page_size,
                        'view' => 'pager/common',
            ));
        }

        $view = array();
        $view['pager'] = $pager;
        $view['total_num'] = $total_num;
        $view['albums'] = $album->offset($offset)
                ->limit($page_size)
                ->orderBy('sys_order_num ASC,a.update_at DESC')
                ->fetchArray();

        return $view;
    }

    //获取某一相册照片
    public static function getPics($album_id, $limit = 0, $user_info = false, $order_by = 'DESC') {

        if (!$album_id) {
            return false;
        }
        $query = DB::select(DB::expr('p.id,p.user_id,p.album_id,p.name,p.img_path,p.upload_at,p.comments_num'))
                ->from(array('pic', 'p'));
        if ($user_info) {
            $query = $query->select(DB::expr('u.realname AS realname'))->join(array('user', 'u'))->on('p.user_id', '=', 'u.id');
        }
        $query = $query->where('album_id', '=', $album_id);
        if ($limit > 0) {
            $query = $query->limit($limit);
        }
        $pics = $query->order_by('upload_at', $order_by)->execute()->as_array();
        return $pics;
    }

    //删除相册
    public static function delete($condition) {

        $query = DB::select()->from('album');

        if (isset($condition['id']) AND $condition['id']) {
            $query = $query->where('id', '=', $condition['id']);
        } elseif (isset($condition['user_id']) AND $condition['user_id']) {
            $query = $query->where('user_id', '=', $condition['user_id']);
        } elseif (isset($condition['event_id']) AND $condition['event_id']) {
            $query = $query->where('event_id', '=', $condition['event_id']);
        } elseif (isset($condition['aa_id']) AND $condition['aa_id']) {
            $query = $query->where('aa_id', '=', $condition['aa_id']);
        } elseif (isset($condition['club_id']) AND $condition['club_id']) {
            $query = $query->where('club_id', '=', $condition['club_id']);
        } elseif (isset($condition['classroom_id']) AND $condition['classroom_id']) {
            $query = $query->where('classroom_id', '=', $condition['classroom_id']);
        } elseif (isset($condition['special_id']) AND $condition['special_id']) {
            $query = $query->where('special_id', '=', $condition['special_id']);
        } elseif (isset($condition['event_id']) AND $condition['event_id']) {
            $query = $query->where('event_id', '=', $condition['event_id']);
        } else {
            return False;
        }

        $albums = $query->execute()->as_array();
        if ($albums) {
            $album_ids = array();
            foreach ($albums as $a) {
                $album_ids[] = $a['id'];
            }
            if (count($album_ids) > 0) {
                DB::delete('pic')->where('album_id', 'IN', $album_ids)->execute();
                DB::delete('album')->where('id', 'IN', $album_ids)->execute();
            }
        }
    }

}

?>