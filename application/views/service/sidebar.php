<?php foreach($categorys AS $key=>$c) :?>
<p class="sidebar_title" <?=$key?'style="margin-top:-1px"':null?>><?=  str_replace('校友服务-', '', $c['name'])?></p>
    <div class="sidebar_box" >
<ul class="sidebar_menus">
<?php foreach($c['items'] AS $key=>$s) :?>
    <li><?php if(empty($s['redirect'])):?><a href="<?=URL::site('service?id='.$s['id']) ?>"><?else:?><a href="<?=$s['redirect'] ?>" target="_blank"><?php endif;?><?=$s['title']?></a></li>
<?php endforeach; ?>
</ul>
    </div>
<?php endforeach; ?>
