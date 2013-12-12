
<form action="" method="post" enctype="multipart/form-data">
<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" >
<tr >
<td height="29" class="td_title" colspan="2"><b>添加链接：</b></td>
</tr>

	<tr>
	    <td class="field">链接分类：</td>
	    <td><select name="type">
		    <?php foreach($type_links as $t=>$n):?>
		    <option value="<?=$t?>" <?=$t==$link['type']?'selected':'';?>><?=$n?></option>
		    <?php endforeach;?>
		</select></td>
	</tr>
	<tr>
	    <td class="field">展现方式：</td>
	    <td><input type="radio" name="is_logo" value="0"   <?=empty($link['is_logo'])?'checked="checked"':'';?>/>文字链接&nbsp;&nbsp; <input type="radio" name="is_logo" value="1"  <?=$link['is_logo']==1?'checked="checked"':'';?>/>图片链接</td>
	</tr>

	<tr>
	    <td class="field">LOGO：</td>
	    <td><input type="file" name="logo"  /></td>
	</tr>
	
	<tr>
	    <td class="field">链接名称：</td>
	    <td><input type="text" name="name" value="<?=$link['name']?>"  style="width:200px"  /></td>
	</tr>

	<tr>
	    <td class="field">链接地址：</td>
	    <td><input type="text" name="url" value="<?=$link['url']?>"  style="width:300px"/></td>
	</tr>

	<tr>
	    <td class="field">链接排序：</td>
	    <td><input type="text" name="order_num" value="<?=$link['order_num']?>"  style="width:300px"/>&nbsp;&nbsp;<span style="color:#999">(填写数字，值越小越靠前)</span></td>
	</tr>

	<tr>
	    <td class="field"></td>
	    <td>
		
		<?php if($link):?>
		<input type="submit" value="保存修改" name="button"  class="button_blue"/>
		<?php else:?>
		<input type="submit" value="确定添加" name="button" class="button_blue"/>
		<?php endif;?>
		

	    </td>
	</tr>

</table><br>

       <input type="hidden" name="id" value="<?= $link['id'] ?>" />

        <?php if($err): ?>
        <div class="notice"><?= $err; ?></div>
        <?php endif; ?>
</form>