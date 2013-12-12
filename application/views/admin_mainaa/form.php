<form action="" method="post" enctype="multipart/form-data">
        <table class="admin_table" border="0" cellpadding="0" cellspacing="1" width="100%">
	    <tr>
	<td colspan="2" class="td_title"><?=$info?'编辑内容':'添加介绍'; ?></td>
    </tr>

	    	    <tr>
	    		<td class="field">内容分类：</td>
	    		<td><select name="type">
               <option value="其他" selected>其他</option>
               <option value="机构" <?= @$info['type']=='机构'?'selected':'';?>>机构</option>
               <option value="章程" <?= @$info['type']=='章程'?'selected':'';?>>章程</option>
           </select></td>
	    	    </tr>
	    	    <tr>
	    		<td class="field">内容标题：</td>
	    		<td><input type="text" name="title" value="<?= $info['title'] ?>"  style="width:500px" class="input_text" /></td>
	    	    </tr>

	    	    <tr>
                        <td colspan="2" style="padding:10px 20px;height: 300px" valign="top">
                            <div style="height:300px">
                                <textarea id="content" name="content" style="width:99%;height:240px"><?= @$info['content'] ?></textarea>
                            </div>
                        </td>
	    	    </tr>

	    	    <tr>
	    		<td class="field">内容排序：</td>
	    		<td style="pading:15px"><input type="text" name="order_num" value="<?= $info['order_num'] ?>"  style="width:300px" class="input_text"/>&nbsp;&nbsp;<span style="color:#999">(填写数字，值越小越靠前,可不填)</span></td>
	    	    </tr>

	    	    <tr>
	    		<td class="center" style="padding:20px;" colspan="2">

		    <?php if ($info): ?>
				    <input type="submit" value="保存修改" name="button" class="button_blue" />
		    <?php else: ?>
		    		    <input type="submit" value="确定添加" name="button" class="button_blue"/>
		    <?php endif; ?>

		    		    <input type="button" value="取消" onclick="window.history.back()" class="button_gray">

		    		</td>
		    	    </tr>

		        </table><br>

		        <input type="hidden" name="id" value="<?= $info['id'] ?>" />

    <?php if ($err): ?>
					    <div class="notice"><?= $err; ?></div>
    <?php endif; ?>
					</form>
<?=View::ueditor('content', array(
    'toolbars' => Kohana::config('ueditor.common'),
    'minFrameHeight' => 350,
    'autoHeightEnabled' => 'false',
));
?>