<!-- admin_people/index:_body -->
<style type="text/css">
    .td_field{width:80px; padding: 0px 4px; text-align: right}
    .del:link,.del.visited{ color: #999}
    .pp:hover .del{ display: inherit}
</style>
<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" >
<tr >
<td height="29" class="td_title" ><b>快速检索：</b></td>
</tr>

<tr>
    <td > 

    <a href="<?= URL::site($_URI) ?>">所有</a>&nbsp;
    <?php for($abc='A';$abc<'Z';$abc++): ?>
    <a href="<?= URL::query(array('abc'=>$abc)) ?>"><?= $abc ?></a>&nbsp;
    <?php endfor; ?>
    <a href="<?= URL::query(array('abc'=>'Z')) ?>">Z</a>
</td>
</tr>
<tr >
<td height="29" class="td_title" ><b>已有院士：</b></td>
</tr>
<tr >
<td >
<div style="margin:5px 20px; padding:10px 0">
<?php if(count($people) == 0): ?>
<p class="ico_info icon">
    没有找到相关人物信息。
</p>
<?php else: ?>
<div>
    <?php foreach($people as $p): ?>
    <span id="people_<?=$p['id']?>" class="pp"><a href="<?= URL::query(array('ppid'=>$p['id']), false) ?>"><?= $p['name'] ?></a> <a href="javascript:del(<?=$p['id']?>)" title="删除" class="del">×</a></span>&nbsp;&nbsp;
    <?php endforeach; ?>
</div>
<?php endif; ?>
</div>
</td>
</tr>
</table>



<form method="post" action="<?= $_URL ?>" id="people_form" enctype="multipart/form-data">
<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" >
<tr >
<td height="29" class="td_title" colspan="2" ><b><?php if(@$cur_pp['id']): ?>修改资料<?php else: ?>新增院士<?php endif; ?></b></td>
</tr>
        
<?php if(isset($cur_pp['id'])): ?>
	<tr>
	    <td></td>
	    <td>
    <img style="padding: 5px; border: 1px solid #eee" src="<?= $cur_pp['pic']; ?>" width="190" />
</td>
	</tr>
    <?php endif; ?>
        <tr>
            <td class="td_field">照片</td>
            <td>
                <input type="file" name="pic" />
            </td>
        </tr>
    <tr>
            <td class="td_field">姓名</td>
            <td>
                <input type="text" name="name"  value="<?= @$cur_pp['name'] ?>" style="width:150px" class="input_text" />
            </td>
        </tr>

        <tr>
            <td class="td_field">首字母索引</td>
            <td><input type="text" name="abc"  value="<?= @$cur_pp['abc'] ?>" style="width:150px" class="input_text" /></td>
        </tr>
        <tr>
            <td class="td_field">出生年月</td>
            <td><input type="text" name="birth"  value="<?= @$cur_pp['birth'] ?>"  style="width:150px" class="input_text"/>&nbsp;&nbsp;逝世年月<input type="text" name="leave"  value="<?= @$cur_pp['leave'] ?>" style="width:150px" class="input_text"/>(选填)</td>
        </tr>

        <tr>
            <td class="td_field" >基本介绍</td>
            <td><textarea name="intro" style="width: 600px; height: 120px;"  class="input_text"><?= @$cur_pp['intro'] ?></textarea>
            </td>
        </tr>
        <tr>
            <td></td>
	    <td >
                <?php if(@$cur_pp['id']): ?>
                <input type="submit" value="保存修改"  class="button_blue"/>
                <?php else: ?>
                <input type="submit" value="确定添加" class="button_blue"/>
                <?php endif; ?>
            </td>
        </tr>

    </table>
    <?php if(isset($err)): ?>
    <div class="notice"><?= $err; ?></div>
    <?php endif; ?>
</form>
</div>

<script type="text/javascript">
function del(cid){
if(confirm('确认要删除吗？')){
        new Request({
            url: '/admin_people/delPeople?cid='+cid,
            success: function(){
                candyDel('people_'+cid);
            }
        }).send();
    }
}
</script>
