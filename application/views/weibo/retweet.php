<form style="margin:0;padding:0px" method="POST" id="retweet_form" action="/weibo/post?id=<?= $_ID ?>">
<div style="width:480px;padding:5px 10px 30px 10px">
    <?php if ($forward): ?>
        <!--转发原帖及其跟贴 -->
    	<div style="background:#f7f7f7;padding:10px;color:#666666;line-height: 1.6em"><a href="/weibo?id=<?= $_ID ?>&uid=<?= $forward['user_id'] ?>" title="Ta的所有话题" style="font-size:12px">@<?= $forward['realname'] ?></a>：<?= Common_Global::weibohtml($forward['content'], $_ID, 100) ?></div>
    	<div style="margin:5px 0;"><a href="javascript:;" onclick="open_retweet_expression('retweet_textarea')" class="tool_ico_face">表情</a></div>
    	<div style="margin:5px 0;"><textarea style="width:98%;height:50px; " id="retweet_textarea" name="content" class="input_text"></textarea><div style="padding:5px 0 10px 0;color:#999">//<a href="/weibo?id=<?= $_ID ?>&uid=<?= $weibo['user_id'] ?>"  style="font-size:12px">@<?= $weibo['realname'] ?></a>：<?= Common_Global::weibohtml($weibo['content'], $_ID, 250); ?></div></div>
    	<div style="height:20px; color: #999">
	    <input type="checkbox" name="comment_weibos[]" value="<?=$forward['id']?>" id="addComment<?=$forward['id']?>" /><label for="addComment<?=$forward['id']?>">同时评论给 <?= $forward['realname'] ?></label><p style="float:right"><input type="button" id="submit_button" value="发布"  class="greenButton2" onclick="retweet_sub()"></p>
	</div>
	<div style="height:20px; color: #999">
	    <input type="checkbox" name="comment_weibos[]" value="<?=$weibo['id']?>" id="addComment<?=$weibo['id']?>" /><label for="addComment<?=$weibo['id']?>">同时评论给 <?= $weibo['realname'] ?></label>
	</div>

        <input type="hidden" name="from_forward" value="<?= $forward['id'] ?>">
        <input type="hidden" name="forward_content" value="[u=<?=$weibo['user_id']?>]<?=$weibo['realname']?>[/u]：<?= $weibo['content'] ?>">
    <?php else: ?>
	    <!--只有原帖 -->

		<div style="background:#f7f7f7;padding:10px;color:#666666;line-height: 1.6em">
		    <a href="/weibo?id=<?= $_ID ?>&uid=<?= $weibo['user_id'] ?>" title="Ta的所有话题" style="font-size:12px">@<?= $weibo['realname'] ?></a>：<?= Common_Global::weibohtml($weibo['content'], $_ID, 100) ?>
		</div>
		<div style="margin:5px 0;">
		    <a href="javascript:;" onclick="open_retweet_expression('retweet_textarea')" class="tool_ico_face">表情</a>
		</div>
		<div style="margin:5px 0;">
		    <textarea style="width:100%;height:50px;border:1px solid #ccc;border-top:1px solid #B4B4B4;border-left: 1px solid #B4B4B4 " id="retweet_textarea" name="content"></textarea>
		</div>
		<div style="height:20px; color: #999">
		    <input type="checkbox" name="comment_weibos[]" value="<?=$weibo['id']?>" id="addComment" /><label for="addComment">同时评论给 <?= $weibo['realname'] ?></label><p style="float:right"><input type="button" id="submit_button" value="发布"   class="greenButton2"  onclick="retweet_sub()"></p></div>

	    <input type="hidden" name="from_forward" value="<?= $weibo['id'] ?>">
	    <input type="hidden" name="forward_content" value="">
    <?php endif; ?></div>
</form>
