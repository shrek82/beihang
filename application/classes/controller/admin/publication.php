<?php

//校友刊物管理中心

class Controller_Admin_Publication extends Layout_Admin {

    public function before() {
        parent::before();
        $leftbar_links = array(
            'admin_publication/index' => '期刊管理',
            'admin_publication/pubForm' => '新增期刊',
            'admin_publication/article' => '文章管理',
            'admin_publication/import' => '文章导入',
            'admin_publication/eleReport' => '电子信息报',
        );
        $this->_render('_body_left', compact('leftbar_links'), 'admin/leftbar');
    }

    //期刊管理
    function action_index() {
        $type = Arr::get($_GET, 'type');
        $page = Arr::get($_GET, 'page');
        $publication = Doctrine_Query::create()
                ->select('p.*')
                ->from('Publication p');

        if ($type) {
            $publication->where('type=?', $type);
        }

        $publication->orderBy('order_num ASC,id DESC');

        $total_pub = $publication->count();

        $pager = Pagination::factory(array(
                    'total_items' => $total_pub,
                    'items_per_page' => 15,
                    'view' => 'pager/common',
        ));

        $view['type'] = $type;
        $view['page'] = $page;
        $view['pager'] = $pager;
        $view['publication'] = $publication->offset($pager->offset)
                ->limit($pager->items_per_page)
                ->fetchArray();

        $this->_title('期刊管理');
        $this->_render('_body', $view);
    }

    //增加及修改期刊
    function action_pubForm() {
        $pub_type = Model_Publication::$pub_type;
        $type = Arr::get($_POST, 'type');
        $err = '';
        $cover_path = '';
        $pdf_path = '';
        $id = Arr::get($_GET, 'id', 0);
        $file_name = date("YmdHis");
        $publication = Doctrine_Query::create()
                ->from('Publication')
                ->where('id = ?', $id)
                ->fetchOne();
        if ($_POST) {
            $valid = Validate::setRules($_POST, 'publication');
            $post = $valid->getData();
            if (!$valid->check()) {
                $view['err'] .= $valid->outputMsg($valid->errors('validate'));
            } else {
                //保存附件
                if ($cover_path) {
                    $post['cover'] = $cover_path;
                }
                // 保存修改内容
                if ($publication) {
                    unset($post['id']);
                    $publication->synchronizeWithArray($post);
                    $publication->save();
                }
                //添加新纪录
                else {
                    $publication = new Publication();
                    $publication->fromArray($post);
                    $publication->save();
                }
                // 处理完毕后刷新页面
                $this->request->redirect('admin_publication/index?type=' . $type);
            }
        }

        $this->_title('发布期刊');
        $this->_render('_body', compact('pub_type', 'err', 'publication'));
    }

    //文章管理
    function action_article() {
        $type = Arr::get($_GET, 'type');
        $pub_id = Arr::get($_GET, 'pub_id');
        $col_id = Arr::get($_GET, 'col_id');
        $publication = Doctrine_Query::create()
                ->from('Publication')
                ->orderBy('type,id DESC')
                ->limit(20)
                ->fetchArray();

        $q = Arr::get($_GET, 'q');
        $content = Doctrine_Query::create()
                ->select('c.id,c.title,c.pub_id,c.page,p.name AS pname,p.issue AS issue')
                ->addSelect('(SELECT col.name FROM PubColumn col WHERE col.pub_id=c.pub_id AND col.col_id=c.col_id LIMIT 1) AS colname')
                ->from('PubContent c');
        if ($pub_id) {
            $content->where('pub_id=?', $pub_id);
        }
        $content->leftJoin('c.Publication p')
                ->orderBy('c.pub_id DESC,c.page ASC');

        $total_content = $content->count();

        $pager = Pagination::factory(array(
                    'total_items' => $total_content,
                    'items_per_page' => 20,
                    'view' => 'pager/common',
        ));

        $view['type'] = $type;
        $view['publication'] = $publication;
        $view['pub_id'] = $pub_id;
        $view['col_id'] = $col_id;
        $view['q'] = $q;
        $view['pager'] = $pager;
        $view['content'] = $content->offset($pager->offset)
                ->limit($pager->items_per_page)
                ->fetchArray();

        $this->_title('期刊管理');
        $this->_render('_body', $view);
    }

