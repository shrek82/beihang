<?php
/**
  +-----------------------------------------------------------------
 * 名称：新闻模型
 * 版本：1.0
 * 版权：www.usho.cn
 * 作者：zhaojiangang
 * 最后更新日期：12-10-12 上午10:48
  +-----------------------------------------------------------------
 */
class Model_News {
    const SP = ' '; // 关键字分隔符
    const FOCUS_PATH = 'static/upload/content/';
    const ATTACHED_PATH = 'static/upload/attached/';
    static $up_rule = array(
        'Upload::valid' => array(),
        'Upload::not_empty' => array(),
        'Upload::type' => array('Upload::type' => array('gif', 'jpg', 'jpeg', 'png')),
        'Upload::size' => array('1M')
    );
    static $attached_rule = array(
        'Upload::valid' => array(),
        'Upload::not_empty' => array(),
        'Upload::type' => array('Upload::type' => array('bmp', 'gif', 'jpg', 'jpeg', 'png', 'pdf', 'doc', 'xls', 'rar', 'zip', 'txt', 'mdb', 'docx', 'flv', 'xlsx')),
        'Upload::size' => array('20M')
    );

    public static function digRank($limit=10) {
        return Doctrine_Query::create()
                        ->select('n.title,n.create_at,n.title_color,c.name,c.id')
                        ->from('News n')
                        ->where('n.is_release = ?', TRUE)
                        ->andWhere('n.create_at > ?', date('Y-m-d H:i:s', (time() - Date::MONTH)))
                        ->orderBy('dig DESC')
                        ->limit($limit)
                        ->fetchArray();
    }

    //获取新闻
    public static function hitRank($limit=10) {
        return Doctrine_Query::create()
                        ->select('n.title,n.create_at,n.title_color,c.name,c.id')
                        ->from('News n')
                        ->where('n.is_release = ?', TRUE)
                        ->andWhere('n.create_at > ?', date('Y-m-d H:i:s', (time() - Date::MONTH)))
                        ->orderBy('hit DESC')
                        ->limit($limit)
                        ->fetchArray();
    }

    //获取新闻列表
    public static function get($condition) {

        $aa_id=isset($condition['aa_id'])?$condition['aa_id']:null;
        $club_id=isset($condition['club_id'])?$condition['club_id']:null;
        $limit=isset($condition['limit'])?$condition['limit']:10;
        $cid=isset($condition['cid'])?$condition['cid']:null;

        $news = Doctrine_Query::create()
                ->select('n.title,n.is_fixed,n.create_at,n.title_color,c.name,c.id,n.is_pic,n.is_top,n.is_fixed,c.id,c.name AS category_name')
                ->from('News n')
                ->leftJoin('n.NewsCategory c')
                ->where('c.is_public = ?', TRUE)
                ->andWhere('n.is_release = ?', TRUE)
                ->andWhere('n.is_draft = ?', FALSE)
                ->orderBy('is_fixed DESC, n.create_at DESC');

        //总会
        if ($aa_id == 'main') {
            $news->andWhere('c.aa_id = 0 AND  c.id<>5');
        }
        //所有地方新闻
        elseif ($aa_id=== 'aa') {
            $news->addSelect('a.name,a.id')
                    ->leftJoin('c.Aa a')
                    ->andWhere('c.aa_id > 0')
                    ->removeDqlQueryPart('orderby')
                    ->orderBy('n.create_at DESC');
        }
        //指定地方校友会
        elseif (is_numeric($aa_id) AND $aa_id > 0) {
            $news->andWhere('c.aa_id = ?', $aa_id)
                    ->removeDqlQueryPart('orderby')
                    ->orderBy('n.is_fixed DESC,n.create_at DESC');
        }
        //指定俱乐部
        elseif (is_numeric($club_id) AND $club_id > 0) {
            $news->andWhere('c.club_id = ?', $club_id)
                    ->removeDqlQueryPart('orderby')
                    ->orderBy('n.is_fixed DESC,n.create_at DESC');
        }
        //所有新闻
        else {
            $news->andWhere('c.aa_id >= 0');
        }

        //指定某些新闻id
        if ($cid) {
            if (is_array($cid)) {
                $news->andWhereIn('c.id', $cid);
            } else {
                $news->andWhere('c.id = ?', $cid);
            }
        }

        return $news->limit($limit)->fetchArray();
    }

