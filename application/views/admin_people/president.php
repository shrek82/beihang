<!-- admin_people/president:_body -->
<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" >
<tr >
<td height="29" class="td_title" ><b>按时期查看：</b></td>
</tr>
<tr>
<td height="25" style="padding:0px 10px" >
    <div style="margin:2px; text-align:left">
	<select name="pub_id" style="padding:1px 2px" onchange="location.href='?period='+this.value">
	   <option value="">所有时期</option>
            <?php foreach($president_period as $key=>$p):?>
	    <option value="<?=$key?>" <?=$key==$period?'selected':'' ?>><?=$p?></option>
	    <?php endforeach;?>
	</select>
    </div>

<?php if(!$president_period):?>
    <div class="nodata">暂时时期分类。</div>
<?php endif;?>
</td>
</tr>
</table>

<!--链接列表 -->
<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" >
<tr>
<td height="29" class="td_title" colspan="5" ><b>历任校长</b></td>
</tr>
    <tr>
        <td width="20%">&nbsp;&nbsp;&nbsp;&nbsp;所在学校</td>
        <td width="20%">姓名</td>
        <td width="30%">职务</td>
	 <td width="20%">任期</td>
        <td class="center">删除</td>
    </tr>

<?php foreach($president as $key=>$p) : ?>
<tr  id="p_<?=$p['id']?>" class="<?php if(($key)%2==0){echo'even_tr';} ?>">
    <td >&nbsp;&nbsp;&nbsp;&nbsp;<?=$p['school']?></td>
    <td ><a href="<?=URL::site('admin_people/presidentForm?id='.$p['id'])?>" title="点击修改"><?=$p['name']?></a></td>
    <td><?=$p['jobs']?></td>
    <td><?=$p['term']?></td>
    <td class="center"><a href="javascript:del(<?=$p['id']?>)">删除</a> </td>

</tr>
<?php endforeach;?>
</table>

    <!--分页 -->
<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" style="margin-top:-1px " >
     <tr>
	<td style="height: 50px"><?= $pager ?></td>
    </tr>
</table>
<script type="text/javascript">
function del(cid){
        new Request({
            url: '/admin_people/delPresident?cid='+cid,
            success: function(){
                candyDel('p_'+cid);
            }
        }).send();
    }
</script>