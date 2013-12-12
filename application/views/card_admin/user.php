<!-- card_admin/index:_body -->
<style type="text/css">
    #content_tb th{ border-top-width: 0}
    #content_tb td{ height: 24px; padding: 0px 5px}
</style>

    <!--链接类型 -->

<?php if(!$user):?>
    <div class="nodata">暂时还没有内容。</div>
<?php else:?>

    <!--链接列表 -->
<table id="content_tb" width="98%">
    <tr>
        <th width="5%">ID</th>
	<th width="20%">姓名</th>
	<th width="20%" class="center">用户手机</th>
	<th width="20%" class="center">类型</th>
	<th width="20%" class="center">申请日期</th>
        <th width="20%" class="center">删除</th>
    </tr>

<?php foreach($user as $u) : ?>
<tr  id="con_<?=$u['id']?>">
    <td style="color:#999"><?=$u['id']?></td>
    <td style="color:#999"><?=$u['realname']?></td>
     <td class="center"><?=$u['mobile']?></td>
    <td class="center"><?=$u['card_type']?></td>
    <td class="center"><?=$u['apply_at']?></td>
    
    <td class="center"><a href="javascript:del(<?=$u['id']?>)">删除</a></td>


</tr>
<?php endforeach;?>
</table>

    <?php endif;?>

    <!--分页 -->
    <div style="margin:20px 0">
	<?=$pager;?>
    </div>
<script type="text/javascript">
function del(cid){
        new Request({
            url: '/card_admin/applydel?cid='+cid,
            success: function(){
                candyDel('con_'+cid);
            }
        }).send();
    }
</script>