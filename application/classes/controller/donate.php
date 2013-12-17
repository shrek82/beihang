<?php

class Controller_Donate extends Layout_Main {

    public function before() {
        $this->template = 'layout/donate';
        parent::before();
    }

    /**
      +------------------------------------------------------------------------------
     * 捐赠首页
      +------------------------------------------------------------------------------
     */
    function action_index() {
        $this->_title('校友捐赠');

        //相关报道
        $view['news'] = Doctrine_Query::create()
                ->select('c.id,c.title,c.create_at')
                ->from('Content c')
                ->leftJoin('c.ContentCategory cat')
                ->where('cat.name="捐赠报道"')
                ->orderBy('c.create_at DESC')
                ->limit(5)
                ->fetchArray();

        //捐赠感言
        $view['ganyan'] = Doctrine_Query::create()
                ->select('c.id,c.title,c.create_at')
                ->from('Content c')
                ->leftJoin('c.ContentCategory cat')
                ->where('cat.name="捐赠感言"')
                ->orderBy('c.create_at DESC')
                ->limit(5)
                ->fetchArray();

        $this->_render('_body', $view);
    }

    /**
      +------------------------------------------------------------------------------
     * 捐赠统计
      +------------------------------------------------------------------------------
     */
    function action_statistics() {
        $name = Arr::get($_GET, 'name');
        $statistics = Doctrine_Query::create()
                ->select('id,name,donate_at,donor')
                ->from('DonateStatistics');

        if ($name) {
            $statistics->where('name LIKE ? OR donor LIKE ?', array('%' . $name . '%', '%' . $name . '%'));
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
        $this->_render('_body', compact('name', 'statistics', 'pager'));
    }

    /**
      +------------------------------------------------------------------------------
     * 年度捐赠
      +------------------------------------------------------------------------------
     */
    function action_annual() {
        //基本介绍
        $intro = Doctrine_Query::create()
                ->from('Content c')
                ->where('c.title="年度捐赠介绍"', '')
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

        //捐赠常见问题
        $faq = Doctrine_Query::create()
                ->from('Content c')
                ->where('c.title="捐赠常见问题"', '')
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

        //捐赠指南
        $guide = Doctrine_Query::create()
                ->from('Content c')
                ->where('c.title="捐赠指南"', '')
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);


        $name = Arr::get($_GET, 'name');

        $statistics = Doctrine_Query::create()
                ->select('id,project,donate_at,donor,speciality,amount')
                ->from('DonateAnnual')
                ->where('payment_status=1')
                ->addWhere('project LIKE ?', array('%年度捐赠%'))
                ->orderBy('donate_at DESC,id DESC')
                ->limit(10)
                ->fetchArray();

        $this->_render('_body', compact('name', 'intro', 'faq', 'guide', 'statistics', 'pager'));
    }
    
    /**
      +------------------------------------------------------------------------------
     * 捐赠项目
      +------------------------------------------------------------------------------
     */
    function action_project() {
        $name = Arr::get($_GET, 'name');
        $reports = Doctrine_Query::create()
                ->select('c.id,c.title,c.create_at')
                ->from('Content c')
                ->leftJoin('c.ContentCategory cat')
                ->where('cat.name="捐赠项目"');

        if ($name) {
            $reports->addWhere('title LIKE ?', array('%' . $name . '%'));
        }

        $pager = Pagination::factory(array(
                    'total_items' => $reports->count(),
                    'items_per_page' => 20,
                    'view' => 'pager/common'
                ));

        $reports = $reports->orderBy('c.create_at DESC')
                ->offset($pager->offset)
                ->limit($pager->items_per_page)
                ->fetchArray();
        $this->_title('捐赠项目');
        $this->_render('_body', compact('name', 'reports', 'pager'));
    }

