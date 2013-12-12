<div class="g  one-whole">

    <article class="post">
        <h4 style="font-weight: bold"><?= $event_data['event']['title'] ?></h4>
        <blockquote>
            发起:<a href="#"><?= $event_data['organiger']['realname'] ?></a><br>
            所属:<?php if ($event_data['event']['aa_id'] == '-1'): ?><a href="#"><?= $_CONFIG->base['orgname'] ?></a><?php else: ?><a href="#" title="进入校友会主页"><?= $event_data['aa']['sname'] ?>校友会</a><?php endif; ?>
            <?php if ($event_data['event']['club_id']): ?>
                &raquo; <a href="#"><?= $event_data['club']['name'] ?></a>
            <?php endif; ?><br>
            地点:<?= $event_data['event']['address'] ?><br>
            时间:<?= Model_Event::SatusFinish($event_data['event']['start'], $event_data['event']['finish']); ?><br>
            状态:<?= Model_Event::status($event_data['event']); ?><br>
            人数:<?= $event_data['event']['sign_limit'] ? $event_data['event']['sign_limit'] . '人' : '不限'; ?> <?= $event_data['total_sign'] ? '已有' . $event_data['total_sign'] . '人报名' : ''; ?>
        </blockquote>
        <div style="margin:10px 0">

            <div id="signstart_box">
                <?php if (time() >= strtotime($event_data['event']['start']) AND time() <= strtotime($event_data['event']['finish'])): ?>
                    <button class="btn  btn-large btn-info disabled btn-block" disabled="true"><i class="icon-play  icon-white"></i> 活动进行中</button>
                <?php elseif (time() >= strtotime($event_data['event']['finish'])): ?>
                    <button class="btn btn-large disabled btn-block" disabled="true"><i class="icon-time"></i> 活动已结束</button>
                <?php elseif ($event_data['event']['is_suspend']): ?>
                    <button class="btn btn-large disabled btn-block" disabled="true"><i class="icon-minus-sign"></i> 活动暂停</button>
                <?php elseif ($event_data['event']['is_stop_sign']): ?>
                    <button class="btn btn-large disabled btn-block" disabled="true"><i class="icon-minus-sign"></i> 停止报名</button>
                <?php elseif ($event_data['event']['sign_limit']>0 AND $event_data['total_sign']>=$event_data['event']['sign_limit']): ?>
                    <button class="btn btn-large disabled btn-block" disabled="true"><i class="icon-minus-sign"></i> 名额已满</button>
                <?php elseif ($_UID): ?>
                    <? if (!$event_data['user_sign_status']['is_signed']): ?>
                        <button class="btn btn-large btn-success btn-block" onclick="zuaa.signEvent(<?= $event_data['event']['id'] ?>);" id="submit_button"><i class=" icon-user icon-white"></i> 立即报名 </button>
                    <?php else: ?>
                        <button class="btn btn-large btn-success btn-block disabled"  id="submit_button" onclick="zuaa.cancelSign(<?= $event_data['event']['id'] ?>);"><i class="icon-ok  icon-white"></i> 已经报名</button>
                    <?php endif; ?>
                <?php else: ?>
                    <a class="btn btn-large btn-block btn-success" href="/mobile/login?<?=$_AIDSTR?>" ><i class="icon-user  icon-white"></i> 登录后报名 </a>
                <?php endif; ?>
            </div>


        </div>
        <div style="word-wrap: break-word; word-break: break-all;overflow:hidden;"><?= $event_data['event']['content'] ?></div>

        <?php if (count($event_data['signs']) > 0): ?>
            <div style="padding:10px; background: #f3f3f3; color: #333;">
                <b>已报名(<?php if ($event_data['total_sign'] == count($event_data['signs'])): ?><?= $event_data['total_sign'] ?>人<?php else: ?><?= count($event_data['signs']) ?>条共计<?= $event_data['total_sign'] ?>人<?php endif; ?>)：</b>
                <?php foreach ($event_data['signs'] AS $s): ?><?= $s['is_anonymous'] ? '匿名' : $s['User']['realname']; ?>&nbsp;<?php endforeach; ?></div>
        <?php endif; ?>

    </article>

    <?= View::factory('mobile/commentlist', array('params' => $params, 'search' => array('event_id' => $event_data['event']['id'], 'order' => 'DESC', 'limit' => 5))); ?>

</div>
