<?php
$sidebar_links = array(
   // 'donate/statistics' => '捐赠统计',
    'donate/thanks'=>'捐赠鸣谢',
    //'donate/eventFund' => '校友活动基金',
    'donate/reports'=>'相关报道',
    'donate/reflections' => '捐赠感言',
    'donate/meth' => '捐赠途径',
    'donate/annual' => '年度捐赠',
);
?>
<div id="sidebar_right">
    <p class="sidebar_title">校友捐赠</p>
    <div class="sidebar_box" style="height:650px">
	<ul class="sidebar_menus">
<?php	foreach ($sidebar_links as $key => $value):?>
	    <li>
		<a href="<?=URL::site($key)?>" class="<?= $_C.'/'.$_A == $key ? 'cur':'' ?>"><?=$value?></a>
	    </li>
<?php endforeach;?>
	    <li><a href="http://www.zuef.zju.edu.cn/" target="_blank">北京航空航天大学教育基金会</a></li>
     <li><a href="http://zuef-usa.org/" target="_blank">北京航空航天大学美国教育基金会</a></li>
	</ul>
	<br>
	<div style="text-align:center">
	    <input type="button" value="我要捐赠" class="button_blue" onclick="window.location.href='<?=URL::site('donate/wantDonate');?>'">
	</div>
    </div>
</div>