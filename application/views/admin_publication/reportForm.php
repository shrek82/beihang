<style type="text/css">
#content_table td{ height: 24px}
#content_table .field{ text-align: right; padding: 0px 4px; width: 80px}
</style>
<form action="" method="post" enctype="multipart/form-data">
<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" >
<tr>
<td height="29" class="td_title" colspan="2"><b><?=$report['id']?'修改报刊':'新增报刊'?>：</b>
</td>
</tr>


<tr>
<td class="field">报刊名称：</td>
<td><input type="text" name="title"   style="width:300px" class="input_text"  value="<?=$report['title']?>"/>&nbsp;&nbsp;<span style="color:#999">如：北航校友电子信息报2010年第8期总第24期</span></td>
</tr>

<tr>
<td class="field">报刊期号：</td>
<td ><input type="text" name="issue"   style="width:300px" class="input_text"  value="<?=$report['issue']?>"/>&nbsp;&nbsp;<span style="color:#999">直接填写数字，如：24</span></td>
</tr>

<tr>
<td class="field">发布日期：</td>
<td ><input type="text" name="create_at"   style="width:300px" class="input_text"  value="<?=$report['create_at']?$report['create_at']:date('Y-m-d');?>"/>&nbsp;&nbsp;<span style="color:#999"></span></td>
</tr>


<tr>
<td class="field">导入内容：</td>
<td ><input type="file" name="file" />
<?php if($report['id']): ?>
<?php
if($report['content_path']):?>
<a href="<?=$report['content_path']?>" target="_blank" style="color:green">内容已经导入</a>
<?php else:?>
<span style="color:red">内容还没有上传</span>
<?php endif;?>
<?php endif;?>
</td>
</tr>


<tr>
<td class="field"></td>
 <td style="padding:20px 0">
<?php if($report):?>
<input type="submit" value="保存修改" name="button" class="button_blue" />
<?php else:?>
<input type="submit" value="确定添加" name="button" class="button_blue"/>
<?php endif;?>
<input type="button" value="取消" onclick="window.history.back()" class="button_gray">
</td>
</tr>

</table><br>

<input type="hidden" name="id" value="" />

<?php if($err): ?>
<div class="notice"><?= $err; ?></div>
<?php endif; ?>
</form>