<?php foreach($weibo AS $w):?>
<div style="padding:5px 5px 10px 0px;  ">
<div style="width:30px; float: left">
    <a href="/weibo?id=<?=$_ID?>&uid=<?=$w['user_id']?>" ><img src="<?=Model_User::avatar($w['user_id'], 48, $w['sex'])?>" style="width:30px; height: 30px; border: 1px solid #eee; padding: 2px"></a></div>
<div style="width:170px; float:right">
<a href="/weibo?id=<?=$_ID?>&uid=<?=$w['user_id']?>" ><?= $w['realname'] ?></a>：<?= Common_Global::weibohtml($w['content'],$_ID,20) ?>
<p style="color:#999"><?=Date::ueTime($w['post_at'])?></p>
</div>
<div class="clear"></div>
</div>
<?php endforeach;?>
<?php if(!$weibo):?>
<div class="nodata">还没有任何新鲜事</div>
<?php endif;?>