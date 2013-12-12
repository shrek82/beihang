<!-- donate/wantDonate:_body -->
<style type="text/css">
    .donate_title{ background-color: #F2F2C6; color: #c00; background-image: url(/static/images/dotted_line_bg3.gif);}
    .project_list li{ margin-bottom: 5px}
    .field{ text-align: right;width: 100px}
    .red{ color: #f00}
</style>
<script language="javascript">
    //显示或隐藏捐赠用途
    function donate_use(val){
	document.getElementById('donate_use').style.display=val=='1'?'':'none';
    }
    //检测表单数据完整性
    function checkform(){
	var project=document.getElementsByName("project");
	var amount=document.getElementById("amount");
	var donor=document.getElementById("donor");
	var speciality=document.getElementById("speciality");
	var company=document.getElementById("company");
	var address=document.getElementById("address");
	var zipcode=document.getElementById("zipcode");
	var tel=document.getElementById("tel");
	var email=document.getElementById("email");

	for (var i=0;i<project.length;i++)
	{
	    if(project[i].checked){
		var project_selected=true;
	    }
	}

	if(!project_selected){
	    alert("您还没有选择捐赠项目，请先选择，谢谢！");
	    return false;
	}

	if(amount.value==''){
            alert('您还没有填写捐赠金额，请先填写，谢谢！');
	    amount.focus();
	    return false;
	}
	else{
	    if(!isNumber(amount.value)){
		alert('很抱歉，您填写的金额不正确，请填写数字，谢谢！');
		amount.focus();
		return false;
	    }
	}

	if(donor.value==''){
            alert('您还没有填写姓名，请先填写，谢谢！');
	    donor.focus();
	    return false;
	}

	if(speciality.value==''){
            alert('您还没有填写专业名称，请先填写，谢谢！');
	    speciality.focus();
	    return false;
	}

	if(company.value==''){
            alert('您还没有填写公司及职位名称，请先填写，谢谢！');
	    company.focus();
	    return false;
	}

	if(address.value==''){
            alert('您还没有填写联系地址，请先填写，谢谢！');
	    address.focus();
	    return false;
	}

	if(zipcode.value==''){
            alert('您还没有填写邮编，请先填写，谢谢！');
	    zipcode.focus();
	    return false;
	}

	if(tel.value==''){
            alert('您还没有填写邮编，请先填写，谢谢！');
	    tel.focus();
	    return false;
	}

	if(email.value==''){
            alert('您还没有填写E-mail，请先填写，谢谢！');
	    email.focus();
	    return false;
	}
	else{
           if(!checkemail(email.value)){
            alert('很抱歉，E-mail格式不正确，请重新输入，谢谢！');
	    email.focus();
	    return false;
	   }
	}

    }

    //验证email是否书写正确
    function checkemail(str){
	var Expression=/\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/;
	var objExp=new RegExp(Expression);
	if(objExp.test(str)==true){
	    return true;
	}else{
	    return false;
	}
    }

    //验证是否为数字
    function isNumber(oNum)
    {
	if(!oNum||oNum=='') return false;
	var strP=/^\d+(\.\d+)?$/;
	if(!strP.test(oNum)) return false;
	try{
	    if(parseFloat(oNum)!=oNum) return false;
	}
	catch(ex)
	{
	    return false;
	}
	return true;
    }
</script>
<?php
//捐赠用途
$will_base=array(
    '帮扶贫困学生，改善他们的学习生活条件',
    '帮扶需要紧急援助的校友',
    '设立“北航校友学生国际交流奖学金”、“北航校友讲座教授”等',
    '支持母校校友工作','其它有益于母校发展的项目'
    );
?>
<div id="main_left" style="background-color:#FCFCF0">
    <form action="<?=URL::site('donate/donateStep2')?>" method="post" onSubmit="return checkform()">
	<p ><img src="/static/images/on_line_donate.gif" /></p>
	<p class="donate_title" style="margin-top:20px">捐赠项目</p>
	<div style="padding:10px 30px">
	    <ul class="project_list">
		<li><label><input type="radio" name="project" value="年度捐赠"  onclick="donate_use('1')" <?=$project=='年度捐赠'?'checked="checked"':'';?> />年度捐赠</label>

		    <div id="donate_use" style="padding:5px 20px; display: none;">
		    捐赠用途<br>
		    <?php foreach($will_base AS $w):?>
		    <label><input type="checkbox" name="will[]" value="<?=$w?>"   <?php foreach($will as $key){if($w==$key){echo 'checked';}}?>/><?=$w?></label><br>
		    <?php endforeach;?>
		    </div>
		</li>
		<li><label><input type="radio" name="project" value="校友活动基金"  onclick="donate_use()" <?=$project=='校友活动基金'?'checked="checked"':'';?>/>校友活动基金</label></li>
		<li><label><input type="radio" name="project" value="捐订《北航校友》"  onclick="donate_use()"  <?=$project=='捐订《北航校友》'?'checked="checked"':'';?>/>捐订《北航校友》</label></li>
		<li><label><input type="radio" name="project" value="其他" onclick="donate_use()" <?=$project=='其他'?'checked="checked"':'';?>/>其他</label></li>
	    </ul>
	</div>

	<p class="donate_title" >捐赠金额</p>
	<table cellspacing="0" cellpadding="0" style="margin:20px">
	    <tr>
		<td class="field"><span class="red">*</span>&nbsp;捐赠金额：</td>
		<td><input type="text" name="amount" id="amount" class="input_text" style="width:250px;height:26px;color:#c00; font-weight: bold;font-size: 18px;padding:4px" value="<?=$amount; ?>">&nbsp;元&nbsp;<span class="red"> (请填入数字)</span></td>
	    </tr>
	</table>

	<p class="donate_title" >捐赠个人(单位)信息</p>
	<table cellspacing="0" cellpadding="0" style="margin:20px">

	    <tr>
		<td></td>
		<td colspan="3" class="red" style="padding:5px 0">
		 请捐赠个人填写尽可能详细信息；若是集体捐赠，请填写联系人信息！
		</td>
	    </tr>
	    <tr>
		<td class="field"><span class="red">*</span>&nbsp;姓名：</td>
		<td><input type="text" name="donor" id="donor" class="input_text" style="width:170px" value="<?=$donor; ?>"></td>
		<td class="field">性别：</td>
		<td><select name="sex">
			<option value="男" <?=$sex=='男'?'selected':''; ?>>男</option>
			<option value="女" <?=$sex=='女'?'selected':''; ?>>女</option>
		    </select></td>
	    </tr>
	    <tr>
		<td class="field">出生年月：</td>
		<td><input type="text" name="birthday" class="input_text" style="width:170px" value="<?=$birthday; ?>"></td>
		<td class="field">籍贯：</td>
		<td><input type="text" name="birthplace" class="input_text" style="width:170px" value="<?=$birthplace; ?>"></td>
	    </tr>

	    <tr>
		<td class="field"><span class="red">*</span>&nbsp;毕业专业：</td>
		<td><input type="text" name="speciality" id="speciality" class="input_text" style="width:170px" value="<?=$speciality; ?>"></td>
		<td class="field">毕业年份：</td>
		<td><input type="text" name="graduation_year" class="input_text" style="width:170px" value="<?=$graduation_year; ?>"></td>
	    </tr>

	    <tr>
		<td class="field"><span class="red">*</span>&nbsp;单位及职务：</td>
		<td colspan="3"><input type="text" name="company" id="company" class="input_text" style="width:400px" value="<?=$company; ?>"></td>
	    </tr>

	    <tr>
		<td class="field"><span class="red">*</span>&nbsp;联系地址：</td>
		<td colspan="3"><input type="text" name="address" id="address" class="input_text" style="width:400px" value="<?=$address; ?>"></td>
	    </tr>

	    <tr>
		<td class="field"><span class="red">*</span>&nbsp;邮编：</td>
		<td colspan="3"><input type="text" name="zipcode"  id="zipcode" class="input_text" style="width:400px" value="<?=$zipcode; ?>"></td>
	    </tr>

	    <tr>
		<td class="field"><span class="red">*</span>&nbsp;联系电话：</td>
		<td colspan="3"><input type="text" name="tel"  id="tel" class="input_text" style="width:400px" value="<?=$tel; ?>"></td>
	    </tr>

	    <tr>
		<td class="field">手机：</td>
		<td colspan="3"><input type="text" name="mobile" class="input_text" style="width:400px" value="<?=$mobile; ?>"></td>
	    </tr>

	    <tr>
		<td class="field"><span class="red">*</span>&nbsp;E-mail：</td>
		<td colspan="3"><input type="text" name="email" id="email" class="input_text" style="width:400px" value="<?=$email; ?>"></td>
	    </tr>

	    <tr>
		<td class="field">捐赠来源：</td>
		<td colspan="3">
		    <label><input type="radio" name="donate_from" value="个人捐赠"  <?=$donate_from=='个人捐赠'||empty($donate_from)?'checked="checked"':''; ?> />个人捐赠</label>
		    <label><input type="radio" name="donate_from" value="集体捐赠"  <?=$donate_from=='集体捐赠'?'checked="checked"':''; ?>/>集体捐赠 </label>
		    集体名称：<input type="text" name="collectiv" class="input_text" size="44px"  value="<?=$collectiv; ?>">
		    
		</td>
	    </tr>

	    <tr>
		<td class="field">回寄捐赠收据：</td>
		<td colspan="3">
                    <input type="radio" name="return_receipt" value="不需要" <?=$return_receipt=='不需要'||empty($return_receipt)?'checked="checked"':''; ?>/>不需要
		    <input type="radio" name="return_receipt" value="需要" <?=$return_receipt=='需要'?'checked="checked"':''; ?> />需要
		</td>
	    </tr>


	</table>

	<p class="donate_title" >捐赠寄语</p>
	<table cellspacing="0" cellpadding="0" style="margin:20px">
	    <tr>
		<td class="field">捐赠寄语：</td>
		<td>
		    <textarea name="message" id="message" style="width:500px; height: 100px;" class="input_text"><?=$message; ?></textarea>
		</td>
	    </tr>
	</table>

	<p class="donate_title" style="margin-top:20px">捐赠方式</p>
	<div style="padding:10px 30px">
	    <ul class="project_list">
		<li><label><input type="radio" name="methods" value="以个人、班级、年级或地区为单位，通过个人、班级、年级召集人或地方校友会汇总到校友总会" checked="checked"  <?=$methods=='以个人、班级、年级或地区为单位，通过个人、班级、年级召集人或地方校友会汇总到校友总会'||empty($methods)?'checked="checked"':''; ?>/>以个人、班级、年级或地区为单位，通过个人、班级、年级召集人或地方校友会汇总到校友总会</label></li>
		<li><label><input type="radio" name="methods" value="邮局汇款"  <?=$methods=='邮局汇款'?'checked="checked"':''; ?>/>邮局汇款</label></li>
		<li><label><input type="radio" name="methods" value="网上捐款"  <?=$methods=='网上捐款'?'checked="checked"':''; ?>/>网上捐款</label></li>
		<li><label><input type="radio" name="methods" value="银行汇款/转账"  <?=$methods=='银行汇款/转账'?'checked="checked"':''; ?>/>银行汇款/转账</label></li>
	    </ul>
	</div>

	<div style="text-align:center;padding-bottom: 50px">
	    <input type="submit" value="下一步" class="button_blue">
	</div>

    </form>
</div>
<?php
include 'sidebar.php';
?>
<div class="clear"></div>