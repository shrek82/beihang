<?php if (!$_UID): ?>
    <div class="notice">
        您还注册或登录，请<a href="javascript:faceboxUserLogin()"><b>登录</b></a> 后进行回帖或讨论。
    </div>
<?php else: ?>

	<div id="comment_form_box">
	    <div class="user_face">
	<?= View::factory('inc/user/avatar2',
			array('id' => $_UID, 'size' => 48, 'sex' => $_SESS->get('sex'),'online'=>true)) ?>
    </div>
    <div class="comment_form">

	<?php
	//判断是否是ios系统
	$agent = $_SERVER['HTTP_USER_AGENT'];
	$is_ios = strpos($agent, 'iPhone') || strpos($agent, 'iPad') ? True : False;
	?>

        <script type="text/javascript">
            var cmt = new ajaxForm('comment_form', {btnSubmit:'cmt_submit'});
        </script>
        <form <?= !$is_ios ? 'onsubmit="post_comment"' : ''; ?> action="<?= URL::site('comment/post') ?>" id="comment_form" method="post">
            <textarea id="cmt_content" name="content" style="width:100%; height: 100px" ></textarea>

	    <?php if (isset($params)): ?>

	    <?php foreach ($params as $key => $val): ?>
		    <input type="hidden" name="<?= $key ?>" value="<?= $val ?>" style="display:none" />
	    <?php endforeach; ?>

	    <?php endif; ?>

	    <?php if ($is_ios): ?>
	    	    <input type="submit" id="cmt_submit" value="立即发表" class="button_blue"  style="margin:10px 0;padding:3px 10px"/>
	    <?php else: ?>
			    <input type="button" id="cmt_submit" onclick="post_comment()" value="立即发表" class="button_blue"  style="margin:10px 0;padding:3px 10px"/>
	    <?php endif; ?>

			    <span style="color:#ccc"></span>

			    <input value="<?= $is_ios ? $_SERVER["REQUEST_URI"] : ''; ?>" name="redirect" type="hidden">
			</form>
		    </div>
		    <div class="clear"></div>
		</div>

<!--模块页面评论样式 -->
<div class="clear"></div>
<div id="loading" style="text-align: center;color:#79ADDB"><img src="/static/ico/loading6.gif" style="vertical-align: middle;">&nbsp;努力加载中，请稍候...</div>
<div id="get_comment_list" ></div>

<?php if (!$is_ios): ?>
<?=View::ueditor('cmt_content', array(
    'toolbars' => Kohana::config('ueditor.common'),
    'minFrameHeight' => 300,
    'autoHeightEnabled' => 'false',
));
?>
<?php endif; ?>

<script type="text/javascript">

				    //文本编辑器提交，适合兼容keditor的浏览器
				    function post_comment(){
					cmt.setOptions({
					    callback: function(new_id){
						get_comment('last');
						$('comment_form').set('action', '<?= URL::site('comment/post') ?>');
						editor.html('');
						if(new_id){
						    showPrompt('发表评论 积分+1',1500);
						}
					    }
					});
					if(!ueditor.hasContents()){ueditor.setContent('');}
                                                                                ueditor.sync();
					cmt.send();
				    }

				    function modify_comment(id){
					$('comment_form').set('action', '<?= URL::site('comment/post') ?>?id='+id);
					var html = $$('#comment_'+id+' .comment_content').get('html');
					ueditor.setContent(''+html+'');
				    }


				    function quote_comment(id){
					var user = $$('#comment_'+id+' .user').get('text');
					var txt = $$('#comment_'+id+' .comment_content').get('html');
					ueditor.setContent('<div class="cmt_quote"><b>引用：</b><br>'+user+''+txt+'</div><br>');
					scrollTo('comment_form',500)
				    }

				    function reply_comment(id){
					var user = $$('#comment_'+id+' .user').get('text');
					var txt = $$('#comment_'+id+' .comment_content').get('html');
					ueditor.setContent('<div class="cmt_quote"><b>回复：</b><br>'+user+''+txt+'</div><br>');
					scrollTo('comment_form',500)
				    }
				</script>

<?php endif; ?>

<script type="text/javascript">
				    function get_comment(page){
					document.getElementById('loading').style.display='block';
						if(page > 1){
						    scrollTo('scrollToComment',500)
						}
					var list = new Request({
					    url: '<?= URL::site('comment/list') ?>',
					    type: 'post',
					    success: function(data){
						$('get_comment_list').set('html', data);
						document.getElementById('loading').style.display='none';
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

