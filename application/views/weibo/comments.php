<div class="weibo_comment">
    <div class="cmt_top"></div>
    <div class="cmt_form">
        <form method="POST" action="/comment/post" id="comment_form_<?= $wid ?>">
            <input type="hidden" name="weibo_id" value="<?= $wid ?>">
            <textarea name="content" id="comment_textarea_<?= $wid ?>" class="comment_textarea"  placeholder="添加评论"  style="border-left-color: #C4C7BD;border-top-color: #C4C7BD;border-width: 1px"></textarea>
            <div style="margin-top:5px;height:30px" id="expressionBox_<?= $wid ?>">
                <p style="width:200px; float: left"><a href="javascript:;" onclick="open_expression('expressionBox_<?= $wid ?>','comment_textarea_<?= $wid ?>')" class="tool_ico_face">表情</a></p>
                <p style="width:330px; float: right; text-align:right"><input type="button" id="submit_button" value="评论" onclick="comment_post(<?= $wid ?>)" class="greenButton2" ></p>
            </div>
        </form>
    </div>

    <div class="cmt_list">
        <div id="cmt_list_<?= $wid ?>">
            <?php include 'comment_list.php'; ?>
        </div>
        <?php if (count($comments) > 0): ?>
            <p class="comment_dotted_line"></p>
            <div class="cmt_tool"><a href="javascript:;" onclick="getcomment(<?= $wid ?>)">关闭</a></div>
        <?php else: ?>
            <div class="nodata">暂时还没有评论</div>
        <?php endif; ?>

    </div>
    <div class="cmt_footer"></div>
</div>
