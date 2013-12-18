<?php

class Controller_Publication extends Layout_Main {

    public function before() {
        $this->template = 'layout/publication';
        parent::before();
    }

    function action_index() {
        $type = Arr::get($_GET, 'type');
        $publication = Doctrine_Query::create()
                ->select('p.*')
                ->from('Publication p')
                ->where('cover IS NOT NULL');

        if ($type) {
            $publication->addWhere('type=?', $type);
        }

        $publication->orderBy('order_num ASC,id DESC');

        $total_pub = $publication->count();

        $pager = Pagination::factory(array(
                    'total_items' => $total_pub,
                    'items_per_page' => 12,
                    'view' => 'pager/common',
        ));

        $view['type'] = $type;
        $view['pager'] = $pager;
        $view['publication'] = $publication->offset($pager->offset)
                ->limit($pager->items_per_page)
                ->fetchArray();

        $this->_title('校友刊物');
        $this->_render('_body', $view);
    }

    function action_list() {
        $pub_id = Arr::get($_GET, 'pub_id');
        $publication = Doctrine_Query::create()
                ->from('Publication')
                ->where('id=?', $pub_id)
                ->orderBy('id DESC')
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

        $columns = Doctrine_Query::create()
                ->from('PubColumn')
                ->where('pub_id=?', $pub_id)
                ->orderBy('col_id ASC')
                ->fetchArray();

        foreach ($columns AS $key => $c) {
            $columns[$key]['article'] = Doctrine_Query::create()
                    ->select('id,title,page')
                    ->from('PubContent')
                    ->where('pub_id=? AND col_id=?', array($pub_id, $c['col_id']))
                    ->orderBy('page ASC')
                    ->fetchArray();
        }

        $this->_title($publication['name'] . $publication['issue']);
        $this->_render('_body', compact('publication', 'columns', 'pub_id'));
    }

    //浏览文章
    function action_article() {
        $id = Arr::get($_GET, 'id');
        $article = Doctrine_Query::create()
                ->from('PubContent c')
                ->leftJoin('c.PubColumn col')
                ->leftJoin('c.Publication p')
                ->where('c.id=?', $id)
                ->addWhere('col.pub_id=p.id')
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

        $more_articles = Doctrine_Query::create()
                ->select('c.id,c.title,c.pub_id,c.col_id')
                ->from('PubContent c')
                ->where('c.pub_id=? AND c.col_id=?', array($article['pub_id'], $article['col_id']))
                ->addWhere('c.id <>?', $article['id'])
                ->orderBy('c.page ASC')
                ->fetchArray();

        $view['article'] = $article;
        $view['more_articles'] = $more_articles;
        $this->_render('_body', $view);
    }

    //电子信息报
    function action_eleReport() {
        $q = Arr::get($_GET, 'q');
        $report = Doctrine_Query::create()
                ->select('id,title,create_at,issue')
                ->from('EleReport')
                ->orderBy('create_at DESC');

        $total_report = $report->count();

        $pager = Pagination::factory(array(
                    'total_items' => $total_report,
                    'items_per_page' => 20,
                    'view' => 'pager/common',
        ));

        $view['q'] = $q;
        $view['pager'] = $pager;
        $view['report'] = $report->offset($pager->offset)
                ->limit($pager->items_per_page)
                ->fetchArray();

        $this->_title('北航校友电子信息报');
        $this->_render('_body', $view);
    }

    function action_subscribe() {
        $id = Arr::get($_GET, 'id');
        $view['all'] = Doctrine_Query::create()
                ->select('c.id,c.title,c.create_at')
                ->from('Content c')
                ->leftJoin('c.ContentCategory cat')
                ->where('cat.name="订阅芳名录"')
                ->orderBy('c.id DESC')
                ->fetchArray();

        if (!$id) {
            $view['content'] = Doctrine_Query::create()
                    ->from('Content c')
                    ->leftJoin('c.ContentCategory cat')
                    ->where('cat.name="订阅芳名录"')
                    ->orderBy('c.create_at DESC')
                    ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
        } else {
            $view['content'] = Doctrine_Query::create()
                    ->from('Content c')
                    ->where('c.id=' . $id)
                    ->orderBy('c.create_at DESC')
                    ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
        }

        $this->_title('订阅芳名录');
        $this->_render('_body', $view);
    }

    //征订征稿
    function action_callForPapers() {
        $view['content'] = Doctrine_Query::create()
                ->from('Content c')
                ->leftJoin('c.ContentCategory cat')
                ->where('cat.name="刊物征订征稿"')
                ->addWhere('c.title="《北航校友》征稿征订启事"')
                ->orderBy('c.create_at DESC')
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

        $this->_title('《北航校友》征稿征订启事');
        $this->_render('_body', $view);
    }

