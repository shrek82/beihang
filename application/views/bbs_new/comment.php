<!--论坛回帖列表 -->
   
    <?php if(count($comments) == 0): ?>
    <p style="color:#999;margin:20px 0">暂无还没有讨论，赶紧来抢个沙发吧？</p>
    <?php else: ?>
    
<?= $pager ?>
    <?php foreach($comments as $ix=>$cmt): ?>
	 <table border="0" cellspacing="0" cellpadding="0" class="unit_table"  id="comment_<?= $cmt['id'] ?>">
	     <tbody>
		 <tr>
		     <td valign="top" class="unit_left_bg">
			 <div class="left_user_face">
<img src="/static/images/online<?=$cmt['User']['Ol']?'1':'0';?>.gif" style="vertical-align:middle" title="当前<?=$cmt['User']['Ol']?'在线':'不在线';?>"  />
<a href="<?= URL::site('user_home?id='.$cmt['user_id']) ?>" class="commentor"><?= $cmt['User']['realname'] ?></a><br>
<a href="<?= URL::site('user_home?id='.$cmt['user_id']) ?>" target="_blank">
<img src="<?= Model_User::avatar($cmt['user_id'],128,$cmt['User']['sex']).'?gen_at='.time() ?>" class="face" title="点击进入该主页" style="width:100px; height: 100px;"></a>
 </div>
<div class="left_user_info">
地区&nbsp;<?= $cmt['User']['city'] ?><br>
注册&nbsp;<?= date('Y-m-d', strtotime($cmt['User']['reg_at'])); ?>
<?php if($cmt['User']['start_year'] AND $cmt['User']['speciality']):?>
<br>专业&nbsp;<?=$cmt['User']['start_year']?>级<?=$cmt['User']['speciality']?>
<?php endif;?>
</div>
			
		     </td>
		     <td valign="top"  style="background:#fff;padding:0px 20px">
	<div class="topic_postdate">
		<span style="color:#f60;"><strong><?= ($pager->current_page-1)*$pager->items_per_page+$ix+1 ?></strong> 楼</span>
		&nbsp;&nbsp;发布于<?= $cmt['post_at'] ?></div>
	<div class="comment_content">

  <?php
  //替换原杭州校友会图片附件路径
  $content = preg_replace('/src="\/upload\/([^\"]+)"/i', 'src="http://v2.zjuhz.com/upload/$1"', $cmt['content']);
  echo $content;
  ?>
</div>

	<div class="topic_tool"><a href="javascript:reply_comment(<?= $cmt['id'] ?>)" class="ico_reply">回复</a>&nbsp;&nbsp;&nbsp;<a href="javascript:quote_comment(<?= $cmt['id'] ?>)" class="ico_quote">引用</a>
<?php if($cmt['user_id'] == $_SESS->get('id')): // 能修改自己 ?>
&nbsp;&nbsp;&nbsp;<a href="javascript:modify_comment(<?= $cmt['id'] ?>)" class="ico_edit">修改</a>
<?php endif; ?></div>
		<?php if($cmt['update_at']):?>
	<div class="update">作者在<?= $cmt['update_at'] ?> 做了修改 </div>
	<?php endif;?>

  <?php if($cmt['bubble']):?>
     <div class="dotted_line"></div>
     <div class="sigline"><img src="/static/images/pen_alt_fill_12x12.gif" title="最新记录" style="vertical-align:middle;margin:0px 4px"><?= Text::limit_chars($cmt['bubble'], 50, '...') ?></div>
  <?php endif; ?>
     
		     </td>
		 </tr>
	     </tbody>
	 </table>
<?php endforeach; ?>

<?= $pager ?>
<?php endif; ?>
<!--//回帖 -->