<div class="clear"></div>
<div id="get_comment_list" ></div>
<?php if( ! $_SESS->get('id')): ?>
<div class="notice">
    您还注册或登录，请<a href="javascript:faceboxUserLogin()"><b>登录</b></a> 后进行回帖或讨论。
</div>
<?php else: ?>


	 <table border="0" cellspacing="0" cellpadding="0" class="unit_table">
	     <thead>
		 <tr>
		     <th colspan="2" class="topic_topbar">发表回复：</th>
		 </tr>
	     </thead>
	     <tbody>
		 <tr>
		     <td valign="top" class="unit_left_bg">
			 <div class="left_user_face">
			 <img src="<?= Model_User::avatar($_SESS->get('id'),128,$_SESS->get('sex')).'?gen_at='.time() ?>" class="face" />
			 </div>
		     </td>
		     <td valign="top"  style="background:#fff;padding:0px 20px">
	    
<?php
	//判断是否是ios系统
	$agent = $_SERVER['HTTP_USER_AGENT'];
  $is_ios = strpos($agent, 'iPhone') || strpos($agent, 'iPad') ? True : False;
?>

<script type="text/javascript">
            var cmt = new CandyForm('comment_form', {btnSubmit:'cmt_submit'});
            </script>
            <form <?= !$is_ios ? 'onsubmit="post_comment"' : ''; ?>  action="<?= URL::site('comment/post') ?>" id="comment_form" method="post">
<textarea cols="100" rows="12" id="cmt_content" name="content" ></textarea>

                <?php if(isset($params)): ?>

                <?php foreach($params as $key=>$val): ?>
                <input type="hidden" name="<?= $key ?>" value="<?= $val ?>" style="display:none" />
                <?php endforeach; ?>

                <?php endif; ?>

		<input value="<?=$is_ios?$_SERVER["REQUEST_URI"]:''; ?>" name="redirect" type="hidden">
	    <?php if ($is_ios): ?>
	    	  <input type="submit" id="cmt_submit"  value="发表回复" class="button_blue"  style="margin:10px 0"/>
	    <?php else: ?>
		<input type="button" id="cmt_submit" onclick="post_comment()" value="发表回复" class="button_blue"  style="margin:10px 0"/>
	    <?php endif; ?>
	    </form>
		     </td>
		 </tr>
	     </tbody>
	 </table>
<div class="bbs_topic_topbar"></div>

<!--快速回复 -->

<?php if (!$is_ios): ?>
                <?=
                View::ueditor('cmt_content', array(
                    'toolbars' => Kohana::config('ueditor.simple'),
                    'minFrameHeight' => 200,
                    'autoHeightEnabled' => 'false',
                    'elementPathEnabled' => 'false',
                    'focus' => 'true',
                ));
                ?>
<?php endif; ?>


<script type="text/javascript">
function post_comment(){
    cmt.setOptions({
        callback: function(){
            get_comment();
           $('comment_form').set('action', '<?= URL::site('comment/post') ?>');
           editor.html('');
        }
    });
     $('cmt_content').set('value', editor.html());
    cmt.send();
}

function modify_comment(id){
    $('comment_form').set('action', '<?= URL::site('comment/post') ?>?id='+id);
    var html = $$('#comment_'+id+' .comment_content').get('html');
    editor.html(''+html);
}

function quote_comment(id){
    var user = $$('#comment_'+id+' .commentor').get('text');
    var txt = $$('#comment_'+id+' .comment_content').get('text');
    editor.html('<div class="cmt_quote">引用：'+user+'【'+txt+'】</div><br />');
    wfx.toElement('comment_form');
}

function reply_comment(id){
    var user = $$('#comment_'+id+' .commentor').get('text');
    var txt = $$('#comment_'+id+' .comment_content').get('text');
    editor.html('<div class="cmt_quote">回复：'+user+'【'+txt+'】</div><br />');
    wfx.toElement('comment_form');
}
</script>
<?php endif; ?>

<script type="text/javascript">
function get_comment(page){
    var list = new Request({
        url: '<?= URL::site('bbs/comment') ?>',
        type: 'post',
        success: function(data){
            $('get_comment_list').set('html', data);
            checkOnline();
			if(page > 1){
				wfx.toElement('get_comment_list');
			}
        }
    });
    var query = '<?= http_build_query(@$params, '', '&'); ?>';
    if($defined(page)){
        query += '&page='+page;
    }
    list.send(query);
}
get_comment();
</script>