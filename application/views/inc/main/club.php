<?php

$rank = Doctrine_Query::create()
            ->select('c.*, (SELECT COUNT(m.id) FROM ClubMember m WHERE c.id = m.club_id) AS mcount,
                           (SELECT COUNT(e.id) FROM Event e WHERE c.id = e.club_id) AS ecount')
            ->from('Club c')
            ->orderBy('mcount DESC, ecount DESC')
			->limit(5)
            ->useResultCache(true, 3600, 'club_rank')
            ->fetchArray();

?>
<table width="100%">
    <?php foreach($rank as $club): ?>
    <tr style="border-bottom: 1px dotted #ccc">
        <td width="36"><img width="36" src="<?= Model_Club::logo($club['id']) ?>" /></td>
        <td>
            <a href="<?= URL::site('club_home?id='.$club['aa_id'].'&club_id='.$club['id']) ?>"><?= $club['name'] ?></a><br />
            <?= $club['mcount'] ?>位成员;
            活动<?= $club['ecount'] ?>个
        </td>
    </tr>
    <?php endforeach; ?>
</table>