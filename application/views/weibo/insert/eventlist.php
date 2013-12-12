<?php if($events):?>
<?php foreach($events AS $e):?>
<p style="height: 20px; line-height: 20px"><a href="javascript:;" onclick="insertstr('[e=<?=$e['id']?>]<?=$e['title']?>[/e]')" title="点击插入"><?= Text::limit_chars($e['title'], 20, '...') ?></a>&nbsp;(<?= date('Y-n-d',strtotime($e['start']));?>&nbsp;<?php if(time()>=strtotime($e['start']) AND  time()<=strtotime($e['finish'])): ?>
<span style="color:#4D7E05">进行中</span>
<?php elseif(time()<=strtotime($e['start'])): ?>
<span style="color:#999"></span>
<?php else: ?>
<span style="color:#999">活动结束</span>
<?php endif; ?>)</p>
<?php endforeach;?>
<?php else:?>
<div style="color:#999">很抱歉，暂时还没有您要查找的活动!</div>
<?php endif;?>