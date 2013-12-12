<?php
    $etype = Kohana::config('icon.etype');
?>

<table border="0" width="100%">
<tr>
<td width="63%" style="border-bottom:0; text-align: right">
<a href="<?=URL::site('admin_event/index')?>" style="<?=empty($_GET)?'font-weight:bold':''?>"></a>
</td>
<td width="37%" style="border-bottom:0;text-align:right; padding: 0px 5px">
    <form style="display:inline" action="" method="get">
        <input type="text" name="q" value="<?= @$_GET['q'] ?>" class="input_text" style="width:200px" /><input type="submit" value="搜索"  class="button_blue"/>
        <input type="hidden" name="id" value="<?= @$_ID ?>" />
    </form>
</td>
</tr>
</table>

<?php if($pager->total_items > 0): ?>
<table width="100%" id="event_tb">
    <tr>
        <th colspan="2" style="text-align:left">基本信息</th>
        <th>状态</th>
        <th width="60" class="center">顶置</th>
	<th class="center">屏蔽</th>
        <th></th>
    </tr>
    <?php foreach($events as $e): ?>
    <tr style="border-bottom:1px dotted #ccc;">
        <td width="50">
            <?php $type_icon = $e['type'] ? $etype['icons'][$e['type']] : 'undefined.png'; ?>
            <div style="height:48px; width:48px; background: #fff url(<?= $etype['url'].$type_icon ?>) no-repeat center top;"></div>
        </td>
        <td style="height:90px;line-height: 1.6em">
            <strong style="font-size: 1.1em">
                <a href="<?= URL::site('event/view?id='.$e['id']) ?>"><?= $e['title'] ?></a>
            </strong><br />
	    发起：<?=$e['realname']?><br>
            地址：<?= $e['address'] ?><br>
	    发布：<?=$e['publish_at']?>
        </td>
        <td class="quiet" width="150" style="text-align: center">
            <?php if(time()>=strtotime($e['start']) AND  time()<=strtotime($e['finish'])):?>
            <span style="color:#4D7E05">进行中</span>
         <?php elseif(time()<=strtotime($e['start'])): ?>
         <span style="color:#4D7E05"><?= Date::span_str(strtotime($e['start'])) ?>后</span>
         <?php else:?>
         <span style="color:#f60">已结束</span>
         <?php endif;?>
        </td>

	
        <td class="center">
            <input type="checkbox" onclick="setEventBool(<?= $e['id'] ?>,'is_fixed')" <?= $e['is_fixed'] == true ? 'checked':'' ?> />
        </td>
	


        <td class="center">
            <input type="checkbox" onclick="setEventBool(<?= $e['id'] ?>,'is_closed')" <?= $e['is_closed'] == true ? 'checked':'' ?> />
        </td>
        <td>
            <a href="<?= URL::site('event/signDownload?id='.$e['id'].'&num='.$e['num']) ?>">下载名单</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<?= $pager ?>

<script type="text/javascript">
function setEventBool(id, field){
    new Request({
        url:'<?= URL::site('event/set') ?>',
        data:'event_id='+id+'&field='+field,
        method:'post'
    }).send();
}

    function homepage(id,n){
        new Request({
            url: '<?= URL::site('admin_event/homepage').URL::query() ?>',
            type: 'post',
            data: 'id='+id+'&n='+n
        }).send();
    }
</script>
<?else:?>
<span class="nodata">暂时还没有任何话题</span>
<?php endif; ?>