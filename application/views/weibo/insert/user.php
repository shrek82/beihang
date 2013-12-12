<div style="width:400px; text-align: left">
<?php if($mark):?>
<div style="margin: 10px 0">已关注的：
<?php foreach($mark AS $u):?>
        <a href="javascript:;" onclick="insertstr2('@<?=$u['realname']?>、',false)" title="点击插入"><?=$u['realname']?></a>&nbsp;
<?php endforeach;?>
</div>
<?php endif;?>
<p>输入姓名：<input type="text" id="namekeys" class="input_text" style="width:250px" value=""><input type="button" value="查找" class="button_blue" onclick="search_insertuser()"></p>
<p style="color:#999;padding:4px 0px 5px 60px; line-height: 1.7em">
已关注校友可直接使用“@姓名”插入；<br>多个姓名请用逗号或空格隔开；<br></p>

<div id="insert_user_list" style="margin:10px 0">
</div>
</div>