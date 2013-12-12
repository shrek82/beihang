<?php if (isset($msg) AND $msg): ?>
    <div style="padding: 5px 0; line-height: 1.6em"><?= $msg['realname'] ?>：<?= $msg['content'] ?><span style="color:#999">（<?= Date::ueTime($msg['send_at']) ?>）</span></div>
    <form id="msg_form" method="post" action="<?= URL::site('user_msg/reply') ?>">
        <table>
            <tr>
                <td ><span style="color:#999">回复：</span></td>
            </tr>
            <tr>
                <td>
                    <textarea name="content" style="width:450px; height: 50px" class="input_text"></textarea>
                </td>
            </tr>
            <tr>
                <td style="text-align: right">
                    <input type="hidden" name="user_id" value="<?= $_UID ?>" />
                    <input type="hidden" name="sort_in" value="<?= $msg['sort_in'] ? $msg['sort_in'] : $msg['id'] ?>" />
                    <input type="hidden" name="send_to" value="<?= $msg['user_id'] ?>" />
                </td>
            </tr>
        </table>
    </form>
<?php else: ?>
    <form id="msg_form" method="post" action="<?= URL::site('user_msg/form') ?>">
        <table>
            <tr>
                <td ><span style="color:#999">发给</span> <?= $user['realname'] ?></td>
            </tr>
            <tr>
                <td>
                    <textarea name="content" style="width:450px; height: 50px" class="input_text"></textarea>
                </td>
            </tr>
            <tr>
                <td style="text-align: right">
                    <input type="hidden" name="send_to" value="<?= $user['id'] ?>" />
                </td>
            </tr>
        </table>
    </form>
<?php endif; ?>
