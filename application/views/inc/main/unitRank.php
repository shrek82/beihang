<?php

$time_span = time() - Date::WEEK;

$result = Doctrine_Query::create()
            ->select('COUNT(b.user_id) AS ucount, b.user_id, u.realname')
            ->from('BbsUnit b')
            ->leftJoin('b.User u')
            ->where('UNIX_TIMESTAMP(b.create_at) > ?', $time_span)
            ->groupBy('user_id')
            ->orderBy('ucount DESC')
            ->limit(4)
            ->useResultCache(true, 3600, 'bbs_post_rank')
            ->fetchArray();

$colors = array( '#9c0','#6cc','#969','#669' );

?>
<?php  if(count($result) > 0): ?>
<table width="100%">
<?php foreach($result as $i=>$r): ?>
    <tr>
        <td width="32"><img style="padding: 1px; border: 1px solid #ccc" width="32" alt="<?= $r['User']['realname'] ?>" src="<?= Model_User::avatar($r['user_id'], 48) ?>" /></td>
        <td>
            <a href="<?= URL::site('user_home?id='.$r['user_id']) ?>"><?= $r['User']['realname'] ?></a> (<?= $r['ucount'] ?>篇)<br />
            <div style="width:<?= $r['ucount']/$result[0]['ucount']*90 ?>%;height: 8px; font-size: 8px; color: #fff; background:<?= Arr::get($colors, $i) ?>"></div>
        </td>
    </tr>
<?php endforeach; ?>
</table>
<?php endif; ?>