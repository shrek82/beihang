<?php

    $aa = Doctrine_Query::create()
            ->from('Aa a')
            ->whereIn('a.id', Model_User::aaIds($_ID))
            ->fetchArray();

    $club = Doctrine_Query::create()
                ->from('Club c')
                ->whereIn('c.id', Model_User::clubIds($_ID))
                ->fetchArray();
?>

<div id="user_home_qlink" class="candyCorner">
    <?php if(count($aa) == 0): ?>
    尚未加入任何组织。
    <?php endif; ?>

    <?php foreach($aa as $a): ?>
    <img src="/static/images/user/ico_house.gif" width="14" height="15" />&nbsp;
     <a href="<?=URL::site('aa_home?id='.$a['id']) ?>" style="color:#333"><?= $a['name'] ?></a>
     <br>
    <?php foreach($club as $c): if($c['aa_id'] == $a['id']): ?>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└&nbsp;<a href="<?= URL::site('club_home?id='.$c['id']) ?>" style="color:#666"><?= $c['name'] ?></a>
     <br>
    <?php endif; endforeach; ?>

    <?php endforeach; ?>
</div>