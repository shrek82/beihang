<?php
$sql='select id,sname,name from aa where id in ('.implode(",",Model_User::aaIds($_ID)).') limit 10';
$aa=DB::query(Database::SELECT,$sql)->execute()->as_array();

$sql='select aa_id,id,name from club where id in ('.implode(",",Model_User::clubIds($_ID)).') limit 10';
$club=DB::query(Database::SELECT,$sql)->execute()->as_array();

$sql='select cm.id,cm.user_id,cm.class_room_id,cr.id,cr.start_year,cr.speciality from class_member cm  LEFT JOIN class_room cr ON cr.id=cm.class_room_id where cm.user_id='.$_ID.' limit 10';
$classroom = DB::query(Database::SELECT,$sql)->execute()->as_array();
?>

<style type="text/css">
    #user_home_qlink{ color:#333; margin-top:5px; background:#EEF6F8; padding: 3px; border: 1px solid #CAE1E6 }
    #user_home_qlink a{ display: block; padding: 0 0 0 8px; margin:1px; text-align: left; text-decoration: none; }
    #user_home_qlink a:hover{ background: #C5DEE3 }
</style>

<div id="user_home_qlink" class="candyCorner">

    <?php if (count($aa) == 0 AND count($classroom)==0): ?>
        还没有加入任何校友会或班级！
    <?php endif; ?>

    <?php foreach ($aa as $a): ?>
        <a href="<?= URL::site('aa_home?id=' . $a['id']); ?>"><b><?= $a['name'] ?></b></a>
        <?php if ($_C != 'index'): ?>
            <?php foreach ($club as $c): if ($c['aa_id'] == $a['id']): ?>
                    <a href="<?= URL::site('club_home?id=' . $a['id'] . '&club_id=' . $c['id']) ?>">&lfloor; <?= $c['name'] ?></a>
                <?php endif;
            endforeach; ?>
        <?php endif; ?>

    <?php endforeach; ?>

    <?php if ($_C == 'index'): ?>
        <?php foreach ($classroom as $c): ?>
            <a href="<?= URL::site('classroom_home/index?id=' . $c['class_room_id']) ?>" ><b><?= $c['start_year'] ?>级<?= $c['speciality'] ?>班</b></a>
        <?php endforeach; ?>
    <?php endif; ?>
</div>