<?php if(count($list)>0):?>
<?php foreach($list AS $w):?>
<div style=" border-top: 1px solid #eee;  margin-bottom: 5px; padding: 0 0 15px 0" id="weibo_<?=$w['idstr']?>">
        <div style="background: #f9f9f9;padding:5px; color: #999;"><?=$w['created_at']?></div>
        <div style="line-height: 1.6em"><?=$w['text']?></div>
        <div style="text-align: right; margin-top:5px; color: #999; padding: 0px 10px"><a href="javascript:del(<?=$w['idstr']?>)" style="color:#FC817B">删除</a>&nbsp;&nbsp;来自:<?=$w['source']?></div>
</div>
<?php endforeach;?>
<?php else:?>
        这家伙什么都没有发布过！
<?php endif; ?>