<!-- user_invite/index:_body -->
<?php
$point_config = Kohana::config('point')->add;
?>
<div id="big_right">
    <div id="plugin_title">我的邀请</div>

    <div class="tab_gray" id="user_topbar" style="margin-top:10px">
        <ul>
            <li><a href="<?= URL::site('user_invite/index') ?>"  style="width:80px">收到的邀请</a></li>
            <li><a href="<?= URL::site('user_invite/sended') ?>"  style="width:80px">发出的邀请</a></li>
            <li><a href="<?= URL::site('user_invite/generate') ?>"  style="width:80px" class="cur">创建邀请</a></li>
        </ul>
    </div>
    <h2 style="color:#c00">邀请校友注册</h2>
    <p style="color:#42AF2D">邀请奖励：每成功邀请1名认证校友，奖励<?= $point_config['invite_register'] ?>点积分；<br>邀请方式：选择以下任意一种方式邀请你认识的校友加入:)<br></p>
    <table width="100%" >
        <tr>
            <td><span style="font-size: 14px; font-weight: bold">方式1：发布公开邀请</span><span style="color:#999;font-size: 12px">（适用于向公共社区用户发送邀请，例如腾讯Q群、人人网等、用户打开链接即可进入注册）</span></td>
        <tr>
        <tr>
            <td>
                <?php if ($inviteLink AND isset($_GET['action'])): ?>
                    <form action="/user_invite/generate" method="post" name="generat_form" id="generat_form">
                        <table width="100%" id="generatTable">
                            <tr>
                                <td valign="top" style="width:80px; text-align: right; padding-top: 6px">想对他们说：</td>
                                <td><textarea class="input_text" style="width:450px;height:60px" name="message"><?= $inviteLink['message'] ?></textarea></td>
                            </tr>
                            <tr>
                                <td style=" display: inherit"></td>
                                <td><input type="hidden" name="id" value="<?= $inviteLink['id'] ?>"><input type="button" id="submit_button" class="button_blue" value="修改邀请" onclick="generatLink()"><input type="button" class="button_gray" value="取消" onclick="window.history.back()"></td>
                            </tr>
                        </table></form>
                <?php elseif ($inviteLink): ?>
                    <div id="invite_link" style="  margin: 10px 0;width: 670px; border: 1px solid #7ED16C; background-color: #E5F5E3; padding: 10px;font-size: 14px; color: #276A1A; font-weight: bold "><?= URL::base(FALSE, TRUE) ?>invite?code=<?= base64_encode($inviteLink['id']) ?></div>
                    <div style="text-align: right;width: 690px;"><a href="/invite?code=<?= base64_encode($inviteLink['id']) ?>" target="_blank">预览</a> | <a href="/user_invite/generate?action=edit" >修改</a></div>
                <?php else: ?>
                    <form action="/user_invite/generate" method="post" name="generat_form" id="generat_form">
                        <table width="100%" id="generatTable">
                            <tr>
                                <td valign="top" style="width:80px; text-align: right; padding-top: 6px">想对大家说：</td>
                                <td><textarea class="input_text" style="width:450px;height:80px" name="message">亲，一起来加入校友网吧！</textarea></td>
                            </tr>
                            <tr>
                                <td style=" display: inherit"></td>
                                <td><input type="button" id="submit_button" class="button_blue" value="创建邀请" onclick="generatLink()"></td>
                            </tr>
                        </table>
                    </form>
                <?php endif; ?>
            </td>
        </tr>

        <?php if (!isset($_GET['action'])): ?>
            <tr style="display: none">
                <td><div style="font-size: 14px; font-weight: bold;margin-top: 30px">方式2：发送邮件邀请<span style="color:#999;font-size: 12px; font-weight: normal">（发送邀请函到对方的邮箱，根据提示进入注册）</span></div></td>
            <tr>
            <tr style="display: none">
                <td>
                    <form action="/user_invite/sendMailInvite" method="post" name="generat_form" id="generat_form">
                        <table width="100%" id="generatTable" >
                            <tr>
                                <td valign="top" style="width:80px; text-align: right; padding-top: 6px">对方邮件：</td>
                                <td><input type="text" style="width:450px; height:22px; color: #999" name="emails" id="emails" class="input_text" ></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td><input type="button" class="button_blue" value="发送邀请" onclick="sendEmailIntive()" id="submit_button" ></td>
                            </tr>
                        </table>
                    </form>
                    <div style="color: #276A1A; margin: 15px 0; display:none " id="inviteMailMsg">恭喜您，邀请发送成功！如需继续发送，请重复以上操作:)</div>
                </td>
            <tr>
            <?php endif; ?>
    </table>
</div>
<script type="text/javascript">
    //生成邀请链接
    function generatLink(){
        new ajaxForm('generat_form', {
            callback: function(link){
                window.location.href='/user_invite/generate';
            },
            textSuccess:'创建成功',
        }).send();
    }
    //发送邮件邀请
    function sendEmailIntive(){
        var mailInviteMsg=document.getElementById('inviteMailMsg');
        new ajaxForm('generat_form',{textSending: '发送中',textError: '重试',textSuccess: '修改成功',callback:function(id){
                document.getElementById('emails').value='';
                mailInviteMsg.style.display='inline',
                setTimeout(function(){mailInviteMsg.style.display='none';document.getElementById('sendButton2').value='发送';},3000);
            }}).send();
    }
</script>