    //添加或修改内容
    function action_articleForm() {
        $id = Arr::get($_GET, 'id', 0);
        $type = Arr::get($_POST, 'type');
        $view['err'] = '';
        $content = Doctrine_Query::create()
                ->from('PubContent')
                ->where('id = ?', $id)
                ->fetchOne();
        $view['content'] = $content;
        if ($_POST) {
            $valid = Validate::setRules($_POST, 'pubcontent');
            $post = $valid->getData();
            if (!$valid->check()) {
                $view['err'] .= $valid->outputMsg($valid->errors('validate'));
            } else {
                // 添加或修改内容
                if ($content) {
                    unset($post['id']);
                    $content->synchronizeWithArray($post);
                    $content->save();
                } else {
                    $content = new PubContent();
                    $content->fromArray($post);
                    $content->save();
                }

                // 处理完毕后刷新页面
                $this->request->redirect('admin_publication/article');
            }
        }

        $this->_render('_body', $view);
    }

    //文章导入
    function action_import() {
        $view['err'] = '';
        $pub_id = Arr::get($_POST, 'pub_id');
        $pub_id = $pub_id ? $pub_id : Arr::get($_GET, 'pub_id');
        $view['pub_id'] = $pub_id;

        $publication = Doctrine_Query::create()
                ->from('Publication')
                ->orderBy('type,id DESC')
                ->fetchArray();

        $view['publication'] = $publication;

        if ($_POST) {
            //如果存在附件
            if ($_FILES['file']['size'] > 0) {
                //上传的txt附件
                $valid = Validate::factory($_FILES);
                $valid->rules('file', Model_Publication::$up_rule);
                if (!$valid->check()) {
                    $view['err'] .= $valid->outputMsg($valid->errors('validate'));
                } else {
                    // 处理导入的文本内容
                    $path = DOCROOT . Model_Publication::TXT_PATH;
                    $file_path = $path . '/' . $_FILES['file']['name'];
                    Upload::save($_FILES['file'], $_FILES['file']['name'], $path);
                    $this->saveImport($file_path, $pub_id);
                    $this->_redirect('admin_publication/article?pub_id=' . $pub_id);
                }
            } else {
                $view['err'] = '您还没有选择txt文件，请选选择！';
            }
        }
        $this->_title('文章导入');
        $this->_render('_body', $view);
    }

//保存导入内容
    function saveImport($file_path, $pub_id) {
        $this->auto_render = FALSE;

        //一次读取失败多次读取
        for ($i = 1; $i <= 15; $i++) {
            $fcontents = file_get_contents($file_path);
            if ($fcontents) {
                break;
            }
        }

        $fcontents = mb_convert_encoding($fcontents, "UTF-8", "GBK");

        //echo '<textarea style="width:100%;height:200px">'.$fcontents.'</textarea>';
        //获取所有栏目
        preg_match_all('/{COLUMN}(.*){\/COLUMN}/', $fcontents, $array_column);
        $array_column = $array_column[1];

        //没有栏目是增加一个“所有文章”栏目
        if (count($array_column) == 0) {
            $array_column = array('所有文章');
            $fcontents = '{COLUMN}所有文章{/COLUMN}' . $fcontents;
        }

        $columns = new Doctrine_Collection('PubColumn');

        //循环添加栏目
        for ($i = 0; $i < count($array_column); $i++) {
            $columns[$i]->pub_id = $pub_id;
            $columns[$i]->col_id = $i + 1;
            $columns[$i]->name = trim($array_column[$i]);
        }
        $columns->save();

        //栏目内文章分段获取
        $array_p = explode('{COLUMN}', $fcontents . '{COLUMN}', -1);
        unset($array_p[0]);

        //循环每个分段
        foreach ($array_p AS $key => $p) {
            preg_match_all('/(.*){\/COLUMN}/iUs', $p, $column_names);
            preg_match_all('/{TITLE}(.*){\/TITLE}/iUs', $p, $array_title);
            preg_match_all('/{AUTHOR}(.*){\/AUTHOR}/iUs', $p, $array_author);
            preg_match_all('/{CONTENT}(.*){\/CONTENT}/iUs', $p, $array_content);
            preg_match_all('/{PAGE}(\d*){\/PAGE}/iUs', $p, $array_page);

            //循环保存每个篇文章
            $pubcontent = new Doctrine_Collection('PubContent');
            for ($i = 0; $i < count($array_title[1]); $i++) {
                $pubcontent[$i]->pub_id = $pub_id;
                $pubcontent[$i]->col_id = $key;
                $pubcontent[$i]->title = isset($array_title[1][$i]) ? trim($array_title[1][$i]) : '暂无标题';
                $pubcontent[$i]->author = isset($array_author[1][$i]) ? trim($array_author[1][$i]) : '';
                $pubcontent[$i]->content = nl2br(htmlspecialchars($array_content[1][$i]));
                $pubcontent[$i]->page = isset($array_page[1][$i]) ? $array_page[1][$i] : NULL;
            }
            $pubcontent->save();

            //栏目名称
            $column_name = isset($column_names[1][0]) ? $column_names[1][0] : 'null';
            //如果为北航校友栏目，自动发布到北航校友新闻
            if (strstr('北航校友', $column_name)) {
                $people_news = new Doctrine_Collection('PeopleNews');
                for ($i = 0; $i < count($array_title[1]); $i++) {
                    $people_news[$i]->title = isset($array_title[1][$i]) ? trim($array_title[1][$i]) : '暂无标题';
                    $people_news[$i]->author_name = isset($array_author[1][$i]) ? trim($array_author[1][$i]) : '';
                    $people_news[$i]->content = nl2br(htmlspecialchars($array_content[1][$i]));
                    $people_news[$i]->create_at = date('Y-m-d H:i:s');
                }
                $people_news->save();
            }
        }
    }

