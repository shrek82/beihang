<style type="text/css">

#content_table td{ height: 24px}
#content_table .field{ text-align: right; padding: 0px 4px; width: 80px}
</style>
<form action="" method="post" enctype="multipart/form-data">
<table border="0" width="98%" id="content_table">
    <tbody>
<tr>
	    <td class="field">内容标题：</td>
	    <td><input type="text" name="title" value="<?=$content['title']?>"  style="width:500px" class="input_text" /></td>
	</tr>

	<tr>
	    <td class="field" valign="top" style="padding:5px">详细介绍： </td>
	    <td><textarea id="content" name="content"><?= @$content['content'] ?></textarea></td>
	</tr>
	<tr>
	    <td class="field">图片附件：</td>
	    <td><input type="file" name="file"  /></td>
	</tr>
	<tr>
	    <td class="field">自定义跳转：</td>
	    <td style="pading:15px"><input type="text" name="redirect" value="<?=$content['redirect']?>"  style="width:300px" class="input_text"/>&nbsp;&nbsp;<span style="color:#999">(如需自定义跳转其他页面，填写网址)</span></td>
	</tr>
	<tr>
	    <td class="field">内容排序：</td>
	    <td style="pading:15px"><input type="text" name="order_num" value="<?=$content['id']?$content['order_num']:$count?>"  style="width:300px" class="input_text"/>&nbsp;&nbsp;<span style="color:#999">(填写数字，值越小越靠前,可不填)</span></td>
	</tr>

	<tr>
	    <td class="field"></td>
	    <td class="center" style="padding:20px">

		<input name="type" value="<?=$type?>" type="hidden">
		<?php if($content):?>
		<input type="submit" value="保存修改" name="button" class="button_blue" />
		<?php else:?>
		<input type="submit" value="确定添加" name="button" class="button_blue"/>
		<?php endif;?>

		<input type="button" value="取消" onclick="window.history.back()" class="button_gray">

	    </td>
	</tr>


    </tbody>
</table><br>

       <input type="hidden" name="id" value="<?= $content['id'] ?>" />

        <?php if($err): ?>
        <div class="notice"><?= $err; ?></div>
        <?php endif; ?>
</form>
<?=View::ueditor('content', array(
    'toolbars' => Kohana::config('ueditor.common'),
    'minFrameHeight' => 350,
    'autoHeightEnabled' => 'false',
));
?>