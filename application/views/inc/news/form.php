<?php
$action = 'news/create';
if ($news) {
    $action = 'news/update';
}
?>
<!-- news/form:_body -->
<form action="<?= URL::site($action) ?>" id="news_form" method="post">
    <table>
        <tr>
            <td>新闻投稿至</td>
            <td><?= View::factory('inc/news/category', array('news_id' => @$news['id'], 'news_category_id' => @$news['news_category_id']));
?>
            </td>
        </tr>
        <tr>
            <td>作者</td>
            <td><input type="text" name="author_name" size="30" value="<?= @$news['author_name'] ? $news['author_name'] : $_SESS->get('realname'); ?>" class="input_text"/></td>
        </tr>
        <tr>
            <td>完整标题</td>
            <td>
                <input type="text" name="title" value="<?= @$news['title'] ?>" id="news_title" size="70"  class="input_text"/>
            </td>
        </tr>
        <tr>
            <td>短标题</td>
            <td>
                <input type="text" name="short_title" value="<?= @$news['short_title'] ?>" id="news_title" size="70"  class="input_text"/>&nbsp;<span style="color:#999">用于显示在屏幕较小屏幕，不超过15个字</span>
            </td>
        </tr>

    </table>
    <div style="width:99%;height:300px"><textarea id="content" name="content" style="width:99%;height:260px"><?= @$news['content'] ?></textarea></div>
    <div><label>开放评论</label>
        <input type="radio" name="is_comment" id="is_comment" value="1" style="vertical-align:middle;" <?= (!$news) || $news['is_comment'] == '1' ? 'checked' : '' ?>> <label for="is_comment">允许评论</label>
        <input type="radio" name="is_comment" id="is_comment" value="0" style="vertical-align:middle;" <?= $news['is_comment'] == '0' ? 'checked' : '' ?>> <label for="is_comment">禁止评论</label>
    </div>
    <div><label>关键字&nbsp;&nbsp;&nbsp;</label>
        <input size="60" type="text" name="tag" value="<?= Model_News::tagNames(@$news['id']) ?>" class="input_text"/>
        <small style="color: #999">使用半角英文空格分隔如“<?=$_CONFIG->base['alumni_name']?> 校友会”</small>
    </div>



    <div style="margin:20px 0">
        <input type="hidden" name="news_id" value="<?= @$news['id'] ?>" />
        <input type="hidden" name="user_id" value="<?= @$news['user_id'] ? $news['user_id'] : $_UID ?>" />
        <input type="hidden" id="is_draft" name="is_draft" value="0" />
        <input type="hidden" id="is_top" name="is_top" value="<?= @$news['is_top'] ?>">
        <input type="hidden" id="create_at" name="create_at" value="<?= @$news['create_at'] ? $news['create_at'] : date('Y-m-d H:i:s') ?>>">
        <input type="hidden" id="is_release" name="is_release" value="<?= @$news['is_release'] ?>">
        <input type="button" onclick="save_news()" value="提交"  class="button_blue" id="submit_button"/>
    </div>
</form>

<?=
View::ueditor('content', array(
    'toolbars' => Kohana::config('ueditor.common')
));
?>

<script type="text/javascript">
    function save_news(){
        if(!ueditor.hasContents()){ueditor.setContent('');}
        ueditor.sync();
        new ajaxForm('news_form',{textSending: '发送中',textError: '重试',textSuccess: '修改成功',callback:function(id){
                window.location.href='/news/view?id='+id;
            }}).send();
    }
</script>