<!-- app/post:_body -->
<?php if ($binding): ?>
<h1 style="padding:0px 20px; text-align: right; margin:10px 0;"><a href="/app/weibolist" style="font-size: 16px">浏览我最新的微博</a></h1>
<?php else: ?>
很抱歉，您尚未绑定微博，请先<a href="/app/binding/sina">绑定</a>。
<?php endif; ?>
