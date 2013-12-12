<?php $etype = Kohana::config('icon.etype'); ?>

<?php if(count($events)==0):?>
<span class="nodata">很抱歉，暂时还没有您要找的活动。</span>
<?else:?>

<p class="search_count">共找到 <?= $pager->total_items ?> 条符合条件的活动：</p>

<table id="srtable" width="100%" class="news_table">
   
    <tr>
        <th>活动</th>
        <th style="text-align: center">已报名/人数</th>
        <th style="text-align: center">开始时间</th>
    </tr>
    <?php foreach($events as $i=>$e): ?>
    <tr>
        <td class="quiet" style="line-height:1.6em;padding: 10px 10px">
	    <a href="<?= URL::site('event/view?id='.$e['id']) ?>" style="font-size:14px; font-weight: bold"><?= $e['title'] ?></a><br>
	    发起：<a href="<?= URL::site('aa_home?id='.$e['aa_id']) ?>" >
                <?= $e['Aa']['name'] ?></a> &raquo;
            <a href="<?= URL::site('club_home?id='.$e['aa_id'].'&club_id='.$e['club_id']) ?>" >
                <?= $e['Club']['name'] ? $e['Club']['name']:'' ?></a>
		<br>地点：<?= $e['address'] ?>
            
        </td>
        <td style="text-align: center">
            <a href="<?= URL::site('event/view?id='.$e['id'].'&tab=event_slist') ?>" title="点击浏览名单">
            <?= $e['sign_num'] ?></a> / <?= $e['sign_limit'] == 0 ? '不限':$e['sign_limit'] ?>
        </td>
        <td class="quiet" style="text-align: center">
            <span><?= date('Y-n-d H:i',strtotime($e['start']));?></span><br />
        </td>
    </tr>
    <tr>
	<td colspan="3" class="dotted_line" style="line-height: 1px; height: 1px"></td>
    </tr>
    <?php endforeach; ?>
</table>

<?= $pager ?>
<?php endif;?>
<!--//活动列表结束-->
