<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" >
<tr>
<td width="33%" style="border-bottom:0;padding:0px 10px" class="td_title">
		<?php if($content):?>
		 修改捐赠统计：
		<?php else:?>
		 新增捐赠统计：
		<?php endif;?>

   </td>
</tr>
</table>

<form action="" method="post" >
<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" >

	<tr>
	    <td class="field">捐赠金额：</td>
	    <td><input type="text" name="amount" value="<?=$content['amount']?>"  style="width:400px" class="input_text"  /></td>
	</tr>

	<tr>
	    <td class="field">捐赠集体/个人：</td>
	    <td><input type="text" name="donor"  value="<?=$content['donor']?>" style="width:400px" class="input_text"/></td>
	</tr>

	<tr>
	    <td class="field">捐赠日期：</td>
	    <td><input type="text" name="donate_at" value="<?= $content['donate_at'] ? $content['donate_at'] : date('Y-m-d') ?>"  style="width:400px" class="input_text"/></td>
	</tr>

	<tr>
	    <td class="field"></td>
	    <td style="padding:20px 0">
		
		<?php if($content):?>
		<input type="submit" value="保存修改" name="button" class="button_blue"/>
		<?php else:?>
		<input type="submit" value="确定添加" name="button"  class="button_blue"/>
		<?php endif;?>
		<input type="button" value="返回" class="button_gray" onclick="window.history.back()">

	    </td>
	</tr>


</table><br>

        <?php if($err): ?>
        <div class="notice"><?= $err; ?></div>
        <?php endif; ?>

       <input type="hidden" name="id" value="<?= $content['id'] ?>" />

</form>
