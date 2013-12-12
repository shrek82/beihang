<!-- aa_admin_news/form:_body -->
<div id="admin950">
    <?php
    $action = 'news/create';
    if ($news) {
        $action = 'news/update';
    }
    ?>
    <!-- news/form:_body -->
    <form action="<?= URL::site($action) ?>" id="news_form" method="post">
        <table style="width:100%">
            <tr>
                <td width="80">新闻投稿至</td>
                <td>
                    <select name="category_id" id="category">
                        <option value="">请选择分类</option>
                        <?php foreach ($category as $c): ?>
                            <option value="<?= $c['id'] ?>"><?= $c['name'] ?></option>
                        <?php endforeach; ?>
                    </select>

                    <script type="text/javascript">
                        var category=document.getElementById('category');
<?php if (@$news['news_category_id']): ?>
        category.value="<?= @$news['news_category_id'] ?>";
<?php endif; ?>
                    </script>
                </td>
            </tr>
            <tr>
                <td>完整标题</td>
                <td>
                    <input type="text" name="title" value="<?= @$news['title'] ?>" id="news_title" style="width:500px;"  class="input_text"/>
                </td>
            </tr>
            <tr>
                <td>短标题</td>
                <td>
                    <input type="text" name="short_title" value="<?= @$news['short_title'] ?>" id="news_title" style="width:500px;"  class="input_text"/>&nbsp;<span style="color:#999">显示在屏幕较小的终端，不超过15个字</span>
                </td>
            </tr>


            <tr>
                <td>新闻概述</td>
                <td><textarea name="intro" class="input_text" style="width:500px;height:50px"><?= $news['intro'] ?></textarea></td>
            </tr>
            <tr>
                <td colspan="2">
                    <textarea id="content" name="content" style="width:800px;height:350px"><?= @$news['content'] ?></textarea>
                </td>
            </tr>
            <tr>
                <td>开发评论</td>
                <td>
                    <input type="radio" name="is_comment" id="is_comment" value="1" style="vertical-align:middle;" <?= !$news || $news['is_comment'] ? 'checked' : '' ?>> <label for="is_comment">允许评论</label>
                    <input type="radio" name="is_comment" id="is_comment" value="0" style="vertical-align:middle;" <?= $news['is_comment'] == '0' ? 'checked' : '' ?>> <label for="is_comment">禁止评论</label>
                </td>
            </tr>
            <tr>
                <td>发布日期</td>
                <td>
                    <input type="text" name="create_at" size="30" value="<?= @$news['create_at'] ? $news['create_at'] : date('Y-m-d H:i:s') ?>" class="input_text"/>
                </td>
            </tr>
            <tr>
                <td>新闻作者</td>
                <td>
                    <input type="text" name="author_name" size="30" value="<?= @$news['author_name'] ? $news['author_name'] : $_SESS->get('realname') ?>" class="input_text"/>
                </td>
            </tr>
            <tr>
                <td>关键字</td>
                <td>
                    <input size="60" type="text" name="tag" value="<?= Model_News::tagNames(@$news['id']) ?>" class="input_text"/>
                    <small>使用半角英文空格分隔如“<?=$_CONFIG->base['university_name']?> 校友会”</small>
                </td>
            </tr>
            <tr>
                <td></td>
                <td style="height:50px"><input type="button" id="submit_button" onclick="save_news()" value="发布新闻"  class="button_blue"/></td>
            </tr>
        </table>
        <input type="hidden" name="news_id" value="<?= @$news['id'] ?>" />
        <input type="hidden" name="user_id" value="<?= @$news['user_id'] ? $news['user_id'] : $_UID ?>" />
        <input type="hidden" id="is_draft" name="is_draft" value="0" />
        <input type="hidden" id="is_top" name="is_top" value="<?= @$news['is_top'] ?>">
        <input type="hidden" id="is_release" name="is_release" value="<?= @$news['is_release'] ? $news['is_release'] : 1 ?>">
    </form>

    <?=
    View::ueditor('content', array(
        'toolbars' => Kohana::config('ueditor.common'),
        'autoHeightEnabled' => 'false',
    ));
    ?>

    <script type="text/javascript">
        function save_news(is_draft){
            if(!ueditor.hasContents()){ueditor.setContent('');}
            ueditor.sync();
            new ajaxForm('news_form', {
                textSending: '发送中',
                textError: '发布失败',
                loading:true,
                textSuccess: '发布成功',
                callback:function(id){
                    window.location.href='/aa_home/newsDetail?id=<?= $_ID ?>&nid='+id;
                }}).send();
        }

        //插入html到编辑器
        function insertHtml(id, html) {
            ueditor.setContent(html);
        }
    </script>
</div>
