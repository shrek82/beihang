<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
        <head>
                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
                        <title>加入校友会 - 北航校友网</title>
                        <link type="text/css" rel="stylesheet" href="/static/css/global.css">
                                <link type="text/css" rel="stylesheet" href="/static/css/reg.css">
                                        </head>
                                        <body>
                                                <?= View::factory('global/top') ?>
                                                <!--main -->
                                                <div id="reg">
                                                        <div class="reg_top"></div>
                                                        <div class="step">步骤：<span style="color:#0BAD07">1</span>/4</div>
                                                        <div id="reg_body">
                                                                <!--left:form -->
                                                                <div class="left">
                                                                        <p><img src="/static/images/reg_title.gif"></p>
                                                                        <div class="form">
                                                                                <form action="<?= Url::site('user/auth') ?>" method="post"  >

                                                                                        <!--input:name -->
                                                                                        <div class="form_row">
                                                                                                <div class="form_label"><span class="must">* </span>姓名：</div>
                                                                                                <div class="form_field">
                                                                                                        <input type="name" class="input_text" style="width:230px">
                                                                                                                <div class="msg" id="msg_name">请填写您的真实姓名</div>
                                                                                                </div>
                                                                                                <div class="clear"></div>
                                                                                        </div>
                                                                                        <!--//input:name -->

                                                                                        <!--input:year -->
                                                                                        <div class="form_row">
                                                                                                <div class="form_label"><span class="must">* </span>入校及毕业：</div>
                                                                                                <div class="form_field">
                                                                                                        <input type="year1" class="input_text" style="width:104px"> - <input type="year1" class="input_text" style="width:104px">
                                                                                                                        <div class="msg" id="msg_year1">填写您的入学或毕业年份，至少填写一项，如：1985-1989</div>
                                                                                                                        </div>
                                                                                                                        <div class="clear"></div>
                                                                                                                        </div>
                                                                                                                        <!--//input:year -->

                                                                                                                        <!--input:year -->
                                                                                                                        <div class="form_row">
                                                                                                                                <div class="form_label">就读专业：</div>
                                                                                                                                <div class="form_field">
                                                                                                                                        <input type="specialized" class="input_text" style="width:230px">
                                                                                                                                                <div class="msg" id="msg_specialized">填写您的就读专业，如：化工</div>
                                                                                                                                </div>
                                                                                                                                <div class="clear"></div>
                                                                                                                        </div>
                                                                                                                        <!--//input:year -->

                                                                                                                        <!--button -->
                                                                                                                        <div class="form_row">
                                                                                                                                <div class="form_label"></div>
                                                                                                                                <div class="form_field">
                                                                                                                                        <input type="button" class="button_green" value="验证">
                                                                                                                                </div>
                                                                                                                                <div class="clear"></div>
                                                                                                                        </div>
                                                                                                                        <!--//button -->

                                                                                                                        <!--css:ajax_msg_ok 、ajax_msg_error  -->
                                                                                                                        <div class="ajax_msg_ok"  id="ajax_check">
                                                                                                                                恭喜您，通过检测！请继续<a href="#">下一步</a>。
                                                                                                                        </div>

                                                                                                                        </form>
                                                                                                                        </div>
                                                                                                                        </div>
                                                                                                                        <!--//left -->
                                                                                                                        <!--What can I do -->
                                                                                                                        <div class="right" >
                                                                                                                                <h3>加入校友会，您可以：</h3>
                                                                                                                                1、及时了解母校的信息；<br>
                                                                                                                                        2、认识更多的北航校友；<br>
                                                                                                                                                3、寻找与您同城其他的北航校友；<br>
                                                                                                                                                        4、加入俱乐部发起或参加各式活动；<br>
                                                                                                                                                                5、关注您的同学最新动态；<br>
                                                                                                                                                                        6、更多...<br>
                                                                                                                                                                                </div>
                                                                                                                                                                                <!--What can I do -->
                                                                                                                                                                                <div class="clear"></div>
                                                                                                                                                                                </div>
                                                                                                                                                                                <div class="reg_footer"></div>
                                                                                                                                                                                </div>
                                                                                                                                                                                <!--//main -->
                                                                                                                                                                                <?= View::factory('global/footer') ?>
                                                                                                                                                                                </body>
                                                                                                                                                                                </html>
