<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?= $u['title'] ?></title>
        <?= View::factory('api/static_media') ?>
    </head>
    <body style="background: #EFEFEF;color: #333">
        <div class="user_box">
            <div style="width:50px; float: left;"><img src="http://zuaa.zju.edu.cn<?= Model_User::avatar($u['user_id'], 48, $u['User']['sex']) ?>" style="width:40px; height: 40px;border-radius: 4px;"></div>
            <div style="width:240px; float: left; ">作者：<?= $u['User']['realname'] ?><br>专业：<?= $u['User']['start_year'] ? $u['User']['start_year'] . '级' : ''; ?><?= $u['User']['speciality'] ? Text::limit_chars($u['User']['speciality'], '10') : '专业信息不详'; ?><br>所在地：<?= $u['User']['city'] ?></div>
                        </div>
                        <div style="padding-top:10px;font-size:18px;font-weight:bold; text-align:left;border-top:1px solid #fff; color: #c00"><?= $u['title'] ?></div>
                        <div style="padding:5px 2px;word-break:break-all; color: #333">
                            <?php if ($event): ?>
                                <div>
                                    <strong>时间：</strong><?php if (date('Y-m-d H:i', strtotime($event['start'])) == date('Y-m-d H:i', strtotime($event['start']))): ?><?= date('Y年m月d日', strtotime($event['start'])) ?>&nbsp;<?= date('H:i', strtotime($event['start'])) ?>~<?= date('H:i', strtotime($event['finish'])) ?><?php else: ?><?= date('Y年m月d日 H:i', strtotime($event['start'])) ?> 至 <?= date('m月d日 H:i', strtotime($event['finish'])) ?><?php endif; ?><br>
                                        <strong>所属：</strong><?php if ($u['aa_id'] <= 0): ?><?= $_CONFIG->base['orgname'] ?><?php else: ?><?= $u['Bbs']['Aa']['sname'] . '校友会' ?><?php endif; ?><?php if ($u['club_id']): ?> &raquo; <?= $u['Bbs']['Club']['name'] ?><?php endif; ?><br>
                                            <strong>地址：</strong><?= $event['address'] ?><br>
                                                <strong>人数：</strong><?= $event['sign_limit'] ? $event['sign_limit'] . '人' : '不限'; ?><br>
                                                    <?php if (!empty($tags)): ?><strong>标签：</strong><?php foreach ($tags as $name): ?><?= $name ?></a>&nbsp;&nbsp;<?php endforeach; ?><br> <?php endif; ?>
                                                        <strong>状态：</strong><?= Model_Event::status($event) ?>
                                                        <?php if ($event['votes'] > 0): ?><br><strong>体验：</strong><?php for ($i = 1; $i <= $event['score']; $i++): ?><img src="http://zuaa.zju.edu.cn/static/images/knewstuff.png" style="vertical-align: middle; ">&nbsp;<?php endfor; ?><?php for ($i = 1; $i <= 5 - $event['score']; $i++): ?><img src="http://zuaa.zju.edu.cn/static/images/knewstuff2.png" style="vertical-align: middle">&nbsp;<?php endfor; ?>&nbsp;&nbsp;<span style="color:#999"><?= $event['votes'] ?>人评分</span><?php endif; ?>
                                                                    </div><br>
                                                                    <?php endif; ?>
                                                                    <div style=" line-height: 1.6em"><?= $u['content']; ?></div>
                                                                    <?php if ($total_sign > 0): ?>
                                                                        <div style="padding:5px 0; ">
                                                                            <strong>已报名(<?= $total_sign ?>人)：</strong><?php foreach ($signs AS $s): ?><?=$s['is_anonymous']?'匿名':$s['User']['realname']; ?>&nbsp;<?php endforeach; ?></div>
                                                                    <?php endif; ?>
                                                                    </div>
                                                                    <?= View::factory('api/comment/list', array('search' => array('bbs_unit_id' => $u['id'], 'order' => 'DESC', 'limit' => 5))); ?>
                                                                    </body>
                                                                    </html>