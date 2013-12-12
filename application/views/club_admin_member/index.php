<!-- club_admin_member/index:_body -->
<div id="admin950">
    <div style="color:#999" class="admin_search">
        <form method="post" action="/club_admin_member/index?id=<?= $_ID ?>" style="text-align: right; margin-top:-5px">
            <input type="text" name="q" class="input_text" placeholder="输入姓名查找"/>
            <input type="submit" value="搜索" class="button_blue"/>
        </form>
    </div>

    <?php if (count($members) == 0): ?>
        <p class="ico_info icon">没有成员信息。</p>
    <?php else: ?>

        <table id="bench_list" width="100%"  border="0"  cellpadding="0" cellspacing="0">
            <tr>
                <th style="text-align:left; width: 10%">姓名</th>
                <th style="text-align:left; width: 20%">姓名</th>
                <th style="text-align:left; width: 20%">头衔</th>
                <th style="width: 30%">最后访问时间</th>
                <th style=" text-align: center;width: 10%">管理员</th>
                <th style="width: 10%">操作</th>

            </tr>
            <?php foreach ($members as $key => $m): ?>
                <tr id="m_<?= $m['user_id'] ?>" class="<?= (($key + 1) % 2) == 0 ? 'even_tr' : 'odd_tr'; ?>">
                    <td style="padding:10px"><a href="javascript:userDetail(<?= $m['user_id'] ?>)" title="浏览详细信息"><?= View::factory('inc/user/avatar', array('id' => $m['user_id'], 'size' => 48, 'sex' => $m['User']['sex'])) ?></a></td>
                    <td>
                        <a style="font-weight:bold" href="<?= URL::site('user_home?id=' . $m['user_id']) ?>"><?= $m['User']['realname'] ?></a>
                        <br />
                        <a href="javascript:;" onclick="sendMsg(<?=$m['user_id']?>)"  title="发送站内信" >发送站内信</a>
                    </td>
                    <td>
                        <input type="text" onblur="mchange(<?= $m['user_id'] ?>)" size="10" name="title" value="<?= $m['title'] ?>"  class="input_text"  id="input_title_<?= $m['user_id'] ?>"/>
                    </td>
                    <td style="text-align: center;">
                        <?php if ($m['visit_at']): ?>
                            <?= Date::ueTime($m['visit_at']) ?>
                        <?php else: ?>
                            <?= Date::ueTime($m['join_at']) ?>
                        <?php endif; ?>
                    </td>
                    <td style="text-align: center;">
                        <input type="checkbox" onclick="setManager(<?= $m['user_id'] ?>)" <?= !empty($m['manager']) ? 'checked' : '' ?>   <?= $m['chairman'] ? 'disabled="disabled"' : ''; ?> />
                    </td>

                    <td style="text-align: center;">
                        <?php if (!$m['chairman']): ?>
                            <a href="javascript:remove(<?= $m['user_id'] ?>)">移出</a>
                        <?php else: ?>
                            <span class="quiet" style="color:#999">无权限</span>
                        <?php endif; ?>
                    </td>

                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    <?= $pager ?>
</div>

<script type="text/javascript">
    function mchange(cid){
        new Request({
            url: '<?= URL::site('club_admin_member/set?id=' . $_ID . '&cid=') ?>'+cid,
            data: 'title='+$('#input_title_'+cid).val(),
            type:'post'
        }).send();
    }

    function setManager(cid){
        new Request({
            url: '<?= URL::site('club_admin_member/setManager?id=' . $_ID . '&cid=') ?>'+cid,
            type:'post'
        }).send();
    }

    function remove(cid){
        new candyConfirm({
            message: '确定要将此成员请出俱乐部吗？',
            url: '<?= URL::site('club_admin_member/remove?id=' . $_ID . '&cid=') ?>'+cid,
            removeDom:'m_'+cid
        }).open();
    }
</script>