<link type="text/css" rel="stylesheet" href="/static/css/reg.css">
<script type="text/javascript" src="/static/My97DatePicker/WdatePicker.js"></script>
<!--main -->
<div id="reg">
        <div class="reg_top"></div>
        <div class="step">注册第<span>3</span>/<?=$_CONFIG->reg['all_step']?>步：</div>
        <div id="reg_body">
                <!--left:form -->
                <div class="left" style="width:600px">
                        <p><img src="/static/images/reg_title3.gif"></p>
                        <div class="reg_tip">填写城市信息，可以快速的推荐您加入离你最近的校友会：）</div>
                        <div class="form">
                                <form id="user_reg_form" action="<?= $_URL ?>" method="post" >

                                        <!--input:city -->
                                        <div class="form_row">
                                                <div class="form_label"><span class="must">* </span>所在城市：</div>
                                                <div class="form_field">
                                                        <input type="text" name="city" class="input_text" style="width:243px" >
                                                        <div class="msg" id="msg_name">填写您当前所在的地区名称，如：杭州</div>
                                                </div>
                                                <div class="clear"></div>
                                        </div>


                                        <!--input:industry -->
                                        <div class="form_row">
                                                <div class="form_label"><span class="must">* </span>所属行业：</div>
                                                <div class="form_field">
                                                        <select name="industry" style="padding:2px;border:1px solid #CCCCCC">
                                                                <?php foreach ($industry as $v): ?>
                                                                        <option value="<?= $v ?>"><?= $v ?></option>
                                                                <?php endforeach; ?>
                                                                <option value="其他">其他</option>
                                                        </select>
                                                </div>
                                                <div class="clear"></div>
                                        </div>


                                        <!--input:company -->
                                        <div class="form_row">
                                                <div class="form_label"><span class="must">* </span>单位名称：</div>
                                                <div class="form_field">
                                                        <input type="text" name="company" class="input_text" style="width:243px" />
                                                <div class="msg" id="msg_name">填写完整的工作信息，可能优先参加一些活动，在校请填写校名</div>
                                                </div>
                                                <div class="clear"></div>
                                        </div>

                                        <!--input:job -->
                                        <div class="form_row">
                                                <div class="form_label"><span class="must">* </span>职务：</div>
                                                <div class="form_field">
                                                        <input type="text" name="job" class="input_text" style="width:243px" >
                                                        <div class="msg" id="msg_name">例如 工程师 或 学生</div>
                                                </div>
                                                <div class="clear"></div>
                                        </div>

                                        <!--input:start_at -->
                                        <div class="form_row">
                                                <div class="form_label"><span class="must"></span>工作日期：</div>
                                                <div class="form_field">
                            <input type="text" name="start_at" class="input_text" style="width:243px" onClick="WdatePicker({dateFmt:'yyyy-MM-dd'});">
                                                        <div class="msg" id="msg_name"> 填写您工作开始日期，如2005-10-01</div>
                                                </div>
                                                <div class="clear"></div>
                                        </div>

                                        <!--button -->
                                        <div class="form_row">
                                                <div class="form_label"></div>
                                                <div class="form_field">
                            <?php if ($_CONFIG->modules['binding']): ?>
                                <input class="button_green" type="button" id="submit_button" onclick="new ajaxForm('user_reg_form', {callback: function(url) {
                                                location.href = '/user/recommend';
                                            }}).send();" type="submit" value="下一步" />
                                   <?php else: ?>
                                <input class="button_green" type="button" id="submit_button" onclick="new ajaxForm('user_reg_form', {callback: function(url) {
                                                location.href = url;
                                            }}).send();" type="submit" value="下一步" />
                                   <?php endif; ?>
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