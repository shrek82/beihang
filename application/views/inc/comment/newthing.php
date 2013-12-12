<?php foreach ($comments as $c): ?>
    <div class="a_newthing_comment">
        <img src="<?= URL::base() ?>static/ico/q4.gif" />
        <a href="<?= URL::site('user_home?id=' . $c['user_id']) ?>"><?= $c['realname'] ?></a>&nbsp;&nbsp;<?= $c['content'] ?><span class="newthing_com_time">&nbsp;&nbsp;(<?= Date::span_str(strtotime($c['post_at'])) ?>前)</span></div>
<?php endforeach; ?>