<div id="main_deny" style="height:300px; font-size:14px; text-align: center">
    <h4 style="color:#333;margin-top: 50px">很抱歉，该内容您暂时还不能访问。</h4>
    <p class="dotted_line" style="width:90%;margin:0px auto"></p>
    <?php if($reason): ?>
    <div class="quiet" ><br>
        原因： <?= $reason ?>
        <a href="javascript:history.back()">点击返回</a>
    </div>
    <?php else: ?>
    <div class="quiet" style="font-size: 12px;line-height: 50px">
        可能您还没有登录网站，请先登录再进行尝试，谢谢！【<a href="javascript:history.back()">点击返回</a>】 
    </div>
    <?php endif; ?>
</div>