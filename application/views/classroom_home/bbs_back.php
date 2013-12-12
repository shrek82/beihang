<!-- classroom_home/bbs:_body -->
<div class="main_content" id="main_content">
 <div style="text-align: right;padding:0px 20px 10px 20px;margin-top: -30px">
<a href="<?= URL::site('classroom_home/bbsPost?id='.$_CLASSROOM['id']) ?>"  /><img src="/static/images/post.png" /></a>
</div>

<?php if(count($unit) == 0): ?>
<p class="nodata">暂时没有话题。</p>
<?php else: ?>

<table width="100%" class="aa_table" id="bbs_table" cellspacing="0" cellpadding="0">
    <tr>
	<th style="text-align:center;width:30px">&nbsp;&nbsp;</th>
        <th style="text-align:left">&nbsp;&nbsp;标题</th>
	<th style="text-align:center;width:120px">作者</th>
	<th style="text-align:center;width:110px">回复/点击</th>
        <th style="text-align:left;width:80px">最后评论</th>
    </tr>
    <?php foreach($unit as $un): ?>
    <tr style="border-bottom: 1px dotted #ccc">
           <td style="height: 35px;text-align:center;">
  <?php if($un['is_fixed']):?>
<a href="<?= URL::site('classroom_home/bbsUnit?id=' . $_CLASSROOM['id'] . '&unit_id=' . $un['id']) ?>" target="_blank" title="新窗口打开 置顶话题"><img src="/static/ico/is_fixed.gif"  /></a>
<?php elseif (strtotime(date('Y-m-d H:i:s'))-strtotime($un['comment_at'])<=86400 OR strtotime(date('Y-m-d H:i:s'))-strtotime($un['create_at'])<=86400):?>
<a href="<?= URL::site('classroom_home/bbsUnit?id=' . $_CLASSROOM['id'] . '&unit_id=' . $un['id']) ?>" target="_blank" title="新窗口打开  新帖或新回复"><img src="/static/ico/folder_new.gif"  border="0" align="absmiddle"  /></a>
<?php else:?>
<a href="<?= URL::site('classroom_home/bbsUnit?id=' . $_CLASSROOM['id'] . '&unit_id=' . $un['id']) ?>" target="_blank" title="新窗口打开"><img src="/static/ico/folder_common.gif" /></a>
      <?php endif;?>
               </td>

  <td>
		    		<a href="<?= URL::site('classroom_home/bbsUnit?id=' . $_CLASSROOM['id'] . '&unit_id=' . $un['id']) ?>" style="<?=$un['is_fixed']?'color:#f30':'';?>">
		    <?= $un['title'] ?>
			</a>
  <?php if($un['is_good']): ?>&nbsp;<img src="/static/ico/recommend_1.gif"  border="0"  title="推荐帖子"/><?php endif; ?>
  <?php if($un['reply_num']>10):?>&nbsp;<img src="/static/ico/hot_1.gif" title="热门话题"><?php endif;?>

  </td>
  <td style="text-align:center;"><a href="<?= URL::site('user_home?id=' . $un['user_id']) ?>" title="浏览该主页" target="_blank"><?= $un['User']['realname'] ?></a>
      <br><span style="color:#999"><?= date('Y-n-d', strtotime($un['create_at'])); ?></span></td>
  <td style="text-align:center;">
			<font style="color:#006600"><?=$un['reply_num']?></font>&nbsp;<font style="color:#999">/</font>&nbsp;<?= $un['hit'] ?></span>
   </td>
   <td style="text-align:center;color:#666"><?=$un['comment_at']?Date::span_str(strtotime($un['comment_at'])):Date::span_str(strtotime($un['create_at'])) ?>前</td>
		    </tr>

    </tr>
    <?php endforeach; ?>
</table>
<?php endif; ?>
<?=$pager?>
</div>
