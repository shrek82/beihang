<!-- 申请加入各组织的申请表 -->
<!-- 被拒绝申请 -->
<?php if($apply['is_reject']): ?>
<h5>很抱歉，该组织管理层拒绝了您的加入申请，请补充申请加入原因或直接与管理员联系，谢谢！</h5>
<form id="apply_form" method="post" action="<?= URL::site('user/apply') ?>">
    拒绝原因：<?= $apply['reject_reason'] ?>
    <table>
        <tr>
            <td>
                补充或修改加入原因：<br><textarea name="content" style="width:300px; height: 50px"><?= $apply['content'] ?></textarea>
            </td>
        </tr>
        <tr>
            <td style="text-align: right">
                <input type="hidden" name="club_id" value="<?= @$club_id ?>" />
                <input type="hidden" name="aa_id" value="<?= @$aa_id ?>" />
                <input type="hidden" name="class_room_id" value="<?= @$class_room_id ?>" />
                <input type="hidden" name="id" value="<?= $apply['id'] ?>" />
            </td>
        </tr>
    </table>
    <input type="submit" value="保存" style="display: none" />
</form>
<?php else: ?>
<form id="apply_form" method="post" action="<?= URL::site('user/apply') ?>">
    <table>
        <tr>
            <td>
                <textarea name="content" style="width:300px; height: 50px"><?= $apply['content'] ?></textarea>
            </td>
        </tr>
        <tr>
            <td style="text-align: right">
                <input type="hidden" name="club_id" value="<?= @$club_id ?>" />
                <input type="hidden" name="aa_id" value="<?= @$aa_id ?>" />
                <input type="hidden" name="class_room_id" value="<?= @$class_room_id ?>" />
                <input type="hidden" name="id" value="<?= $apply['id'] ?>" />
            </td>
        </tr>
    </table>
    <input type="submit" value="保存" style="display: none" />
</form>
<?php endif; ?>
