<script type="text/javascript">
    zuaa.logined_redirect = '<?= $_SESS->get('logined_redirect')?$_SESS->get('logined_redirect'):'/mobile?'.$_AIDSTR ?>; ?>';
</script>

<div class="g  one-whole" style="min-height: 250px">
    <form id="regform" method="POST" action="/api_user/register?apikey=3gbrowser&format=json">
        <fieldset>
            <legend style="font-weight: bold">加入校友会</legend>
            <input type="text" placeholder="真是姓名" name="realname" id="realname" style="width:100%;margin-top: 10px" ><br>
            <input type="text" placeholder="入学年份  例如 1998" name="start_year" id="start_year" style="width:100%;margin-top: 10px" ><br>
            <input type="text" placeholder="毕业专业 " name="speciality" id="speciality" style="width:100%;margin-top: 10px" ><br>
            <input type="text" placeholder="邮箱 作为登录帐号" name="email" id="email" style="width:100%;margin-top: 10px" ><br>
            <input type="password" placeholder="密码" name="password" id="password" style="width:100%;margin-top: 10px" ><br>
            <input type="text" placeholder="手机号码  用于身份审核" name="mobile" id="mobile" style="width:100%;margin-top: 10px" ><br>
            <input type="text" placeholder="所在地区  例如 杭州" name="city" id="city" style="width:100%;margin-top: 10px" ><br>
            <label class="checkbox" style="color:#999"><input type="checkbox" name="auto_apply_joinaa" value="1" checked>申请加入以上地区校友会</label>

            <div style="margin: 15px 0">
                <button type="button" class="btn btn-large btn-success btn-block" onclick2="zuaa.reg()" id="submit_button"  >暂无开放注册</button>
                <br><a href="/mobile/login?<?= $_AIDSTR ?>" >已有帐号？</a>

                <p style="color:#999;margin-top: 10px">温馨提示：注册成功后，一般会有半个工作日审核时间，不便之谨请谅解，谢谢！</p>

            </div>
        </fieldset>
    </form>
</div>

