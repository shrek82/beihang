<?php if (count($data) == 0): ?>
    <div style="color:#999">最近没有更新任何信息。</div>
<?php else: ?>
    <ul id="syn_ul">
        <?php $i = 1 ?>
        <?php foreach ($data as $key => $value): ?>
            <?php if ($value['syn_type'] == 'news'): ?>
                <?php $row = $value['data']; ?>
                <li class="syn_news <?= $i % 2 == 0 ? 'row2' : '' ?>">
                    <a href="<?= URL::site('news/view?id=' . $row['id']) ?>" target="_blank"><?= Text::limit_chars($row['title'], 30, '...'); ?></a>
                    <?= $row['is_pic'] ? '&nbsp;<font><img src="/static/ico/image_s.gif" title="包含图片" class="middle"></font>' : ''; ?>
                    <span><?= Date::span_str(strtotime($row['create_at'])) ?>前</span>
                </li>
                <li class="syn_line"></li>
            <?php endif; ?>

            <?php if ($value['syn_type'] == 'bbs'): ?>
                <?php $row = $value['data']; ?>
                <?php if (strtotime(date('Y-m-d H:i:s')) - strtotime($row['comment_at']) <= 86400 OR strtotime(date('Y-m-d H:i:s')) - strtotime($row['create_at']) <= 86400): ?>
                    <li class="syn_unit_new  <?= $i % 2 == 0 ? 'row2' : '' ?>">
                    <?php else: ?>
                    <li class="syn_unit <?= $i % 2 == 0 ? 'row2' : '' ?>">
                    <?php endif; ?>
                    <a href="<?= URL::site('bbs/view' . $row['type'] . '?id=' . $row['id']) ?>" target="_blank"><?= Text::limit_chars($row['title'], 27, '...'); ?></a>(<font style="color:#008000"><?= $row['reply_num'] ?></font>/<?= $row['hit'] ?>)
                    <?= $row['is_pic'] ? '&nbsp;<font><img src="/static/ico/image_s.gif" title="包含图片" class="middle"></font>' : ''; ?>
                    <?php if ($row['is_good']): ?><img src="/static/ico/recommend_1.gif"  border="0" class="middle" title="推荐帖子"/><?php endif; ?>
                    <span><?= Date::span_str(strtotime($row['create_at'])) ?>前</span>
                </li>
                <li class="syn_line"></li>
            <?php endif; ?>

            <?php if ($value['syn_type'] == 'event'): ?>
                <?php $row = $value['data']; ?>
                <li class="syn_event <?= $i % 2 == 0 ? 'row2' : '' ?>">
                    <a href="<?= Db_Event::getLink($row['id'], $row['aa_id'], $row['club_id']) ?>" target="_blank"><?= Text::limit_chars($row['title'], 24, '...'); ?>...活动</a>
                   <?php if($row['sign_num']>0):?> (已有<font style="color:#008000"><?= $row['sign_num'] ?></font>人报名)<?endif;?>
                    <span>
                        <?php if (isset($row['start'])): ?>
                            <?php if (time() >= strtotime($row['start']) AND time() <= strtotime($row['finish'])): ?>
                                进行中
                            <?php elseif (time() <= strtotime($row['start'])): ?>
                                <?= Date::span_str(strtotime($row['start'])) ?>后
                            <?php else: ?>
                                <?= Date::span_str(strtotime($row['start'])) ?>前
                            <?php endif; ?>
                        <?php else: ?>
                            <?= Date::span_str(strtotime($row['publish_at'])) ?>前</span>
                    <?php endif; ?>
                    </span>
                </li>
                <li class="syn_line"></li>
            <?php endif; ?>

            <?php if ($value['syn_type'] == 'album'): ?>
                <?php $row = $value['data']; ?>
                <li class="syn_album <?= $i % 2 == 0 ? 'row2' : '' ?>">
                    <?php foreach ($row as $pic): ?>
                        <div class="photo_box">
                            <a href="<?= URL::site('album/picView?id=' . $pic['id']) ?>" target="_blank" title="<?= $pic['album_name'] ?>"><img src="<?= URL::base() . $pic['img_path']; ?>" /></a><br />
                            (<?= Date::span_str(strtotime($pic['upload_at'])) ?>前上传)
                        </div>
                    <?php endforeach; ?>
                </li>
                <li class="syn_line"></li>
            <?php endif; ?>


            <?php $i = $i + 1; ?>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>