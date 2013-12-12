    <p class="sidebar_title">为您服务</p>
    <div class="sidebar_box" >
<ul class="sidebar_menus">
<?php foreach($service AS $key=>$s) :?>
    <li><?php if(empty($s['redirect'])):?><a href="<?=URL::site('service?id='.$s['id']) ?>"><?else:?><a href="<?=$s['redirect'] ?>" target="_blank"><?php endif;?><?=$s['title']?></a></li>
<?php endforeach; ?>
</ul>
    </div>
    <p class="sidebar_title" style="margin-top:-1px">招生就业</p>
    <div class="sidebar_box" >
<ul class="sidebar_menus">
<?php foreach($jobs AS $key=>$s) :?>
    <li><?php if(empty($s['redirect'])):?><a href="<?=URL::site('service?id='.$s['id']) ?>"><?else:?><a href="<?=$s['redirect'] ?>" target="_blank"><?php endif;?><?=$s['title']?></a></li>
<?php endforeach; ?>
</ul>
    </div>