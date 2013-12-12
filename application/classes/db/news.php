<?php
/**
  +-----------------------------------------------------------------
 * 名称：新闻模型
 * 版本：1.0
 * 版权：www.usho.cn
 * 作者：zhaojiangang
 * 最后更新日期：12-10-12 上午9:46
  +-----------------------------------------------------------------
 */
class Db_News {

    //手机版推荐新闻
    public static function getMobileRecommend($condition) {

        $recommend = DB::select(DB::expr('n.id,n.title,n.create_at,n.short_title,n.images,n.recommended_path,c.aa_id AS aa_id,a.name AS aa_name'))
                ->from(array('news', 'n'))
                ->join(array('news_category', 'c'))
                ->on('c.id', '=', 'n.category_id')
                ->join(array('aa', 'a'))
                ->on('a.id', '=', 'c.aa_id');

        //校友总会
        if ($condition['cat'] == 'main') {
            $recommend = $recommend->where('c.aa_id', '=', 0);
        }
        //加入的校友会的
        elseif ($condition['cat'] == 'joined') {
            if (!$condition['uid']) {
                $recommend = $recommend->where('c.aa_id', '=', -1);
            } else {
                $recommend = $recommend->where('c.aa_id', 'in', Model_User::aaIds($condition['uid']));
            }
        }
        //其他校友会
        elseif ($condition['cat'] == 'other') {
            if ($condition['uid']) {
                $aa_ids = Model_User::aaIds($condition['uid']);
                $aa_ids[] = 0;
            } else {
                $aa_ids[] = 0;
            }
            $recommend = $recommend->where('c.aa_id', 'not in', $aa_ids);
        }
        //默认为总会的
        else {
            $recommend = $recommend->where('c.aa_id', '=', 0);
        }

        $recommend = $recommend->where('n.is_pic', '=', 1)
                ->where('n.is_focus', '=', 1)
                ->limit($condition['limit'])
                ->order_by('n.id', 'DESC')
                ->execute()
                ->as_array();

        foreach ($recommend AS $key => $r) {
            $image_path = null;
            if ($r['recommended_path']) {
                $image_path = $r['recommended_path'];
            } elseif ($r['images']) {
                $images = unserialize($r['images']);
                if (isset($images[0])) {
                    $image_path = Common_Global::getImageBysuffix($images[0], 'thumbnail');
                }
            } else {
                $image_path = 'static/images/recommended_image_background.png';
            }
            unset($recommend[$key]['recommended_path']);
            unset($recommend[$key]['images']);
            $recommend[$key]['aa_name'] = $r['aa_name'] ? $r['aa_name'] : '校友总会';
            $image_path = $image_path ? $condition['siteurl'] . '/' . $image_path : $condition['siteurl'] . '/' . 'static/images/recommended_image_background.png';
            $recommend[$key]['image_path'] = $image_path;
        }

        return $recommend;
    }

    //总会焦点新闻
    public static function getFocus($limit = 3) {
        $news = DB::select(DB::expr('id,title,short_title,img_path'))
                ->from(DB::expr('news'))
                ->where('is_draft', '=', 0)
                ->where('is_focus', '=', 1)
                ->order_by('id', 'desc')
                ->limit($limit)
                ->execute()
                ->as_array();
        return $news;
    }

    //获取手机版列表新闻
    public static function getMobileList($condition) {

        $query = DB::select(DB::expr('n.id,n.title,n.short_title,n.intro,n.create_at AS create_date,n.is_pic AS is_pic,n.is_top AS is_fixed,n.author_name AS author,n.small_img_path AS thumbnail_pic,n.source,n.is_comment AS allow_comment,n.hit AS hits,n.comments_num AS comments_count'))
                ->select(DB::expr('a.id AS aa_id,a.name as aa_name,c.id AS category_id,c.aa_id'))
                ->from(array('news', 'n'))
                ->join(array('news_category', 'c'))
                ->on('c.id', '=', 'n.category_id')
                ->join(array('aa', 'a'))
                ->on('a.id', '=', 'c.aa_id');

        //校友总会
        if ($condition['cat'] == 'main') {
            $query = $query->where('c.aa_id', '=', 0);
        }
        //加入的校友会的
        elseif ($condition['cat'] == 'joined') {
            if (!$condition['uid']) {
                return array();
            }
            $query = $query->where('c.aa_id', 'in', Model_User::aaIds($condition['uid']));
        }
        //指定校友会
        elseif ((int) $condition['cat'] > 0) {
            $query = $query->where('c.aa_id', '=', (int) $condition['cat']);
        }
        //其他校友会
        else {
            if ($condition['uid']) {
                $aa_ids = Model_User::aaIds($condition['uid']);
                $aa_ids[] = '0';
            } else {
                $aa_ids[] = '0';
            }
            $query = $query->where('c.aa_id', 'not in', $aa_ids);

        }

        $query = $query->where('c.is_public', '=', 1)
                ->where('n.is_release', '=', 1)
                ->where('n.is_pic', '=', 1)
                ->where('n.is_draft', '=', 0);

        if ($condition['since_id']) {
            $query = $query->where('c.id', '>', $condition['since_id']);
        }

        if ($condition['max_id']) {
            $query = $query->where('n.id<?', $condition['max_id']);
        }

        $news = $query->offset($condition['offset'])
                ->limit($condition['limit'])
                ->order_by('n.id', 'DESC')
                ->execute()
                ->as_array();

        foreach ($news AS $key => $n) {
            $news[$key]['aa_name'] = $n['aa_name'];
            $news[$key]['intro'] = Text::limit_chars($n['intro'], 40, '...');
            $news[$key]['is_fixed'] = $n['is_fixed'] ? 'true' : 'false';
            $news[$key]['allow_comment'] = $n['allow_comment'] ? 'true' : 'false';
            $news[$key]['is_pic'] = $n['is_pic'] ? 'true' : 'false';
            $news[$key]['thumbnail_pic'] = $n['thumbnail_pic'] ? $condition['siteurl'] . '/' . $n['thumbnail_pic'] : $condition['siteurl'] . '/static/images/news_icon.gif';
            $news[$key]['browser_url'] = $condition['siteurl'] . '/news/view?id=' . $n['id'];
        }

        return $news;
    }

}

?>
