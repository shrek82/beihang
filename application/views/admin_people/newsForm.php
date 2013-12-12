<!-- admin_news/form:_body -->
<?php
$action = 'news/create';
if ($news) {
    $action = 'news/update';
}
?>
<script type="text/javascript">
    var news_form = new ajaxForm('news_form', { callback:function(){ history.back() } });
</script>

<form action="<?= URL::site('admin_people/newsForm?id='.$news['id']) ?>" id="news_form" method="post"  >
    <table class="admin_table" border="0" cellpadding="0" cellspacing="1" width="100%">
	    <tr>
	<td colspan="2" class="td_title"><?=$news?'编辑新闻':'添加新闻'; ?></td>
    </tr>

    	    <tr>
    		<td class="field">新闻标题：</td>
    		<td><input type="text" name="title" value="<?= $news['title'] ?>"  style="width:600px" class="input_text" />
    		</td>
    	    </tr>

    	    <tr>
    		<td colspan="2" style="padding:10px 20px">
                                                <div style="height:300px">
                                <textarea id="content" name="content" style="width:99%;height:240px"><?= @$news['content'] ?></textarea>
                            </div>
                </td>
    	    </tr>

    	    <tr>
    		<td class="field">关键字：</td>
		<td><input type="text" name="tag" value="<?= $news['tag'] ?>"  style="width:400px" class="input_text" /><span style="color:#999">&nbsp;&nbsp;填写新闻涉及的人或事</span></td>
    	    </tr>
    	    <tr>
    		<td class="field">发布时间：</td>
    		<td><input type="text" name="create_at" value="<?= $news['create_at'] ? $news['create_at'] : date('Y-m-d H:i:s') ?>"  style="width:400px" class="input_text" /><span style="color:#999"> </span></td>
    	    </tr>


    	    <tr>
    		<td style="padding:20px; text-align: center" colspan="2" >


		    <?php if ($news): ?>
	    		    <input type="submit" value="保存修改" name="button" class="button_blue" />
		    <?php else: ?>
				    <input type="submit" value="立即发布" name="button" class="button_blue" />
		    <?php endif; ?>
				    <input type="button" value="取消" onclick="window.history.back()" class="button_gray">
				</td>
			    </tr>

		    </table><br>
    <?php if ($err): ?>
				        <div class="notice"><?= $err; ?></div>
    <?php endif; ?>
				    </form>

<?=View::ueditor('content', array(
    'toolbars' => Kohana::config('ueditor.common'),
    'minFrameHeight' => 400,
    'autoHeightEnabled' => 'false',
));
?>
