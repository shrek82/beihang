<!-- mobile/help:_body -->
<div class="g  one-whole" style="min-height: 300px">
    <article class="post">
        <img src="/static/images/weixinhelp.jpg"><br>
        发送“<span style="color:green;">?</span>”：查看使用帮助<br>
        发送“<span style="color:green;">1</span>”：查看最近活动<br>
        发送“<span style="color:green;">1页码</span>”：翻页查看活动，例如"12"查看第2页活动;<br>
        发送“<span style="color:green;">1关键字</span>”：查找相关活动，例如"1金融"查找金融相关活动;<br>
        发送“<span style="color:green;">2</span>”：查看最新话题<br>
        发送“<span style="color:green;">2关键字</span>”：查找相关话题，例如"1竺可桢"查找竺可桢相关话题;<br>

<?php if (!$_UID): ?>
            <div style="margin: 15px 0;color: #999">
                <a href="/mobile/login?<?=$_AIDSTR?>">立即登录</a> 或 <a href="/mobile/reg?<?=$_AIDSTR?>">注册</a>
            </div>
        <?php endif; ?>
    </article>
</div>