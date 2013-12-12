<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" >
<tr >
<td height="29" class="td_title" ><b>链接分类：</b></td>
</tr>
<tr>
<td height="25" style="padding:0px 10px" >
<?php foreach($type_links as $t=>$n):?>
    <a href="<?=URL::site('admin_links/index?type='.$t)?>" <?=$t==$type?'style="font-weight:bold;"':'';?>><?=$n?></a>&nbsp;&nbsp;
<?php endforeach;?>
    <a href="<?=URL::site('admin_links/index?is_logo=1')?>" <?=$is_logo==1?'style="font-weight:bold;"':'';?>>图片链接</a>
<?php if(!$type_links):?>
    <div class="nodata">暂时分类。</div>
<?php endif;?>
</td>
</tr>
</table>

<!--链接列表 -->
<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" >
<tr>
<td height="29" class="td_title" colspan="4" ><b>链接管理：</b></td>
</tr>
    <tr>
        <td width="40%">名称</td>
        <td>网址</td>
        <td>修改</td>
        <td>删除</td>
    </tr>

<?php foreach($links as $key=>$url) : ?>
<tr  id="link_<?=$url['id']?>" class="<?php if(($key)%2==0){echo'even_tr';} ?>">
    <td style="color:#999;"><?=$url['is_logo']?'[图片]':'[文字]';?>&nbsp;<a href="<?=$url['url']?>" title="点击浏览该网站" target="_blank"><?=$url['name']?></a></td>
    <td><?=$url['url']?></td>
    <td><a href="<?=URL::site('admin_links/add?id='.$url['id'])?>">修改</a></td>
    <td><a href="javascript:del(<?=$url['id']?>)">删除</a> </td>

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
            url: '/admin_links/del?cid='+cid,
            success: function(){
                candyDel('link_'+cid);
            }
        }).send();
    }
</script>