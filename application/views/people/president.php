<style type="text/css">
    .ptable{border:1px solid #ccc; margin:5px 0px 25px 0}
    .ptable td{height: 22px;padding:2px 4px}
    .ptable .even_tr td{ background: #EBF3FA}
    .thead td{ background: #DCE8F2; height: 22px; line-height: 22px; font-weight: bold; color: #333}
</style>
<div id="main_left">
    <p><img src="/static/images/president_title.gif" /></p><br>

<?php foreach($president_period as $key=>$value):?>
    <p style="color:#c00; font-weight: bold;font-size: 14px"><?=$value?></p>
<table width="100%" border="0" cellpadding="0" cellspacing="1" class="ptable">
	<tr class="thead">
	    <td>校名</td>
	    <td>职位</td>
            <td style="width:50px">姓名</td>
	    <td>任期</td>
	</tr>
    <?php foreach($president[$key] as $k=> $p):?>
	<tr class="<?php if(($k)%2==0){echo'even_tr';} ?>">
	    <td><?=$p['school']?></td>
	    <td><?=$p['jobs']?></td>
	    <td><?=$p['name']?></td>
	    <td><?=$p['term']?></td>
	</tr>
    <?php endforeach;?>
	</table>
    <?php endforeach;?>


</div>
<?php
include 'sidebar.php';
?>
<div class="clear"></div>