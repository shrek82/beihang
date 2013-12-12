

<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" style="margin-top:0px ">
    <tr>
	<td colspan="2" class="td_title">详细介绍</td>
    </tr>
</table>

<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" style="margin-top:0px ">
    <tr>
        <td width="70%">标题</td>
	<td style="text-align:center;width:20%">更新日期</td>
        <td style="text-align:center">删除</td>
    </tr>

    <?php if(count($info) == 0): ?>
    <tr>
        <td colspan="3" style="background-color:#fff;padding:10px; text-align: left; color: #999">暂无介绍。</td>
    </tr>
    <?php endif; ?>
    
<?php foreach($info as $key=> $i) : ?>
<tr  id="info_<?=$i['id']?>" class="<?php if(($key)%2==0){echo'even_tr';} ?>">
    <td style="color:#999;"><a href="<?=URL::site('admin_mainaa/form?id='.$i['id'])?>" title="点击修改"><?=$i['title']?></a></td>
   <td style="text-align:center"><?=$i['update_at']?></td>
    <td style="text-align:center"><a href="javascript:del(<?=$i['id']?>)">删除</a> </td>

</tr>
<?php endforeach;?>
</table>



<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" style="margin-top:-1px " >
     <tr>
	<td style="height: 50px"><?= $pager ?></td>
    </tr>
</table>

<script type="text/javascript">
function del(cid){
    var b = new Facebox({
        title: '删除确认！',
        message: '确定要删除此内容吗？注意删除后将不能再恢复。',
        icon:'question',
        ok: function(){
            new Request({
                url: '<?= URL::site('admin_mainaa/del?cid=') ?>'+cid,
                type: 'post',
                success: function(){
                    candyDel('info_'+cid);
                }
            }).send();
            b.close();
        }
    });
    b.show();
}
</script>