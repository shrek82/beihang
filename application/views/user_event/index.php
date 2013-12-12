<!-- user_event/index:_body -->
<?php
    $etype = Kohana::config('icon.etype');
?>

<div id="big_right">
 <div id="plugin_title">我的活动</div>

<div class="tab_gray" id="user_topbar" style="margin-top:10px">
   <ul>
       <li><a href="<?= URL::site('user_event/index') ?>" class="cur" style="width:80px">我发起的活动</a></li>
       <li><a href="<?= URL::site('user_event/join') ?>"  style="width:80px">参与的活动</a></li>
       <li><a href="<?= URL::site('event/form') ?>"  style="width:80px" target="_blank">发起活动</a></li>
    </ul>
</div>

    <div>
        您已经发起了 <strong><?= $pager->total_items ?></strong> 次活动。
        <?php if($pager->total_items == 0): ?>
        <a href="<?= URL::site('user_event/form') ?>">我要发起活动</a>
        <?php endif; ?>
    </div>

    <?php if($pager->total_items > 0): ?>
    <table width="100%" id="event_table">
	<thead>
	<tr>
	    <td colspan="2" >活动信息</td>
      <td style="text-align:center">操作</td>
            <td style="text-align:center">修改</td>
           
        </tr>
	</thead>
	<tbody>
        <?php foreach($events as $e): ?>
        <tr>
            <td width="50">
                <?php $type_icon = $e['type'] ? $etype['icons'][$e['type']] : 'undefined.png'; ?>
                <div style="height:48px; width:48px; background: #fff url(<?= $etype['url'].$type_icon ?>) no-repeat center top;"></div>
            </td>
            <td style="line-height:1.6em; padding: 15px 0">
                <strong style="font-size: 1.1em">
                    <a href="<?= URL::site('event/view?id='.$e['id']) ?>" title="点击预览"><?= $e['title'] ?></a>
                </strong><br />
地点：<?= $e['address'] ?><br />
时间：<?= $e['start'] ?>&nbsp;(<?= Model_Event::status($e) ?>)

            </td>

            <td class="quiet" width="100" style="text-align:center">
            
                <?php if(strtotime($e['finish']) < time()): ?>
                <a href="<?= URL::site('event/form?id='.$e['id'].'&restart=y') ?>">重新发起</a>
                <?php else: ?>
                <span style="color:#999">尚未结束</span>
                <?php endif; ?>
		<br>
            </td>
            
            <td class="quiet" width="100" style="text-align:center">
              <a href="<?= URL::site('event/form?id='.$e['id']) ?>">编辑</a>
 
		<br>
            </td>


        </tr>
        <?php endforeach; ?>
	</tbody>
    </table>
    <?= $pager ?>
    <?php endif; ?>

</div>
