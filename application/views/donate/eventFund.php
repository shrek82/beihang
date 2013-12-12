<!-- donate/eventFund:_body -->
<div id="main_left">
    <p><img src="/static/images/donate_fund.gif" /></p>

<div style="margin:10px">
    <form method="get" action="<?=$_URL?>">
        <input type="text" value="<?=$name?>" id="name" name="name" placeholder="输入捐赠人姓名搜索" size="50" class="input_text" />
    <input type="submit" value="搜索"  class="button_blue"/>
    </form>
</div>
    <table border="0" width="100%" id="dontae_table" cellspacing="0" cellpadding="0" style="margin-top: 20px">
	<thead>
	    <tr>
		<th style="text-align: center;width:20%">&nbsp;&nbsp;捐赠日期</th>
		<th  style="text-align: left;width:60%">捐赠人</th>
		<th  style="text-align: left">捐赠金额</th>
		
	    </tr>
	</thead>
	<tbody>
    <?php foreach($statistics as $s): ?>
	    <tr>
		<td style="text-align: center;"><?= date('Y-n-d',strtotime($s['donate_at'])); ?></td>
		<td><?=$s['donor']?></td>
		<td><?=$s['amount']?></td>
		
	    </tr>
    <?php endforeach; ?>

	<?php if(!$statistics):?>
	    <tr>
		<td colspan="3">
		    <div class="nodata" style="padding:10px">很抱歉，暂时还没有该类内容。</div>
		</td>
	    </tr>
	 <?php endif;?>
	    
	</tbody>
    </table>

    <?= $pager ?>

</div>
<?php
include 'sidebar.php';
?>
<div class="clear"></div>
