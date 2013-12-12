<!-- admin_aa/index:_body -->

<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" >
<tr>
<td height="29" class="td_title" colspan="5"><b>投稿管理</b></td>
</tr>
        <?php if(!$content): ?>
        <tr>
            <td>暂时还没有投稿。</td>
        </tr>
        <?php else: ?>
        <tr>
            <td width="5%" style="text-align:center">ID</td>
	    <td width="40%">标题</td>
	    <td style="text-align:center;width:10%">作者</td>
	    <td class="center" width="15%">投稿时间</td>
            <td class="center" width="10%">是否审阅</td>

        </tr>
        <?php foreach($content as $key=> $c): ?>
        <tr id="con_<?= $c['id'] ?>" class="<?php if(($key)%2==0){echo'even_tr';} ?>">
            <td  style="text-align:center"><?= $c['id'] ?></td>
	    <td ><a href="<?= URL::site('admin_publication/contributeForm?id='.$c['id']) ?>" title="点击查看"><?= $c['title'] ?></a></td>
            <td  style="text-align:center;" ><a href="/user_home?id=<?=$c['user_id']?>" target="_blank"><?=$c['realname']?></a></td>

	    <td class="center"><?=$c['create_at']?></td>
            <td class="center"><?php if ($c['is_read'] == TRUE): ?><span style="color:green">已查阅</span><?php endif; ?>
		    <?php if ($c['is_read'] == FALSE): ?><span style="color:#ff6600">未查阅</span><?php endif; ?></td>
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
function del(cid){
        new Request({
            url: '/admin_publication/delArticle?cid='+cid,
            success: function(){
                candyDel('con_'+cid);
            }
        }).send();
    }

</script>