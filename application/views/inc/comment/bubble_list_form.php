    <div id="buttle_comments_<?= $row['id'] ?>" class="buttle_comments">
    <?php if (count($row['comments']) > 0): ?>
    <?php foreach ($row['comments'] as $c): ?>
        <div class="a_bubble_comment">
            <img src="<?= URL::base() ?>static/ico/q4.gif" />
            <a href="<?= URL::site('user_home?id=' . $c['user_id']) ?>"><?= $c['realname'] ?></a>&nbsp;&nbsp;<?= $c['content'] ?><span class="bubble_com_time">&nbsp;&nbsp;(<?= Date::span_str(strtotime($c['post_at'])) ?>前)</span></div>
    <?php endforeach; ?>
    <?php endif; ?>
    </div>

        <div class="bubble_com_form">
            <form name="bubble_form_<?= $row['id'] ?>" method="post" id="bubble_form_<?= $row['id'] ?>" style="margin:0;padding: 0" action="<?= URL::site('comment/post') ?>">
                <p id="b_face_<?= $row['id'] ?>" style="display:none">[表情]</p>
                <input type="hidden" name="bubble_id" value="<?= $row['id'] ?>" style="display:none;" />
                <textarea id="b_textarea_<?= $row['id'] ?>" name="content" class="input_text" style="margin:0px;width:98%;color:#666;padding:5px;height:15px;overflow-y:hidden" placeholder="添加评论" onFocus="bubble_onfoucs('<?= $row['id'] ?>')" onblur="bubble_onblur('<?= $row['id'] ?>')"  onkeydown='countChar("b_textarea_<?= $row['id'] ?>","b_counter_<?= $row['id'] ?>");' onkeyup='countChar("b_textarea_<?= $row['id'] ?>","b_counter_<?= $row['id'] ?>");'></textarea>
                <div id="b_button_<?= $row['id'] ?>" style="display:none; text-align:left; margin: 3px 0;height:30px">
                    <p style="float:left;"><input type="button" id="submit_button" value="发表"  onclick="bubble_comment_post('<?= $row['id'] ?>')" class="button_blue"  style="padding:2px 12px;margin: 0"></p>
                    <p style="float:right">可以输入<span id="b_counter_<?= $row['id'] ?>">140</span>字</p>
        </div>
    </form>
</div>