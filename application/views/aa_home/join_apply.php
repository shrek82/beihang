<!-- 申请加入各组织的申请表 -->
<!-- 被拒绝申请 -->
<?php if($apply['is_reject']): ?>
<h5>很抱歉，该组织管理层拒绝了您的加入申请，请补充申请加入原因或直接与管理员联系，谢谢！</h5>
<form id="apply_form" method="post" action="<?= URL::site('user/apply') ?>">
    拒绝原因：<?= $apply['reject_reason'] ?>
    <table>
        <tr>
            <td>
                补充或修改加入原因：<br><textarea name="content" style="width:350px; height: 50px"><?= $apply['content'] ?></textarea>
            </td>
        </tr>
        <tr>
            <td style="text-align: right">
                <input type="hidden" name="aa_id" value="<?= @$aa_id ?>" />
                <input type="hidden" name="id" value="<?= $apply['id'] ?>" />
            </td>
        </tr>
    </table>
<div style="text-align:right"><input type="button" value="保存修改" class="button_login"  onclick="applySignAa()"/></div>
</form>
<?php elseif($apply):?>
<div style="height:80px;line-height: 70px;padding:20px;color: #f60">
正在等待管理员验证，谨留意站内短信通知，谢谢!
</div>
<?php else: ?>
<form id="apply_form" method="post" action="<?= URL::site('user/apply') ?>">
    <table>
        <tr>
            <td>
                <textarea name="content" style="width:350px; height: 50px" class="input_text">Hi,我叫<?= $_SESS->get('realname') ?>，希望加入校友会讨论并参加各类活动，希通过，谢谢！</textarea>
            </td>
        </tr>
        <tr>
            <td style="text-align: right">
                <input type="hidden" name="aa_id" value="<?= @$aa_id ?>" />
                <input type="hidden" name="id" value="<?= $apply['id'] ?>" />
            </td>
        </tr>
    </table>
<div style="text-align:right"><input id="submit_button" type="button" value="确定" class="greenButton" onclick="applySignAa()" /></div>
</form>
<?php endif; ?>
