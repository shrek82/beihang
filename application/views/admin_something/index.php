<!-- admin_bbs/comment:_body -->
<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" >
    <tr >
        <td height="29" class="td_title" >
            <div class="title_name">
                <b>按内容分类查看</b></div>
            <div class="title_search">
                <form name="search" action="" method="get">
                    <select name="search_type" style="padding:1px 4px;">
                        <option value="title" <?php
                        if ($search_type == 'title') {
                            echo 'selected';
                        }
                        ?>>按内容</option>
                        <option value="author" <?php
                        if ($search_type == 'author') {
                            echo 'selected';
                        }
                        ?>>按作者</option>
                    </select>
                    <input name="q" type="text" style="width:200px" class="keyinput" value="<?= $q ?>">
                    <input type="submit" value="搜索">
                </form>
            </div>
        </td>
    </tr>
    <tr>
        <td height="25" style="padding:0px 10px" >
            <a href="<?= URL::site('admin_something/') ?>" style="<?= empty($_GET) ? 'font-weight:bold' : '' ?>">所有</a> &nbsp;|&nbsp;
            <a href="<?= URL::site('admin_something?object=original') ?>" style="<?= $object == 'news' ? 'font-weight:bold' : '' ?>">原创</a> &nbsp;|&nbsp;
            <a href="<?= URL::site('admin_something?object=event') ?>" style="<?= $object == 'bbs_unit' ? 'font-weight:bold' : '' ?>">活动相关</a> &nbsp;|&nbsp;
        </td>
    </tr>
</table>


<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" >
    <tr >
        <td height="29" class="td_title" colspan="4"><b>新鲜事</b>
    <tr>
        <td width="50" class="center">时间</td>
        <td class="center">作者</td>
        <td>评论内容</td>
        <td class="center">删除</td>
    </tr>
    <?php if (count($somethings) == 0): ?>
        <tr>
            <td colspan="4">暂时还没有任何新鲜事</td>
        </tr>
    <?php else: ?>

                <?php foreach ($somethings as $key => $c): ?>
            <tr class="<?php
                    if (($key) % 2 == 0) {
                        echo'even_tr';
                    }
                    ?>" id="something_<?= $c['id'] ?>">
                <td  class="center" style="width:70px">
            <?= Date::span_str(strtotime($c['post_at'])) ?>前
                </td>
                <td class="center"><a href="<?= URL::site('user_home?id=' . $c['user_id']) ?>"><?= $c['realname'] ?></a></td>
                <td style="width:75%"><?= Common_Global::weibohtml($c['content'], $c['aa_id']) ?> </td>
                <td class="center"><a href="javascript:del(<?= $c['id'] ?>)">删除</a></td>
            </tr>
    <?php endforeach; ?>
    </table>

    <table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" style="margin-top:-1px " >
        <tr>
            <td style="height: 50px"><?= $pager ?></td>
        </tr>
    </table>
<?php endif; ?>

<script type="text/javascript">
    function del(cid) {
        var b = new Facebox({
            title: '删除确认！',
            icon: 'question',
            message: '确定要删除此条新鲜事吗？注意删除后将不能再恢复。',
            ok: function() {
                new Request({
                    url: '<?= URL::site('admin_something/del?cid=') ?>' + cid,
                    type: 'post',
                    success: function() {
                        candyDel('something_' + cid);
                    }
                }).send();
                b.close();
            }
        });
        b.show();
    }
</script>