<style type="text/css">
body{ background: #f5f5f5; font-family: verdana}
.cmt_box{border-bottom:1px solid #eee; padding-bottom:5px; margin-bottom: 5px}
.author_box{height:20px;font-size: 12px}
.author{width: 60%;color:#3E71C6;float: left}
.cmt_content{ font-size: 14px;}
.post_date{width: 35%;color:#999;float:right; text-align: right}
.quote_new{ border:1px solid #CECE7E;background:#FCFCF2;font-size:12px; padding:4px; margin-bottom:4px}
.quote_new .quote_author{ margin:2px 5px 0 5px; padding: 0}
.quote_new .quote_name{ float: left;width:80%;color: #666;padding:0px; margin: 0}
.quote_new .quote_floor{ float: right;width:10%; text-align: right;color: #666;padding:0px; margin: 0}
.quote_new .quote_content{ clear: both;color: #333; font-size: 12px; text-align: left;padding:0px 5px; margin:-10px 0px 15px 0px}
</style>
<?php if(count($comment)>0):?>
<?php foreach($comment AS $key=>$c):?>
<div class="cmt_box">
    <div class="author_box">
        <div class="author"><?=$c['user']['realname']?>&nbsp;<?=$c['user']['speciality']?></div>
        <div class="post_date"><?=$c['str_create_date']?></div>
    </div>
    <div class="cmt_content">
<?php if ($c['quote_ids']): ?>
<?=View::factory('comment/quote', array('ids' => $c['quote_ids'],'floor'=>array())) ?>
<?php endif; ?>
    <?=$c['content']?>
    </div>
</div>
<?php endforeach;?>
<?php endif;?>