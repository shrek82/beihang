<!-- service/index:_body -->
<div id="main_left">
    <p><img src="/static/images/service_title.gif"></p>
    <p style="padding:10px 15px 0px 15px;color: #999;font-size:14px">吃住行指南 -> <?=$content['title']?></p>
    <div style="margin:10px 15px;">
	<?php foreach($school as $s):?>
	<a href="<?=URL::site('service/guide?id='.$s['id'])?>" style="<?=$id==$s['id']?'font-weight:bold;color:#c00':''?>"><?=$s['title']?></a>&nbsp;&nbsp;
	<?php endforeach;?>
    </div>
    <div style="padding:10px 15px;line-height: 1.6em"><?=$content['content']?></div>
</div>

<!--链接类型 -->
<div id="sidebar_right">
<?php include 'sidebar.php'; ?>
</div>
<div class="clear"></div>

