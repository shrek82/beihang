<!-- card_admin/index:_body -->
<style type="text/css">
    #content_tb th{ border-top-width: 0}
    #content_tb td{ height: 24px; padding: 0px 5px}
</style>

    <!--链接类型 -->

<?php if(!$content):?>
    <div class="nodata">暂时还没有内容。</div>
<?php else:?>

    <!--链接列表 -->
<table id="content_tb" width="98%">
    <tr>
        <th width="40%">标题</th>
	<th width="20%" class="center">修改日期</th>
        <th width="10%" class="center">修改</th>
        <th width="10%" class="center">删除</th>
    </tr>

<?php foreach($content as $c) : ?>
<tr  id="con_<?=$c['id']?>">
    <td style="color:#999"><a href="<?=URL::site('card_admin/form?id='.$c['id'])?>" title="点击修改"><?=$c['title']?></a></td>
    <td class="center"><?=$c['update_at']?></td>
    <td class="center"><a href="<?=URL::site('card_admin/form?id='.$c['id'])?>">修改</a></td>
    <td class="center"><a href="javascript:del(<?=$c['id']?>)">删除</a></td>


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
            url: '/card_admin/del?cid='+cid,
            success: function(){
                candyDel('con_'+cid);
            }
        }).send();
    }
</script>