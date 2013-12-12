<!-- aa_admin_member/index:_body -->
<div id="admin950">
    <form action="<?= URL::site('aa_admin_member/index?id='.$_ID) ?>" method="post" style="padding: 5px; text-align: right" id="aa_admin_member">
        <input type="text" name="q" value="<?=$q;?>" size="30"   class="input_text" placeholder="输入姓名查找"/>
        <input type="submit" value="查找" class="button_blue"  />
    </form>

    <table cellspan="0" border="0" >
        <tr>
            <td><img src="/static/images/page_excel.gif" /></td>
            <td><a href="<?= URL::site('aa_admin_member/export?id=' . $_ID) ?>" target="_blank">下载成员名单</a></td>
        </tr>
    </table>

    <?php if (count($members) == 0): ?>
        <p class="ico_info icon">没有成员信息。</p>
    <?php else: ?>

        <table id="bench_list" width="100%"  border="0"  cellpadding="0" cellspacing="0">
            <tr>
                <th style="text-align:left; width: 10%">头像</th>
                <th style="text-align:left; width: 20%">姓名</th>
                <th style="text-align:left; width: 20%">头衔</th>
                <th style="text-align:center">最近访问</th>
                <th style="text-align:left">管理员</th>
                <th style="text-align:left">操作</th>
            </tr>
            <?php foreach ($members as $key => $m): ?>
                <tr id="m_<?= $m['user_id'] ?>" class="<?= (($key + 1) % 2) == 0 ? 'even_tr' : 'odd_tr'; ?>">
                    <td style="padding:10px"><a href="javascript:userDetail(<?= $m['user_id'] ?>)" title="浏览详细信息"><?= View::factory('inc/user/avatar', array('id' => $m['user_id'], 'size' => 48, 'sex' => $m['User']['sex'])) ?></a></td>
                    <td>
                        <a style="font-weight:bold" href="<?= URL::site('user_home?id=' . $m['user_id']) ?>"><?= $m['User']['realname'] ?></a>
                        <br />
                        <a href="javascript:;" onclick="sendMsg(<?=$m['user_id']?>)"  title="发送站内信" ><img src="/static/images/user/email.gif" style="vertical-align: middle"></a>

                    </td>
                    <td>
                        <input type="text" onblur="mchange(<?= $m['user_id'] ?>)" size="10" name="title" class="input_text"  id="input_title_<?= $m['user_id'] ?>" value="<?= $m['title'] ?>"  />
                    </td>

                    <td style="text-align: center;">
                        <?php if ($m['visit_at']): ?>
                            <?= Date::ueTime($m['visit_at']) ?>
                        <?php else: ?>
                            <?= Date::ueTime($m['join_at']) ?>
                        <?php endif; ?>
                    </td>
                    <td >
                        <input type="checkbox" onclick="setManager(<?= $m['user_id'] ?>)" <?= !empty($m['manager']) ? 'checked' : '' ?>   <?= $m['chairman'] ? 'disabled="disabled"' : ''; ?> />
                    </td>

                    <td>
                        <?php if (!$m['chairman']): ?>
                            <a href="javascript:remove(<?= $m['user_id'] ?>)">移出</a>
                        <?php else: ?>
                            <span class="quiet" style="color: #f60">创建人</span>
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
            type: 'post',
            url: '<?= URL::site('aa_admin_member/set?id=' . $_ID . '&cid=') ?>'+cid,
            data: 'title='+$('#input_title_'+cid).val()
        }).send();
    }

    function setManager(cid){
        var url='<?= URL::site('aa_admin_member/setManager') . URL::query() ?>';
        new Request({
            url: url,
            type: 'post',
            data:'id=<?= $_ID ?>&cid='+cid
        }).send();
    }

    function remove(cid){
        new candyConfirm({
            message: '确定要将此成员请出校友会吗？',
            url: '<?= URL::site('aa_admin_member/remove?id=' . $_ID. '&cid=') ?>'+cid,
            removeDom:'m_'+cid
        }).open();
    }
</script>