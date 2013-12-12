<!-- admin_aa/index:_body -->
<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" >
<tr >
<td height="29" class="td_title" ><b>按期数查看文章</b></td></tr>
<tr>
<td width="63%" style="border-bottom:0; text-align: right">
    <div style="margin:2px; text-align:left">
	<select name="pub_id" style="padding:1px 2px" onchange="location.href='?pub_id='+this.value">
	   <option>最近期刊</option>
            <?php foreach($publication as $p):?>
	    <option value="<?=$p['id']?>" <?=$pub_id==$p['id']?'selected':'' ?>><?=$p['name']?><?=$p['issue']?></option>
	    <?php endforeach;?>
	</select>
    </div>
</td>

</tr>
</table>

<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" >
<tr>
<td height="29" class="td_title" colspan="6"><b>文章管理</b></td>
</tr>
        <?php if(!$content): ?>
        <tr>
            <td>暂时还没有内容。</td>
        </tr>
        <?php else: ?>
        <tr>
            <td style="text-align:center">ID</td>
	    <td>所属期刊</td>
	    <td>标题</td>
            
	    <td class="center">所属栏目</td>
            <td class="center">页码</td>
	    <td class="center">删除</td>
        </tr>
        <?php foreach($content as $key=> $c): ?>
        <tr id="con_<?= $c['id'] ?>" class="<?php if(($key)%2==0){echo'even_tr';} ?>">
            <td width="6%" style="text-align:center"><?= $c['id'] ?></td>
	    <td width="20%"><?= $c['pname'] ?><?=$c['issue']?></td>
            <td  width="35%" ><a href="<?= URL::site('admin_publication/articleForm?id='.$c['id']) ?>" title="点击修改"><?= $c['title'] ?></a></td>
	    
	    <td width="10%" class="center"><?=$c['colname']?$c['colname']:'--'?></td>
            <td width="10%" class="center"><?=$c['page']?></td>
	    <td width="5%" class="center"><a  href="javascript:del(<?=$c['id']?>)">删除</a></td>
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