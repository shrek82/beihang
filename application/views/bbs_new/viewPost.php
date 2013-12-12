<script  language="JavaScript">
<!--
var  flag=false;
function  DrawImage(ImgD){
     var  image=new  Image();
     image.src=ImgD.src;
     if(image.width>0  &&  image.height>0){
       flag=true;
       if(image.width/image.height>=  650/650){
         if(image.width>650){
         ImgD.width=650;
         ImgD.height=(image.height*650)/image.width;
         }else{
         ImgD.width=image.width;
         ImgD.height=image.height;
         }

         }
       else{
         if(image.height>650){
         ImgD.height=650;
         ImgD.width=(image.width*650)/image.height;
         }else{
         ImgD.width=image.width;
         ImgD.height=image.height;
         }

         }
       }
}
//-->
</script>
<div style="color:#666;margin:5px 0">
    您的位置：
    <?php
    if ($unit['Bbs']['aa_id'] > 0):
	$bbs_url = '/bbs/list?f=' . $unit['Bbs']['aa_id'] . '&b=' . $unit['Bbs']['id'];
    ?>
        <a href="<?= URL::site('aa_home?id=' . $unit['Bbs']['aa_id']) ?>"><?= $unit['Bbs']['Aa']['sname'] ?>校友会</a> &raquo;
        <a href="<?= URL::site('bbs?f=' . $unit['Bbs']['aa_id']) ?>">交流园地</a> &raquo;

    <?php elseif ($unit['Bbs']['aa_id'] == 0): ?>
	    <a href="<?= URL::site('aa') ?>">校友总会</a> &raquo;
	    <a href="<?= URL::site('bbs?f=0') ?>">交流园地</a> &raquo;
    <?php endif; ?>

    <?php if ($unit['Bbs']['club_id'] > 0):
		$bbs_url = 'club_home/bbs?id=' . $unit['Bbs']['aa_id'] . '&club_id=' . $unit['Bbs']['club_id']; ?>
	        <a href="<?= URL::site('club_home?id=' . $unit['Bbs']['aa_id'] . '&club_id=' . $unit['Bbs']['club_id']) ?>"><?= $unit['Bbs']['Club']['name'] ?></a> &raquo;
    <?php endif; ?>

    <?php
		if ($unit['Bbs']['aa_id'] > 0) {
		    $bbs_url = 'bbs/list?f=' . $unit['Bbs']['aa_id'] . '&b=' . $unit['bbs_id'];
		} else {
		    $bbs_url = 'bbs/list?f=0&b=' . $unit['bbs_id'];
		}
    ?>
	        <a href="<?= URL::site($bbs_url) ?>"><?= $unit['Bbs']['name'] ?></a>
	    </div>

	    <!--//当前位置 -->

	    <!--发布新帖及回复按钮 -->
	    <div class="bbs_post_reply_bar">
	        <a href="javascript:rollToComment()"><img src="/static/images/reply.png" boorder="0"></a>&nbsp;&nbsp;&nbsp;&nbsp;
	        <a href="<?=
		URL::site('bbs/unitForm') . URL::query(array(
			    'aa_id' => $unit['Bbs']['aa_id'],
			    'club_id' => $unit['Bbs']['club_id'],
			    'b' => $unit['Bbs']['id'],
			    'id' => null
			))
    ?>" boorder="0"><img src="/static/images/post.png" /></a>
	 </div>
	 <!--//发布新帖及回复按钮 -->

	 <!--主题 -->
	 <table border="0" cellspacing="0" cellpadding="0" class="unit_table">
	     <thead>
		 <tr>
		     <th colspan="2" class="topic_topbar">主题：<?= $unit['title'] ?></th>
		 </tr>
	     </thead>
	     <tbody>
		 <tr>
		     <td valign="top" class="unit_left_bg">

<div class="left_user_face">
<img src="/static/images/online<?=$unit['User']['Ol']?'1':'0';?>.gif" style="vertical-align:middle" title="当前<?=$unit['User']['Ol']?'在线':'不在线';?>">
<a href="<?= URL::site('user_home?id='.$unit['user_id']) ?>" class="commentor"><?= $unit['User']['realname'] ?></a><br>
<a href="<?= URL::site('user_home?id='.$unit['user_id']) ?>" target="_blank">
<img src="<?= Model_User::avatar($unit['user_id'],128,$unit['User']['sex']).'?gen_at='.time() ?>" class="face" title="点击进入该主页"></a>
</div>

<div class="left_user_info">
地区&nbsp;<?= $unit['User']['city'] ?$unit['User']['city']:'无'; ?><br>
注册&nbsp;<?= date('Y-m-d', strtotime($unit['User']['reg_at'])); ?>
<?php if($unit['User']['start_year'] AND $unit['User']['speciality']):?>
<br>专业&nbsp;<?=$unit['User']['start_year']?>级<?=$unit['User']['speciality']?>
<?php endif;?>
</div></td>
<td valign="top" style="background:#fff;padding:0px 20px">
<div class="topic_postdate"><span style="color:#f60; font-weight: bold">楼主</span>&nbsp;&nbsp;发布于<?= $unit['create_at'] ?></div>
<div class="comment_content" style="word-break:break-all">

