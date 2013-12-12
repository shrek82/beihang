<?php foreach($comments as $c):?>
<p class="comment_dotted_line"></p>
<table border="0" cellspacing="0" cellpadding="0">
    <tr>
	<td valign="top" style="padding:5px 10px 5px 0px">
	    <a href="/user_home?id=<?= $c['user_id'] ?>" title="进入个人主页"><img src="<?= Model_User::avatar($c['user_id'], 48, $c['sex']) ?>" style="width:30px;height:30px; padding: 2px; background: #fff; border: 1px solid #CCCCCC"></a>
	</td>
	<td valign="top" style="padding:5px 10px 5px 0px;width:500px; line-height: 1.6em">
	    <a href="/usr_home?id=<?= $c['user_id'] ?>"><?= $c['realname'] ?></a>&nbsp;<?= Common_Global::weibohtml($c['content'],$_ID) ?>&nbsp;<span style="color:#999">(<?= Date::span_str(strtotime($c['post_at'])); ?>前)</span>
	    <p style="text-align:right"><a href="javascript:;" onclick="replycmt(<?=$c['weibo_id']?>,<?=$c['user_id']?>,'<?= $c['realname'] ?>')" style="color:#6AADDB">回复</a></p>
	</td>
    </tr>
</table>
<?php endforeach;?>