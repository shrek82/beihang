<?php
$data = DB_Comment::get($search);
$comments = $data['comments'];
$floor = $data['floor'];
?>
<?php if(count($comments)>0):?>
<div style="margin:5px 0; font-weight: bold; color: #c00">最新回复：</div>
<div style="border-top:1px solid #DAD8D8;border-bottom:1px solid #fff"></div>
<?php foreach($comments AS $c):?>
<div class="cmt_box">
    <div class="author_box">
        <div class="author"><?=$floor['floor_'.$c['id']]?>楼 <?=$c['realname']?>&nbsp;<?=$c['start_year']?$c['start_year'].'级':'';?><?=  Text::limit_chars($c['speciality'],7,'...')?></div>
        <div class="post_date"><?= Date::ueTime($c['post_at']); ?></div>
    </div>
    <div style=" clear: both; font-size: 15px; width: 300px;word-break:break-all; color: #333"><?=Emotion::autoToUrl(Common_Global::mobileText($c['content']),true);?></div>
</div>
<?php endforeach;?>
<?php else:?>
<?php endif;?>