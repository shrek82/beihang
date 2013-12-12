<!-- admin/filter:_body -->
<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" style="margin-top:0px " class="admin_talbe">
<tr >
<td height="29" class="td_title" ><b>所有非法关键词：</b>
</td>
</tr>
<tr>
<td height="25" style="padding:0px 10px" >
    <?php if(count($filters) == 0): ?>
    <span class="nodata">没有任何分类信息。</span>
    <?php else: ?>
        <?php foreach($filters as $f): ?>
    <a href="<?=URL::site('admin/filter?id='.$f['id'])  ?>)" title="点击修改"><?= $f['string'] ?></a>&nbsp;&nbsp;
        <?php endforeach; ?>
    <?php endif; ?>
</td>
</tr>
</table>


<form action="<?= URL::query(); ?>" id="news_filter_form" method="post" style="margin:0; padding: 0">
<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" style="margin-top:2px ">
    <tr>
	<td colspan="2" class="td_title"><?= $filter?'修改':'添加' ?>非法关键词</td>
    </tr>
    <tr >
	<td class="field" style="width:150px">关键词：</td>
	<td><input size="30" type="text" name="string" value="<?= @$filter['string'] ?>" class="input_text"/></td>
    </tr>
    <tr>
	<td class="field" style="width:150px"></td>
	<td > <input type="hidden" name="id" value="<?= @$filter['id'] ?>" />
        <input type="submit" value="<?= $filter?'保存修改':'确定添加' ?>" class="button_blue" />
	<?php if($filter['id']):?><input type="button" value="删除" class="button_blue"  onclick="window.location.href='<?=URL::site('admin/delFilter?id='.$filter['id']) ?>'"/><?php endif;?>

	</td>
    </tr>
</table>
</form>
