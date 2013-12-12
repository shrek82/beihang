<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" >
<tr >
<td height="29" class="td_title" >
    <div class="title_name">
    <b>按分类查看：</b></div>
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
快速检索：<a href="<?=URL::site('admin_content/index')?>" style="<?=empty($_GET)?'font-weight:bold':''?>">所有</a> &nbsp;&nbsp;
<?php foreach($content_type as $t):?>
    <a href="<?=URL::site('admin_content/index?type='.$t['id'])?>" <?=$t['id']==$type?'style="font-weight:bold;"':'';?>><?=$t['name']?></a>&nbsp;&nbsp;
<?php endforeach;?>
</td>
</tr>
</table>


    <!--链接列表 -->
<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" >
    <tr >
<td height="29" class="td_title" colspan="6"><b>内容管理</b>
    <div style="float:right; font-weight: normal;margin:0px 10px">
	    <input type="button"  value="新增内容" onclick="window.location.href='<?=URL::site('admin_content/form')?>'">

    </div>

</td>
</tr>
    <tr>
        <td width="55%">&nbsp;&nbsp;&nbsp;&nbsp;标题</td>
	 <td width="10%" class="center">分类</td>
	 <td width="5%" class="center">属性</td>
	<td width="15%" class="center">修改日期</td>
        <td width="6%" class="center">修改</td>
        <td width="6%" class="center">删除</td>
    </tr>
<?php if(!$content):?>
    <tr>
	<td colspan="6">
	    <div class="nodata">&nbsp;&nbsp;&nbsp;&nbsp;暂时还没有该类内容。</div></td>
    </tr>
<?php else:?>

<?php foreach($content as $key=>$c) : ?>
<tr  id="con_<?=$c['id']?>" class="<?php if(($key)%2==0){echo'even_tr';} ?>">
    <td >&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?=URL::site('admin_content/form?id='.$c['id'])?>" title="点击修改"><?=$c['title']?></a></td>
    <td class="center"><?=$c['cname']?></td>
    <td class="center"><?php if($c['redirect']):?><img src='/static/images/admin/url.gif' alt="站外链接"/><?php else:?>-<?php endif;?></td>
    <td class="center"><?=$c['update_at']?></td>
    <td class="center"><a href="<?=URL::site('admin_content/form?id='.$c['id'])?>">修改</a></td>
    <td class="center"><?php if($c['is_system']):?><span class="nodata">删除</span><?php else:?><a href="javascript:del(<?=$c['id']?>)">删除</a><?php endif;?></td>


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
        message: '确定要删除此内容吗？注意删除后将不能再恢复。',
        icon:'question',
        ok: function(){
            new Request({
                url: '<?= URL::site('admin_content/del?cid=') ?>'+cid,
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