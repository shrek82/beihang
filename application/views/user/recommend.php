<link type="text/css" rel="stylesheet" href="/static/css/reg.css">
<!--main -->
<div id="reg">
    <div class="reg_top"></div>
    <div class="step">注册第<span>4</span>/<?= $_CONFIG->reg['all_step'] ?>步：</div>
    <div id="reg_body">
        <!--left:form -->
        <div class="left" style="width:600px">
            <p><img src="/static/images/reg_title4.gif"></p>
            <div class="reg_tip">&nbsp;&nbsp;温馨提示：已通过档案认证校友可直接加入校友会，暂未通过校友需申请加入:)</div>
            <div class="form">

                <form id="user_reg_form" action="<?= $_URL ?>" method="post">

                    <!-- 地方校友会推荐 -->
                    <div class="form_row" style="margin:0px 30px">
                        <?php if (count($aa) == 0): ?>
                            <div style="font-size:12px;color: #999">很抱歉，没有可以推荐给您的地方校友会。</div>
                        <?php else: ?>
                            <h5>我们推荐您加入以下校友会：</h5>
                            <?php foreach ($aa as $a): ?>
                                <input checked type="checkbox" name="aa_id[]" value="<?= $a['id'] ?>" id="aa_<?= $a['id'] ?>" />
                                <label for="aa_<?= $a['id'] ?>"><?= $a['name'] ?></label>
                                <br>
                            <?php endforeach; ?>

                        <?php endif; ?>
                    </div>


                    <!-- 推荐加入班级录 -->
                    <div class="form_row" style="margin:0px 30px">

                        <?php if (isset($classroom) > 0): ?>
                            <?php if (count($classroom) > 0): ?>
                                <h5>推荐加入班级：</h5>
                                <?php foreach ($classroom as $cm): ?>
                                    <input checked type="checkbox" name="classroom_id[]" value="<?= $cm['id'] ?>" id="classroom_<?= $cm['id'] ?>" />
                                    <label for="classroom_<?= $cm['id'] ?>"><?= $cm['school'] ? $cm['school'] . '&nbsp;' : '' ?><?= $cm['institute'] ? $cm['institute'] . '&nbsp;-&nbsp;' : '' ?><?= $cm['speciality'] ? $cm['speciality'] : $cm['name']; ?>
                                        (
                                        <?= $cm['start_year'] ? $cm['start_year'] : '?' ?> ~
                                        <?= $cm['finish_year'] ? $cm['finish_year'] : '?' ?>年
                                        )
                                    </label>
                                    <br>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        <?php endif; ?>

                    </div>


                    <!--button -->
                    <div class="form_row" style="margin:20px 30px">

                        <div class="form_field">
                            <?php if(count($aa) OR (isset($classroom) AND count($classroom))):?>
                            <input type="button" id="submit_button" class="button_green" onclick="sendrecommend();" value="<?= $row['alumni_id'] ? '加入' : '申请加入'; ?>" />
                            <?php endif;?>
                            
                                <?php if ($_CONFIG->modules['binding']): ?>
                                <input type="button" class="button_gray2" onclick="location.href = '/user/binding';" value="跳过"/>
                            <?php else: ?>
                                <input type="button" class="button_gray2" onclick="location.href = '/user_home';" value="跳过"/>
                            <?php endif; ?>
                        </div>
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

<script type="text/javascript">
<?php if ($_CONFIG->modules['binding']): ?>
        function sendrecommend(){
            new ajaxForm('user_reg_form', {
                textSuccess: '加入成功',
                textError: '重试加入',
                redirect: '<?= URL::site('user/binding') ?>'
            }).send();
        }
<?php else: ?>
        function sendrecommend(){
            new ajaxForm('user_reg_form', {
                textSuccess: '加入成功',
                textError: '重试加入',
                redirect: '<?= URL::site('user_home/') ?>'
            }).send();
        }
<?php endif; ?>
</script>