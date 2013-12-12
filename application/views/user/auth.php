<link type="text/css" rel="stylesheet" href="/static/css/reg.css">
<script type="text/javascript">

    function CookieEnable()
    {
        var result=false;
        if(navigator.cookiesEnabled)
            return true;
        document.cookie = "testcookie=yes;";
        var cookieSet = document.cookie;
        if (cookieSet.indexOf("testcookie=yes") > -1)
            result=true;
        document.cookie = "";
        return result;
    }

    if(!CookieEnable())
    {
        alert("对不起，您的浏览器的Cookie功能被禁用，请开启");
    }
</script>
<!--main -->
<div id="reg">
    <div class="reg_top"></div>
    <div class="step">注册第<span>1</span>/<?=$_CONFIG->reg['all_step']?>步：</div>
    <div id="reg_body">
        <!--left:form -->
        <div class="left">
            <p><img src="/static/images/reg_title.gif"></p>
            <div class="reg_tip">
                我们也觉得注册烦透了，但为确保校友的真实性，还请您如实填写下面信息吧 :)</div>
            <div class="form">

                <?php if (!$_UID): ?>
                    <form id="user_reg_form" action="<?= $_URL ?>" method="post">
                        <!--input:name -->
                        <div class="form_row">
                            <div class="form_label"><span class="must">* </span>真实姓名：</div>
                            <div class="form_field">
                                <input type="text" name="realname" class="input_text" style="width:243px" maxlength="30">
                                <div class="msg" id="msg_name">请填写您的真实姓名</div>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <!--//input:name -->

                        <!--input:year -->
                        <div class="form_row">
                            <div class="form_label"><span class="must">* </span>入学年份：</div>
                            <div class="form_field">
                                <input type="text" name="start_year" class="input_text" style="width:75px" maxlength="4" >


                                &nbsp;&nbsp;毕业年份：
                                <input type="text" name="graduation_year" class="input_text" style="width:75px" maxlength="4">

                                <div class="msg" id="msg_year1">至少填写一处，如：1985</div>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <!--//input:year -->

                        <!--input:year -->
                        <div class="form_row">
                            <div class="form_label"><span class="must">* </span>就读专业：</div>
                            <div class="form_field">
                                <input type="text" name="speciality" class="input_text" style="width:243px" maxlength="10">
                                <div class="msg" id="msg_specialized">请填写专业或学院关键字，例如：化工</div>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <!--//input:year -->

                        <!--button -->
                        <div class="form_row">
                            <div class="form_label" style=""></div>
                            <div class="form_field">
                                <input type="button" id="submit_button" class="button_green" value="验证"  onclick="checkAlumni()">
                            </div>
                            <div class="clear"></div>
                        </div>
                        <!--//button -->

                    </form>
                <?php else: ?>
                    <div style="padding:0 20px; color:#f60;font-size:12px">您当前已经注册或登录了，不需要再注册了，谢谢！</div>
                <?php endif; ?>
                <div id="backData"  style="font-size: 12px"></div>
            </div>
        </div>
        <!--//left -->
        <!--What can I do -->
        <div class="right" >
            <h3>加入校友会，您可以：</h3>
            1、及时了解母校的信息；<br>
            2、认识更多的<?= $_CONFIG->base['alumni_name'] ?>；<br>
            3、寻找与您同城其他的<?= $_CONFIG->base['alumni_name'] ?>；<br>
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
<script type="text/javascript">
    function checkAlumni(){
        var backDiv=document.getElementById('backData');
        backDiv.innerHTML='';
        new ajaxForm('user_reg_form', {
            textSending: '验证中',
            textSuccess: '重新验证',
            textError: '重新验证',
            callback: function(data){
                backDiv.innerHTML=data;
            }
        }).send();
    }

</script>