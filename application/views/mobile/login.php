<script type="text/javascript">
zuaa.logined_redirect='<?=$_SESS->get('logined_redirect');?>';
</script>

<div class="g  one-whole" style="min-height: 250px">
    <form id="loginform" method="POST">
        <fieldset>
            <legend style="font-weight: bold;margin-bottom: 10px">登录校友网</legend>
            <input type="text" placeholder="邮箱" name="account" id="account" style="width:100%;margin-top: 10px" >
            <br>
            <input type="password" placeholder="密码" name="password" id="password"  style="width:100%;">

            <?php if($_WEIXINOPENID):?>
            <label class="checkbox" style="color:#999">
                <input type="hidden" name="weixin_openid" value="<?=$_WEIXINOPENID?>">
                <input type="checkbox" name="rememberme" value="1" checked>记住我的微信ID</label>
            <?php else:?>
            <label class="checkbox" style="color:#999"><input type="checkbox" name="rememberme" value="1" checked>下次自动登录</label>
            <?php endif;?>
            <div style="margin: 15px 0">
                <button type="button" class="btn btn-large btn-success btn-block" onclick="zuaa.login()" id="submit_button">立即登录</button>
                <br>
                <a href="/mobile/reg?<?= $_AIDSTR ?>">还没有帐号?</a>
            </div>
        </fieldset>
    </form>
</div>

