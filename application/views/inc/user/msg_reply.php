<?php
//版本：1.0
?>
<?php foreach($msgs as $msg): ?>
<div style="background:#fff;margin:5px 10px;margin-top: 2px;border:1px solid #eee;padding:10px" >

            <img src="/static/images/online<?=$msg['online']?'1':'0';?>.gif">
            <a href="<?= URL::site('user_home?id='.$msg['user_id']) ?>" style="color:#0B60AF; "><?= $_UID==$msg['User']['id']?'我自己':$msg['User']['realname']; ?></a>
            <span style="color:#999"><?= $msg['send_at']?></span>
            <div style="padding:5px; line-height: 1.6em">
            <?= nl2br($msg['content']) ?>
            </div>
</div>

<?php endforeach; ?>

<table width="98%" border="0" cellspacing="1" cellpadding="0" style="margin:10px;margin-top: 2px" >
    <tr>
        <td style="padding:5px; text-align: left " >回复：<br>
    <form id="msg_form_<?= $id ?>" action="<?= URL::site('user_msg/reply') ?>" method="post" style="text-align: center">
    <textarea name="content" style="width: 100%;height:60px" class="input_text"></textarea><br>
    <input type="hidden" name="sort_in" value="<?= $id ?>" />
    <input type="hidden" name="send_to" value="<?= $msgs[0]['user_id'] == $_UID ? $msgs[0]['send_to']:$msgs[0]['user_id'] ?>" />
    <input type="hidden" name="user_id" value="<?= $_UID ?>" />
    <input type="button" id="submit_button" onclick="new ajaxForm('msg_form_<?= $id ?>', {callback:function(){ show_reply(<?= $id ?>) }}).send()" value="回复"  class="button_blue"/>
    <input type="button" onclick="show_reply(<?= $id ?>)" value="取消"  class="button_gray"/>
</form></td>
    </tr>
</table>
