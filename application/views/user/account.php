<link type="text/css" rel="stylesheet" href="/static/css/reg.css">
<!--main -->
<div id="reg">
    <div class="reg_top"></div>
    <div class="step">注册第<span>2</span>/<?= $_CONFIG->reg['all_step'] ?>步：</div>
    <div id="reg_body">
        <!--left:form -->
        <div class="left" style="width:600px">
            <p><img src="/static/images/reg_title2.gif"></p>

            <div class="reg_tip">亲爱的<b><?= $basedata['realname'] ?></b>校友，欢迎回来！完成以下资料，你就可以登录校友网啦:)</div>
            <div class="form">
                <form id="user_reg_form" action="<?= $_URL ?>" method="post">

                    <!--input:account -->
                    <div class="form_row">
                        <div class="form_label"><span class="must">* </span>登录帐号：</div>
                        <div class="form_field">
                            <input type="text" name="account" class="input_text" style="width:243px" value="<?= $_SESS->get('receiver_email') ?>">
                            <div class="msg" id="msg_name">填写一个您常用的E-mail作为本站登录账号</div>
                        </div>
                        <div class="clear"></div>
                    </div>


                    <!--input:sex -->
                    <div class="form_row">
                        <div class="form_label"><span class="must">* </span>性别：</div>
                        <div class="form_field">
                            <input type="radio" name="sex" value="男" <?= isset($alumni['sex'])&&$alumni['sex']=='男' ? 'checked' : '' ?> /> 男
                            <input type="radio" name="sex" value="女" <?= isset($alumni['sex'])&&$alumni['sex']=='女' ? 'checked' : '' ?> /> 女
                        </div>
                        <div class="clear"></div>
                    </div>


                    <!--input:mobile -->
                    <div class="form_row">
                        <div class="form_label"><span class="must">* </span>手机号码：</div>
                        <div class="form_field">
                            <input type="text" name="mobile" class="input_text" style="width:243px" value=""  />
                            <div class="msg" id="msg_name">填写手机号码，以便我们及时与您联系，我们不会公开您的手机号码</div>
                        </div>
                        <div class="clear"></div>
                    </div>

                    <!--input:password -->
                    <div class="form_row">
                        <div class="form_label"><span class="must">* </span>登录密码：</div>
                        <div class="form_field">
                            <input type="password" name="password" class="input_text" style="width:243px"  value="">
                            <div class="msg" id="msg_name">请输入至少6位或以上密码</div>
                        </div>
                        <div class="clear"></div>
                    </div>

                    <!--input:password -->
                    <div class="form_row">
                        <div class="form_label"><span class="must">* </span>确认密码：</div>
                        <div class="form_field">
                            <input type="password" name="password2" class="input_text" style="width:243px" value="" >
                            <div class="msg" id="msg_name">再次输入刚才的密码</div>
                        </div>
                        <div class="clear"></div>
                    </div>


                    <div class="form_row">
                        <div class="form_label"></div>
                        <div class="form_field" style="font-size:12px">
                            <input type="checkbox" name="agreement" value="1" checked >我已经阅读并同意《<a href="<?= URL::site('help?type=1&id=2') ?>" target="_blank">注册协议</a>》
                        </div>
                        <div class="clear"></div>
                    </div>


                    <!--button -->
                    <div class="form_row">
                        <div class="form_label"></div>
                        <div class="form_field">
                            <input type="button"  class="button_gray2" onclick="location.href = '<?= URL::base() ?>user/register/1'" value="上一步" />
                            <input type="button" id="submit_button"  class="button_green" onclick="
                                    new ajaxForm('user_reg_form', {
                                        textError: '下一步',
                                        textSending: '创建中',
                                        textSuccess: '创建成功',
                                        'callback': function() {
                                            location.href = '<?= URL::base() . 'user/register/3'; ?>'
                                        }}).send()" value="下一步" />
                        </div>
                        <div class="clear"></div>
                    </div>
                    <!--//button -->

                </form>
            </div>
        </div>
        <!--//left -->
        <!--What can I do -->
        <div class="right" >

        </div>
        <!--What can I do -->
        <div class="clear"></div>
    </div>
    <div class="reg_footer"></div>
</div>
<!--//main -->