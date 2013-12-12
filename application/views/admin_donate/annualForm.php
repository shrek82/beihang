<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" >
<tr>
<td height="29" class="td_title" colspan="4"><b>		<?php if($content):?>
		 修改捐赠统计：
		<?php else:?>
		 新增捐赠统计：
		<?php endif;?></b>
</td>
</tr>
    </table>


<form action="" method="post" >
<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" >
	<tr>
	    <td class="field">* 捐赠项目或名称：</td>
	    <td><input type="text" name="project" value="<?=$content['project']?>"  style="width:400px" class="input_text"  /></td>
	</tr>

	<tr>
	    <td class="field">* 捐赠金额/物品名称：</td>
	    <td><input type="text" name="amount"  value="<?=$content['amount']?>" style="width:400px" class="input_text"/></td>
	</tr>

	<tr>
	    <td class="field">* 捐赠集体/个人：</td>
	    <td><input type="text" name="donor"  value="<?=$content['donor']?>" style="width:400px" class="input_text"/></td>
	</tr>

	<tr>
	    <td class="field">捐赠方式：</td>
	    <td><input type="text" name="methods" value="<?= $content['methods'] ?>"  style="width:400px" class="input_text"/>&nbsp;&nbsp;<span style="color:#999">如：邮局汇款</span></td>
	</tr>



	<tr>
	    <td class="field">捐赠人专业及毕业年份：</td>
	    <td><input type="text" name="speciality" value="<?= $content['speciality'] ?>"  style="width:400px" class="input_text"/></td>
	</tr>

	<tr>
	    <td class="field">公司名称：</td>
	    <td><input type="text" name="company" value="<?= $content['company'] ?>"  style="width:400px" class="input_text"/></td>
	</tr>

	<tr>
	    <td class="field">联系地址：</td>
	    <td><input type="text" name="address" value="<?= $content['address'] ?>"  style="width:400px" class="input_text"/></td>
	</tr>

	<tr>
	    <td class="field">联系电话：</td>
	    <td><input type="text" name="tel" value="<?= $content['tel'] ?>"  style="width:400px" class="input_text"/></td>
	</tr>

	<tr>
	    <td class="field">E-mail：</td>
	    <td><input type="text" name="email" value="<?= $content['email'] ?>"  style="width:400px" class="input_text"/></td>
	</tr>

	<tr>
	    <td class="field">支付状态：</td>
	    <td>
		    <input type="radio" name="payment_status" value="1" <?=$content['payment_status']=='1'||empty($content['payment_status'])?'checked="checked"':''; ?>/>已支付
		    <input type="radio" name="payment_status" value="0" <?=$content['payment_status']=='0'?'checked="checked"':''; ?> />暂未支付
	    </td>
	</tr>

	<tr>
	    <td class="field">订单编号：</td>
	    <td><input type="text" name="billno" value="<?= $content['billno'] ?>"  style="width:400px" class="input_text"/></td>
	</tr>

	<tr>
	    <td class="field">ips编号：</td>
	    <td><input type="text" name="ipsbillno" value="<?= $content['ipsbillno'] ?>"  style="width:400px" class="input_text"/></td>
	</tr>

	<tr>
	    <td class="field">支付日期：</td>
	    <td><input type="text" name="donate_at" value="<?= $content['donate_at'] ? $content['donate_at'] : date('Y-m-d') ?>"  style="width:400px" class="input_text"/></td>
	</tr>

	<tr>
	    <td class="field">* 捐赠日期：</td>
	    <td><input type="text" name="donate_at" value="<?= $content['donate_at'] ? $content['donate_at'] : date('Y-m-d') ?>"  style="width:400px" class="input_text"/></td>
	</tr>

	<tr>
	    <td class="field"></td>
	    <td style="padding:20px 0">
		
		<?php if($content):?>
		<input type="submit" value="保存修改" name="button" class="button_blue"/>
		<?php else:?>
		<input type="submit" value="确定添加" name="button"  class="button_blue"/>
		<?php endif;?>
		<input type="button" value="返回" class="button_gray" onclick="window.history.back()">

	    </td>
	</tr>

</table><br>

        <?php if($err): ?>
        <div class="notice"><?= $err; ?></div>
        <?php endif; ?>

       <input type="hidden" name="id" value="<?= $content['id'] ?>" />

</form>


<script type="text/javascript">
var ck = candyCKE('intro', 'Pro');
<?= Candy::initCKFinder('ck') ?>
</script>
