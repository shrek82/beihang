<!-- donate/annual:_body -->
<div id="main_left">
    <p><img src="/static/images/donate_annual.gif" /></p>

    <div style="padding:20px; line-height: 1.6em">
	<?=$intro['content']?>
    </div>

<fieldset style="border:1px solid #ccc;margin:10px">
  <legend style="font-weight:bold;font-size:14px; color: #c00">
    &nbsp;常见问题&nbsp;
  </legend>
    <div style="padding:10px; line-height: 1.6em">
	<?=$faq['content']?>
    </div>
</fieldset>

<fieldset style="border:1px solid #ccc;margin:10px">
  <legend style="font-weight:bold;font-size:14px; color: #c00">
    &nbsp;捐赠指南&nbsp;
  </legend>
    <div style="padding:10px; line-height: 1.6em">
	<?=$guide['content']?>
    </div>
</fieldset>

<fieldset style="border:1px solid #ccc;margin:10px">
  <legend style="font-weight:bold;font-size:14px; color: #c00">
    &nbsp;捐赠鸣谢&nbsp;
  </legend>

    <table border="0" width="98%" id="dontae_table" cellspacing="0" cellpadding="0" style="margin:10px">
	<thead>
	    <tr>
		<th style="text-align: center;width:18%">&nbsp;&nbsp;捐赠日期</th>
		<th  style="text-align: left">捐赠项目</th>
		<th  style="text-align: left">捐赠人</th>
		<th  style="text-align: left">专业及毕业年份</th>
		<th  style="text-align: left">捐赠金额</th>
	    </tr>
	</thead>
	<tbody>
    <?php foreach($statistics as $s): ?>
	    <tr>
		<td style="text-align: center;"><?= date('Y-n-d',strtotime($s['donate_at'])); ?></td>
		<td><?= $s['project'] ?></td>
		<td><?=$s['donor']?></td>
		<td><?=$s['speciality']?></td>
		<td><?=$s['amount']?></td>
	    </tr>
    <?php endforeach; ?>
	</tbody>
    </table>

    <div style=" text-align: right;padding:10px"><a href="<?=URL::site('donate/thanks?name=年度捐赠') ?>"> >> 更多</a></div>
</fieldset>
</div>
<?php
include 'sidebar.php';
?>
<div class="clear"></div>
