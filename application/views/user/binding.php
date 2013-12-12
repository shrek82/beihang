<link type="text/css" rel="stylesheet" href="/static/css/reg.css">
<!--main -->
<div id="reg">
    <div class="reg_top"></div>
    <div class="step">注册第<span>5</span>/<?=$_CONFIG->reg['all_step']?>步：</div>
    <div id="reg_body">
        <!--left:form -->
        <div class="left" style="width:600px">
            <p><img src="/static/images/reg_title5.gif"></p>
            <div class="reg_tip">&nbsp;&nbsp;花几秒钟来绑定您的微博帐号，可让更多校友来关注您，也可随时分享您的新鲜事、活动等信息到微博！</div>
            <div class="form">

                <form id="user_reg_form" action="<?= $_URL ?>" method="post">

                    <!-- 地方校友会推荐 -->
                    <div class="form_row" style="margin:0px 30px">
                        <table width="800" id="bbs_table" style="border-bottom:1px dotted #E0EDF7">
                            <tr>
                                <td style=" text-align: left;font-size: 14px; color: #666;" ><img src="/static/logo/sina/32x32.png" style="vertical-align: middle" /> 新浪微博 </td>
                                <td style=" text-align: right;width: 80%; padding: 10px" ><a href="<?= @$bindingSinaUrl ?>" class="binding" style="float:right"></a></td>
                            </tr>
                        </table>
                    </div>
                    <!--button -->
                    <div class="form_row" style="margin:20px 30px">
                        <input class="button_green" onclick="location.href='<?= URL::site('user/job') ?>'" type="button" value="上一步" />
                        <input type="button" class="button_gray2" onclick="location.href='<?= URL::site('user_home') ?>'" value="跳过" title="跳过本步骤进入个人主页"/>
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