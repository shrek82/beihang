<style type="text/css">
    #user_detail td{ padding:2px 4px}
</style>
<h4> 注册信息：</h4>
  <p class="dotted_line" ></p>
<table border="0" cellspacing="0" cellpadding="0" style="margin-top:10px;width:700px" id="user_detail">
  <tr>
    <td width="20%" style="text-align: right;padding-right: 5px"> 注册姓名：</td>
    <td width="40%"><?=$user['realname']?></td>
    <td rowspan="3" width="40%" style="text-align:left;border: 2px solid #f333"><?= View::factory('inc/user/avatar', array('id'=>$user['id'],'size'=>48,'sex'=>$user['sex'])) ?></td>
  </tr>
  <tr>
    <td width="20%" style="text-align: right;padding-right: 5px"> 登陆账号：</td>
    <td width="80%"><?=$user['account']?>(<?=$user['actived']?'<span style="color:green">已激活</span>':'<span style="color:gray">未激活</span>'?>)</td>
  </tr>
  <tr>
    <td style="text-align: right;padding-right: 5px">性别：</td>
    <td colspan="2"><?=$user['sex']?></td>
  </tr>
  <tr>
    <td style="text-align: right;padding-right: 5px">出生年月：</td>
    <td colspan="2"><?=$user['birthday']?$user['birthday']:'-' ?></td>
  </tr>
  <tr>
    <td style="text-align: right;padding-right: 5px">学号：</td>
    <td colspan="2"><?=$user['student_no']?$user['student_no']:'-' ?></td>
  </tr>
  <?php if($user['city']):?>
  <tr>
    <td style="text-align: right;padding-right: 5px">所在城市：</td>
    <td colspan="2"><?=$user['city']?$user['city']:'-' ?></td>
  </tr>
  <?php endif;?>
  <tr>
    <td style="text-align: right;padding-right: 5px">就读时间：</td>
    <td colspan="2"><?=$user['start_year']?$user['start_year']:'?' ?>~<?=$user['finish_year']?$user['finish_year']:'?' ?></td>
  </tr>
  <tr>
    <td style="text-align: right;padding-right: 5px">就读专业：</td>
    <td colspan="2"><?=$user['institute']?$user['institute'].'-':'' ?><?=$user['speciality']?$user['speciality']:'-' ?></td>
  </tr>
   <tr>
    <td style="text-align: right;padding-right: 5px">登陆次数：</td>
    <td colspan="2"><?=$user['login_num']?$user['login_num']:0 ?>次</td>
  </tr>
  <?php if($contact['tel']):?>
  <tr>
    <td style="text-align: right;padding-right: 5px">联系电话：</td>
    <td colspan="2"><?=$contact['tel']?$contact['tel']:'-'; ?></td>
  </tr>
  <?php endif;?>
  <?php if($contact['mobile']):?>
    <tr>
    <td style="text-align: right;padding-right: 5px">手机号码：</td>
    <td colspan="2"><?=$contact['mobile']?$contact['mobile']:'-' ?></td>
  </tr>
<?php endif;?>
  <?php if($contact['address']):?>
    <tr>
    <td style="text-align: right;padding-right: 5px">联系地址：</td>
    <td colspan="2"><?=$contact['address']?$contact['address']:'-'?></td>
  </tr>
  <?php endif;?>
    <tr>
    <td style="text-align: right;padding-right: 5px">注册日期：</td>
    <td colspan="2"><?=$user['reg_at']?></td>
  </tr>
    <tr>
    <td style="text-align: right;padding-right: 5px">工作单位：</td>
    <td colspan="2"><?php if(isset($work['company']) AND $work['company']):?><?= $work['start_at'] ?><?=str_replace('0000-00-00','',$work['leave_at'])==''?' ~ 至今':$work['leave_at']?>&nbsp;<?= $work['company'] ?>(<?= $work['job'] ?>)<?php else:?><span style="color:#999">暂无工作信息</span><?php endif;?></td>
  </tr>
</table>

  <?php if($webmanager):?>
    <p style="font-size:14px; font-weight: bold;margin:10px 0"> <?=$user['file_no']?'<span style="color:green">已挂钩的档案信息：</span>':'与“'.$user['realname'].'”姓名相同的档案信息：'; ?></p>
  <p class="dotted_line" ></p>
