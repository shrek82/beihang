<?php if($users):?>
<?php foreach($users AS $u):?>
<input type="checkbox" name="selected_insert_user" value="[u=<?=$u['id']?>]<?=$u['realname']?>[/u]" checked="checked" />&nbsp;<a href="javascript:;" onclick="insertstr('[u=<?=$u['id']?>]<?=$u['realname']?>[/u]')" title="点击插入"><?=$u['realname']?></a><?php if($u['start_year'] OR $u['speciality']):?><span style="color:#999">(<?=$u['start_year']?$u['start_year'].'级':'';?><?=$u['speciality']?'-'.$u['speciality']:'';?>)</span><?php endif;?><br>
<?php endforeach;?>
<div style="padding:10px; text-align: right"><input type="button" value="插入所选姓名" class="button_blue" onclick="insert_selected_name()"></div>
<?php else:?>
<div style="color:#999">很抱歉，没有您要查找的校友!</div>
<?php endif;?>