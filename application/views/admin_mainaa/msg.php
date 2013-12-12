<!-- admin_zaa/msg:_body -->
<div style="margin: 0 20px">
    <div style="text-align:right;padding:0px 20px">
	<input type="button" onclick="location.href='<?= URL::site('admin_zaa/msgForm') ?>'" value="发布新公告"  class="button_blue"/>
    </div>
    <table class="admin_table" id="msg_list" width="100%" style="margin-top: 10px">
        <tr>
            <th width="60%">标题</th>
            <th width="20%">有效时间</th>
            <th width="20%" style="text-align:center">操作</th>
        </tr>
        <?php foreach($msgs as $m): ?>
        <tr class="msg_item" id="m_<?= $m['id'] ?>">
            <td class="msg_title">
                <a href="#" class="msg_tt"><?= $m['title'] ?></a>
                <div class="msg_ct"><?= $m['content'] ?></div>
            </td>
            <td  width="20%" class="timestamp">
                <?= $m['start_at'] ?> ~
                <?= $m['expire_at'] ?>
            </td>
            <td width="10%" class="center">
                <a href="<?= URL::site('admin_zaa/msgForm?id='.$m['id']) ?>">修改</a>
                <a href="javascript:del(<?= $m['id'] ?>)">删除</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<script type="text/javascript">
    $$('.msg_tt').each(function(el){
        var box = el.getNext('.msg_ct');
        box.slide('hide');
        el.addEvent('click', function(){
            box.slide();
        });
    });
    function del(id){
        new Request({
            url: '<?= URL::site('admin/msgForm?del=y') ?>&id='+id,
            success: function(){
                candyDel('m_'+id);
            }
        }).send();
    }
</script>