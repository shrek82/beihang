<!-- admin_club/index:_body -->
<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" style="margin-top:9px ">
    <tr >
        <td height="29" class="td_title" >
            <div class="title_name">
                <b>按地区查看：</b>
            </div>
            <div class="title_search">
                <form name="search" action="<?= URL::site('admin_club') ?>" method="get" style="margin:0">
                    <input name="q" type="text" style="width:200px" class="keyinput">
                    <input type="submit" value="搜索">
                </form>
            </div>
        </td>
    </tr>
</table>


<form method="post" action="<?= URL::site('admin_club') ?>">
    <table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" style="margin-top:0px ">

        <?php if (count($club) == 0): ?>
            <tr>
                <td>没有任何校友会信息。</td>
            </tr>
        <?php else: ?>
            <tr>
                <td class="center">ID</td>
                <td >名称及成员</td>
                <td class="center">所属校友会</td>
                <td class="center">分类</td>
                <td class="center">待处理加入申请</td>
                <td class="center">预览</td>
                <td class="center">管理</td>
            </tr>
            <?php foreach ($club as $key => $c): ?>
                <tr id="club_<?= $c['id'] ?>" class="<?php if (($key) % 2 == 0) {
            echo'even_tr';
        } ?>">
                    <td width="5%" class="center"><?= $c['id'] ?></td>
                    <td  width="20%" ><a href="<?= URL::site('club_admin_base/index?id=' . $c['id']) ?>" id="save_<?= $c['id'] ?>" title="点击修改俱乐部介绍" target="_blank"><?= $c['name'] ?></a>&nbsp;<span style="color:#999">(<?= $c['total_member'] ?>人</span>)</td>
                    <td width="15%" class="center"><?= $c['aa_name'] ?></td>
                    <td width="15%" class="center"> <?= $c['type'] ?> </td>
                    <td width="10%" class="center"><a href="<?= URL::site('club_admin?id=' . $c['id']) ?>" id="save_<?= $c['id'] ?>" title="点击管理俱乐部" target="_blank"><?= $c['total_join'] ? $c['total_join'] : '&nbsp;' ?></a></td>
                    <td width="8%" class="center"><a href="/club_home?id=<?= $c['id'] ?>" target="_blank">预览</a></td>
                    <td width="8%" class="center"><a href="javascript:del(<?= $c['id'] ?>)">删除</a></td>
                </tr>
    <?php endforeach; ?>
<?php endif; ?>


    </table>

    <table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" style="margin-top:-1px " >
        <tr>
            <td style="height: 50px; text-align: center">
                <input type="submit" value="保存修改" class="button_blue">
                <div style="margin:20px 0"><?= $pager ?></div>
            </td>
        </tr>
    </table>
</form>


<script type="text/javascript">
    function del(cid) {
        var b = new Facebox({
            title: '删除确认！',
            message: '确定要删除该俱乐部吗？注意删除后将不能再恢复。',
            icon: 'question',
            ok: function() {
                new Request({
                    url: '<?= URL::site('admin_club/del?cid=') ?>' + cid,
                    type: 'get',
                    success: function() {
                        candyDel('club_' + cid);
                    }
                }).send();
                b.close();
            }
        });
        b.show();
    }
</script>