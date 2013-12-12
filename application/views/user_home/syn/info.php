<div style="margin:15px 10px;line-height: 1.6em; color: #666">
<?php if(Model_User::privateChecker($rules, 'tel',$_ID)):?>
    电话：<?=$user_info['Contact']['tel']?$user_info['Contact']['tel']:'-' ?> <br>
 <?php endif;?>

   
<?php if(Model_User::privateChecker($rules, 'mobile',$_ID)):?>
    手机：<?=$user_info['Contact']['mobile']?$user_info['Contact']['mobile']:'-' ?> <br>
 <?php endif;?>

<?php if(Model_User::privateChecker($rules, 'qq',$_ID)):?>
    QQ：<?=$user_info['Contact']['qq']?$user_info['Contact']['qq']:'-' ?> <br>
 <?php endif;?>

<?php if(Model_User::privateChecker($rules, 'email',$_ID)):?>
    邮箱：<?=$user_info['account'] ?> <br>
 <?php endif;?>

<?php if(Model_User::privateChecker($rules, 'address',$_ID)):?>
    地址：<?=$user_info['Contact']['address']?$user_info['Contact']['address']:'-' ?> <br>
 <?php endif;?>

自我介绍：
<div style="margin:3px 0">
 <?=$user_info['intro']?$user_info['intro']:'还没有任何介绍' ?>
    </div>

<?php if(Model_User::privateChecker($rules, 'work',$_ID) AND $works):?>
工作经历：<br>
   <?php foreach($works as $w): ?>
           <?= $w['start_at'] ?> -
            <?= $w['leave_at'] == '0000-00-00' ? '至今' : $w['leave_at'] ?><?= $w['company'] ?>(<?= $w['job'] ?>)<br>
    <?php endforeach; ?>
</div>
<?php endif;?>