<!-- admin_donate/statistics:_body -->
<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" >
<tr>
<td height="29" class="td_title" colspan="4"><b>捐赠统计</b><div style="float:right; font-weight: normal;margin:0px 10px">
	<form name="search" action="" method="get">
	    <input name="q" type="text" style="width:200px" class="keyinput">
	    <input type="submit" value="搜索">
	</form>
    </div></td>
</tr>
	  

    <tr>
	<td width="50%" >&nbsp;&nbsp;捐赠项目及金额</td>
	<td width="20%">捐赠个人或集体</td>
        <td width="17%" class="center">捐赠时间</td>
        <td width="7%" class="center">删除</td>
    </tr>

    <?php if(count($statistics) == 0): ?>
    <tr>
        <td colspan="6">没有任何信息。</td>
    </tr>
    <?php else: ?>

    <?php foreach($statistics as $key=> $s): ?>
    <tr  id="info_<?=$s['id']?>" class="<?php if(($key)%2==0){echo'even_tr';} ?>">
	<td>&nbsp;&nbsp;<a href="<?= URL::site('admin_donate/statisticsForm?id='.$s['id']) ?>" title="点击修改"><?=$s['name']?></a></td>
	<td><a href="<?=URL::site('admin_donate/statistics?q='.$s['donor'])?>" title="浏览<?=$s['donor']?>其他捐赠"><?= $s['donor'] ?></a></td>
        <td class="timestamp" style="text-align: center"><?= date('Y-n-d',strtotime($s['donate_at'])) ?></td>
        <td class="handler" style="text-align: center">
            <a href="javascript:del(<?=$s['id']?>)">删除</a>
        </td>
    </tr>
    <?php endforeach; ?>
    <?php endif; ?>
</table>
<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" style="margin-top:-1px " >
     <tr>
	<td style="height: 50px; text-align: center">
	<?= $pager ?></td>
    </tr>
</table>
<script type="text/javascript">
function del(cid){
    var b = new Facebox({
        title: '删除确认！',
        message: '确定要删除此条记录吗？注意删除后将不能再恢复。',
        icon:'question',
        ok: function(){
            new Request({
                url: '<?= URL::site('admin_donate/delStatistics?cid=') ?>'+cid,
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