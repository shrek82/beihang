<!-- admin_publiaction/elereport:_body -->

<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" >
<tr>
<td height="29" class="td_title" colspan="6"><b>电子信息报</b>
</td>
</tr>
        <?php if(!$report): ?>
        <tr>
            <td>暂时还没有内容。</td>
        </tr>
        <?php else: ?>
        <tr>
            <td style="text-align:center">ID</td>
	    <td>名称期号</td>
	    <td>发布日期</td>
	    <td class="center">删除</td>
        </tr>
        <?php foreach($report as $key=>$r): ?>
        <tr id="con_<?= $r['id'] ?>" class="<?php if(($key)%2==0){echo'even_tr';} ?>">
            <td width="6%" style="text-align:center"><?= $r['id'] ?></td>
            <td  width="45%"><a href="<?= URL::site('admin_publication/reportForm?id='.$r['id']) ?>" title="点击修改"><?= $r['title'] ?><?=$r['issue']?></a></td>
	    <td width="20%"><?=$r['create_at']?></td>
	    <td width="5%" class="center"><a  href="javascript:del(<?=$r['id']?>)">删除</a></td>
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
            url: '/admin_publication/delEleReport?cid='+cid,
            success: function(){
                candyDel('con_'+cid);
            }
        }).send();
    }
    
</script>