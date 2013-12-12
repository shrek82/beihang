<!-- aa_home/club:_body -->
<style type="text/css">
ul.club_box li{ width: 100px; height: 100px; float: left; padding: 2px ; margin: 10px; text-align: center}
ul.club_box li img{width:70px; height: 70px;-webkit-border-radius: 12px;border-radius: 12px;-webkit-box-shadow:2px 2px 2px #d8d8d8;;box-shadow:2px 2px 2px #d8d8d8;background-color: #fff;padding:2px;border: 1px solid #f5f5f5}
ul.club_box li img:hover{border: 1px solid #D9D8D8;-webkit-box-shadow:2px 2px 2px #C1BFBF;;box-shadow:2px 2px 2px #C1BFBF;}
</style>
<div id="main_left">
    <?php if (count($clubs) > 0): ?>
    <ul class="club_box">
        <?php foreach ($clubs as $key => $club): ?>
        <li>
<a href="<?= URL::site('club_home?id=' . $club['id']) ?>" target="_blank" ><img src="<?=$club['logo_path']?$club['logo_path']:'/static/upload/club/default.jpg';?>" alt="<?= $club['name'] ?>LOGO"  /></a><br>
<?=str_replace('俱乐部','',$club['name'])?>(<span style="color:#1F9800"><?= $club['member_num'] ?></span>人)
        </li>
<?php endforeach; ?>
          </ul>

<div class="clear"></div>
        <?= $pager ?>
    <?php else: ?>
        <div class="nodata">暂时还没有成立任何俱乐部</div>
    <?php endif; ?>
    <div class="clear"></div>
</div>

<div id="main_right">

    <p class="column_tt">最新活动</p>
    <div class="aa_block">
        <ul class="aa_conlist">
            <?php foreach ($event as $e): ?>
                <li><a href="<?= URL::site('event/view?id=' . $e['id']) ?>"><?= Text::limit_chars($e['title'], 14, '..') ?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>

    <p class="column_tt">最进加入</p>
    <div class="aa_block" style="padding:10px 0">
        <?php if (!$joinmember): ?>
            <p class="nodata" style="padding:0px 5px">暂无加入</p>
        <?php endif; ?>

        <?php foreach ($joinmember as $m): ?>
            <div class="ubox" style="margin-right:10px">
                <a href="<?= URL::site('user_home?id=' . $m['user_id']) ?>"><?= View::factory('inc/user/avatar', array('id' => $m['user_id'], 'size' => 48))
        ?></a>
                <p class="face_name"><a href="<?= URL::site('user_home?id=' . $m['user_id']) ?>"><?= $m['realname'] ?></a><br>
                    <span><?= Date::span_str(strtotime($m['join_at']) + 1) ?>前</span></p>
            </div>
        <?php endforeach; ?>
        <div class='clear'></div>
    </div>
</div>