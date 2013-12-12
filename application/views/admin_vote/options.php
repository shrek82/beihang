<!-- admin_vote/index:_body -->

<style type="text/css">
.pvotes{ width: 0px; background-color: #f60;height:12px; border: 1px solid #f30; float: left;margin: 2px}
p.no1{  background-color:#74A5BC;border-color:#558BA5}
p.no2{  background-color:#6F9B40;border-color:#436C17}
p.no3{  background-color:#f60;border-color:#f30}
p.no4{  background-color:#5F83C6;border-color:#2C55A5}
p.no5{  background-color:#f00;}
p.no6{  background-color:#E0E512;border-color:#A7AA1F}
</style>
<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" >
    <tr >
<td height="29" class="td_title" colspan="8"><b>调查名称：<?=$vote['title']?></b>
    <div style="float:right; font-weight: normal;margin:0px 10px">
	    <input type="button"  value="新增选项" onclick="window.location.href='<?=URL::site('admin_vote/options?vote_id='.$vote['id'])?>'">

    </div>

</td>
</tr>
    <tr>
        <td width="5%" class="center">ID</td>
	<td width="25%" class="center">选项名称</td>
	<td width="20%" class="center">票数</td>
        <td width="6%" class="center">修改</td>
        <td width="6%" class="center">删除</td>
    </tr>
<?php if(!$options):?>
    <tr>
	<td colspan="8">
	    <div class="nodata">&nbsp;&nbsp;&nbsp;&nbsp;暂时还没有该类投票。</div></td>
    </tr>
<?php else:?>

<?php foreach($options as $key=>$o) : ?>
<tr  id="con_<?=$o['id']?>" class="<?php if(($key)%2==0){echo'even_tr';} ?>">
    <td  class="center"><?=$key+1?></td>
    <td >&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?=URL::site('admin_vote/options?vote_id='.$o['vote_id'].'&id='.$o['id'])?>" title="点击修改"><?=$o['title']?></a></td>
    <td  style="background:#fff; color: #666"><p class="pvotes no<?=$key+1?>"></p><?=$vote['total_votes']?round(($o['votes']/$vote['total_votes'])*100,0):'-';?>%</td>
    <td class="center"><a href="<?=URL::site('admin_vote/options?vote_id='.$o['vote_id'].'&id='.$o['id'])?>" title="点击修改">修改</a></td>
     <td class="center"><a href="javascript:del(<?=$o['id']?>)">删除</a></td>


</tr>
<?php endforeach;?>
</table>

    <?php endif;?>

	    <form action="" method="post" >

		<input type="hidden" name="id" value="<?= $option['id'] ?>" />
		<input type="hidden" name="vote_id" value="<?=$option['vote_id']?$option['vote_id']:$vote['id'] ?>">
	        <table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" >
	    	<tr >
	    	    <td height="29" class="td_title" colspan="2"><b><?= $option ? '修改选项' : '新增选项'; ?>：</b></td>
	    	</tr>

	    	<tr>
	    	    <td class="field">选项名称：</td>
	    	    <td><input type="text" name="title" value="<?= $option['title'] ?>"  style="width:400px" class="input_text"  /></td>
	    	</tr>

	    	<tr>
	    	    <td class="field">分类排序：</td>
	    	    <td><input type="text" name="order_num" value="<?= $option['order_num'] ?$option['order_num']:count($options)+1 ?>"  style="width:400px" class="input_text"/>&nbsp;&nbsp;<span style="color:#999">(填写数字，值越小越靠前)</span></td>
	    	</tr>

	    	<tr>
	    	    <td class="field"></td>
	    	    <td style="padding:10px 0">

		<?php if ($option): ?>
                                       <input type="submit" value="确定修改" name="button"  class="button_blue"/>
		<?php else: ?>
					<input type="submit" value="确定添加" name="button"  class="button_blue"/>
		<?php endif; ?>

					<input type="button" value="取消" onclick="window.history.back()" class="button_gray">


				    </td>
				</tr>


				</tbody>
			    </table><br>



    <?php if ($err): ?>
					    <div class="notice"><?= $err; ?></div>
    <?php endif; ?>
</form>


<script language="javascript">
var voteList = new Array();
<?php foreach($options as $key=>$o):?>
voteList[<?=$key?>] = <?=$vote['total_votes']?round(($o['votes']/$vote['total_votes'])*100,0):'-';?>;
<?php endforeach;?>
$(document).ready(function(){
  //$("#voteBox dd:first").animate({width:"80%"},800);//伸展动画
  for(var i=0;i<<?=count($options)?>;i++){
	 $(".pvotes").eq(i).animate({width:voteList[i]+"%"},800);
  }
});
</script>

<script type="text/javascript">
function del(cid){
    var b = new Facebox({
        title: '删除确认！',
        message: '确定要删除此选项吗？注意删除后将不能再恢复。',
        icon:'question',
        ok: function(){
            new Request({
                url: '<?= URL::site('admin_vote/delOption?cid=') ?>'+cid,
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