    //删除文章
    function action_delArticle() {
        $this->auto_render = FALSE;
        $cid = Arr::get($_GET, 'cid');
        $del = Doctrine_Query::create()
                ->delete('PubContent')
                ->where('id =?', $cid)
                ->execute();
    }

    //删除期刊
    function action_delPub() {
        $this->auto_render = FALSE;
        $cid = Arr::get($_GET, 'cid');
        Doctrine_Query::create()
                ->delete('PubContent')
                ->where('pub_id =?', $cid)
                ->execute();

        Doctrine_Query::create()
                ->delete('PubColumn')
                ->where('pub_id =?', $cid)
                ->execute();

        Doctrine_Query::create()
                ->delete('Publication')
                ->where('id =?', $cid)
                ->execute();

        @unlink(DOCROOT . Model_Publication::COVER_PATH . 'resize/' . $cid . '.jpg');
        @unlink(DOCROOT . Model_Publication::COVER_PATH . $cid . '.jpg');
    }

    //删除里面文章
    function action_delColumnArt() {
        $this->auto_render = FALSE;
        $cid = Arr::get($_GET, 'cid');
        $cid = Arr::get($_GET, 'cid');
        Doctrine_Query::create()
                ->delete('PubContent')
                ->where('pub_id =?', $cid)
                ->execute();
        Doctrine_Query::create()
                ->delete('PubColumn')
                ->where('pub_id =?', $cid)
                ->execute();
    }

    //电子信息报
    function action_eleReport() {
        $q = Arr::get($_GET, 'q');
        $report = Doctrine_Query::create()
                ->select('id,title,create_at,issue')
                ->from('EleReport')
                ->orderBy('id DESC');

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

        $this->_title('电子信息报');
        $this->_render('_body', $view);
    }

