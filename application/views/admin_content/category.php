<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" >
    <tr >
	<td height="29" class="td_title" ><b>已有分类：</b></td>
    </tr>
    <tr>
	<td height="25" style="padding:0px 10px" >
	    <?php if (!$content_type): ?>
    	    <div class="nodata">暂时还没有该类内容。</div>
	    <?php else: ?>
	    <?php foreach ($content_type as $t): ?>
	    	    <a href="<?= URL::site('admin_content/category?id=' . $t['id']); ?>" ><?= $t['name'] ?></a>&nbsp;&nbsp;
	    <?php endforeach; ?>
	    <?php endif; ?>
	    	</td>
	        </tr>
	    </table>

	    <form action="" method="post" >
	        <table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" >
	    	<tr >	    
	    	    <td height="29" class="td_title" colspan="2"><b><?= $category ? '修改分类' : '新建分类'; ?>：</b></td>
	    	</tr>

	    	<tr>
	    	    <td class="field">分类名称：</td>
	    	    <td><input type="text" name="name" value="<?= $category['name'] ?>"  style="width:200px" class="input_text"  /></td>
	    	</tr>

	    	<tr>
	    	    <td class="field">分类排序：</td>
	    	    <td><input type="text" name="order_num" value="<?= $category['order_num'] ?>"  style="width:200px" class="input_text"/>&nbsp;&nbsp;<span style="color:#999">(填写数字，值越小越靠前)</span></td>
	    	</tr>

	    	<tr>
	    	    <td class="field"></td>
	    	    <td style="padding:10px 0">

		<?php if ($category): ?>
		<?php if ($category['is_system']): ?>
				<input type="submit" value="系统分类" name="button" class="button_gray" disabled="disabled" title="系统分类，暂时禁止修改" />
		<?php else: ?>
		    		<input type="submit" value="保存修改" name="button" class="button_blue" />
		<?php endif ?>

		<?php else: ?>
					<input type="submit" value="确定添加" name="button"  class="button_blue"/>
		<?php endif; ?>

					<input type="button" value="取消" onclick="window.history.back()" class="button_gray">


				    </td>
				</tr>


				</tbody>
			    </table><br>

			    <input type="hidden" name="id" value="<?= $category['id'] ?>" />

    <?php if ($err): ?>
					    <div class="notice"><?= $err; ?></div>
    <?php endif; ?>
</form>
