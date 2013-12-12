<!-- admin_classroom/index:_body -->

<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1"  >
    <tr >
        <td height="29" class="td_title" colspan="5"><b>班级话题：</b>
            <div style="float:right; font-weight: normal;margin:0px 10px">
                <form name="search" action="" method="get">
                    搜索：
                    <input name="q" type="text" style="width:200px" class="keyinput">
                    <input type="submit" value="搜索">
                </form>
            </div>
        </td>
    </tr>

    <tr>
        <td width="40%">&nbsp;&nbsp;&nbsp;&nbsp;标题</td>
        <td width="10%" class="center">发布</td>
        <td width="10%" class="center">回复/浏览</td>
        <td width="10%" class="center">创建日期</td>
    </tr>

    <?php if (count($unit) == 0): ?>
        <tr>
            <td colspan="6">没有任何新闻信息。</td>
        </tr>
    <?php else: ?>

        <?php foreach ($unit as $key => $u): ?>
            <tr  id="classroom_<?= $u['id'] ?>" class="<?php if (($key) % 2 == 0) {
            echo'even_tr';
        } ?>">
                <td >&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?= URL::site('classroom_home/bbsUnit?id=' . $u['ClassBbs']['classroom_id'] . '&unit_id=' . $u['id']) ?>" target="_blank"><?= $u['title'] ?></a></td>
                <td class="center"><?= $u['User']['realname'] ?></td>
                <td class="center"><span style="color:#009900"><?= $u['reply_num'] ?></span>&nbsp;/&nbsp;<?= $u['hit'] ?></td>
                <td style="text-align: center"><?= date('Y-n-d', strtotime($u['create_at'])); ?></td>
            </tr>
    <?php endforeach; ?>
<?php endif; ?>
</table>
<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" style="margin-top:-1px " >
    <tr>
        <td style="height: 50px"><?= $pager ?></td>
    </tr>
</table>
<script type="text/javascript">
    function verify(cid){
        new Request({
            url: '<?= URL::site('admin_classroom/verify') . URL::query() ?>',
            type: 'post',
            data: 'cid='+cid
        }).send();
    }

    function del(cid){
        var b = new Facebox({
            title: '删除确认！',
            icon:'question',
            message: '确定要删除此班级吗？<Br>注意删除本班级将同时删除本班级的所有相关信息！',
            ok: function(){
                new Request({
                    url: '<?= URL::site('admin_classroom/del?cid=') ?>'+cid,
                    type: 'post',
                    success: function(){
                        candyDel('classroom_'+cid);
                    }
                }).send();
                b.close();
            }
        });
        b.show();
    }
</script>