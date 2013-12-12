    <h3> 档案信息：</h3>
  <p class="dotted_line" ></p>
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:10px;">
  <tr>
    <td width="20%" style="text-align: right;padding-right: 5px">姓名：</td>
    <td width="65%"><?=$user['name']?></td>
    <td rowspan="3" width="15%" style="text-align:right;border: 2px solid #f333"><?= View::factory('inc/user/avatar', array('id'=>$user['id'],'size'=>48,'sex'=>$user['sex'])) ?></td>
  </tr>
  <tr>
    <td style="text-align: right;padding-right: 5px">性别：</td>
    <td colspan="2"><?=$user['sex']?></td>
  </tr>
  <tr>
    <td style="text-align: right;padding-right: 5px">学号：</td>
    <td colspan="2"><?=$user['student_no']?$user['student_no']:'-' ?></td>
  </tr>
  <tr>
    <td style="text-align: right;padding-right: 5px">就读时间：</td>
    <td colspan="2"><?=$user['begin_year']?$user['begin_year']:'?' ?>~<?=$user['graduation_year']?$user['graduation_year']:'?' ?>年</td>
  </tr>
  <tr>
    <td style="text-align: right;padding-right: 5px">就读专业：</td>
    <td colspan="2"><?=$user['speciality']?$user['speciality']:'-' ?></td>
  </tr>

    <tr>
    <td style="text-align: right;padding-right: 5px">就读学院(系)：</td>
    <td colspan="2"><?=$user['institute']?$user['institute']:'-'?></td>
  </tr>
</table>