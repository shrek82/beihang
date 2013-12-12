<?php

class Controller_Admin_Donate extends Layout_Admin {

    function before() {
        parent::before();

        $leftbar_links = array(
            'admin_donate/statistics' => '捐赠统计',
            'admin_donate/annual' => '年度统计',
            'admin_donate/eventFund' => '校友活动基金',
        );

        $this->_render('_body_left', compact('leftbar_links'), 'admin/leftbar');
    }

    function action_index() {
        $this->_redirect('admin_donate/annual');
        $this->_title('校友捐赠');
        $this->_render('_body');
    }

    //年度捐赠
    function action_annual() {
        $pay = Arr::get($_GET, 'pay');
        $q = Arr::get($_GET, 'q');
        $import_total = Arr::get($_GET, 'import_total');
        $annual = Doctrine_Query::create()
                ->select('*')
                ->from('DonateAnnual');

        if ($q) {
            $annual->where('donor LIKE ?', array('%' . $q . '%'));
        }

        if ($pay || $pay == '0') {
            $annual->where('payment_status= ?', $pay);
        }

        $pager = Pagination::factory(array(
                    'total_items' => $annual->count(),
                    'items_per_page' => 20,
                    'view' => 'pager/common'
        ));

        $annual = $annual->orderBy('donate_at DESC,id DESC')
                ->offset($pager->offset)
                ->limit($pager->items_per_page)
                ->fetchArray();

        $this->_render('_body', compact('q', 'pay', 'annual', 'pager', 'import_total'));
    }

//捐赠统计表单
    function action_annualForm() {
        $id = Arr::get($_GET, 'id');
        $view['err'] = '';
        $content = Doctrine_Query::create()
                ->from('DonateAnnual')
                ->where('id=?', $id)
                ->fetchOne();

        $view['content'] = $content;

        if ($_POST) {

            // 链接内容数据
            $valid = Validate::setRules($_POST, 'donate_annual');
            $post = $valid->getData();
            if (!$valid->check()) {
                $view['err'] .= $valid->outputMsg($valid->errors('validate'));
            } else {
                // 修改内容
                if ($content) {
                    unset($post['id']);
                    $content->synchronizeWithArray($post);
                    $content->save();
                } else {
                    $content = new DonateAnnual();
                    $content->fromArray($post);
                    $content->save();
                }

                // 处理完毕后刷新页面
                $this->request->redirect('admin_donate/annual');
            }
        }

        $this->_render('_body', $view);
    }

    //更改年度捐赠支付状态
    function action_annualPayment() {
        $this->auto_render = FALSE;
        $id = Arr::get($_GET, 'id');
        if ($id) {

            $annual = Doctrine_Query::create()
                    ->from('DonateAnnual')
                    ->where('id = ?', $id)
                    ->fetchOne();

            if ($annual['payment_status'] == TRUE) {
                $annual['payment_status'] = FALSE;
            } else {
                $annual['payment_status'] = TRUE;
            }

            $annual->save();
        }
    }

    //删除年度捐赠记录
    function action_delAnnual() {
        $this->auto_render = FALSE;
        $cid = Arr::get($_GET, 'cid');
        $del = Doctrine_Query::create()
                ->delete('DonateAnnual')
                ->where('id =?', $cid)
                ->execute();
    }

//捐赠统计
    function action_statistics() {
        $pay = Arr::get($_GET, 'pay');
        $q = Arr::get($_GET, 'q');
        $statistics = Doctrine_Query::create()
                ->select('*')
                ->from('DonateStatistics');

        if ($q) {
            $statistics->where('name LIKE ? OR donor LIKE ?', array('%' . $q . '%', '%' . $q . '%'));
        }

        $pager = Pagination::factory(array(
                    'total_items' => $statistics->count(),
                    'items_per_page' => 20,
                    'view' => 'pager/common'
        ));

        $statistics = $statistics->orderBy('donate_at DESC,id DESC')
                ->offset($pager->offset)
                ->limit($pager->items_per_page)
                ->fetchArray();
        $this->_render('_body', compact('q', 'pay', 'statistics', 'pager'));
    }

    //捐赠统计表单
    function action_statisticsForm() {
        $id = Arr::get($_GET, 'id');
        $view['err'] = '';
        $content = Doctrine_Query::create()
                ->from('DonateStatistics')
                ->where('id=?', $id)
                ->fetchOne();

        $view['content'] = $content;

        if ($_POST) {

            // 链接内容数据
            $valid = Validate::setRules($_POST, 'donate_statistics');
            $post = $valid->getData();
            if (!$valid->check()) {
                $view['err'] .= $valid->outputMsg($valid->errors('validate'));
            } else {
                // 修改内容
                if ($content) {
                    unset($post['id']);
                    $content->synchronizeWithArray($post);
                    $content->save();
                } else {
                    $content = new DonateStatistics();
                    $content->fromArray($post);
                    $content->save();
                }

                // 处理完毕后刷新页面
                $this->request->redirect('admin_donate/statistics');
            }
        }

        $this->_render('_body', $view);
    }