<?php
 //设置大图自动缩小js
$content=preg_replace('/<img/im', '<img onload="javascript:DrawImage(this);" ', $unit['Post']['content']);
//链接为新窗口打开
$content = preg_replace("/<a([^\>]*)(\starget\=\"?\w+\"?)|<a([^\>]*)/i", "<a$1$3 target=_blank", $content);
echo $content;
?>

</div>


	<?php if ($unit['Post']['hidden']): ?>
	    	<div class="hidden" style="word-break:break-all">
	    <?php if (Model_Bbs::isReply($unit['id'], $_SESS->get('id'))): ?>
	    <?= $unit['Post']['hidden'] ?>
	    <?php else: ?>
			有部分内容评论后可见。已回复？请<a href="javascript:history.go(0)">刷新一下</a>
	    <?php endif; ?>
			</div>
	<?php endif; ?>


<div style=" margin:100px 0 10px 0;text-align:right;padding-right: 10px;">
    <?php if ($unit['user_id'] == $_SESS->get('id')): // 能修改自己   ?>
    <a href="<?= URL::site('bbs/unitForm?id=' . $unit['id']); ?>" class="ico_edit">修改</a>&nbsp;
	    <?php elseif($_SESS->get('role') == '管理员'): // 管理员修改   ?>
		    	    <a href="<?= URL::site('bbs/unitForm?id=' . $unit['id']); ?>" class="ico_edit">修改</a>&nbsp;
	    <?php endif; ?>

	    <?php if ($_SESS->get('role') == '管理员'): ?>
				    <a href="javascript:homepage(<?= $unit['id'] ?>)" class="<?= $unit['SysFilter']['id'] ? 'ico_yes' : 'ico_no' ?>" title="在网站首页显示该话题" id="homepage_link">首页显示</a>&nbsp;
				    <a href="<?= URL::site('admin_bbs/focusForm?id=' . $unit['id']); ?>" class="ico_television" title="增加到论坛首页幻灯片播放">幻灯片</a>&nbsp;
				    <a href="javascript:del(<?= $unit['id'] ?>)" class="ico_del" title="删除该话题">删除</a>&nbsp;

				    <script type="text/javascript">
					function homepage(cid){
					    new Request({
						url: '<?= URL::site('admin_bbs/homepage?cid=') ?>'+cid,
						type: 'post',
						success: function(){
						    var homepage_link=document.getElementById('homepage_link');
						    homepage_link.className=homepage_link.className=='ico_yes'?'ico_no':'ico_yes';
						}
					    }).send();
					}

					function del(cid){
					    var b = new Facebox({
						title: '删除确认！',
						message: '确定要删除该话题和所有对该话题的评论吗？',
						submitValue: '确定',
						submitFunction: function(){
						    new Request({
							url: '<?= URL::site('admin_bbs/del?cid=') ?>'+cid,
							type: 'post',
							success: function(){
							    window.location.href='<?= URL::site($bbs_url) ?>';
							}
						    }).send();
						    b.close();
						}
					    });
					    b.show();
					}
				    </script>

	    <?php endif; ?>
				</div>
	<?php if ($unit['update_at']): ?>
					<div class="update">作者在<?= $unit['update_at'] ?>做了修改 </div>
	<?php endif; ?>

  <?php if($unit['bubble']):?>
     <div class="dotted_line"></div>
     <div class="sigline"><img src="/static/images/pen_alt_fill_12x12.gif" title="最新记录" style="vertical-align:middle;margin:0px 4px"><?= Text::limit_chars($unit['bubble'], 50, '...') ?></div>
  <?php endif; ?>
		     </td>
		 </tr>
	     </tbody>
	 </table>

	<!--//主题 -->

<!--评论模块 -->
<div id="unitComment" >
<?=View::factory('bbs/comment_form',
	array('params' => array('bbs_unit_id' => $unit['id'],
	'reply_num' => $unit['reply_num'])))
 ?>
</div>
<!--评论表单 -->

<script type="text/javascript">
				        function unitTab(name){
				    	$$('.hide').setStyle('display', 'none');
				    	$$('#unit_bar a').removeClass('cur');
				    	var cur = $(name);
				    	$$('#unit_bar a[href*="'+name+'"]').addClass('cur');
				    	cur.setStyle('display', 'block');
				        }
				        function rollToComment(){
				    	new Fx.Scroll(window).toBottom();
				        }
<?php if (Arr::get($_GET, 'cmt') == 'y'): ?>
	rollToComment();
<?php endif; ?>
window.addEvent('domready', function(){
candyImageAutoResize('unitPost', 800);
});
</script>