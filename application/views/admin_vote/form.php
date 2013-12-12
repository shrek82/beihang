<!-- admin_vote/form:_body -->
<form action="" method="post" enctype="multipart/form-data">
    <table class="admin_table" border="0" cellpadding="0" cellspacing="1" width="100%">
	    <tr>
	<td colspan="2" class="td_title"><?=$vote?'编辑调查':'添加调查'; ?></td>
	    </tr>
	    <tr>
		<td class="field">投票类型：</td>
		<td><select name="type" id="type">
    			<option value="checkbox" <?= $vote['type']=='checkbox' ? 'selected' : ''; ?> >多选</option>
			<option value="radio" <?= $vote['type']=='radio' ? 'selected' : ''; ?> >单选</option>
    		    </select>
		    <?php if (!$vote['type']): ?>
	    		    <script language="javascript">
			document.getElementById("type").value='<?= $vote['type'] ?>';
			    </script>
		    <?php endif; ?>
	    		</td>
	    	    </tr>

	    	    <tr>
	    		<td class="field">调查名称：</td>
	    		<td><input type="text" name="title" value="<?= $vote['title'] ?>"  style="width:500px" class="input_text" /></td>
	    	    </tr>

	    	    <tr>
	    		<td class="field">调查时间段：</td>
			<td><input type="text" name="start_date" value="<?= $vote['start_date']?$vote['start_date']:date('Y-m-d') ?>"  style="width:200px" class="input_text" />&nbsp;&nbsp;至&nbsp;&nbsp;<input type="text" name="finish_date" value="<?= $vote['finish_date'] ?>"  style="width:200px" class="input_text" />&nbsp;&nbsp;<span style="color:#999">例如：2011-01-01，不填为不限制</span></td>
	    	    </tr>

	    	    <tr>
	    		<td class="field" valign="top">初始化选项：</td>
	    		<td><textarea id="option" name="option" style="width:600px; height:100px"></textarea><br><span style="color:#999">提示：每行填写一条投票选项</span></td>
	    	    </tr>

	    	    <tr>
	    		<td colspan="2" style="padding:10px 20px"><textarea id="content" name="content"><?= @$vote['content'] ?></textarea></td>
	    	    </tr>

	    	    <tr>
	    		<td class="field"></td>
	    		<td class="center" style="padding:20px">

		    <?php if ($vote): ?>
				    <input type="submit" value="保存修改" name="button" class="button_blue" />
		    <?php else: ?>
		    		    <input type="submit" value="确定添加" name="button" class="button_blue"/>
		    <?php endif; ?>

		    		    <input type="button" value="取消" onclick="window.history.back()" class="button_gray">

		    		</td>
		    	    </tr>


		    	</tbody>
		        </table><br>

		        <input type="hidden" name="id" value="<?= $vote['id'] ?>" />

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