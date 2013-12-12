<style type="text/css">
#links_table td{ height: 24px}
#links_table .field{ text-align: right; padding: 0px 4px; width: 80px}
</style>
<form action="" method="post" enctype="multipart/form-data">
<table border="0" width="100%" id="links_table">
    <tbody>
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
		<input type="submit" value="保存修改" name="button" />
		<?php else:?>
		<input type="submit" value="确定添加" name="button" />
		<?php endif;?>
			    </td>
	</tr>


    </tbody>
</table><br>

       <input type="hidden" name="id" value="<?= $link['id'] ?>" />

        <?php if($err): ?>
        <div class="notice"><?= $err; ?></div>
        <?php endif; ?>
</form>
