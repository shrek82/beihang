<!-- admin_bbs/focus:_body -->
<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" >
<tr >
<td height="29" class="td_title" colspan="4" ><b>论坛幻灯片管理</b>
</td>
</tr>





    <?php if(count($units) == 0): ?>
<tr><td colspan="4" ><div class="nodata">暂时还没有幻灯片！</div></td></tr>
    <?php else: ?>

    <tr>
	<td>话题标题</td>
        <td class="center">话题发布于</td>
	<td class="center">修改</td>
        <td class="center">删除</td>
    </tr>
    <?php foreach($units as $key=>$u): ?>
    <tr id="con_<?=$u['id']?>" class="<?php if(($key)%2==0){echo'even_tr';} ?>">
        <td>
            <a target="_blank" href="<?= URL::site('bbs/view'.$u['type'].'?id='.$u['id']) ?>"><?= $u['title'] ?></a>
        </td>
        <td class="center"><?= Date::span_str(strtotime($u['create_at'])) ?>前</td>
	<td class="center"><a href="<?= URL::site('admin_bbs/focusForm?id='.$u['id']) ?>">修改</a></td>
	<td class="center"><a href="javascript:del(<?=$u['id']?>)" title="删除幻灯片">删除</a></td>

    </tr>
    <?php endforeach; ?>
    </table>
    
<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" style="margin-top:-1px " >
     <tr>
	<td style="height: 50px"><?= $pager ?></td>
    </tr>
</table>
    <?php endif; ?>


<script type="text/javascript">

function del(cid){
    var b = new Facebox({
        title: '删除确认！',
        message: '确定要删除此幻灯片吗？删除幻灯片并不会删除该话题。',
        icon:'question',
        ok: function(){
            new Request({
                url: '<?= URL::site('admin_bbs/delFocus?cid=') ?>'+cid,
                type: 'post',
                success: function(){
                    candyDel('con_'+cid);
                }
            }).send();
            b.close();
        }
    });
    b.show();
}
</script>
