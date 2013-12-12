<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" style="margin-top:9px ">
<tr >
<td height="29" class="td_title" ><b>已有论坛分类：</b></td>
</tr>
<tr>
<td height="25" style="padding:0px 10px" >
    <a onclick="getBbsForm(0)"  href="javascript:;" style="color:#f30"><b>新建分类</b></a>&nbsp;&nbsp;|
    <?php if(count($bbs) > 0): ?>
    <?php foreach($bbs as $b): ?>
    <?= $b['order_num'] ?>、<a href="javascript:getBbsForm(<?= $b['id'] ?>)"><?= $b['name'] ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;
    <?php endforeach; ?>
    <?php endif; ?>
</td>
</tr>
</table>

<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" >
<tr >
<td height="29" class="td_title" ><b>添加/修改分类：</b></td>
</tr>
    <tr >
	<td>
<div id="bbs_form_loader" style="padding:15px 20px">

</div>
	</td>
    </tr>
</table>

<script type="text/javascript">
function getBbsForm(id){
    $('bbs_form_loader').load('<?= URL::site('admin_bbs/form') ?>?bbs_id='+id);
}
getBbsForm()
</script>