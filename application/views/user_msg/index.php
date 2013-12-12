<!-- user_msg/index:_body -->
<div id="big_right">
    <div id="plugin_title">短消息</div>

    <div class="tab_gray" id="user_topbar" style="margin-top:10px">
        <ul>
            <li><a href="<?= URL::site('user_msg/form') ?>" style="width:50px">签写消息</a></li>
            <li><a href="<?= URL::site('user_msg/index') ?>" class="cur" style="width:50px">收信箱</a></li>
            <li><a href="<?= URL::site('user_msg/sys') ?>" style="width:50px">系统消息</a></li>
        </ul>
    </div>

    <?php if (count($msgs) == 0): ?>
        没有信息。
    <?php else: ?>

        <table width="100%" id="msg_list">
            <?php foreach ($msgs as $msg): ?>
                <tr>
                    <td style="border-bottom: 1px dotted #ccc;padding:7px 0">

                        <span style="color:#999"><?= Date::span_str(strtotime($msg['send_at'])); ?>前</span>
                        <?php if ($msg['user_id'] == $_UID): ?>
                            发送给 <a href="<?= URL::site('user_home?id=' . $msg['Rec']['id']) ?>" ><?= $msg['Rec']['realname'] ?></a>
                        <?php else: ?>
                            收到 <a href="<?= URL::site('user_home?id=' . $msg['User']['id']) ?>"><?= $msg['User']['realname'] ?></a>
                        <?php endif; ?>
                        “<a href="javascript:show_reply(<?= $msg['id'] ?>)" title="点击展开" style="color:#0B60AF;<?= $msg['new_num'] > 0 ? 'font-weight:bold' : ''; ?>"><?= Text::limit_chars(strip_tags($msg['content']), 30, '...') ?> </a>”

                        包含<?= $msg['replay_num'] + 1 ?>条对话
                        <?php if ($msg['new_num'] > 0): ?>
                            (<span style="color:#217C0D"><?= $msg['new_num'] ?> 条未读</span>)
                        <?php endif; ?>
                        <span id="msg_oc_<?= $msg['id'] ?>" ><img src="/static/images/blueadd_s.gif"></span>
                        <?php if ($msg['send_to'] == $_UID): ?><?php endif; ?>
                        <div id="msg_<?= $msg['id'] ?>" style="margin:5px 0"></div>

                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <?= $pager ?>
    <?php endif; ?>  
</div>

<script type="text/javascript">
    function show_reply(id){
        var shower = $('#msg_'+id);
        var flag = $('#msg_oc_'+id);
        if(shower.html()){
            flag.html('<img src="/static/images/blueadd_s.gif">');
            shower.html('');
        } else {
            flag.html('<img src="/static/images/user/loading6.gif">');
            $('#msg_'+id).load('<?= URL::site('user_msg/reply?msg_id=') ?>'+id);
            flag.html('<img src="/static/images/-.gif">');
        }
    }
</script>