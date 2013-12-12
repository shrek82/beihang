<!-- global/footer_bottom:_footer_bottom -->
<!--footer-->
<div class="links">
    <a href="<?= URL::site('aa') ?>">关于我们</a> <a href="/help/file">档案查询</a>  <a href="<?= URL::site('bbs/list?f=0&b=68') ?>">意见建议</a><a href="#">  隐私声明</a><a href="#">版权申明</a></div>
联系信箱：<?=$_SETTING['contact_email']?> 地址：<?=$_SETTING['contact_address']?> 邮编：<?=$_SETTING['contact_zip']?><br>
北京航空航天大学校友总会版权所有<br>
<!--//footer-->
<!--返回到顶部 -->
<a id="go2top" class="go2top" href="#header" style="display: none; "><span class="go2top-inner"></span></a>
<script type="text/javascript">
    $(document).ready(function() {
        readyScript.footer = getToTop;
<?php if ($_UID): ?>readyScript.msg = function(){setTimeout(function() {check_pm_notice();}, 3000);};<?php endif;?>
<?php if ($_SESS->get('prompt')): ?>showPrompt('<?= $_SESS->get('prompt') ?>', 2000); <?= $_SESS->delete('prompt') ?><?php endif; ?>
<?php if ($_SESS->get('checkJoinEvent')): ?>setTimeout(function() { checkjoinevent(); }, 4000);<?= $_SESS->delete('checkJoinEvent') ?><?php endif; ?>
runReadyScript();
    });
</script>