<div id="admin950">
    <!-- 待审核通过的申请 -->
    <?php if (count($apply) == 0): ?>
        <div class="nodata" style="height:400px">暂时还没有申请信息。</div>
    <?php else: ?>
        <table id="bench_list" width="100%"  border="0"  cellpadding="0" cellspacing="0">
            <tr>
                <th colspan="2" height="30" class="left" width="20%">正在申请加入的校友：</th>
                <th width="40%">&nbsp;</th>
                <th width="20%">当前总会认证状态</th>
                <th style="text-align:center" width="20%">操作</th>
            </tr>
            <?php foreach ($apply as $key => $ap): ?>
                <tr id="apply_<?= $ap['id'] ?>" class="<?= (($key + 1) % 2) == 0 ? 'even_tr' : 'odd_tr'; ?>">
                    <td width="48" style="padding:10px">
                        <a href="javascript:userDetail(<?= $ap['user_id'] ?>)" title="浏览详细信息"><?= View::factory('inc/user/avatar', array('id' => $ap['user_id'], 'size' => 48, 'sex' => $ap['User']['sex'])) ?></a>
                    </td>
                    <td width="70">
                        <a href="<?= URL::site('user_home?id=' . $ap['user_id']) ?>">
                            <?= $ap['User']['realname'] ?></a><br />

                        <?= Date::span_str(strtotime($ap['apply_at'])) ?>前

                    </td>

                    <td>
                        <?= $ap['content'] ?>
                    </td>
                    <td style="text-align:center"><span style="<?php if ($ap['User']['role'] == '校友(已认证)') : ?>color:#007219<?php endif; ?>"><?= $ap['User']['role'] ?></span></td>
                    <td class="center">
                        <a href="javascript:accept(<?= $ap['id'] ?>)" style="color:green">批准</a> |
                        <?php if ($ap['is_reject']): ?>
                            <span class="quiet" style="color:#f00">已拒绝</span>
                        <?php else: ?>
                            <a href="javascript:reject(<?= $ap['id'] ?>)">拒绝</a>
                        <?php endif; ?>

                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <? endif; ?>

    <div><?= $pager ?></div>
</div>
<script type="text/javascript">
    function accept(id){
        var r = new Request({
            url: '<?= URL::site('aa_admin/apply/accept') . URL::query() ?>&apply_id='+id,
            success: function(){
                candyOk('apply_'+id);
            }
        });
        r.send();
    }

    function reject(id){
        var box = new Facebox({
            title: '拒绝原因',
            url: '<?= URL::site('aa_admin/apply/reject') . URL::query() ?>&apply_id='+id,
            okVal: '拒绝',
            cancelVal:'取消',
            cancel:true,
            ok: function(){
                new ajaxForm('reason_form', {callback:function(){
                        candyDel('apply_'+id);
                        box.close();
                    }}).send();
            }
        });
        box.show();
    }
</script>