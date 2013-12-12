<?php
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
?>
<data>
    <info>新鲜事</info>
    <description><?=$description?>新鲜事</description>
    <page><?=$page?></page>
    <pageSize><?=$pagesize?></pageSize>
    <total><?=$total_weibo?></total>
<?php if (count($weibo)>0): ?>
<?php foreach ($weibo AS $w): ?>
    <item>
	<id><?= $w['cid'] ?></id>
	<face>http://<?=$_SERVER['HTTP_HOST']?><?=Model_User::avatar($w['user_id'], 48, $w['sex'])?></face>
      <bigImagePath><?=$w['img_path']?'http://'.$_SERVER['HTTP_HOST'].'/'.str_replace('resize/','',$w['img_path']):'';?></bigImagePath>
      <smallImagePath><?=$w['img_path']?'http://'.$_SERVER['HTTP_HOST'].'/'.$w['img_path']:'';?></smallImagePath>
      <allImagePath><?=$w['img_paths']?$w['img_paths']:'';?></allImagePath>
	<uid><?=$w['user_id']?></uid>
	<userName><?= $w['realname'] ?></userName>
	<speciality><?=$w['speciality']?></speciality>
	<startYear><?=$w['start_year']?></startYear>
       <postDate> <?=$w['post_at'] ?></postDate>
	<content><?= $w['content'] ?></content>
	<replyNum><?= $w['reply_num']>0?$w['reply_num']:'0'; ?></replyNum>
	<clients><?= $w['clients'] ?></clients>
	<fromForward><?=$w['from_forward']?></fromForward>
	<forwardNum><?=$w['forward_num']?$w['forward_num']:'0';?></forwardNum>
	</item>
<?php endforeach; ?>
<?php endif; ?>
</data>