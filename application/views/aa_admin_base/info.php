<!-- aa_admin_base/info:_body -->
<div id="admin950">
    <div style="text-align: right; margin-bottom: 10px"><input class="button_blue" value="+添加新介绍" type="button"  onclick="window.location.href='<?= URL::site('aa_admin_base/form?id=' . $_ID) ?>'"></div>
   <?php if(count($infos)==0):?>
    <div class="nodata" style="height:400px">暂时介绍，快来添加一条吧！</div>
    <?php else:?>
    <table id="bench_list" width="100%"  border="0"  cellpadding="0" cellspacing="0" style="margin-bottom: 60px">
        <tr>
            <th style="text-align:left;width:600px">标题</th>
            <th class="center" style="width:70px">预览</th>
            <th class="center">可见</th>
            <th class="center">删除</th>
        </tr>
        <?php foreach ($infos as $key => $inf): ?>
            <tr class="<?= (($key + 1) % 2) == 0 ? 'even_tr' : 'odd_tr'; ?>" id="info_<?= $inf['id'] ?>">
                <td style="padding:5px"><a href="<?= URL::site('aa_admin_base/form?id=' . $_ID . '&cid=' . $inf['id']) ?>" title="点击进入编辑页面"><?= $inf['title'] ?></a></td>
                <td class="center"><a href="<?= URL::query(array('infoid' => $inf['id'])) ?>">预览</a></td>
                <td class="center"><input type="checkbox" value="true" onclick="setBool(<?= $inf['id'] ?>,'is_public')" <?= $inf['is_public'] == true ? 'checked' : '' ?> /></td>
                <td class="center"><a href="javascript:del(<?= $inf['id'] ?>)" title="删除此条内容" style="color:#f00">×</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <?php endif;?>

    <script type="text/javascript">
        function del(cid){
            new candyConfirm({
                message: '确定要删除此条内容吗？注意删除后将不能再恢复。',
                url: '<?= URL::site('aa_admin_base/del?id=' . $_ID . '&cid=') ?>'+cid,
                callback:function(){
                    window.location.href='<?= URL::site('aa_admin_base/info?id=' . $_ID) ?>';
                }
            }).open();
        }
        
        function setBool(cid,field){
            new Request({
                type:'post',
                url: '<?= URL::site('aa_admin_base/set?id=' . $_ID) ?>',
                data: 'cid='+cid+'&bool_field='+field
            }).send();
        }

    </script>
</div>


