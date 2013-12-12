<?php
//从调取页指定参数
$data = DB_Comment::get($search);
$comments = $data['comments'];
$floor = $data['floor'];
?>
<?php if (count($comments) > 0): ?>
    <div style="margin:5px 0; font-weight: bold; color: #c00">最新回复：</div>
    <div style="border-top:1px solid #DAD8D8;border-bottom:1px solid #fff"></div>
    <div id="comment_list">
        <?php foreach ($comments AS $c): ?>
            <div class="cmt_box">
                <div class="author_box">
                    <div class="author"><?= $floor['floor_' . $c['id']] ?>楼 <?= $c['realname'] ?>&nbsp;<?= $c['start_year'] ? $c['start_year'] . '级' : ''; ?><?= Text::limit_chars($c['speciality'], 6, '...') ?></div>
                    <div class="post_date"><?= Date::ueTime($c['post_at']); ?></div>
                </div>
                <div style="clear: both;word-break:break-all;padding-bottom: 15px"><?= Emotion::autoToUrl(Common_Global::mobileText($c['content']), true); ?></div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div style='color: #999; text-align: center;border:2px dotted #EAE9E9;-webkit-border-radius: 12px;border-radius: 12px;margin: 15px 0;padding: 10px'>
        还没有评论，来做个沙发吧？
    </div>
<?php endif; ?>

<?php if (!$_SETTING['close_bbs_comment'] AND !$_SETTING['close_other_comment']): ?>
    <form id="comment_form">
        <fieldset>
            <?php if (isset($params)): ?><?php foreach ($params as $key => $val): ?>
                    <input type="hidden" name="<?= $key ?>" value="<?= $val ?>" />
                <?php endforeach; ?>
            <?php endif; ?>
            <h5><span style="vertical-align: middle"><img src="/static/app_imag/zuaa/ico_last_comment@2x.png" style="width:15px;height:15px"> </span>发表评论</h5>
            <textarea style="width:100%;height:80px;padding:2px 4px;line-height: 1.6em" name="content" id="comment_content"></textarea>
            <div style="text-align:left">
                <?php if ($_UID): ?>
                    <button type="button" class="btn btn-success" id="comment_submit_button" onclick="zuaa.postComment()">&nbsp;&nbsp;发布&nbsp;&nbsp;</button>
                <?php else: ?>
                    <a type="button" class="btn" href="/mobile/login?<?= $_AIDSTR ?>" >登录后评论</a>
                <?php endif; ?>
            </div>
        </fieldset>
    </form>
<?php endif; ?>