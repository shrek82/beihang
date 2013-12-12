<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>查看新鲜事</title>
        <?= View::factory('api/static_media') ?>
    </head>
    <body style="background: #EFEFEF;color: #333">
        <div class="user_box">
            <div style="width:50px; float: left;"><img src="http://zuaa.zju.edu.cn<?= Model_User::avatar($weibo['user_id'], 48, $weibo['User']['sex']) ?>" style="width:40px; height: 40px;border-radius: 4px;"></div>
            <div style="width:240px; float: left; ">作者：<?= $weibo['User']['realname'] ?><br>专业：<?= $weibo['User']['start_year'] ? $weibo['User']['start_year'] . '级' : ''; ?><?= $weibo['User']['speciality'] ? $weibo['User']['speciality'] : '专业信息未知'; ?><br>所在地：<?= $weibo['User']['city'] ?></div>
                        </div>
                        <div style="border-top:1px solid #FFF;padding:10px 2px;word-break:break-all; line-height: 1.6em">
                            <?= Emotion::autoToUrl(Common_Global::sinatext($weibo['content']), true); ?>
                        </div>
                        <div style="color:#B8B7B7;font-size: 12px;"><?= Date::ueTime($weibo['post_at']); ?>发布&nbsp;&nbsp;来自<span style="color:#9DB7C9"><?= $weibo['clients'] ? $weibo['clients'] : '网页版'; ?></span></div>
                        <?= View::factory('api/comment/list', array('search' => array('weibo_id' => $weibo['id'], 'order' => 'DESC', 'limit' => 15))); ?>
                        </body>
                        </html>