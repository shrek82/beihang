<!-- user_msg/sys:_body -->
<div id="big_right">
    <div id="plugin_title">签写消息</div>

    <div class="tab_gray" id="user_topbar" style="margin-top:10px">
	<ul>
	    <li><a href="<?= URL::site('user_msg/form') ?>" style="width:50px">签写消息</a></li>
	    <li><a href="<?= URL::site('user_msg/index') ?>" style="width:50px">收信箱</a></li>
	    <li><a href="<?= URL::site('user_msg/sys') ?>" class="cur" style="width:50px">系统消息</a></li>
	</ul>
    </div>

    <?php if(count($msg) == 0): ?>
    <p class="ico_info icon">
        尚无消息～
    </p>
    <?php else: ?>
    <table>
        <?php foreach($msg as $m): ?>
        <tr>
            <td>
                <a href="#" class="msg_tt icon-i <?= in_array($m['id'], $new_msg) ? 'ico_sound_on':'ico_sound_off' ?>"><?= $m['title'] ?></a>
                <div class="msg_ct"><?= $m['content'] ?></div>
            </td>
            <td class="timestamp" width="120" valign="top"><?= $m['post_at'] ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php endif; ?>

</div>

<script type="text/javascript">
    $$('.msg_tt').each(function(el){
        var box = el.getNext('.msg_ct');
        if(box.hasClass('ico_sound_off')) box.slide('hide');
        el.addEvent('click', function(){
            box.slide();
        });
    });
</script>