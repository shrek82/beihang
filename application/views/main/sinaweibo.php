<div id="news" >
    <h1 style="display: none;"><a href="#" title="<?= $news_special['name'] ?>"><?= Text::limit_chars($news_special['name'], 20, '..') ?></a></h1>

    <div style="margin-bottom:0px; background: #F2FAFE; padding: 10px 2px;font-size: 12px">
        <?php if (isset($special_album['img_path'])): ?>
            <a href="/album/picIndex?id=<?= $special_album['id'] ?>" title="更多图片报道" target="_blank"><img src="/<?= str_replace('resize/', 'bmiddle/', $special_album['img_path']) ?>" border="0" style="height:150px; margin: 0px 10px 0 0" align="left"/></a>
        <?php endif; ?>
        <?= $news_special['intro'] ?>
        <div class="clear"> </div>
    </div>
    <p style="margin:2px;color:#0D5194;font-size: 12px;padding:2px 0; font-weight: bold"><?= $news_special['name'] ?></p>
    <?php foreach ($weibolist AS $w): ?>
        <div style="padding:4px 8px; border-bottom: 1px dotted #DBE7F4; color: #333; line-height: 1.6em">
            <a href="http://api.t.sina.com.cn/<?= $w['uid'] ?>/statuses/<?= $w['mid'] ?>" style="color:#81AAC6" target="_blank"><?= Date::ueTime($w['created_at']); ?> </a><span style="color:#81AAC6">: </span><?= Kohana_Text::auto_link($w['text']) ?>
        </div>
    <?php endforeach; ?>
    <?php if (count($weibo_comments) > 0): ?>
        <p style="margin:2px 0;color:#0D5194;font-size: 12px;padding:2px 0; font-weight: bold">大家都在说：</p>
        <div id="marquee3" style="height:180px; overflow: hidden;">
            <div id="weibo_comments">
                <?php foreach ($weibo_comments AS $c): ?>
                    <div style="padding:8px; border-bottom: 1px dotted #DBE7F4; color: #333;height: 45px;">
                        <div style="float:left;width:35px"><img src="<?= $c['profile_image_url'] ?>" style="width:40px;height:40px"/></div>
                        <div style="float:right;width:350px"><a href="http://api.t.sina.com.cn/2173025362/statuses/<?= $c['weibo_id'] ?>" style="color:#81AAC6" target="_blank"><?= $c['cmt_name'] ?> </a><span style="color:#81AAC6">: </span><?= Text::limit_chars($c['text'], 50, '...') ?></div>
                        <div class="clear"></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
    <div style="margin: 10px; text-align: right"><span class="middle"><img src="/static/ico/logo/t_sina.gif" /></span><a href="http://e.weibo.com/zjuaa" target="_blank">立即进入微博互动</a></div>
</div>