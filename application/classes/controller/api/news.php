<?php

//新闻
class Controller_Api_News extends Layout_Mobile {

    function before() {
        parent::before();
    }

    //推荐焦点图片
    function action_recommended() {
        $condition['limit'] = $this->getParameter('limit', 1);
        $condition['cat'] = $this->getParameter('cat', 'main');
        $condition['uid'] = $this->_uid;
        $condition['siteurl'] = $this->_siteurl;

        //总会新闻从缓存读取
        if ($condition['cat'] == 'mains') {
            $recommend = $this->_cache->get('mobile_recommended_news_main');
           if (!$recommend) {
                $recommend = Db_News::getMobileRecommend($condition);
                $this->_cache->set('mobile_recommended_news_main', $recommend, 600);
          }
        } else {
            $recommend = Db_News::getMobileRecommend($condition);
        }

        $this->response($recommend, 'list', null);
    }

    //获取新闻分类
    function action_category() {
        $data = array();
        $data[] = array(
            'name' => '总会新闻',
            'parameter' => 'main',
        );
        $data[] = array(
            'name' => '加入的校友会',
            'parameter' => 'joined',
        );
        $data[] = array(
            'name' => '其他校友会',
            'parameter' => 'other',
        );
        $this->response($data, 'list', 'list');
    }

    //获取新闻列表
    function action_index() {
        $condition=array();
        $condition['limit'] = $this->getParameter('limit', 15);
        $condition['page'] = $this->getParameter('page', 1);
        $condition['max_id'] = $this->getParameter('max_id');
        $condition['since_id'] = $this->getParameter('since_id');
        $condition['cat'] = $this->getParameter('cat', 'main');
        $condition['offset'] = ($condition['page'] - 1) * $condition['limit'];
        $condition['uid'] = $this->_uid;
        $condition['siteurl'] = $this->_siteurl;

        //返回json数据
        $data = array();
        $data['column'] = '校友新闻';
        $data['description'] = '';

        //校友总会
        if ($condition['cat'] == 'main') {
            $data['description'] = '总会新闻';
        }
        //加入的校友会的
        elseif ($condition['cat'] == 'joined') {
            if (!$condition['uid']) {
                $this->error('您还没有登录或加入任何地方校友会');
            } else {
                $data['description'] = '我加入的校友会新闻';
            }
        }
        //指定校友会
        elseif ((int) $condition['cat'] > 0) {
            $data['description'] = '指定分类新闻';
        }
        //其他校友会
        else {
            $data['description'] = '其他校友会新闻';
        }

        //总会新闻从缓存读取
        if ($condition['cat'] == 'main' AND $condition['page']==1) {
            $news = $this->_cache->get('mobile_news_' . $condition['cat']);
            if (!$news) {
                $news = Db_News::getMobileList($condition);
                $this->_cache->set('mobile_news_' . $condition['cat'],$news,600);
            }
        }
        //加入的校友会新闻和从其他校友会新闻从数据库读取最新数据
        else {
            $news = Db_News::getMobileList($condition);
        }

        $data['list'] = $news;
        $this->response($data, null, null);
    }

    /**
      +------------------------------------------------------------------------------
     * 查看新闻详情
      +------------------------------------------------------------------------------
     */
    function action_view() {

        $id = $this->getParameter('id');

        $news = Doctrine_Query::create()
                ->select('n.*,c.name AS category_name,c.aa_id AS aa_id,a.name AS aa_name')
                ->from('News n')
                ->leftJoin('n.NewsCategory c')
                ->leftJoin('c.Aa a')
                ->where('n.id = ?', $id)
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

        if (!$news) {
            $this->error('新闻不存在或已经被删除！');
        } else {
            Model_News::hit($id);
        }

        $data = array();

        if ($news) {
            $data['id'] = $news['id'];
            $data['title'] = $news['title'];
            $data['short_title'] = $news['short_title'];
            $data['vice_title'] = $news['vice_title'];
            $data['source'] = $news['source'] ? $news['source'] : '';
            $data['aa_name'] = $news['aa_name'] ? $news['aa_name'] : '校友总会';
            $data['author'] = $news['author_name'];
            $data['is_pic'] = $news['is_pic'] ? 'true' : 'false';
            $data['is_fixed'] = $news['is_top'] ? 'true' : 'false';
            $data['allow_comment'] = $news['is_comment'] ? 'true' : 'false';
            $data['comments_count'] = isset($news['comments_num']) ? $news['comments_num'] : 0;
            $data['hits'] = $news['hit'];
            $data['thumbnail_pic'] = 'http://' . $_SERVER['HTTP_HOST'] . '/' . $news['small_img_path'];
            $data['intro'] = Text::limit_chars(trim($news['intro'], 40));
            $htmlAndPics = Common_Global::standardHtmlAndPics($news['content'], $data['title']);
            $news['content'] = $htmlAndPics['html'];
            $data['content'] = View::factory('api/news/view', array('news' => $news));
            $data['pics'] = $htmlAndPics['pics'];
            $data['create_date'] = $news['create_at'];
            unset($news['source']);
        }

        //echo $data['content'];
        $this->response($data, null, null);
    }

}

?>
