<!-- 申请成为班级管理员 -->
<!-- 被拒绝申请 -->
<?php if($apply['is_reject']): ?>
<h5>很抱歉，网站管理员拒绝了您的申请，请补充申请原因或直接与管理员联系，谢谢！</h5>
<form id="apply_form" method="post" action="<?= URL::site('user/apply') ?>">
    拒绝原因：<?= $apply['reject_reason'] ?>
    <table>
        <tr>
            <td>
                补充或修改申请原因：<br><textarea name="content" style="width:300px; height: 50px"><?= $apply['content'] ?></textarea>
            </td>
        </tr>
        <tr>
            <td style="text-align: right">
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
                <input type="hidden" name="class_room_id" value="<?= @$class_room_id ?>" />
                <input type="hidden" name="id" value="<?= $apply['id'] ?>" />
            </td>
        </tr>
    </table>
    <input type="submit" value="保存" style="display: none" />
</form>
<?php endif; ?>
