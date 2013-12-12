<!-- aa_home/newsDetail:_body -->
<style type="text/css">
/*comments*/
#comments_list .one_cmt_right{float:left; margin-left:8px; width:580px; text-align: left;}
#comment_form_box .comment_form{float:left; width:580px;}
#cmt_content{width:600px; height:70px}
</style>
<div id="main_left">
    <div style="font-size: 16px; font-weight: bold; text-align: center; color: #325F98"><?= str_replace('——', '<br>——', $news['title']) ?></div>
    <div style=" margin: 10px;color:#999; text-align: center">发布：<?= $news['create_at']; ?>&nbsp;&nbsp;<?= $news['author_name'] ? '作者：' . $news['author_name'] : ''; ?>&nbsp;&nbsp;点击：<?= $news['hit'] ?></div>
    <div class="dottle_line"></div>
    <div  style="padding:20px; line-height: 1.8em; font-size: 14px;color:#444" >
        <?php if (!$news['is_release']): ?>
            <div class="notice">该新闻尚未发布，只有管理员跟发布人可见。</div>
        <?php endif; ?>

        <?php if ($news['is_draft']): ?>
            <div class="notice">该新闻为预览效果，目前还是草稿，别人无法浏览。</div>
        <?php endif; ?>

<?php $news['content']=str_replace('float:none', '', $news['content']); ?>
<?= $news['content']; ?>
    </div>

    <?php if ($news['is_comment']): ?>
        <p class="comments_title">评论</p>
        <div style="padding:10px">
            <!--回复及评论 -->
            <?= View::factory('inc/comment/newform', array('params' => array('news_id' => $news['id']))) ?>
            <!--//回复及评论 -->
        </div>
    <?php endif; ?>
</div>

<div id="main_right">
    <div class="con_title">
        <span class="title_name">相关新闻</span>
    </div>
    <div class="aa_block">
        <?php if (count($relate) > 0): ?>
            <ul class="aa_conlist">
                <?php foreach ($relate as $r): ?>
                    <li><a href="<?= URL::site('aa_home/newsDetail?id=' . $_ID . '&nid=' . $r['id']) ?>" ><?= Text::limit_chars($r['title'], 13, '..') ?></a></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <span class="nodata">暂无相关新闻</span>
        <?php endif; ?>
    </div>
</div>
<div class="clear"></div>