<?php

//档案管理
class Controller_Admin_Alumni extends Layout_Admin {

    function before() {
        parent::before();
    }

    //档案管理首页
    function action_index() {
        $search_type = Arr::get($_GET, 'search_type');
        $q = Arr::get($_GET, 'q');
        $q = trim($q);

        $files = Doctrine_Query::create()
                ->select('a.id,a.name,a.sex,a.file_no,a.student_no,a.education,a.begin_year,a.graduation_year,a.speciality,a.update_date')
                ->from('Alumni a')
                ->where('a.id>0');

        if ($q AND $search_type) {
            if ($search_type == 'name') {
                $files->andWhere('a.name LIKE ?', '%' . $q . '%');
            } elseif ($search_type == 'student_no') {
                $files->addWhere('a.student_no= ?', $q);
            } elseif ($search_type == 'file_no') {
                $files->addWhere('a.file_no= ?', $q);
            } elseif ($search_type == 'native_place') {
                $files->addWhere('a.native_place LIKE ?', '%' . $q . '%');
            } elseif ($search_type == 'mobile') {
                $files->addWhere('a.mobile=?', $q);
            } elseif ($search_type == 'email') {
                $files->addWhere('a.email= ?', $q);
            } elseif ($search_type == 'institute') {
                $files->addWhere('a.institute LIKE ?', '%' . $q . '%');
            } elseif ($search_type == 'speciality') {
                $files->addWhere('a.speciality LIKE ?', '%' . $q . '%');
            } elseif ($search_type == 'degree_ustc') {
                $files->addWhere('a.degree_ustc LIKE ?', '%' . $q . '%');
            } elseif ($search_type == 'begin_year') {
                $files->addWhere('a.begin_year =?', (int) $q);
            } elseif ($search_type == 'graduation_year') {
                $files->addWhere('a.graduation_year =?', (int) $q);
            } elseif ($search_type == 'job_domain') {
                $files->addWhere('a.job_domain LIKE ?', '%' . $q . '%');
            } elseif ($search_type == 'workplace') {
                $files->addWhere('a.workplace LIKE ?', '%' . $q . '%');
            } elseif ($search_type == 'rank') {
                $files->addWhere('a.rank LIKE ?', '%' . $q . '%');
            } elseif ($search_type == 'career') {
                $files->addWhere('a.career LIKE ?', '%' . $q . '%');
            } elseif ($search_type == 'branch') {
                $files->addWhere('a.branch LIKE ?', '%' . $q . '%');
            } elseif ($search_type == 'introduce') {
                $files->addWhere('a.introduce LIKE ?', '%' . $q . '%');
            } elseif ($search_type == 'donation') {
                $files->addWhere('a.donation LIKE ?', '%' . $q . '%');
            } elseif ($search_type == 'remarks') {
                $files->addWhere('a.remarks LIKE ?', '%' . $q . '%');
            } else {

            }
        }

        $total_files = $files->count();

        $pager = Pagination::factory(array(
                    'total_items' => $total_files,
                    'items_per_page' => 20,
                    'view' => 'pager/common',
                ));

        $view['files'] = $files->offset($pager->offset)
                ->limit($pager->items_per_page)
                ->fetchArray();

        $view['pager'] = $pager;
        $view['q'] = $q;
        $view['search_type'] = $search_type;

        $this->_title('档案管理');
        $this->_render('_body', $view);
    }

    //详情
    function action_view() {
        $id = Arr::get($_GET, 'id');
        $file = Doctrine_Query::create()
                ->from('Alumni a')
                ->where('a.id=?', $id)
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
        $view['file'] = $file;
        $this->_render('_body', $view);
    }

    //修改档案
    function action_form() {
        $id = Arr::get($_POST, 'id');

        $id = $id ? $id : Arr::get($_GET, 'id');
        $file = Doctrine_Query::create()
                ->from('Alumni a')
                ->where('a.id=?', $id)
                ->fetchOne();
        $view['file'] = $file;

        if ($_POST AND $file) {
            $valid = Validate::setRules($_POST, 'alumni');
            $post = $valid->getData();
            unset($post['id']);
            foreach ($post AS $key => $p) {
                $post[$key] = $post[$key] ? $post[$key] : null;
            }
            $post['begin_year'] = $post['begin_year'] ? (int) Text::limit_chars($post['begin_year'], 4) : null;
            $post['graduation_year'] = $post['graduation_year'] ? (int) Text::limit_chars($post['graduation_year'], 4) : null;
            $post['update_date'] = date('Y-m-d H:i:s');
            $file->synchronizeWithArray($post);
            $file->save();

            //记录操作日志
            $log_data = array();
            $log_data['type'] = '修改档案信息';
            $log_data['description'] = '修改了' . $post['name'] . '(序号:' . $file['id'] . ')的档案信息';
            Common_Log::add($log_data);

            echo $file->id;
            exit;
        }

        $this->_render('_body', $view);
    }

}

?>