<table width="100%" id="info_notice">
<?php if(count($marks) > 0): ?>
<tr>
    <td colspan="2">
        <?php foreach($marks as $m): ?>
        <a href="<?= URL::site('user_home?id='.$m['user_id']) ?>"><?= $m['User']['realname'] ?></a> ,
        <?php endforeach; ?>
        将<?= $_MASTER ? '我':'TA' ?>列入关注
    </td>
</tr>
<?php endif; ?>
<?php foreach($news as $n): ?>
<tr>
    <td colspan="2">新闻： <a href="<?= URL::site('news/view?id='.$n['id']) ?>"><?= $n['title'] ?></a> 在<?= Date::span_str(strtotime($n['create_at'])) ?>前发布。</td>
</tr>
<?php endforeach; ?>
<?php foreach($units as $unit): ?>
<tr>
    <td colspan="2">
        <img src="<?= URL::base().'static/images/'.$unit['type'].'.gif' ?>" />
        <a href="<?= URL::site('bbs/view'.$unit['type'].'?id='.$unit['id'].'&cmt=y') ?>"><?= $unit['title'] ?></a>
        有<?= count($unit['Comments']) ?>条新回复。
    </td>
</tr>
<?php endforeach; ?>
<?php foreach($events as $e): ?>
<tr>
    <td colspan="2">
        <img src="<?= URL::base().'static/images/tea.gif' ?>" />
        <a href="<?= URL::site('event/view?id='.$e['id']) ?>"><?= $e['title'] ?></a>
        在<?= Date::span_str(strtotime($e['start'])) ?>后开始。
    </td>
</tr>
<?php endforeach; ?>
</table>