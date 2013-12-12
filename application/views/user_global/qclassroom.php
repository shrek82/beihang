<?php 

    $classroom = Doctrine_Query::create()
            ->from('ClassMember cm')
            ->where('user_id=?',$_ID)
	           ->leftJoin('cm.ClassRoom cr')
            ->addWhere('cr.id>0')
            ->fetchArray();

    //echo Kohana::debug($classroom);
?>



<div id="user_home_qlink" class="candyCorner">

    <?php if(count($classroom) == 0): ?>
    尚未加入任何班级。
    <?php endif; ?>

    <?php foreach($classroom as $c): ?>
    <img src="/static/images/user/ico_house.gif" width="14" height="15" />&nbsp;
	    <a href="<?= URL::site('classroom_home/index?id='.$c['class_room_id']) ?>" style="color:#333" targent="_blank"><?= substr($c['ClassRoom']['start_year'], -2) ?>级<?= $c['ClassRoom']['speciality'] ?></a><br>
    <?php endforeach; ?>

</div>