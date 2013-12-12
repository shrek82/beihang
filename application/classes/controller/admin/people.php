<?php

//北航校友

class Controller_Admin_People extends Layout_Admin {

    function before() {
        parent::before();

        $leftbar_links = array(
            'admin_user/index' => '用户列表',
            'admin_user/people' => '北航校友',
        );

        $president_period = array(
            '1' => '所有时期'
        );

        View::set_global('president_period', $president_period);

        $this->_render('_body_left', compact('leftbar_links'), 'admin/leftbar');
    }

    function action_index() {
        $ppid = Arr::get($_GET, 'ppid', 0);
        $cur_pp = Doctrine_Query::create()
                ->from('People p')
                ->where('p.id = ?', $ppid)
                ->fetchOne();
        $view['cur_pp'] = $cur_pp;
        $file_name = date("YmdHis");
        $view['err'] = null;
        $pic_path = '';

        if ($_POST) {

            //保存照片
            if ($_FILES['pic']['size'] > 0) {
                // 上传的pic
                $valid = Validate::factory($_FILES);
                $valid->rules('pic', Model_User::$up_rule);
                if (!$valid->check()) {
                    $view['err'] .= $valid->outputMsg($valid->errors('validate'));
                } else {
                    $path = DOCROOT . Model_User::PEOPLE_PATH;
                    Upload::save($_FILES['pic'], $file_name . '.jpg', $path);
                    $pic_path = URL::base() . Model_User::PEOPLE_PATH . $file_name . '.jpg';
                }
            }

            $valid = Validate::setRules($_POST, 'people');
            $post = $valid->getData();
            if (!$valid->check()) {
                $view['err'] .= $valid->outputMsg($valid->errors('validate'));
            } else {

                //保存附件

                if ($pic_path) {
                    $post['pic'] = $pic_path;
                }

                // 保存修改内容
                if ($cur_pp) {
                    unset($post['id']);
                    $cur_pp->synchronizeWithArray($post);
                    $cur_pp->save();
                }

                //添加新纪录
                else {
                    $cur_pp = new People();
                    $cur_pp->fromArray($post);
                    $cur_pp->save();
                }
            }
        }


        $abc = Arr::get($_GET, 'abc');

        $people = Doctrine_Query::create()
                ->from('People p')
                ->orderBy('p.abc ASC');

        if ($abc) {
            $people->where('p.abc = ?', $abc);
        }

        $view['people'] = $people->fetchArray();

        $this->_title('北航校友');
        $this->_render('_body', $view);
    }

    //历任校长
    function action_president() {
        $period = Arr::get($_GET, 'period');
        $president = Doctrine_Query::create()
                ->from('President')
                ->orderBy('order_num,id');

        if ($period) {
            $president->where('period=?', $period);
        }

        $total_president = $president->count();

        $pager = Pagination::factory(array(
                    'total_items' => $total_president,
                    'items_per_page' => 15,
                    'view' => 'pager/common',
        ));

        $view['period'] = $period;
        $view['pager'] = $pager;
        $view['president'] = $president->offset($pager->offset)
                ->limit($pager->items_per_page)
                ->fetchArray();

        $this->_title('历任校长');
        $this->_render('_body', $view);
    }

    //增加和修改校长
    function action_presidentForm() {
        $id = Arr::get($_GET, 'id', 0);
        $period = Arr::get($_POST, 'period');
        $view['sess_president_period'] = $this->_sess->get('sess_president_period');
        $view['err'] = '';

        $view['total_president'] = Doctrine_Query::create()
                ->from('President')
                ->count();

        $president = Doctrine_Query::create()
                ->from('President')
                ->where('id = ?', $id)
                ->fetchOne();
        $view['president'] = $president;
        if ($_POST) {


            $valid = Validate::setRules($_POST, 'president');
            $post = $valid->getData();
            if (!$valid->check()) {
                $view['err'] .= $valid->outputMsg($valid->errors('validate'));
            } else {

                // 添加或修改内容
                if ($president) {
                    unset($post['id']);
                    $president->synchronizeWithArray($post);
                    $president->save();
                } else {
                    $president = new President();
                    $president->fromArray($post);
                    $president->save();
                }
                //记住上次分类id
                $this->_sess->set('sess_president_period', Arr::get($_POST, 'period'));

                // 处理完毕后刷新页面
                $this->request->redirect('admin_people/presidentForm?period=' . $period);
            }
        }

        $this->_render('_body', $view);
    }

    //删除
    function action_delPresident() {
        $this->auto_render = FALSE;
        $cid = Arr::get($_GET, 'cid');
        $del = Doctrine_Query::create()
                ->delete('President')
                ->where('id =?', $cid)
                ->execute();
    }

    //求是新闻
    function action_news() {
        $q = urldecode(Arr::get($_GET, 'q'));
        $view['q'] = $q;

        $news = Doctrine_Query::create()
                ->select('n.*')
                ->from('PeopleNews  n');


        if ($q) {
            $news->andWhere('n.title LIKE ?', '%' . $q . '%');
        }

        $news->orderBy('n.create_at DESC');

        $total_items = $news->count();

        $pager = Pagination::factory(array(
                    'total_items' => $total_items,
                    'items_per_page' => 15,
                    'view' => 'pager/common',
        ));

        $view['pager'] = $pager;

        // view
        $view['news'] = $news->offset($pager->offset)
                ->limit($pager->items_per_page)
                ->fetchArray();

        $this->_title('新闻列表');
        $this->_render('_body', $view);
    }

    //添加修改求是新闻
    function action_newsForm() {
        $id = Arr::get($_GET, 'id', 0);
        $news = Doctrine_Query::create()
                ->from('PeopleNews')
                ->where('id = ?', $id)
                ->fetchOne();
        $view['news'] = $news;
        $view['err'] = '';

        if ($_POST) {
            // 链接内容数据
            $valid = Validate::setRules($_POST, 'people_news');
            $post = $valid->getData();
            if (!$valid->check()) {
                $view['err'] .= $valid->outputMsg($valid->errors('validate'));
            } else {
                // 更新链接 or 创建链接
                if ($news) {
                    unset($post['id']);
                    $news->synchronizeWithArray($post);
                    $news->save();
                } else {
                    $news = new PeopleNews();
                    $news->fromArray($post);
                    $news->save();
                }

                // 处理完毕后刷新页面
                $this->request->redirect('admin_people/news');
            }
        }
        $this->_render('_body', $view);
    }

    //删除北航校友
    function action_delPeople() {
        $this->auto_render = FALSE;
        $cid = Arr::get($_GET, 'cid');
        $people = Doctrine_Query::create()
                ->from('People')
                ->where('id = ?', $cid)
                ->fetchOne();

        if ($people) {
            $people->delete();
        }

        @unlink($people['pic']);
    }

    //删除
    function action_delNews() {
        $this->auto_render = FALSE;
        $cid = Arr::get($_GET, 'cid');
        Doctrine_Query::create()
                ->delete('PeopleNews')
                ->where('id =?', $cid)
                ->execute();
    }

}
