<?php if (count($data) == 0): ?>
    <div style="color:#999">最近没有更新任何信息。</div>
<?php else: ?>
    <ul id="syn_ul">
        <?php foreach ($data as $time => $item): ?>

            <?php foreach ($item as $type => $rows): ?>

                <?php if ($type == 'bbs'): // 论坛 ?>
                    <?php foreach ($rows as $row): ?>
                        <li class="syn_unit">
                            <a href="<?= URL::site('user_home?id=' . $row['user_id']) ?>" target="_blank"><?= $row['realname'] ?></a><?= Date::span_str(strtotime($row['create_at'])) ?>前
                            <a href="<?= URL::site('bbs/view' . $row['type'] . '?id=' . $row['id']) ?>"><?= Text::limit_chars($row['title'], 32, '...'); ?></a>(<?= $row['reply_num'] ?>/<?= $row['hit'] ?>)
                        </li>
                        <li class="syn_line"></li>
                    <?php endforeach; ?>
                <?php endif; ?>

                <?php if ($type == 'event_pub'): // 活动发起 ?>
                    <?php foreach ($rows as $row): ?>
                        <li class="syn_event">
                            <a href="<?= URL::site('user_home?id=' . $row['user_id']) ?>"><?= $row['realname'] ?></a><?= Date::span_str(strtotime($row['publish_at'])) ?>前发起
                            <a href="<?= Db_Event::getLink($row['id'], $row['aa_id'], $row['club_id']) ?>" target="_blank"><?= Text::limit_chars($row['title'], 32, '...'); ?></a>
                            (<font style="color:#008000"><?= $row['sign_num'] ?></font>人报名)</li>
                        <li class="syn_line"></li>
                    <?php endforeach; ?>
                <?php endif; ?>

                <?php if ($type == 'event_sign'): // 活动发起 ?>
                    <?php foreach ($rows as $row): ?>
                        <li class="syn_event">
                            <a href="<?= URL::site('user_home?id=' . $row['user_id']) ?>"><?= $row['realname'] ?></a><?= Date::span_str(strtotime($row['sign_at'])) ?>前参加了
                            <a href="<?= Db_Event::getLink($row['event_id'], $row['Event']['aa_id'], $row['Event']['club_id']) ?>" target="_blank"><?= Text::limit_chars($row['Event']['title'], 32, '...'); ?></a>
                        </li>
                        <li class="syn_line"></li>
                    <?php endforeach; ?>
                <?php endif; ?>

                <?php if ($type == 'photo'): // 相片 ?>
                    <li class="syn_album">
                        <?php foreach ($rows as $row): ?>
                            <div class="photo_box">
                                <a href="<?= URL::site('album/picIndex?id=' . $row['Album']['id']) ?>" target="_blank"><img src="<?= URL::base() . $row['img_path']; ?>" /></a><br />
                                <a href="<?= URL::site('user_home?id=' . $row['user_id']) ?>"><?= $row['realname'] ?></a>
                                (<?= Date::span_str(strtotime($row['upload_at'])) ?>前上传)
                            </div>
                        <?php endforeach; ?>
                    </li>
                <?php endif; ?>

                <?php if ($type == 'weibo'): // 新鲜事 ?>
                    <?php foreach ($rows as $row): ?>
                        <li class="syn_bubble" style="line-height: 1.6em">
                            <a href="<?= URL::site('user_home?id=' . $row['user_id']) ?>"><?= $row['realname'] ?></a><?= Date::span_str(strtotime($row['post_at'])) ?>前说道 <a href="/weibo/content?id=<?=$row['aa_id']?>&wid=<?=$row['id']?>" target="_blank"  title="点击查看详情"><?=$row['content']?></a>
                        </li>
                        <li class="syn_line"></li>
                    <?php endforeach; ?>
                <?php endif; ?>

                <?php if ($type == 'invite_register'): // 相片 ?>
                    <?php foreach ($rows as $row): ?>
                        <li class="syn_event">
                            <a href="<?= URL::site('user_home?id=' . $row['user_id']) ?>"><?= $row['realname'] ?></a><?= Date::span_str(strtotime($row['accept_date'])) ?>前邀请
                            <a href="<?= URL::site('user_home?id=' . $row['receiver_user_id']) ?>"><?= $row['receiver_name'] ?></a> 加入了北航校友网
                        </li>
                        <li class="syn_line"></li>
                    <?php endforeach; ?>
                <?php endif; ?>

                <div class="clear"></div>

            <?php endforeach; ?>

        <?php endforeach; ?>
    </ul>
<?php endif; ?>