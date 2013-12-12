<div id="news">
    <?php if ($top_news): ?>
        <h1><a href="<?= URL::site('news/view?id=' . $top_news['id']) ?>" title="<?= $top_news['title'] ?>" target="_blank"><?= Text::limit_chars($top_news['title'], 20, '..') ?></a></h1>
        <div class="des">简述：<?= Text::limit_chars($top_news['intro'], 62, '..') ?>&nbsp;&nbsp;<a href="<?= URL::site('news/view?id=' . $top_news['id']) ?>" style="color:#666">全文</a></div>
    <?php else: ?>
        <h3 style="margin:0px;color:#0D5194">新闻动态</h3>
    <?php endif; ?>
    <div class="con_list a14">
        <ul>
            <?php foreach ($news_list as $n): ?>
                <li><a href="<?= URL::site('news/view?id=' . $n['id']) ?>" title="<?= $n['title'] ?>" target="_blank"><?= Text::limit_chars($n['title'], 23, '..') ?></a><?= $n['is_pic'] ? '&nbsp;<font><img src="/static/images/imgs.gif" title="图片新闻"></font>' : ''; ?><span><?= date('n-d', strtotime($n['create_at'])); ?></span></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div style="margin:0px; text-align: right;color: #999"><a href="/news" style="color:#999">>>更多新闻</a></div>
    <p style="margin:-10px 0 0 0;height: 24px;line-height: 24px;border-bottom: 1px solid #E7F1FD;padding-left: 2px"><span style="vertical-align:middle"><img src="/static/images/hunqing.jpg" /></span>&nbsp;<span><a href="/news" style="font-size: 13px;font-weight: bold">微博直播</a></span></p>
    <?php foreach ($weibolist AS $w): ?>
        <div style="padding:4px 8px; border-bottom: 1px dotted #DBE7F4; color: #333; line-height: 1.6em">
            <a href="http://api.t.sina.com.cn/<?= $w['uid'] ?>/statuses/<?= $w['mid'] ?>" style="color:#F5A595" target="_blank"><?= Date::ueTime($w['created_at']); ?> </a><span style="color:#81AAC6">: </span><?= Kohana_Text::auto_link($w['text']) ?>
        </div>
    <?php endforeach; ?>
    <?php if (count($weibo_comments) > 0): ?>
        <p style="margin:5px 0 0 0;height: 24px;line-height: 24px;border-bottom:0px solid #E7F1FD;padding-left: 2px"><a href="/news" style="font-size: 13px;font-weight: bold">最新评论</a></span></p>
        <div id="marquee3" style="height:100px; overflow: hidden;">
            <div id="weibo_comments">
                <ul>
                    <?php foreach ($weibo_comments AS $c): ?>
                        <li style="border-bottom: 1px dotted #DBE7F4; color: #333;height: 50px;">
                            <div style="float:left;width:35px;margin-top: 5px"> <img src="<?= $c['profile_image_url'] ?>" style="border-width: 0;vertical-align: middle;-webkit-border-radius: 5px;border-radius: 5px;width:40px"></div>
                            <div style="float:right;width:365px;margin-top: 5px"><a href="http://api.t.sina.com.cn/2173025362/statuses/<?= $c['weibo_id'] ?>" style="color:#81AAC6" target="_blank"><?= $c['cmt_name'] ?> </a><span style="color:#81AAC6">: </span><?= Text::limit_chars($c['text'], 50, '...') ?></div>
                            <div class="clear"></div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <script type="text/javascript">
            readyScript.news_special = function() {
                $("#weibo_comments ul").RollTitle({line: 1, speed: 500, timespan: 1500});
            };
        </script>
    <?php endif; ?>
    <div style="margin-bottom:5px; text-align: right;color: #999"><a href="http://weibo.com/u/3334742070" target="_blank" style="color: #999">>> 官方微博</a></div>
</div>
<!--//end news-->