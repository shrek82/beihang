<!-- donate/thanks:_body -->
<div id="main_left">
    <p><img src="/static/images/donate_thanks.gif" /></p>

<div style="margin:10px">
    <form method="get" action="<?=$_URL?>">
        <input type="text" value="<?=$name?>" id="name" name="name" placeholder="输入单位或姓名搜索" size="50" class="input_text" />
    <input type="submit" value="搜索"  class="button_blue"/>
    </form>
</div>
    <table border="0" width="100%" id="dontae_table" cellspacing="0" cellpadding="0" style="margin-top: 20px">
	<thead>
	    <tr>
		<th style="text-align: center;width:18%">&nbsp;&nbsp;捐赠日期</th>
		<th  style="text-align: left">捐赠项目</th>
		<th  style="text-align: left">捐赠人</th>
		<th  style="text-align: left">专业及毕业年份</th>
		<th  style="text-align: left">捐赠实物或金额</th>
	    </tr>
	</thead>
	<tbody>
    <?php foreach($statistics as $s): ?>
	    <tr>
		<td style="text-align: center;"><?= date('Y-m-d',strtotime($s['donate_at'])); ?></td>
		<td><?= $s['project'] ?$s['project']:'年度捐赠';?></td>
		<td><?=Text::limit_chars($s['donor'], 12, '...')?></td>
		<td><?=Text::limit_chars($s['speciality'], 12, '...')?></td>
		<td><?=Text::limit_chars($s['amount'], 12, '...')?></td>
	    </tr>
    <?php endforeach; ?>
	</tbody>
    </table>

    <?= $pager ?>

</div>
<?php
include 'sidebar.php';
?>
<div class="clear"></div>
