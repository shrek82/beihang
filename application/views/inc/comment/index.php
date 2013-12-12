<!--论坛回帖列表 -->

<div id="comment_list">

    <?php if(count($comments) == 0): ?>
    <span style="color:#999">暂无任何讨论，去抢个沙发？</span>
    <?php else: ?>

    <?= $pager ?>
    <?php foreach($comments as $ix=>$cmt): ?>

<div class="bbs_topic bbs_reply" id="comment_<?= $cmt['id'] ?>">
    <div class="left same_height">
	<a href="<?= URL::site('user_home?id='.$cmt['user_id']) ?>" target="_blank"><img src="<?= Model_User::avatar($cmt['user_id'],128).'?gen_at='.time() ?>" class="face" title="点击进入该主页"></a><br>
	<a href="<?= URL::site('user_home?id='.$cmt['user_id']) ?>" class="commentor"><?= $cmt['User']['realname'] ?></a>

	<br><?php if($cmt['User']['city']):?><span style='color:#999'>(<?= $cmt['User']['city'] ?>)</span><?php endif;?><br>
    </div>
    <div class="right same_height">
	<div class="topic_postdate">
		<span style="color:#f60;"><strong><?= ($pager->current_page-1)*$pager->items_per_page+$ix+1 ?></strong> 楼</span>
		&nbsp;&nbsp;发布于<?= $cmt['post_at'] ?></div>
	<div class="comment_content"><?= $cmt['content'] ?></div>
	<div class="topic_tool"><a href="javascript:reply_comment(<?= $cmt['id'] ?>)" class="ico_reply">回复</a>&nbsp;&nbsp;&nbsp;<a href="javascript:quote_comment(<?= $cmt['id'] ?>)" class="ico_quote">引用</a>
<?php if($cmt['user_id'] == $_SESS->get('id')): // 能修改自己 ?>
&nbsp;&nbsp;&nbsp;<a href="javascript:modify_comment(<?= $cmt['id'] ?>)" class="ico_edit">修改</a>
<?php endif; ?></div>
    </div>
</div>
    <?php endforeach; ?>
    <?= $pager ?>

    <?php endif; ?>

</div>

<!--//回帖 -->