    //删除捐赠统计数据
    function action_delStatistics() {
        $this->auto_render = FALSE;
        $cid = Arr::get($_GET, 'cid');
        $del = Doctrine_Query::create()
                ->delete('DonateStatistics')
                ->where('id =?', $cid)
                ->execute();
    }

    //捐赠统计
    function action_eventFund() {
        $q = Arr::get($_GET, 'q');
        $fund = Doctrine_Query::create()
                ->select('*')
                ->from('DonateFund');

        if ($q) {
            $fund->where('donor LIKE ?', array('%' . $q . '%'));
        }

        $pager = Pagination::factory(array(
                    'total_items' => $fund->count(),
                    'items_per_page' => 20,
                    'view' => 'pager/common'
        ));

        $fund = $fund->orderBy('donate_at DESC,id DESC')
                ->offset($pager->offset)
                ->limit($pager->items_per_page)
                ->fetchArray();
        $this->_render('_body', compact('q', 'fund', 'pager'));
    }

    //活动基金表单
    function action_fundForm() {
        $id = Arr::get($_GET, 'id');
        $view['err'] = '';
        $content = Doctrine_Query::create()
                ->from('DonateFund')
                ->where('id=?', $id)
                ->fetchOne();

        $view['content'] = $content;

        if ($_POST) {

            // 链接内容数据
            $valid = Validate::setRules($_POST, 'donate_fund');
            $post = $valid->getData();
            if (!$valid->check()) {
                $view['err'] .= $valid->outputMsg($valid->errors('validate'));
            } else {
                // 修改内容
                if ($content) {
                    unset($post['id']);
                    $content->synchronizeWithArray($post);
                    $content->save();
                } else {
                    $content = new DonateFund();
                    $content->fromArray($post);
                    $content->save();
                }

                // 处理完毕后刷新页面
                $this->request->redirect('admin_donate/eventFund');
            }
        }

        $this->_render('_body', $view);
    }

    //删除活动捐赠
    function action_delFund() {
        $this->auto_render = FALSE;
        $cid = Arr::get($_GET, 'cid');
        $del = Doctrine_Query::create()
                ->delete('DonateFund')
                ->where('id =?', $cid)
                ->execute();
    }

    //导入年度统计文件
    function action_importAnnual() {
        if ($_POST) {
            //如果存在附件
            $file_name = date("YmdHis");
            if ($_FILES['file']['size'] > 0) {
                //上传的txt附件
                $valid = Validate::factory($_FILES);
                $valid->rules('file', Model_Donate::$up_rule);
                if (!$valid->check()) {
                    $view['err'] .= $valid->outputMsg($valid->errors('validate'));
                } else {
                    // 处理导入的文本内容
                    $path = DOCROOT . Model_Donate::EXCEL_PATH;
                    $file_path = $path . '/' . $file_name . '.xls';
                    Upload::save($_FILES['file'], $file_name . '.xls', $path);
                    $import_total = $this->saveImport($file_path);
                    @unlink($file_path);
                    $this->_redirect('admin_donate/annual?import_total=' . $import_total);
                }
            } else {
                $view['err'] = '您还没有选择excel文件，请选选择！';
            }
        }
        $view['err'] = '';

        $this->_render('_body', $view);
    }

    //保存导入上传的年度统计数据
    function saveImport($file_path) {
        $this->auto_render = FALSE;
        Candy::import('excelReader');
        $data = new Spreadsheet_Excel_Reader();
        $data->setOutputEncoding('UTF-8');
        $data->read($file_path);
        $data_array = array();

        for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
            if (isset($data->sheets[0]['cells'][$i][2])) {
                $index = $i - 2;
                $data_array[$index]['name'] = $data->sheets[0]['cells'][$i][2];
            }
        }

        unset($data->sheets[0]['cells'][1]);
        //echo Kohana::debug($data->sheets[0]['cells']);

        $sheets = $data->sheets[0]['cells'];

        $columns = new Doctrine_Collection('DonateAnnual');

        foreach ($sheets as $i => $value) {

            $columns[$i]->donate_at = !empty($value[1]) ? date('Y-m-d', strtotime("$value[1]")) : null;
           // echo $columns[$i]->donate_at;
            //$columns[$i]->donate_at = !empty($value[1]) ? date('Y-m-d', strtotime("$value[1] - 8 hour")) : null;
            $columns[$i]->project = isset($value[2]) ? $value[2] : NULL;
            $columns[$i]->donor = isset($value[3]) ? $value[3] : NULL;
            $columns[$i]->amount = isset($value[4]) ? $value[4] : NULL;
            $columns[$i]->speciality = isset($value[5]) ? $value[5] : NULL;
            $columns[$i]->payment_status = True;
        }

        $columns->save();
        return count($columns);
    }

}