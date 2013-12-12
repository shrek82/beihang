<div class="g  one-whole  desk-six-tenths">
    <article class="post">
        <h4><?= $event['title'] ?></h4>
        <blockquote>地点:<?= $event['address'] ?><br>
时间:<?= date('Y年m月d日 H点i分', strtotime($event['start'])); ?><br>
参与:<a href="<?= URL::site('event/view?id=' . $event['id'] . '&tab=event_slist') ?>" title="点击浏览名单"><?= $event['sign_num'] ?></a>人
讨论:<a href="<?= URL::site('event/view?id=' . $event['id'] . '&tab=event_comment') ?>" title="点击浏览讨论"><?= $event['comments_num'] ?></a>条
        </blockquote>

        <div style="word-wrap: break-word; word-break: break-all;overflow:hidden;"><?= $event['content'] ?></div>
    </article>

    <div style='color: #999; text-align: center;border:2px dotted #EAE9E9;-webkit-border-radius: 12px;border-radius: 12px;margin: 15px 0;padding: 10px'>
评论功能稍候呈现。
    </div>
</div>
