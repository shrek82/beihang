<!-- club_home/info:_body -->
<div id="main_left">
<div id="aa_home_left">
    <div class="column_tt"><?= $info['title'] ?></div>
    <div id="info_content" style="line-height: 1.7em; color: #333; padding: 10px"><?= $info['content'] ?></div>
</div>

    <div class="clear"></div>
    </div>

<div id="main_right">
    <!-- info -->
    <div class="column_tt">相关介绍</div>
    <div class="aa_block">
    <ul class="aa_conlist">
        <li><a href="<?= URL::site('club_home/info?id='.$_ID) ?>"><?= $_CLUB['name'] ?>简介</a></li>
    <?php foreach($infos as $inf): ?>
        <li><a href="<?= URL::site('club_home/info?id='.$_ID.'&info_id='.$inf['id']) ?>"><?= $inf['title'] ?></a></li>
    <?php endforeach; ?>
    </ul>
    </div>
</div>