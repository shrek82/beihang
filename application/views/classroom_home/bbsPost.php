<div id="admin950">
    <form action="<?=URL::site('classroom_home/bbsPost?id='.$_CLASSROOM['id'])?>" id="bbs_unit_form" method="post">
    <table cellspacing="0" cellpadding="0" style="margin: 0px 20px;width:95%">
	<tr>
	    <td colspan="2" style="font-size:14px; color: #444;padding:10px; background: url(/static/images/topicnew.gif) no-repeat 10px; padding-left: 35px">
		<?=$unit?'修改话题':'发布新话题';?>
		</td>
	</tr>
        <input name="bbs_id" type="hidden" value="<?=$class_bbs['id']?>">
	<tr>
	    <td colspan="2" style="padding-top:10px;font-size:14px">标题：<input type="text" name="title" class="input_text" style="width:600px;font-size:14px;height:25px" value="<?=$unit['title']?>"></td>
	</tr>
	<tr>
	    <td colspan="2">
		    <div style="width:99%;height:330px"><textarea id="content" name="content" style="width:99%;height:260px"><?= @$unit['content'] ?></textarea></div>
	    </td>
	</tr>
	<tr>
	    <td colspan="2" style="text-align:center;padding:10px">
		<input type="button" id="submit_button" onclick="unitSave()" value="<?=$unit?'保存修改':'立即发布';?>" class="button_blue" />&nbsp;&nbsp;
	<input type="button" onclick="history.back()" value="返回"  class="button_gray" /></td>

	</tr>
    </table>
	<input type="hidden" value="<?=$unit['id']?>" name="unit_id">
	</form>
</div>
<?=View::ueditor('content', array(
    'toolbars' => Kohana::config('ueditor.common'),
    'minFrameHeight' => 300,
    'autoHeightEnabled' => 'false',
    'elementPathEnabled' => 'false',
    'focus' => 'true',
));?>

<script type="text/javascript">
    function unitSave(){
        var unit_form = new ajaxForm('bbs_unit_form', {callback:function(id){location.href='<?= URL::site('classroom_home/bbsUnit?id='.$_CLASSROOM['id'].'&unit_id=') ?>'+id}});
        if(!ueditor.hasContents()){ueditor.setContent('');}
        ueditor.sync();
        unit_form.send();
    }
</script>