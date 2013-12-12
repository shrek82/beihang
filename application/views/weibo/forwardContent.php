<?php
$weibo = Doctrine_Query::create()
		->select('c.*,u.realname AS realname,u.sex AS sex,u.speciality as speciality ,u.start_year as start_year')
		->from('WeiboContent c')
		->leftJoin('c.User u')
		->where('c.id=?', $wid)
		->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
?>
<div class="forwardContent">
    <div  class="forward_top"></div>
    <div class="forward_con">
	<a href="/weibo?id=<?= $_ID ?>&uid=<?= $weibo['user_id'] ?>" title="Ta的所有话题" style="font-size:12px">@<?= $weibo['realname'] ?></a>：<?= Common_Global::weibohtml($weibo['content'],$_ID) ?>
	<div class="weibo_content_tool" style="margin:0">
	    <p class="wct_left"><a href='/weibo/content?id=<?= $_ID ?>&wid=<?= $weibo['id'] ?>'><?= Date::ueTime($weibo['post_at']); ?>前</a>&nbsp;&nbsp;<span class="clients"><?= $weibo['clients'] ? '来自<span class="cname">' . $weibo['clients'] . '</span>' : ''; ?></span></p>
	    <p class="wct_right"><a href="javascript:;"  onclick="retweet(<?=$weibo['id']?>)">转发<?= $weibo['forward_num'] > 0 ? '(' . $weibo['forward_num'] . ')' : ''; ?></a>&nbsp;|&nbsp;<a href="/weibo/content?id=<?=$_AA['id']?>&wid=<?=$weibo['id']?>" >评论<?= $weibo['reply_num'] > 0 ? '(' . $weibo['reply_num'] . ')' : ''; ?></a></p>
	</div>
    </div>
    <div  class="forward_bottom"></div>
</div>
