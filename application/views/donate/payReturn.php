<!-- donate/wantDonate:_body -->
<style type="text/css">
    .donate_title{ background-color: #F2F2C6; color: #c00; background-image: url(/static/images/dotted_line_bg3.gif);}
    .project_list li{ margin-bottom: 5px}
    .field{ text-align: right;width: 100px}
    table td{padding:3px}
    .red{ color: #f00}
</style>

<div id="main_left" style="background-color:#FCFCF0;height:700px">
    <p ><img src="/static/images/on_line_donate.gif" /></p>

    <div style="text-align:center; width: 600px; margin: 50px auto">
	<?php if($pay_success):?>
	<img src="/static/images/success.gif" class="middle" />&nbsp;&nbsp;<span style="color:#006600;font-size: 14px; font-weight: bold">恭喜您，支付成功。</span>
	<p style="margin:15px;color: #333"><?=$pay_msg?>&nbsp;&nbsp;<a href="<?=URL::site('donate/thanks')?>">点击查看</a></p>
	<?php else:?>
	<img src="/static/images/error.gif" class="middle"/>&nbsp;&nbsp;<span style="color:#cc0000;font-size: 14px; font-weight: bold">很遗憾，支付失败了。</span>
	<p style="margin:15px; color: #333"><?=$pay_msg?>&nbsp;&nbsp;<?php if($attach):?><a href="<?=URL::site('donate/pay?id='.$attach)?>">点击重试</a><?php endif;?></p>
	    <?php endif;?>
    </div>
</div>
<?php
include 'sidebar.php';
?>
<div class="clear"></div>