<?php if($alumni&&$user['file_no']):?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:10px;margin-bottom: 40px">
  <tr>
    <td width="20%" style="text-align: right;padding-right: 5px"> 姓名：</td>
    <td width="80%"><?=$alumni['name']?></td>
  </tr>
  <tr>
    <td width="20%" style="text-align: right;padding-right: 5px"> 档案编号：</td>
    <td width="80%"><?=$alumni['file_no']?></td>
  </tr>
  <tr>
    <td width="20%" style="text-align: right;padding-right: 5px"> 学号：</td>
    <td width="80%"><?=$alumni['student_no']?></td>
  </tr>
  <tr>
    <td width="20%" style="text-align: right;padding-right: 5px"> 就读情况：</td>
    <td width="80%"><?=$alumni['school']?> - <?=$alumni['institute']?> - <?=$alumni['speciality']?> - <?=$alumni['education']?>(<?=$alumni['begin_year']?$alumni['begin_year']:'?'?>~<?=$alumni['graduation_year']?$alumni['graduation_year']:'?' ?>年)</td>
  </tr>
  <tr>
    <td width="20%" style="text-align: right;padding-right: 5px"> 学制：</td>
    <td width="80%"><?=$alumni['education']?></td>
  </tr>
  <tr>
    <td width="20%" style="text-align: right;padding-right: 5px"> 出生年月：</td>
    <td width="80%"><?=$alumni['birthday']?></td>
  </tr>
  <tr>
    <td width="20%" style="text-align: right;padding-right: 5px"> 籍贯：</td>
    <td width="80%"><?=$alumni['native_place']?></td>
  </tr>
</table>
  <?php else:?>
  <div style="height:130px; overflow: auto">
  <ul style="margin:15px 20px" id="alumni_ul">
      <?php if($like_alumni):?>
      <?php  foreach ($like_alumni as $alumni):?>
      <li ><?=$alumni['name'] ?><?php if($alumni['birthday']):?>(<span style="<?=$alumni['birthday']==$user['birthday']?'color:#008000':'';?>"><?=$alumni['birthday']?></span>)<?php endif;?>：<?=$alumni['school']?$alumni['school'].'&nbsp;-&nbsp;':'' ?><span style="<?=$alumni['institute']==$user['institute']?'color:#008000':'';?>"><?=$alumni['institute']?$alumni['institute'].'&nbsp;-&nbsp;':'' ?></span><span style="<?=$user['speciality']&&$alumni['speciality']&&isbaohan($alumni['speciality'],$user['speciality'])?'color:#008000':''; ?>"><?=$alumni['speciality']?$alumni['speciality'].'&nbsp;-&nbsp;':'<span style="color:#999">档案无专业信息 </span>' ?></span>(<span style="<?=$user['start_year']&&$alumni['begin_year']&&$user['start_year']==$alumni['begin_year']?'color:#008000':'color:#ff0000';?>"><?=$alumni['begin_year']?$alumni['begin_year']:'?'?></span>~<span style="<?=$user['finish_year']==$alumni['graduation_year']?'color:#008000':'';?>"><?=$alumni['graduation_year']?$alumni['graduation_year']:'?' ?></span>年) - <?=$alumni['education']?>&nbsp;&nbsp;<a href="javascript:setAlumni(<?=$user['id']?>,<?=$alumni['id']?>)"  title="点击挂钩此档案并设置已认证" >挂钩</a> <span id="span_alumni_<?=$alumni['id']?>" style="vertical-align: middle"></span></li>
      <?php endforeach;?>
            <li></li>
      <?php else:?>
	    <li class="nodata">没有找到与姓名相同的档案信息</li>
      <?php endif;?>
  </ul>
   </div>
  <?php endif;?>
<?php endif;?>

  <?php if(isset($admin_logs) AND $admin_logs):?>
  <h3 style="color:#ff6600"> 管理日志：</h3>
  <ul style="margin:5px 20px; list-style: none" >
      <?php foreach($admin_logs AS $log):?>
  <li ><?=$log['realname']?> 于 <?=$log['manage_at']?> <?= $log['description'] ?></li>
  <?php endforeach;?>
  </ul>
  <?php endif;?>

<?php if ($_SESS->get('role') == '管理员' AND $user['role']!='管理员'): ?>
<form id="user_info" action="<?= URL::site('admin_user/setRole?val='.urlencode('校友(已认证)').'&id='.$user['id']) ?>" method="post">
<input type="submit" value="保存" style="display: none" />
</form>
<?php endif;?>

  <?php
  function isbaohan($str,$search){
      if(strstr($str,$search)){
          return true;
      }
      return false;
  }
  ?>