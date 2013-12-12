<?php
/**
  +-----------------------------------------------------------------
 * 名称：相册模型
 * 版本：1.0
 * 版权：www.usho.cn
 * 作者：zhaojiangang
 * 最后更新日期：12-10-12 上午10:27
  +-----------------------------------------------------------------
 */
class Model_Album {
    const EXT = '.jpg';
    const PICS_DIR = 'static/upload/pic/';
    const PIC_MAX_WIDTH = 800;
    const PIC_MAX_HEIGHT =600;

    static $up_rule = array(
        'Upload::valid' => array(),
        'Upload::not_empty' => array(),
        'Upload::type' => array('Upload::type' => array('gif', 'jpg', 'jpeg', 'png')),
        'Upload::size' => array('3M')
    );

    public static function isExist($params) {
        $album = Doctrine_Query::create()->from('Album a');

        foreach ($params as $field => $value) {
            $album->where('a.' . $field . ' = ?', $value);
        }

        return (bool) $album->count();
    }

    public static function get($params) {
        $aa_id = Arr::get($params, 'aa_id');
        $club_id = Arr::get($params, 'club_id');
        $user_id = Arr::get($params, 'user_id');
        $event_id = Arr::get($params, 'event_id');

        $album = Doctrine_Query::create()
                ->from('Album a')
                ->leftJoin('a.Pics p')
                ->orderBy('a.update_at DESC');

        if ($aa_id) {
            $album->where('a.aa_id = ?', $aa_id);
        }

        if ($club_id) {
            $album->where('a.club_id = ?', $club_id);
        }

        if ($user_id) {
            $album->where('a.user_id = ?', $user_id);
        }

        if ($event_id) {
            $album->where('a.event_id = ?', $event_id);
        }

        return $album->fetchArray();
    }

    //更新相册照片数量
    public static function updatePicNum($album_id) {
        $total = Doctrine_Query::create()
                ->from('Pic')
                ->where('album_id=?', $album_id)
                ->count();

        $album = Doctrine_Query::create()
                ->from('Album')
                ->where('id= ?', $album_id)
                ->fetchOne();
        if ($album) {
            $album['pic_num'] = $total;
            $album['update_at'] = date('Y-m-d H:i:s');
            $album->save();
        }
        return $total;
    }

    //更新评论总数
    public static function updateCommentNum($pic_id) {
        $total_comments = Doctrine_Query::create()
                ->from('Comment')
                ->where('pic_id = ?', $pic_id)
                ->count();
        $pic = Doctrine_Query::create()
                ->from('Pic')
                ->where('id = ?', $pic_id)
                ->fetchOne();
        $pic['comments_num'] = $total_comments;
        $pic->save();
        return $total_comments;
    }

}