<?php
	if ($signature_1ocal == $signature) {
	    //交易是否成功
	    if ($succ == 'Y') {

		if ((int) $attach > 0) {
		    $annual = Doctrine_Query::create()
				    ->select('*')
				    ->from('DonateAnnual')
				    ->where('id = ?', $attach)
				    ->fetchOne();

		    if ($annual) {
			if ($annual['payment_status']) {
			    $view['pay_msg'] = '原因：您已经支付过了，无需再次支付了！';
			} else {
			    if ($annual['id'] == $attach AND $annual['amount'] == $amount AND $billno == $annual['billno']) {
				$pay_post['payment_at'] = date('Y-m-d H:i:s');
				$pay_post['payment_status'] = True;
				$pay_post['ipsbillno'] = $ipsbillno;
				$annual->fromArray($pay_post);
				$annual->save();
				$view['pay_success'] = TRUE;
				$view['pay_msg'] = '感谢您，捐款成功！';
			    } else {
				$view['pay_msg'] = '原因：支付内容与订单不符。';
			    }
			}
		    } else {
			$view['pay_msg'] = '原因：该捐赠订单尚未提交或已被删除。';
		    }
		}
	    } else {
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

		if ($errCode AND isset($errCodeArray[$errCode])) {
		    $view['pay_msg'] = '原因：' . $errCodeArray[$errCode];
		} else {
		    $view['pay_msg'] = '原因：数字签名不正确';
		}
	    }
	}

?>
