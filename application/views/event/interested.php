<?php if (!isset($_COOKIE['interested_event_id' . $event_id])): ?>
    <a href="javascript:;" onclick="interested(<?= $event_id ?>,<?= $interested_num?>)" class="interested" >感兴趣(<?= $interested_num?>)</a>
<?php else: ?>    感兴趣(<?= $interested_num ?>)<?php endif; ?>