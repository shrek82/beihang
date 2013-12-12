<!-- admin_vote/index:_body -->
<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" >
<tr >
<td height="29" class="td_title" >
    <div class="title_name">
    <b>按分类查看：</b>
    </div>

<div class="title_search">
	<form name="search" action="" method="get">
	    <input name="q" type="text" style="width:200px" class="keyinput" value="<?=$q?>">
	    <input type="submit" value="搜索">
	</form>
    </div>
</td>
</tr>
<tr>
<td height="25" style="padding:0px 10px" >
快速检索：<a href="<?=URL::site('admin_vote/index')?>" style="<?=empty($_GET)?'font-weight:bold':''?>">全部</a> &nbsp;&nbsp;<a href="<?=URL::site('admin_vote/index')?>" style="<?=empty($_GET)?'font-weight:bold':''?>">单选</a> &nbsp;&nbsp;
<a href="<?=URL::site('admin_vote/index')?>" style="<?=empty($_GET)?'font-weight:bold':''?>">多选</a> &nbsp;&nbsp;
</td>
</tr>
</table>


    <!--链接列表 -->
<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" >
    <tr >
<td height="29" class="td_title" colspan="8"><b>投票管理</b>
    <div style="float:right; font-weight: normal;margin:0px 10px">
	    <input type="button"  value="新增投票" onclick="window.location.href='<?=URL::site('admin_vote/form')?>'">

    </div>

</td>
</tr>
    <tr>
        <td width="30%">&nbsp;&nbsp;&nbsp;&nbsp;投票名称</td>
	<td width="10%" class="center">选项</td>
	 <td width="10%" class="center">发布人</td>
	 <td width="10%" class="center">参与人数</td>
	 <td width="10%" class="center">发布日期</td>
	<td width="10%" class="center">截止日期</td>

        <td width="6%" class="center">预览</td>
        <td width="6%" class="center">删除</td>
    </tr>
<?php if(!$vote):?>
    <tr>
	<td colspan="8">
	    <div class="nodata">&nbsp;&nbsp;&nbsp;&nbsp;暂时还没有该类投票。</div></td>
    </tr>
<?php else:?>

<?php foreach($vote as $key=>$v) : ?>
<tr  id="con_<?=$v['id']?>" class="<?php if(($key)%2==0){echo'even_tr';} ?>">
    <td >&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?=URL::site('admin_vote/form?id='.$v['id'])?>" title="点击修改"><?=$v['title']?></a></td>
    <td class="center"><a href="<?=URL::site('admin_vote/options?vote_id='.$v['id'])?>" >选项</a></td>
    <td class="center"><?=$v['User']['realname']?></td>
    <td class="center"><?=$v['user_number']?></td>
    <td class="center"><?= date('Y-m-d',strtotime($v['create_at'])) ?></td>
    <td class="center"><?=$v['finish_date']?></td>

    <td class="center"><a href="<?=URL::site('vote/view?id='.$v['id'])?>" target="_blank">预览</a></td>
     <td class="center"><a href="javascript:del(<?=$v['id']?>)">删除</a></td>


</tr>
<?php endforeach;?>
</table>

    <?php endif;?>

    <!--分页 -->
<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" style="margin-top:-1px " >
     <tr>
	<td style="height: 50px"><?= $pager ?></td>
    </tr>
</table>

<script type="text/javascript">

function del(cid){
    var b = new Facebox({
        title: '删除确认！',
        message: '确定要删除此投票吗？注意删除后将不能再恢复。',
        icon:'question',
        ok: function(){
            new Request({
                url: '<?= URL::site('admin_vote/del?cid=') ?>'+cid,
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