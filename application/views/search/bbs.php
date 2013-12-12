<!-- news/search:_body -->
<?php if(count($unit)==0):?>
<span class="nodata">很抱歉，暂时还没有您要找的话题。</span>
<?else:?>
<p class="search_count">共找到 <?= $pager->total_items ?> 条符合条件的话题：</p>

<table width="100%" id="srtable" class="news_table">
       <?php if(count($unit)==0):?>
    <tr>
	<td colspan="4">
	    <span class="nodata">很抱歉，暂时还没有您要查找的内容。</span>
	</td>
    </tr>
    <?else:?>
     <tr>
        <th>标题</th>
	<th style="text-align: center">作者</th>
        <th style="text-align: center">回复/点击</th>
        <th style="text-align: center">发布时间</th>
    </tr>
    <?php foreach($unit as $u): ?>
    <tr>
        <td class="news_title"><a href="<?= URL::site('bbs/viewPost?id='.$u['id']) ?>" target="_blank"><?= $u['title'] ?></a></td>
        <td class="center"><a href="<?=URL::site('user_home?id='.$u['user_id'])?>" title="点击进入主页"><?=$u['realname']?></a></td>
	<td class="center"><span style="color:#008000"><?=$u['reply_num']?></span> / <?= $u['hit'] ?></td>
        <td class="center"><?= date('Y-n-d', strtotime($u['create_at'])); ?></td>
    </tr>
    <?php endforeach; ?>
    <?php endif;?>
</table>
<?= $pager ?>
<?php endif;?>