    function action_reportView() {
        $this->auto_render = False;
        $id = Arr::get($_GET, 'id');
        $report = Doctrine_Query::create()
                ->from('EleReport')
                ->where('id=?', $id)
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

        if ($report['content_path']) {
            $content = Remote::get(URL::base(TRUE, 'http') . $report['content_path']);
            $content = mb_convert_encoding($content, "UTF-8", "GBK");
        } else {
            $content = '很抱歉，暂时还没有上传内容!';
        }

        echo $content;
    }

    function action_reportFrameView() {
        $this->auto_render = False;
        $id = Arr::get($_GET, 'id');
        $report = Doctrine_Query::create()
                ->from('EleReport')
                ->where('id=?', $id)
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

        if ($report['content_path']) {
            $content = Remote::get(URL::base(TRUE, 'http') . $report['content_path']);
            $content = mb_convert_encoding($content, "UTF-8", "GBK");
        } else {
            $content = '很抱歉，暂时还没有上传内容!';
        }

        echo $content;
    }

    function report_filter($str) {
        //$str = preg_replace("/<xml>(.*)<\/xml>/si", '', $str);
        $str = preg_replace("/<title>(.*)<\/title>/si", '', $str);
        $str = preg_replace("/<script>(.*)<\/script>/si", '', $str);
        $str = preg_replace("/<style>(.*)<\/style>/si", '', $str);
        $str = preg_replace("/<link>(.*)<\/link>/si", '', $str);
        $str = preg_replace("/<head>(.*)<\/head>/si", '', $str);
        $str = preg_replace("/<o:([^>]+)<\/o:[^>]+/si", '', $str);
        $str = preg_replace("/<v:([^>]+)<\/v:[^>]+/si", '', $str);
        $str = preg_replace("/<(\/?html.*?)>/si", '', $str);
        $str = preg_replace("/<(\/?span.*?)>/si", '', $str);
        $str = preg_replace("/<(\/?v:shape.*?)>/si", '', $str);
        $str = preg_replace("/<(\/?body.*?)>/si", '', $str);
        $str = preg_replace("/<(\/?u.*?)>/si", '', $str);
        $str = preg_replace('/v:shapes=".*"/i', '', $str);
        $str = preg_replace('/align=center/i', '', $str);
        $str = preg_replace("@<![^>]+if[^>]+>@", '', $str);
        return $str;
    }

    function url_trans($str) {
        // 新闻
        preg_match_all("@http://zuaa.+x[w|y|s]{1}[^>]+>([^<]+)@", $str, $matches);
        $search_keys = $matches[1];
        foreach ($matches[0] as $i => $match) {
            //$q = urlencode($search_keys[$i]);
            $q = $search_keys[$i];
            $rematch = preg_replace("@http://[^\"|\']+@", URL::site('search?q=' . $q . '&for=news&from=eleReport'), $match);
            $str = str_replace($match, $rematch, $str);
        }
        // 论坛
        preg_match_all("@http://zuaa.+bbs[^>]+>([^<]+)@", $str, $matches);
        $search_keys = $matches[1];
        foreach ($matches[0] as $i => $match) {
            //$q = urlencode($search_keys[$i]);
            $q = $search_keys[$i];
            $rematch = preg_replace("@http://[^\"|\']+@", URL::site('search?q=' . $q . '&for=bbs&from=eleReport'), $match);
            $str = str_replace($match, $rematch, $str);
        }
        return $str;
    }

    //邮件退订
    function action_unsubscribe() {

        $email = Arr::get($_GET, 'email');
        $key = Arr::get($_GET, 'key');
        $user_id = Arr::get($_GET, 'uid');
        $view['err'] = '';
        $md5 = md5(md5($email));

        if ($md5 != $key) {
            $view['err'] = '退订失败，校验码错误!';
        } elseif ($email AND $user_id) {
            $unsubscribe = Doctrine_Query::create()
                    ->from('UnsubscribeEmail')
                    ->where('email=?', $email)
                    ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
            if (!$unsubscribe) {
                $unsubscribe = new UnsubscribeEmail();
                $unsubscribe->email = $email;
                $unsubscribe->user_id = $user_id;
                $unsubscribe->create_at = date('Y-n-d H:i:s');
                $unsubscribe->save();
            } else {
                $view['err'] = '您已经退订过了，不需要再次退订啦!';
            }
        } else {
            $view['err'] = '退订失败，请提供邮箱或用户id信息!';
        }

        $this->_render('_body', $view);
    }

}

?>
