<!-- admin_aa/index:_body -->
<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" style="margin-top:9px ">
<tr >
<td height="29" class="td_title" >
    <div class="title_name">
    <b>按地区查看：</b>
    </div>
    <div class="title_search">
	<form name="search" action="<?=URL::site('admin_aa?class='.$class)?>" method="get" style="margin:0">
	    <input name="q" type="text" style="width:200px" class="keyinput">
	    <input type="submit" value="搜索">
	</form>
    </div>
</td>
</tr>
<tr>
<td height="25" style="padding:0px 10px" >
	地区：<a href="<?=URL::site('admin_aa/index')?>" style="<?=!$group?'font-weight:bold':''?>">所有</a> &nbsp;|&nbsp;
<?php foreach($aa_group as $g):?>
	<a href="<?=URL::site('admin_aa/index?group='.$g['id']);?>"  style="<?=$g['id']==$group?'font-weight:bold':''?>"><?=$g['name']?></a>&nbsp;|&nbsp;
	    <?php endforeach;?>
</td>
</tr>
</table>


    <form method="post" action="<?=URL::site('admin_aa/setOrder?group='.Arr::get($_GET,'group').'&page='.Arr::get($_GET,'page'))?>">
<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" style="margin-top:0px ">
<td height="29" class="td_title" colspan="9"><b><?=$class?></b></tr>
        <?php if(count($aa) == 0): ?>
        <tr>
            <td>没有任何校友会信息。</td>
        </tr>
        <?php else: ?>
        <tr>
            <td class="center">ID</td>
	    <td >名称及成员(点击编辑)</td>
            <td class="center">性质</td>
	    <td class="center">所在地区</td>
	   
            <td class="center">
                管理员
            </td>
            <td class="center">待处理加入申请</td>
            <td class="center">显示排序</td>
            <td class="center">访问地址</td>
            <td class="center">预览</td>
        </tr>
        <?php foreach($aa as $key=> $a): ?>
        <tr id="aa_<?= $a['id'] ?>" class="<?php if(($key)%2==0){echo'even_tr';} ?>">
            <td width="5%" class="center"><?= $a['id'] ?></td>
            <td  width="20%" ><a href="<?=URL::site('admin_aa/form?id='.$a['id'])?>" id="save_<?= $a['id'] ?>" title="编辑"><?= $a['name'] ?></a>&nbsp;<span style="color:#999">(<?=$a['total_member']?>人</span>)</td>
	    <td width="10%" class="center"><?= $a['class'] ?></td>
	    <td width="15%" class="center">

         <?php if($a['class']=='地方校友会'):?>
	 <?php foreach($aa_group as $g):?>
		    <?=$g['id']==$a['group']?$g['name']:'';?>
		<?php endforeach;?>
         <?php else:?>
         <span style="color:#999">-</span>
 <?php endif;?>
         <?php if(!$a['group']):?>&nbsp;<?php endif;?>

	    </td>
	    
	    <td width="10%" class="center"><?= isset($a['Members'][0]['User']['realname'])?$a['Members'][0]['User']['realname']:'<span style="color:#999">-</span>';?></td>
            <td width="10%" class="center"><a href="" title="点击查看加入申请"><?=$a['total_join']?$a['total_join']:'&nbsp;' ?></a></td>
<td width="10%" class="center"><input name="aa_id[]" type="hidden" value="<?=$a['id']?>"><input name="order_num<?= $a['id'] ?>" value="<?=$a['order_num']?>" style="padding:0px 4px;width:52px"></td>
<td width="15%" class="center"><input name="ename<?= $a['id'] ?>" value="<?=$a['ename']?>" style="padding:0px 4px;width:100px"></td>
            <td width="8%" class="center"><?php if($a['ename']):?><a href="/<?= $a['ename'] ?>" target="_blank">预览</a><?php else:?><a href="<?= URL::site('aa_home?id='.$a['id']) ?>" target="_blank">预览</a><?php endif;?>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>


</table>

<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" style="margin-top:-1px " >
     <tr>
	<td style="height: 50px; text-align: center">
	    <input type="submit" value="保存修改" class="button_blue">
	     <div style="margin:20px 0"><?=$pager?></div>
	</td>
    </tr>
</table>
</form>


<script type="text/javascript">
function aa_save(id){

    var saver = new Request({
        url: '<?= URL::site('admin_aa/setChairman') ?>?id='+id,
        data: $('aa_'+id),
        type: 'post',
        beforeSend: function(){
            $('save_'+id).set('html', '保存中..');
        },
        success: function(data){
            $('save_'+id).set('html', data);
        }
    });
    saver.send();
}

function setGroup(id, val){

    var saver = new Request({
        url: '<?= URL::site('admin_aa/setGroup') ?>?id='+id+'&group='+val,
        type: 'post',
        beforeSend: function(){
            $('save_'+id).set('html', '保存中..');
        },
        success: function(data){
            $('save_'+id).set('html', data);
        }
    });
    saver.send();
}

</script>