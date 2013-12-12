<?php

$rank = Doctrine_Query::create()
            ->select('a.*, (SELECT COUNT(m.id) FROM AaMember m WHERE a.id = m.aa_id) AS mcount,
                           (SELECT COUNT(e.id) FROM Event e WHERE a.id = e.aa_id) AS ecount')
            ->from('Aa a')
            ->orderBy('mcount DESC, ecount DESC')
			->limit(5)
            ->useResultCache(true, 3600, 'aa_rank')
            ->fetchArray();

?>
<?php
#人气排行逐个颜色
$text_color=array('#ED3B0D','#FF5114','#FF7827','#FF8A33','#FFB000','#B5B5B5','#B5B5B5');
?>
<table width="100%">
    <?php foreach($rank as $i => $aa): if($aa['mcount'] > 0): ?>
    <tr style="border-bottom: 1px dotted #ccc">
        <td width="20" style="vertical-align: middle">
            <span style="font-size: <?= 18-$i ?>px; color:#ccc">No.<?= $i+1 ?></span>
        </td>
        <td>
            <a href="<?= URL::site('aa_home?id='.$aa['id']) ?>"><?= $aa['name'] ?></a><br />
            <?= $aa['mcount'] ?>位成员;
            活动<?= $aa['ecount'] ?>个
        </td>
    </tr>
    <?php endif;endforeach; ?>
</table>