<?php

// 随机抽一个院士
$people = Doctrine_Query::create()
            ->select('p.*, RANDOM() AS rand')
            ->from('People p')
            ->orderBy('rand')
            ->limit(1)
            ->useResultCache(true, 3600, 'people_rand')
            ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
?>
<!-- 校友风采 -->

<table width="100%">
    <tr>
        <td valign="top" width="80" rowspan="2">
            <img width="80" style="border: 1px solid #3e5ba5; padding: 2px;" src="<?= URL::site('index/image/'.$people['id'].'/People') ?>" />
        </td>
        <td align="left">
            <b><?= $people['name'] ?></b> 
            <a href="<?= URL::site('alumni/people/'.$people['id']) ?>">&gt;&gt;详细</a>
            <br />
            <span class="quiet">
                <?= Text::limit_chars($people['intro'], 50, '..') ?>
            </span>
        </td>
    </tr>
    <tr><td align="right"><a href="<?= URL::site('alumni/people') ?>">更多院士</a></td></tr>
</table>