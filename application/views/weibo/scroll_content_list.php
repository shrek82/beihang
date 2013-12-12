<?php foreach ($weibo AS $w): ?>
    <li>
        <div style="float: left;width:60px;">
            <a href="/weibo?id=<?= $_ID ?>&uid=<?= $w['user_id'] ?>" title="Ta的所有话题"><img src="<?= Model_User::avatar($w['user_id'], 48, $w['sex']) ?>" style="-webkit-border-radius: 6px;-moz-border-radius: 6px;"></a>
        </div>
        <div style="float: left;width:600px;">
            <div style="color: #54677C;line-height: 1.6em"><a href="/weibo?id=<?= $_ID ?>&uid=<?= $w['user_id'] ?>" title="Ta的所有话题"><?= $w['realname'] ?></a>：<?= Common_Global::weibohtml($w['content'], $_ID, 80) ?></div>
            <div><a href="/weibo/content?id=<?= $_ID ?>&wid=<?= $w['id'] ?>"  title="查看全文" class="weibo_date_link"><?= Date::span_str(strtotime($w['post_at'])); ?>前</a></div>
        </div>
    </li>
<?php endforeach; ?>