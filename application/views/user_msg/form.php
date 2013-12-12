<!-- user_msg/form:_body -->
<div id="big_right">
    <div id="plugin_title">签写消息</div>
    <div class="tab_gray" id="user_topbar" style="margin-top:10px">
        <ul>
            <li><a href="<?= URL::site('user_msg/form') ?>" class="cur" style="width:50px">签写消息</a></li>
            <li><a href="<?= URL::site('user_msg/index') ?>" style="width:50px">收信箱</a></li>
            <li><a href="<?= URL::site('user_msg/sys') ?>" style="width:50px">系统消息</a></li>
        </ul>
    </div>
    <div>


        <div >
            <div style="color:#666">已关注的校友：</div> 
            <form action="<?= URL::site($_URI) ?>" method="post" id="user_msg">
                <div  style="width:650px">
                    <?php if (count($mark_user) > 0): ?>
                        <?php foreach ($mark_user as $u): ?>
                            <label><input name="send_to[]" type="checkbox" value="<?= $u['MUser']['id'] ?>"><?= $u['MUser']['realname'] ?></label>&nbsp;
                        <?php endforeach ?>
                    <?php else: ?>
                        <div style="color:#999">暂无关注</div> 
                    <?php endif; ?>
                </div>
                <div style="margin-top: 10px">
                    <textarea id="msg_content" name="content" style="width:650px;height:200px"></textarea>
                </div>
                <div style="padding:10px 0"><input onclick="post_msg();" type="submit" id="submit_button" value="发送到选择校友" class="input_button" /></div>
            </form>
        </div>


    </div>
</div>

<?=
View::ueditor('msg_content', array(
    'toolbars' => Kohana::config('ueditor.simple'),
    'minFrameHeight' => 180,
    'autoHeightEnabled' => 'false',
    'elementPathEnabled' => 'false'
));
?>

<script type="text/javascript">
                    function post_msg() {
                        if (!ueditor.hasContents()) {
                            ueditor.setContent('');
                        }
                        ueditor.sync();
                        new ajaxForm('user_msg', {
                            callback: function() {
                                location.href = '<?= URL::site('user_msg/index') ?>';
                            }
                        }).send();
                    }

</script>