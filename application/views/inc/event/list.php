<?php
    $etype = Kohana::config('icon.etype');
?>

<?php if($pager->total_items > 0): ?>
<table width="100%" class="event_table" id="event_table" cellspacing="0" cellpadding="0">
    <tr>
        <th style="text-align: left;height:25px">名称</th>
	       <th style="text-align: left">名称</th>
        <th style="text-align: center">开始时间</th>
    </tr>
    <?php foreach($events as $e): ?>
    <tr>
        <td width="50">
            <?php $type_icon = $e['type'] ? $etype['icons'][$e['type']] : 'undefined.png'; ?>
            <div style="height:48px; width:48px; background: #fff url(<?= $etype['url'].$type_icon ?>) no-repeat center top;"></div>
        </td>
        <td>
        <a href="<?= URL::site('event/view?id='.$e['id']) ?>" class="title"><?= $e['title'] ?></a>
<br />
            地点：<?= $e['address'] ?><br>
            时间：<?= date('Y-m-d', strtotime($e['start'])); ?>
        </td>
        <td class="quiet"  style="text-align: center">
                     <?php if(time()>=strtotime($e['start']) AND  time()<=strtotime($e['finish'])):?>
            <span style="color:#4D7E05">进行中</span>
         <?php elseif(time()<=strtotime($e['start'])): ?>
         <span style="color:#4D7E05"><?= Date::span_str(strtotime($e['start'])) ?>后</span>
         <?php else:?>
         <span style="color:#f60">已结束</span>
         <?php endif;?>
        </td>

    </tr>
    <?php endforeach; ?>
</table>
<?= $pager ?>
<?php endif; ?>
