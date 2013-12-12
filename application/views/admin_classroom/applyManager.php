<!-- admin_classroom/applyManager:_body -->

<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" style="margin-top:9px ">
<tr >
<td height="29" class="td_title" colspan="5"><b>加入校友会申请：</b>
</tr>
    <tr>
        
	<td width="20%" class="conter">申请校友及理由</td>
	<td width="10%" style="text-align:center">当前校友审核状态</td>
<td width="25%">班级名称</td>
	<td width="10%" class="center">创建日期</td>
        <td width="9%" class="center">操作</td>
    </tr>

    <?php if(count($apply) == 0): ?>
    <tr>
        <td colspan="6">暂时还没有申请信息。</td>
    </tr>
    <?php else: ?>

    <?php foreach($apply as $key=> $a): ?>
    <tr id="apply_<?=$a['id']?>" class="<?php if(($key)%2==0){echo'even_tr';} ?>">
	
	<td><a href="javascript:userDetail(<?= $a['user_id'] ?>)" title="浏览详细信息"><?= $a['realname'] ?></a><br>理由：<?=$a['content']?></td>
<td style="text-align:center;<?php if($a['role']=='校友(已认证)') :?>color:#007219<?php endif;?>"><?=$a['role']?></td>
	<td >
<?= $a['ClassRoom']['start_year']?$a['ClassRoom']['start_year']:'?' ?> ~ <?= $a['ClassRoom']['finish_year']?$a['ClassRoom']['finish_year']:'?' ?>年
 <a href="<?= URL::site('classroom_home?id='.$a['class_room_id']) ?>"  target="_blank"><?= $a['ClassRoom']['speciality']?$a['ClassRoom']['speciality']:$a['ClassRoom']['name'] ?></a>
        </td>
	<td style="text-align: center"><?= $a['apply_at'] ?></td>
        <td class="handler" style="text-align: center">
            <a href="javascript:accept(<?= $a['id'] ?>,<?= $a['user_id'] ?>,<?= $a['class_room_id'] ?>)">批准</a> |
            <?php if($a['is_reject']): ?>
            <span class="quiet"><a href="javascript:reject(<?= $a['id'] ?>)">已拒绝</a></span>
            <?php else: ?>
            <a href="javascript:reject(<?= $a['id'] ?>)">拒绝</a>
            <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; ?>
    <?php endif; ?>
</table>
<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" style="margin-top:-1px " >
     <tr>
	<td style="height: 50px"><?= $pager ?></td>
    </tr>
</table>
<script type="text/javascript">

function accept(cid,user_id,class_room_id){
    var b = new Facebox({
        title: '批准确认！',
        icon:'question',
        message: '确定要通过该申请吗？',
        ok: function(){
            new Request({
		url: '<?= URL::site('admin_classroom/applyAccept?cid=') ?>'+cid+'&user_id='+user_id+'&class_room_id='+class_room_id,
                type: 'post',
                success: function(){
                    candyDel('apply_'+cid,'#A2E39B');
                }
            }).send();
            b.close();
        }
    });
    b.show();
}

function reject(cid){
    var box = new Facebox({
        title: '拒绝原因',
        url: '<?= URL::site('admin_classroom/applyReject?cid=') ?>'+cid,
        ok: function(){
            new ajaxForm('reason_form', {callback:function(){
                box.close();
            }}).send();
        }
    });

    box.show();
}

function userDetail(uid){
    var box = new Facebox({
        url: '<?= URL::site('user/userDetail?webmanager=1&id=') ?>'+uid,
	width:500
    });
    box.show();
}
</script>