    //新增和修改电子信息报
    function action_reportForm() {
        $id = Arr::get($_GET, 'id', 0);
        $view['err'] = '';
        $file_name = date("YmdHis");
        $content_path = '';

        $report = Doctrine_Query::create()
                ->from('EleReport')
                ->where('id = ?', $id)
                ->fetchOne();
        $view['report'] = $report;
        if ($_POST) {
            //如果存在附件
            if ($_FILES['file']['size'] > 0) {
                //上传的txt附件
                $valid = Validate::factory($_FILES);
                $valid->rules('file', Model_Publication::$up_rule);
                if (!$valid->check()) {
                    $view['err'] .= $valid->outputMsg($valid->errors('validate'));
                } else {
                    // 处理导入的文本内容
                    $path = DOCROOT . Model_Publication::TXT_PATH;
                    $file_path = $path . '/' . $file_name . '.txt';
                    $content_path = URL::base() . Model_Publication::TXT_PATH . $file_name . '.txt';
                    Upload::save($_FILES['file'], $file_name . '.txt', $path);
                }
            }

            $valid = Validate::setRules($_POST, 'elereport');
            $post = $valid->getData();
            if (!$valid->check()) {
                $view['err'] .= $valid->outputMsg($valid->errors('validate'));
            } else {
                // 添加或修改内容
                if ($report) {
                    unset($post['id']);
                    $post['content_path'] = $content_path;
                    $report->synchronizeWithArray($post);
                    $report->save();
                } else {
                    if ($content_path) {
                        $post['content_path'] = $content_path;
                    }
                    $report = new EleReport();
                    $report->fromArray($post);
                    $report->save();
                }
                // 处理完毕后刷新页面
                $this->request->redirect('admin_publication/eleReport');
            }
        }

        $this->_render('_body', $view);
    }

    function action_delEleReport() {
        $this->auto_render = FALSE;
        $cid = Arr::get($_GET, 'cid');

        $report = Doctrine_Query::create()
                ->from('EleReport')
                ->where('id =?', $cid)
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

        @unlink(DOCROOT . $report['content_path']);

        $del = Doctrine_Query::create()
                ->delete('EleReport')
                ->where('id =?', $cid)
                ->execute();
    }

    function action_contribute() {
        $type = Arr::get($_GET, 'type');
        $pub_id = Arr::get($_GET, 'pub_id');
        $col_id = Arr::get($_GET, 'col_id');
        $publication = Doctrine_Query::create()
                ->from('Publication')
                ->orderBy('type,id DESC')
                ->limit(20)
                ->fetchArray();

        $q = Arr::get($_GET, 'q');
        $content = $pub = Doctrine_Query::create()
                ->select('c.id,c.title,c.user_id,c.is_read,c.create_at,c.update_at,c.reply')
                ->addSelect('u.realname AS realname')
                ->from('PubContribute c')
                ->leftJoin('c.User u')
                ->orderBy('c.id DESC');

        $total_content = $content->count();

        $pager = Pagination::factory(array(
                    'total_items' => $total_content,
                    'items_per_page' => 20,
                    'view' => 'pager/common',
        ));

        $view['type'] = $type;
        $view['publication'] = $publication;
        $view['pub_id'] = $pub_id;
        $view['col_id'] = $col_id;
        $view['q'] = $q;
        $view['pager'] = $pager;
        $view['content'] = $content->offset($pager->offset)
                ->limit($pager->items_per_page)
                ->fetchArray();

        $this->_title('期刊管理');
        $this->_render('_body', $view);
    }

    function action_contributeForm() {
        $cid = Arr::get($_GET, 'id', 0);
        $content = Doctrine_Query::create()
                ->from('PubContribute')
                ->where('id = ?', $cid)
                ->fetchOne();

        $view['err'] = '';
        $view['content'] = $content;

        if ($_POST) {
            //修改内容
            if ($content) {
                $post['reply'] = Arr::get($_POST, 'reply');
                $post['is_read'] = True;
                $post['update_at'] = date('Y-m-d H:i:s');
                $content->synchronizeWithArray($post);
                $content->save();
                if (Arr::get($_POST, 'reply')) {
                    //发送站内信
                    unset($post);
                    $post['send_to'] = $content['user_id'];
                    $post['sort_in'] = 0;
                    $post['user_id'] = $this->_sess->get('id');
                    $post['content'] = '尊敬的校友，您好！感谢你的投稿，您的投稿《' . $content['title'] . '》我已经查阅并已回复，详情请浏览您的文章管理页面。';
                    $post['send_at'] = date('Y-m-d H:i:s');
                    $post['update_at'] = date('Y-m-d H:i:s');
                    $msg = new UserMsg();
                    $msg->fromArray($post);
                    $msg->save();
                }
            }
            // 处理完毕后刷新页面
            $this->request->redirect('admin_publication/contribute');
        }

        $post['is_read'] = True;
        $post['update_at'] = date('Y-m-d H:i:s');
        $content->synchronizeWithArray($post);
        $content->save();
        $this->_render('_body', $view);
    }

}

?>
