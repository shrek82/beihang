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
	form.action='<?=URL::site('donate/donateStep2')?>';
	form.submit();
    }
</script>
<div id="main_left" style="background-color:#FCFCF0">
    <form action="<?=URL::site('donate/donateStep3')?>" method="post"  id="donate_form">
	<p ><img src="/static/images/on-line_donate.gif" /></p>
	<p class="donate_title" style="margin-top:20px">捐赠项目</p>

	<div style="padding:10px 60px">
	    <ul class="project_list">
		<li><?=$post['project']?><input type="hidden" name="project" value="<?=$post['project']?>"></li>
                    <?php if(isset($post['will'])): ?>
		    <div id="donate_use" style="padding:5px 20px;">
			捐赠用途:<br>
			<?php foreach($post['will'] AS $w):?>
			<?=$w?>
			<input type="hidden" name="will[]" value="<?=$w?>"><br>
			<?php endforeach;?>
		    </div>
		<?php endif;?>
	    </ul>
	</div>

	<p class="donate_title" >捐赠金额</p>
	<Table cellspacing="0" cellpadding="0" style="margin:20px">
	    <tr>
		<td class="field">&nbsp;捐赠金额：</td>
		<td><span style="font-weight:bold;color: #f30;font-size:26px"><?=sprintf('%.2f', $post['amount'])?></span><input type="hidden" name="amount"  value="<?=$post['amount']?>">&nbsp;元&nbsp;</td>
	    </tr>
	</Table>

	<p class="donate_title" >捐赠个人(单位)信息</p>
	<Table cellspacing="0" cellpadding="0" style="margin:20px">

	    <tr>
		<td class="field"><span class="red">*</span>&nbsp;姓名：</td>
		<td><?=$post['donor']?><input type="hidden" name="donor" value="<?=$post['donor']?>"   ></td>
		<td class="field">性别：</td>
		<td>
		    <?=$post['sex']?><input type="hidden" name="sex"  value="<?=$post['sex']?>">
</td>
	    </tr>
	    <tr>
		<td class="field">出生年月：</td>
		<td><?=$post['birthday']?><input type="hidden" name="birthday"  value="<?=$post['birthday']?>"></td>
		<td class="field">籍贯：</td>
		<td><?=$post['birthplace']?><input type="hidden" name="birthplace"  value="<?=$post['birthplace']?>"></td>
	    </tr>

	    <tr>
		<td class="field"><span class="red">*</span>&nbsp;毕业专业：</td>
		<td><?=$post['speciality']?><input type="hidden" name="speciality" value="<?=$post['speciality']?>"></td>
		<td class="field">毕业年份：</td>
		<td><?=$post['graduation_year']?><input type="hidden" name="graduation_year"  value="<?=$post['graduation_year']?>"></td>
	    </tr>

	    <tr>
		<td class="field"><span class="red">*</span>&nbsp;单位及职务：</td>
		<td colspan="3"><?=$post['company']?><input type="hidden" name="company" value="<?=$post['company']?>"></td>
	    </tr>

	    <tr>
		<td class="field"><span class="red">*</span>&nbsp;联系地址：</td>
		<td colspan="3"><?=$post['address']?><input type="hidden" name="address" value="<?=$post['address']?>"></td>
	    </tr>

	    <tr>
		<td class="field"><span class="red">*</span>&nbsp;邮编：</td>
		<td colspan="3"><?=$post['zipcode']?><input type="hidden" name="zipcode"  value="<?=$post['zipcode']?>"></td>
	    </tr>

	    <tr>
		<td class="field"><span class="red">*</span>&nbsp;联系电话：</td>
		<td colspan="3"><?=$post['tel']?><input type="hidden" name="tel"  value="<?=$post['tel']?>"></td>
	    </tr>

	    <tr>
		<td class="field">手机：</td>
		<td colspan="3"><?=$post['mobile']?><input type="hidden" name="mobile"  value="<?=$post['mobile']?>"></td>
	    </tr>

	    <tr>
		<td class="field"><span class="red">*</span>&nbsp;E-mail：</td>
		<td colspan="3"><?=$post['email']?><input type="hidden" name="email"  value="<?=$post['email']?>"></td>
	    </tr>

	    <tr>
		<td class="field">捐赠来源：</td>
		<td colspan="3">
		    <?=$post['donate_from']?><input type="hidden" name="donate_from"  value="<?=$post['donate_from']?>">
		    <?php if(isset($post['collectiv'])&&$post['donate_from']=='集体捐赠'):?>
		    &nbsp;集体名称：<input type="hidden" name="collectiv"  value="<?=$post['collectiv']?>">
		    <?php endif;?>
		    
		</td>
	    </tr>

	    <tr>
		<td class="field">回寄捐赠收据：</td>
		<td colspan="3">
		    <?=$post['return_receipt']?><input type="hidden" name="return_receipt"  value="<?=$post['return_receipt']?>">
		</td>
	    </tr>


	</Table>

	<p class="donate_title" >捐赠寄语</p>
	<Table cellspacing="0" cellpadding="0" style="margin:20px">
	    <tr>
		<td class="field">捐赠寄语：</td>
		<td>
		    <?=$post['message']?><input type="hidden" name="message"  value="<?=$post['message']?>">
		</td>
	    </tr>
	</Table>

	<p class="donate_title" style="margin-top:20px">支付方式</p>
	<div style="padding:10px 30px">
	    <ul class="project_list">
		<li><?=$post['methods']?><input type="hidden" name="methods"  value="<?=$post['methods']?>"></li>
	    </ul>
	</div>

	<div style="text-align:center;padding-bottom: 50px">
	    <input type="submit" value="确定捐赠" class="button_blue">
	    <input type="button" value="返回修改" class="button_gray"  onclick="history_back()">
	    
	</div>

    </form>
</div>
<?php
include 'sidebar.php';
?>
<div class="clear"></div>