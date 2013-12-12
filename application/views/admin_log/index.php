<!-- admin_log/index:_body -->
<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" >
<tr >
<td height="29" class="td_title" >
    <div class="title_name">
    <b>查找日志：</b>
    </div>
    <div class="title_search">
	<form name="search" action="" method="get">
            关键字：
	    <input name="q" type="text" style="width:200px" class="keyinput">
	    <input type="submit" value="搜索">
	</form>
    </div>
</td>
</tr>
<tr>
<td height="25" style="padding:0px 10px" >
按内容检索：<a href="<?=URL::site('admin_log/index')?>" style="<?=empty($loginfo)?'font-weight:bold':''?>">所有</a> &nbsp;|&nbsp;
<a href="<?=URL::site('admin_log/index?loginfo=user_id');?>"  style="<?=$loginfo=='user'?'font-weight:bold':''?>">帐号管理</a>&nbsp;|&nbsp;
<a href="<?=URL::site('admin_log/index?loginfo=news_id');?>"  style="<?=$loginfo=='news_id'?'font-weight:bold':''?>">新闻管理</a>&nbsp;|&nbsp;
<a href="<?=URL::site('admin_log/index?loginfo=aa_id');?>"  style="<?=$loginfo=='aa_id'?'font-weight:bold':''?>">地方校友会管理</a>&nbsp;|&nbsp;
<a href="<?=URL::site('admin_log/index?loginfo=classroom_id');?>"  style="<?=$loginfo=='classroom_id'?'font-weight:bold':''?>">班级管理</a>&nbsp;|&nbsp;
<a href="<?=URL::site('admin_log/index?loginfo=bbs_unit_id');?>"  style="<?=$loginfo=='bbs_unit_id'?'font-weight:bold':''?>">话题管理</a>&nbsp;&nbsp;
</td>
</tr>
</table>

<?php if (!$logs): ?>
    <div class="nodata">暂无管理记录。</div>
<?php else: ?>

	<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" style="margin-top:0px ">
	    <tr>
		<td colspan="2" class="td_title">管理日志</td>
	    </tr>
	</table>

	<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" style="margin-top:0px ">
	    <tr>
		<td width="5%" style="text-align:center">序号</td>
		<td style="text-align:center;width:50px">管理员</td>
		<td width="5%" style="text-align:center">操作</td>
	        <td width="60%">描述</td>
		
		<td style="text-align:center;width:20%">操作日期</td>
	    </tr>

    <?php foreach ($logs as $key => $i) : ?>
	    <tr  id="info_<?= $i['id'] ?>" class="<?php if (($key) % 2 == 0) {
		echo'even_tr';
	    } ?>">
		<td style="text-align:center"><?= $i['id'] ?></td>
		<td style="text-align:center"><a href="/admin_log/index?manager_id=<?=$i['manager_id']?>" title="浏览该管理员所有操作日志"><?= $i['realname'] ?></a></td>
		<td width="10%" style="text-align:center"><?= $i['type'] ?></td>
		<td >
            <?php if($i['user_id']):?>
		    <a href="/admin_user/index?search_type=uid&q=<?=$i['user_id']?>"><?= $i['description'] ?></a>
        <?php elseif($i['news_id']):?>
		    <a href="/news/view?id=<?=$i['news_id']?>" target="_blank"><?= $i['description'] ?></a>
	<?php elseif($i['aa_id']):?>
		    <a href="/aa_home?id=<?=$i['aa_id']?>" target="_blank"><?= $i['description'] ?></a>
        <?php elseif($i['classroom_id']):?>
		    <a href="/classroom_home?id=<?=$i['classroom_id']?>" target="_blank"><?= $i['description'] ?></a>
	<?php elseif($i['bbs_unit_id']):?>
		    <a href="/bbs/viewPost?id=<?=$i['bbs_unit_id']?>" target="_blank"><?= $i['description'] ?></a>
        <?php else:?>
	<?php endif;?>
	    </td>
                
		<td style="text-align:center"><?= $i['manage_at'] ?></td>
	    </tr>
<?php endforeach; ?>
	</table>

<?php endif; ?>

<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" style="margin-top:-1px " >
<tr>
<td style="height: 50px"><?= $pager ?></td>
</tr>
 </table>