    /**
      +------------------------------------------------------------------------------
     * 捐赠鸣谢
      +------------------------------------------------------------------------------
     */
    function action_thanks() {
        $name = Arr::get($_GET, 'name');
        $statistics = Doctrine_Query::create()
                ->select('id,project,donate_at,donor,speciality,amount')
                ->from('DonateAnnual')
                ->where('payment_status=1');

        if ($name) {
            $statistics->addWhere('project LIKE ? OR donor LIKE ?', array('%' . $name . '%', '%' . $name . '%'));
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

        $this->_render('_body', compact('name', 'statistics', 'pager'));
    }

    /**
      +------------------------------------------------------------------------------
     * 校友活动基金
      +------------------------------------------------------------------------------
     */
    function action_eventFund() {
        $name = Arr::get($_GET, 'name');
        $statistics = Doctrine_Query::create()
                ->select('id,donate_at,donor,amount')
                ->from('DonateFund');

        if ($name) {
            $statistics->addWhere('donor LIKE ?', array('%' . $name . '%'));
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

        $this->_render('_body', compact('name', 'statistics', 'pager'));
    }

    /**
      +------------------------------------------------------------------------------
     * 捐赠报道
      +------------------------------------------------------------------------------
     */
    function action_reports() {
        $name = Arr::get($_GET, 'name');
        $reports = Doctrine_Query::create()
                ->select('c.id,c.title,c.create_at')
                ->from('Content c')
                ->leftJoin('c.ContentCategory cat')
                ->where('cat.name="捐赠报道"');

        if ($name) {
            $reports->addWhere('title LIKE ?', array('%' . $name . '%'));
        }

        $pager = Pagination::factory(array(
                    'total_items' => $reports->count(),
                    'items_per_page' => 20,
                    'view' => 'pager/common'
                ));

        $reports = $reports->orderBy('c.create_at DESC')
                ->offset($pager->offset)
                ->limit($pager->items_per_page)
                ->fetchArray();
        $this->_title('捐赠报道');
        $this->_render('_body', compact('name', 'reports', 'pager'));
    }

    /**
      +------------------------------------------------------------------------------
     * 捐赠感言
      +------------------------------------------------------------------------------
     */
    function action_reflections() {
        $name = Arr::get($_GET, 'name');
        $contents = Doctrine_Query::create()
                ->select('c.id,c.title,c.create_at')
                ->from('Content c')
                ->leftJoin('c.ContentCategory cat')
                ->where('cat.name="捐赠感言"');

        if ($name) {
            $contents->addWhere('title LIKE ?', array('%' . $name . '%'));
        }

        $pager = Pagination::factory(array(
                    'total_items' => $contents->count(),
                    'items_per_page' => 20,
                    'view' => 'pager/common'
                ));

        $contents = $contents->orderBy('c.create_at DESC')
                ->offset($pager->offset)
                ->limit($pager->items_per_page)
                ->fetchArray();
        $this->_title('捐赠报道');
        $this->_render('_body', compact('name', 'contents', 'pager'));
    }

    /**
      +------------------------------------------------------------------------------
     * 捐赠感言
      +------------------------------------------------------------------------------
     */
    function action_gratitude() {
        $name = Arr::get($_GET, 'name');
        $contents = Doctrine_Query::create()
                ->select('c.id,c.title,c.create_at')
                ->from('Content c')
                ->leftJoin('c.ContentCategory cat')
                ->where('cat.name="真情感言"');

        if ($name) {
            $contents->addWhere('title LIKE ?', array('%' . $name . '%'));
        }

        $pager = Pagination::factory(array(
                    'total_items' => $contents->count(),
                    'items_per_page' => 20,
                    'view' => 'pager/common'
                ));

        $contents = $contents->orderBy('c.create_at DESC')
                ->offset($pager->offset)
                ->limit($pager->items_per_page)
                ->fetchArray();
        $this->_title('真情感言');
        $this->_render('_body', compact('name', 'contents', 'pager'));
    }

    /**
      +------------------------------------------------------------------------------
     * 内容展示
      +------------------------------------------------------------------------------
     */
    function action_meth() {
        $content = Doctrine_Query::create()
                ->from('Content c')
                ->where('c.title="捐赠途径"', '')
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
        $this->_title('捐赠途径');
        $this->_render('_body', compact('content'));
    }

    /**
      +------------------------------------------------------------------------------
     * 我要捐赠
      +------------------------------------------------------------------------------
     */
    function action_wantDonate() {
        $view = $_POST;

        if (!$view) {

            $user_id = $this->_uid;
            $realname = null;
            $mobile = null;

            if ($user_id) {
                $user = Doctrine_Query::create()
                        ->select('uc.mobile,uc.address,uc.tel,uc.mobile')
                        ->addSelect('u.realname AS realname,u.sex AS sex,u.realname AS realname,u.account AS account')
                        ->addSelect('e.speciality AS speciality,e.finish_at AS finish_at')
                        ->addSelect('w.company AS company,w.job AS job')
                        ->from('UserContact uc')
                        ->leftJoin('uc.User u')
                        ->leftJoin('u.Edus e')
                        ->leftJoin('u.Works w')
                        ->where('uc.user_id = ?', $user_id)
                        ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

                $view['donor'] = $user['realname'];
                $view['sex'] = $user['sex'];
                $view['speciality'] = $user['speciality'];
                $view['graduation_year'] = $user['finish_at'];
                $view['company'] = $user['company'] . '-' . $user['job'];
                $view['address'] = $user['address'];
                $view['tel'] = $user['tel'];
                $view['mobile'] = $user['mobile'];
                $view['email'] = $user['account'];
            } else {
                $view['donor'] = '';
                $view['sex'] = '';
                $view['speciality'] = '';
                $view['graduation_year'] = '';
                $view['company'] = '';
                $view['address'] = '';
                $view['tel'] = '';
                $view['mobile'] = '';
                $view['email'] = '';
            }

            $view['project'] = '';
            $view['will'] = array();
            $view['amount'] = '';
            $view['birthday'] = '';
            $view['birthplace'] = '';
            $view['zipcode'] = '';
            $view['donate_from'] = '';
            $view['collectiv'] = '';
            $view['return_receipt'] = '';
            $view['message'] = '';
            $view['methods'] = '';
        }

        $this->_render('_body', $view);
    }

    /**
      +------------------------------------------------------------------------------
     * 确认捐款内容(确认输入内容)
      +------------------------------------------------------------------------------
     */
    function action_donateStep2() {
        $view['post'] = $_POST;
        $this->_title('确认捐赠内容');
        $this->_render('_body', $view);
    }

    /**
      +------------------------------------------------------------------------------
     * 保存捐赠信息
      +------------------------------------------------------------------------------
     */
    function action_donateStep3() {
        $err = null;

        if ($_POST) {
            // 内容数据
            $valid = Validate::setRules($_POST, 'donate');
            $post = $valid->getData();
            $donate_from = Arr::get($_POST, 'donate_from');
            $collectiv = Arr::get($_POST, 'collectiv');

            if (!$valid->check()) {
                $err .= $valid->outputMsg($valid->errors('validate'));
            } else {
                $donate = new DonateAnnual();
                $post['user_id'] = $this->_uid;
                $post['donate_at'] = date('Y-m-d H:i:s');
                $post['amount'] = sprintf('%.2f', Arr::get($_POST, 'amount'));
                $post['billno'] = date('YmdHis') . mt_rand(1000, 9999);
                $post['payment_status'] = FALSE;
                if (isset($_POST['will'])) {
                    $post['will'] = implode(';', Arr::get($_POST, 'will'));
                }
                if ($donate_from == '集体捐赠' && $collectiv) {
                    $post['donor'] = $collectiv;
                }
                $donate->fromArray($post);
                $donate->save();

                //在线支付跳转至支付页面
                if (Arr::get($_POST, 'methods') == '网上捐款') {
                    $this->_redirect('donate/pay?id=' . $donate->id);
                    exit;
                }
            }
        }

        $this->_title('提示');
        $this->_render('_body');
    }

    //在线支付系统
    function action_pay() {
        $id = Arr::get($_GET, 'id');
        $annual = Doctrine_Query::create()
                ->select('*')
                ->from('DonateAnnual')
                ->where('id = ?', $id)
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

        if (!$annual) {
            $view['err_msg'] = '很抱歉，该订单不存在或已被删除。';
        } else {
            if ($annual['payment_status']) {
                $view['err_msg'] = '很抱歉，该订单已经支付，无需再支付了。';
            }
        }

        $donate_pay = Kohana::config('donate_pay');
        $amount = sprintf('%.2f', $annual['amount']);
        $bill_date = date('Ymd');
        $Currency_Type = 'RMB';

        $view['annual'] = $annual;
        //订单金额
        $view['amount'] = $amount;
        //商户编号
        $view['Mer_code'] = $donate_pay['Mer_code']; //商户号
        $view['bill_date'] = $bill_date;
        $view['Merchanturl'] = 'http://' . $_SERVER['HTTP_HOST'] . URL::site('donate/payReturn'); //支付成功返回URL
        $view['FailUrl'] = 'http://' . $_SERVER['HTTP_HOST'] . URL::site('donate/payReturn'); //支付失败返回URL
        $view['ErrorUrl'] =null;// 'http://' . $_SERVER['HTTP_HOST'] . URL::site('donate/payReturn'); //支付错误返回URL
        $view['Currency_Type'] = $Currency_Type;
        $view['Attach'] = $annual['id']; //商户附加数据包，本为捐款记录id
        $view['OrderEncodeType'] = 5;   //订单支付加密方式,0:不加密,2:md5摘要,9:错误
        $view['RetEncodeType'] = 17;       //交易返回加密方式 10:老接口,11:md5withRsa,12:md5摘要,9:错误
        $view['Rettype'] = 1;       //是否提供Server返回方式  0:无Server to Server,1:有Server to Server,9:错误
        $view['ServerUrl'] = '';      //Server to Server返回页面']
        //说明：若OrderEncodeType字段的值设置为2时，需同时在此字段中传入Md5摘要. Sigmd5=订单号+金额(保留2位小数)+日期+支付币种+IPS证书
        $SignMD5 = 'billno' . $annual['billno'];
        $SignMD5.='currencytype' . $Currency_Type;
        $SignMD5.='amount' . $amount;
        $SignMD5.='date' . $bill_date;
        $SignMD5.='orderencodetype' . $view['OrderEncodeType'];
        $SignMD5.=$donate_pay['Mer_cert'];
       // echo Kohana::debug($SignMD5);
       // echo '<br>';
        $view['SignMD5'] = strtolower(md5($SignMD5));
        $this->_title('支付确认');
        //echo Kohana::debug($donate_pay);
        //echo '<br>';
        //echo Kohana::debug($view);
        $this->_render('_body', $view);
    }

    //在线支付返回页面
    function action_payReturn() {

        $errCode = Arr::get($_GET, 'errCode');
        $billno = Arr::get($_GET, 'billno'); //订单编号
        $amount = Arr::get($_GET, 'amount'); //订单金额
        $bill_date = Arr::get($_GET, 'date');
        $succ = Arr::get($_GET, 'succ');
        $msg = Arr::get($_GET, 'msg');
        $attach = Arr::get($_GET, 'attach');
        $ipsbillno = Arr::get($_GET, 'ipsbillno');
        $retEncodeType = Arr::get($_GET, 'retencodetype'); //前面用的是 17
        $currency_type = Arr::get($_GET, 'Currency_type'); //币种
        $signature = Arr::get($_GET, 'signature'); //返回的数字签证信息
        $view['pay_success'] = FALSE;
        $view['pay_msg'] = '';
        $donate_pay = Kohana::config('donate_pay');

        $errCodeArray = array(
            'P0001' => '商户号为空 或者 用户名为空',
            'P0002' => '商户号长度大于6位',
            'P0003' => '商户订单号为空',
            'P0005' => '商户订单号太长',
            'P0006' => '商户金额为空',
            'P0007' => '商户金额不合法',
            'P0009' => '商户日期为空或者长度不对',
            'P0010' => '商户日期不合法',
            'P0011' => '商户订单签名不能为空',
            'P0012' => '商户订单签名错',
            'P0015' => '币种未激活',
            'P0016' => '支付方式未开通',
            'P0017' => '商户未开通(配置文件不存在)',
            'P0106' => '商户禁止访问',
            'P0107' => '未从正确的入口进入',
        );

        //根据返回信息生成数字签证信息
        $signature_1ocal = md5('billno' . $billno . 'currencytype' . $currency_type . 'amount' . $amount . 'date' . $bill_date . 'succ' . $succ . 'ipsbillno' . $ipsbillno . 'retencodetype' . $retEncodeType . $donate_pay['Mer_cert']);
        $signature_1ocal = strtolower($signature_1ocal);
        //和返回的数字签证做比较
        if ($signature_1ocal == $signature) {
            //交易是否成功
            if ($succ == 'Y') {
                //如果有捐赠ID
                if ((int) $attach > 0) {
                    $annual = Doctrine_Query::create()
                            ->select('*')
                            ->from('DonateAnnual')
                            ->where('id = ?', $attach)
                            ->fetchOne();
                    //存在捐赠记录
                    if ($annual) {
                        //已经支付过了
                        if ($annual['payment_status']) {
                            $view['pay_success'] = TRUE;
                            $view['pay_msg'] = '您已经支付过了，无需再次支付了！';
                        }
                        //支付款项符合
                        elseif ($annual['id'] == $attach AND $annual['amount'] == $amount AND $billno == $annual['billno']) {
                            $pay_post['payment_at'] = date('Y-m-d H:i:s');
                            $pay_post['payment_status'] = True;
                            $pay_post['ipsbillno'] = $ipsbillno;
                            $pay_post['amount'] = $annual['amount'] . ' 元';
                            $annual->fromArray($pay_post);
                            $annual->save();
                            $view['pay_success'] = TRUE;
                            $view['pay_msg'] = '感谢您，捐款成功！';
                        }
                        //款项与支付结果不符
                        else {
                            $view['pay_msg'] = '原因：支付内容与订单不符。';
                        }
                    }
                    //不存在捐赠记录
                    else {
                        $view['pay_msg'] = '原因：该捐赠订单尚未提交或已被删除。';
                    }
                }
            }
            //交易失败
            else {
                if ($errCode AND isset($errCodeArray[$errCode])) {
                    $view['pay_msg'] = '原因：' . $errCodeArray[$errCode];
                } else {
                    $view['pay_msg'] = '错误代码：' . $errCode;
                }
            }
        } else {
            if ($errCode AND isset($errCodeArray[$errCode])) {
                $view['pay_msg'] = '原因：' . $errCodeArray[$errCode];
            } else {
                $view['pay_msg'] = '错误代码：' . $errCode;
            }
        }
        $view['attach'] = $attach;
        $this->_title('支付结果');
        $this->_render('_body', $view);
    }

    /**
      +------------------------------------------------------------------------------
     * 捐赠返回提示
      +------------------------------------------------------------------------------
     */
    function action_success() {
        $this->_render('donate/success');
    }

    /**
      +------------------------------------------------------------------------------
     * 内容展示
      +------------------------------------------------------------------------------
     */
    function action_view() {
        $id = Arr::get($_GET, 'id');
        $content = Doctrine_Query::create()
                ->select('c.*,cat.name AS catName')
                ->from('Content c')
                ->leftJoin('c.ContentCategory cat')
                ->where('c.id=?', $id)
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

        $more = Doctrine_Query::create()
                ->select('c.id,c.title')
                ->from('Content c')
                ->where('c.type=?', $content['type'])
                ->limit(10)
                ->fetchArray();


        $this->_title($content['title']);
        $this->_render('_body', compact('content', 'more'));
    }

    /**
      +------------------------------------------------------------------------------
     * 滚动图片
      +------------------------------------------------------------------------------
     */
    function action_scroll() {
        $this->auto_render = FALSE;
        $view['project'] = Doctrine_Query::create()
                ->select('c.id,c.title,c.img_path')
                ->from('Content c')
                ->leftJoin('c.ContentCategory cat')
                ->where('cat.name="捐赠展示"')
                ->orderBy('c.id DESC')
                ->limit(10)
                //->useResultCache(true, 3600, 'donate_project')
                ->fetchArray();

        echo View::factory('donate/scroll', $view);
    }

}

?>
