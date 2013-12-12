<form action="" method="post" enctype="multipart/form-data">
    <table class="admin_table" border="0" cellpadding="0" cellspacing="1" width="100%">
	    <tr>
	<td colspan="2" class="td_title"><?=$content?'编辑内容':'添加内容'; ?></td>
    </tr>
	    <tr>
		<td class="field">内容分类：</td>
		<td><select name="type" id="type">
			<?php foreach ($content_type as $t): ?>
    			<option value="<?= $t['id'] ?>" <?= $t['id'] == $content['type'] ? 'selected' : ''; ?> ><?= $t['name'] ?></option>
			<?php endforeach; ?>
    		    </select>
		    <?php if (!$content['type']&&$sess_content_type): ?>
	    		    <script language="javascript">
			document.getElementById("type").value='<?= $sess_content_type ?>';
			    </script>
		    <?php endif; ?>
	    		</td>
	    	    </tr>

	    	    <tr>
	    		<td class="field">内容标题：</td>
	    		<td><input type="text" name="title" value="<?= $content['title'] ?>"  style="width:500px" class="input_text" /></td>
	    	    </tr>

	    	    <tr>
	    		<td colspan="2" style="padding:10px 20px">
                           
                            <div style="width:99%;height:380px">
                                <textarea id="content" name="content" style="width:99%;height:320px"><?= @$content['content'] ?></textarea>
                            </div>
                            </td>
	    	    </tr>
	    	    <tr>
	    		<td class="field">图片附件：</td>
	    		<td><input type="file" name="file"  /></td>
	    	    </tr>
	    	    <tr>
	    		<td class="field">发布日期：</td>
	    		<td ><input type="text" name="create_at" value="<?= $content['create_at'] ? $content['create_at'] : date('Y-m-d H:i:s') ?>"  style="width:300px" class="input_text"/>&nbsp;&nbsp;</td>
	    	    </tr>


	    	    <tr>
	    		<td class="field">自定义跳转：</td>
	    		<td ><input type="text" name="redirect" value="<?= $content['redirect'] ?>"  style="width:300px" class="input_text"/>&nbsp;&nbsp;<span style="color:#999">(如需自定义跳转其他页面，填写网址)</span></td>
	    	    </tr>
	    	    <tr>
	    		<td class="field">内容排序：</td>
	    		<td><input type="text" name="order_num" value="<?= $content['order_num'] ?>"  style="width:300px" class="input_text"/>&nbsp;&nbsp;<span style="color:#999">(填写数字，值越小越靠前,可不填)</span></td>
	    	    </tr>
<tr>
	<td colspan="2" class="td_title">以下属性仅对“首页静态图片展示”有效</td>
    </tr>
	    	    <tr>
	    		<td class="field">显示日期段：</td>
	    		<td ><input type="text" name="start_date" value="<?= $content['start_date'] ? $content['start_date'] : date('Y-m-d') ?>"  style="width:150px" class="input_text"/> - <input type="text" name="end_date" value="<?= $content['end_date'] ? $content['end_date'] :date('Y-m-d',strtotime('+30 day'));; ?>"  style="width:150px" class="input_text"/>&nbsp;&nbsp;<span style="color:#999">例如： 2011-01-01-2011-02-01，两个日期都必须填写</span></td>
	    	    </tr>
	    	    <tr>
	    		<td class="field">日期段内显示状态：</td>
	    		<td ><input type="radio" name="is_close" value="0" <?= empty($content['is_close']) ? 'checked':'' ?> /> 显示
            <input type="radio" name="is_close" value="1" <?= $content['is_close'] ? 'checked':'' ?> /> 不显示</td>
	    	    </tr>
	    	    <tr>
	    		<td class="field"></td>
	    		<td class="center" style="padding:20px">

		    <?php if ($content): ?>
				    <input type="submit" value="保存修改" name="button" class="button_blue" />
		    <?php else: ?>
		    		    <input type="submit" value="确定添加" name="button" class="button_blue"/>
		    <?php endif; ?>

		    		    <input type="button" value="取消" onclick="window.history.back()" class="button_gray">

		    		</td>
		    	    </tr>


		    	</tbody>
		        </table><br>

		        <input type="hidden" name="id" value="<?= $content['id'] ?>" />

    <?php if ($err): ?>
					    <div class="notice"><?= $err; ?></div>
    <?php endif; ?>
					</form>
<?=View::ueditor('content', array(
    'toolbars' => Kohana::config('ueditor.common'),
    'minFrameHeight' => 300,
    'autoHeightEnabled' => 'false',
));
?>