    # 访问记录

    public static function dig($news_id) {
        $session = Session::instance();
        $log = $session->get('news_digg', array());
        if (!in_array($news_id, $log)) {
            Doctrine_Query::create()
                    ->update('News')
                    ->set('dig', 'dig +1')
                    ->where('id = ?', $news_id)
                    ->execute();

            $log[] = $news_id;
            $session->set('news_digg', $log);
        }
    }

    # 访问记录

    public static function hit($news_id) {
        Doctrine_Query::create()
                ->update('News')
                ->set('hit', 'hit +1')
                ->where('id = ?', $news_id)
                ->execute();
    }

    static function size($rate) {
        $size = 12;

        if ($rate > 5 && $rate <= 10)
            $size = 13;
        if ($rate > 10 && $rate <= 20)
            $size = 14;
        if ($rate > 20 && $rate <= 30)
            $size = 14;
        if ($rate > 30)
            $size = 15;

        return $size;
    }

    static function rand($from='NewsTags', $limit=10) {
        $tag = Doctrine_Query::create()
                ->select('count(t.id) AS rate, t.name, random() AS rand')
                ->from($from . ' t')
                ->groupBy('t.name')
                ->orderBy('rand')
                ->limit($limit)
                ->fetchArray();

        return $tag;
    }

    static function relate($news, $limit=5, $aa_id=null) {
        if (count($news['Tags']) == 0) {
            $in_tags = array(0);
        } else {
            foreach ($news['Tags'] as $tag) {
                $in_tags[] = $tag['name'];
            }
        }

        $relate = Doctrine_Query::create()
                ->from('News n')
                ->select('n.id,n.title,n.create_at')
                ->leftJoin('n.Tags t')
                ->whereIn('t.name', $in_tags);
        if ($aa_id > 0) {
            $relate = $relate->leftJoin('n.NewsCategory c')
                    ->andWhere('c.aa_id = ?', $aa_id);
        }
        $relate = $relate->andWhere('n.id != ?', $news['id'])
                ->orderBy('n.create_at DESC')
                ->limit($limit)
                ->fetchArray();

        return $relate;
    }

    static function prev($cur_id) {
        $news = Doctrine_Query::create()
                ->from('News')
                ->where('id < ?', $cur_id)
                ->andWhere('is_release = ?', TRUE)
                ->orderBy('id DESC')
                ->limit(1)
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

        return $news;
    }

    static function next($cur_id) {
        $news = Doctrine_Query::create()
                ->from('News')
                ->where('id > ?', $cur_id)
                ->andWhere('is_release = ?', TRUE)
                ->orderBy('id ASC')
                ->limit(1)
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

        return $news;
    }

    static function tagArray($tag_str) {
        if (!$tag_str)
            return array();

        $tags = explode(self::SP, $tag_str);
        $tag_arr = array();

        // 过滤重复标签
        $tags = array_unique($tags);

        foreach ($tags as $tag) {
            if (trim($tag) != '')
                $tag_arr[]['name'] = $tag;
        }
        return $tag_arr;
    }

    static function tagNames($news_id=0) {
        $tags = Doctrine_Query::create()->from('NewsTags')
                ->where('news_id = ?', $news_id)
                ->fetchArray();

        if (count($tags) == 0)
            return '';

        $temp_arr = array();
        foreach ($tags as $tag) {
            $temp_arr[] = $tag['name'];
        }
        return implode(self::SP, $temp_arr);
    }

    //更新评论总数
    public static function updateCommentNum($news_id) {
        $total_comments = Doctrine_Query::create()
                ->from('Comment')
                ->where('news_id = ?', $news_id)
                ->count();

        $news = Doctrine_Query::create()
                ->from('News')
                ->where('id = ?', $news_id)
                ->fetchOne();
        $news['comments_num'] = $total_comments;
        $news->save();
        return $total_comments;
    }

}