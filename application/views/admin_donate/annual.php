<!-- admin_donate/statistics:_body -->
<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" >
<tr>
<td height="29" class="td_title" colspan="4">
    <div class="title_name">
    <b>年度统计(捐赠鸣谢)</b></div>

<div class="title_search">
	<form name="search" action="" method="get">
	    <input name="q" type="text" style="width:200px" class="keyinput">
	    <input type="submit" value="搜索">
	</form>
    </div>
</td>
</tr>
<tr>
    <td colspan="4">
快速检索：<a href="<?=URL::site('admin_donate/annual')?>" style="<?=empty($_GET)?'font-weight:bold':''?>">所有</a> &nbsp;|&nbsp;
<a href="<?=URL::site('admin_donate/annual?pay=1');?>"  style="<?=$pay?'font-weight:bold':''?>">已支付</a>&nbsp;|&nbsp;
<a href="<?=URL::site('admin_donate/annual?pay=0');?>"  style="<?=$pay=='0'?'font-weight:bold':''?>">待支付</a>&nbsp;&nbsp;
    </td>
</tr>
    </table>

<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" >
    <tr>
	<td width="10%" class="center">捐赠时间</td>
	<td width="15%" >捐赠项目或名称</td>
	<td width="10%">捐赠个人或集体</td>
	<td width="12%">专业级毕业年份</td>
	<td width="20%">捐赠金额/物品名称</td>
	<td width="5%" style="text-align:center">已支付</td>
        <td width="7%" class="center">删除</td>
    </tr>

    <?php if(count($annual) == 0): ?>
    <tr>
        <td colspan="7">没有任何信息。</td>
    </tr>
    <?php else: ?>

    <?php foreach($annual as $key=>$s): ?>
    <tr id="info_<?=$s['id']?>" class="<?php if(($key)%2==0){echo'even_tr';} ?>">
	 <td class="timestamp" style="text-align: center"><?= date('Y-n-d',strtotime($s['donate_at'])) ?></td>
	<td>&nbsp;&nbsp;<a href="<?= URL::site('admin_donate/annualForm?id='.$s['id']) ?>" title="点击修改"><?= Text::limit_chars($s['project'], 12, '..') ?></a></td>
	<td><?= Text::limit_chars($s['donor'], 12, '..') ?></td>
	<td><?= $s['speciality'] ?></td>
	<td><?= $s['amount'] ?></td>
	<td style="text-align:center"><input type="checkbox" value="true" onclick="payment(<?= $s['id'] ?>)" <?= $s['payment_status'] ? 'checked':'' ?> title="点击更改支付状态"/></td>
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
        new Request({
            url: '/admin_donate/delStatistics?cid='+cid,
            success: function(){
                candyDel('info_'+cid);
            }
        }).send();
    }
    
//修改支付状态
 function payment(id){
        new Request({
            url: '/admin_donate/annualPayment?id='+id
        }).send();
}

function del(cid){
    var b = new Facebox({
        title: '删除确认！',
        message: '确定要删除此条记录吗？注意删除后将不能再恢复。',
        icon:'question',
        ok: function(){
            new Request({
                url: '<?= URL::site('admin_donate/delAnnual?cid=') ?>'+cid,
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


<?php if($import_total>0):?>
<script type="text/javascript">
    var b = new Facebox({
        title: '导入提示',
        message: '恭喜您，已从Excel导入<?=$import_total?>条年度统计记录，欢迎下次使用!'
    });
    b.show();
</script>
<?php endif;?>