<!-- donate/wantDonate:_body -->
<style type="text/css">
    .donate_title{ background-color: #F2F2C6; color: #c00; background-image: url(/static/images/dotted_line_bg3.gif);}
    .project_list li{ margin-bottom: 5px}
    .field{ text-align: right;width: 100px}
    table td{padding:3px}
    .red{ color: #f00}
</style>

<script language="javascript">
    // 返回修改
    function history_back() {
	var form=document.getElementById('donate_form');
	form.action='<?= URL::site('donate/donateStep2') ?>';
	form.submit();
    }
</script>
<div id="main_left" style="background-color:#FCFCF0;height:700px">

    <?php if (!isset($err_msg)): ?>

        <form action="https://pay.ips.com.cn/ipayment.aspx" method="post"  id="donate_form">
    	<p ><img src="/static/images/on-line_donate.gif" /></p>
    	<p class="donate_title" >支付确认</p>
    	<Table cellspacing="0" cellpadding="0" style="margin:20px">

    	    <tr>
    		<td class="field">&nbsp;支付金额：</td>
    		<td><span style="font-weight:bold;color: #f30;font-size:26px"><?= $amount ?></span> 元</td>
    	    </tr>

    	    <tr>
    		<td class="field">&nbsp;支付个人(单位)：</td>
    		<td><?= $annual['donor'] ?></td>
    	    </tr>
    	    <tr>
    		<td class="field">&nbsp;捐赠编号：</td>
    		<td><?= $annual['billno'] ?></td>
    	    </tr>
    	    <tr>
    		<td class="field">&nbsp;支付方式：</td>
    		<td><select name="Gateway_Type">
    			<option value="01" selected="selected">借记卡</option>
    			<option value="04">IPS账户支付</option>
    			<option value="08">IB支付</option>
    			<option value="16">电话支付</option>
    			<option value="64">储值卡支付</option>
    		    </select></td>
    	    </tr>
    	    <tr>
    		<td class="field">&nbsp;语言种类：</td>
    		<td> <select name="Lang">
    			<option value="GB">简体中文</option>
    			<option value="EN">英语</option>
    			<option value="BIG5">繁体中文</option>
    			<option value="JP">日文</option>
    			<option value="FR">法文</option>
    		    </select></td>
    	    </tr>
    	</Table>

    	<div style="text-align:center;padding-bottom: 50px">
    	    <input type="submit" value="立即前往支付" class="button_blue">

    	</div>

    	<input type="hidden" name="Mer_code" value="<?= $Mer_code ?>">
    	<input type="hidden" name="Billno" value="<?= $annual['billno'] ?>">
    	<input type="hidden" name="Amount"  value="<?= $amount ?>" />
    	<input type="hidden" name="Date" value="<?= $bill_date ?>" />
    	<input type="hidden" name="Currency_Type"  value="<?=$Currency_Type?>" />
    	<input type="hidden" name="Merchanturl"  value="<?= $Merchanturl ?>" />
    	<input type="hidden" name="FailUrl"  value="<?= $FailUrl ?>" />
    	<input type="hidden" name="ErrorUrl"  value="<?= $ErrorUrl ?>" />
    	<input type="hidden" name="Attach"  value="<?= $Attach ?>" />
    	<input type="hidden" name="DispAmount" value="<?= $amount ?>" />
    	<input type="hidden" name="OrderEncodeType"  value="<?= $OrderEncodeType ?>" />
    	<input type="hidden" name="RetEncodeType"  value="<?= $RetEncodeType ?>" />
    	<input type="hidden" name="Rettype"  value="<?= $Rettype ?>" />
	<input type="hidden" name="ServerUrl" value="<?=$ServerUrl ?>">
	<input type="hidden" name="SignMD5" value="<?=$SignMD5?>">
        </form>
    <?php else: ?>
<p ><img src="/static/images/on-line_donate.gif" /></p>
	        <div style="margin:20px"><?= $err_msg ?></div>
    <?php endif; ?>
	    </div>
<?php
		include 'sidebar.php';
?>
<div class="clear"></div>