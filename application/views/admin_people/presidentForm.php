
<form action="" method="post" >
<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" >
<tr >
<td height="29" class="td_title" colspan="2"><b><?=isset($president['id'])?'修改':'新增';?>校长：</b></td>
</tr>

	<tr>
	    <td class="field">时期：</td>
	    <td><select name="period">
		    <?php foreach($president_period as $key=>$p):?>
		    <option value="<?=$key?>" <?=$key==$president['period']?'selected':'';?>><?=$p?></option>
		    <?php endforeach;?>
		</select>

		    <?php if (!$president['period']&&$president_period): ?>
	    		    <script language="javascript">
			document.getElementById("period").value='<?= $president_period ?>';
			    </script>
		    <?php endif; ?>
	    </td>
	</tr>
	<tr>
	    <td class="field">所在学校：</td>
	    <td><input type="text" name="school" value="<?=$president['school']?>"  style="width:300px" class="input_text" /></td>
	</tr>

	<tr>
	    <td class="field">职务：</td>
	    <td><input type="text" name="jobs" value="<?=$president['jobs']?>"  style="width:300px" class="input_text"/></td>
	</tr>
	
	<tr>
	    <td class="field">姓名：</td>
	    <td><input type="text" name="name" value="<?=$president['name']?>"  style="width:300px"  class="input_text"/></td>
	</tr>



	<tr>
	    <td class="field">任期：</td>
	    <td><input type="text" name="term" value="<?=$president['term']?>"  style="width:300px" class="input_text"/>&nbsp;&nbsp;<span style="color:#999">例如：1993.3-1997.12</span></td>
	</tr>

	<tr>
	    <td class="field">排序：</td>
	    <td><input type="text" name="order_num" value="<?=$president['order_num']?$president['order_num']:$total_president+1 ?>"  style="width:300px" class="input_text"/>&nbsp;&nbsp;<span style="color:#999">(填写数字，值越小越靠前)</span></td>
	</tr>


	<tr>
	    <td class="field"></td>
	    <td>
		<?php if($president):?>
		<input type="submit" value="保存修改" name="button"  class="button_blue"/>
		<?php else:?>
		<input type="submit" value="确定添加" name="button" class="button_blue"/>
		<?php endif;?>
	    </td>
	</tr>

</table><br>

       <input type="hidden" name="id" value="<?= $president['id'] ?>" />

        <?php if($err): ?>
        <div class="notice"><?= $err; ?></div>
        <?php endif; ?>
</form>
