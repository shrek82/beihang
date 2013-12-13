<!-- admin_news/form:_body -->
<?php
$action = 'news/create';
if ($news) {
    $action = 'news/update';
}
?>

<form action="<?= URL::site($action) ?>" id="news_form" method="post"  >
    <table class="admin_table" border="0" cellpadding="0" cellspacing="1" width="100%">
        <tr>
            <td colspan="2" class="td_title"><?= $news ? '编辑新闻' : '添加新闻'; ?></td>
        </tr>

        <tr>
            <td class="field">新闻分类：</td>
            <td>
                <?php if (isset($aa_news_category['Aa']['id']) AND $aa_news_category['Aa']['id']>0): ?>
                    <?= $aa_news_category['Aa']['name'] ?> > <?= $aa_news_category['name'] ?>
                    <input type="hidden" value="<?= $news['category_id'] ?>" name="category_id">
                <?php else: ?>
                    <select name="category_id">
                        <?php foreach ($news_category as $c): ?>
                            <option value="<?= $c['id'] ?>"  <?= $news['news_category'] === $c['id'] ? 'selected' : '' ?>><?= $c['name'] ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>

            </td>
        </tr>

        <tr>
            <td class="field">所属专题：</td>
            <td>
                <select name="special_id">
                    <option>无</option>
                    <?php foreach ($news_special as $s): ?>
                        <option value="<?= $s['id'] ?>"  <?= $news['special_id'] === $s['id'] ? 'selected' : '' ?>><?= $s['name'] ?></option>
                    <?php endforeach; ?>
            </td>
        </tr>
        <tr>
            <td class="field">完整标题：</td>
            <td><input type="text" name="title" value="<?= $news['title'] ?>"  style="width:650px" class="input_text" />&nbsp;<span style="color:#999">完整的新闻标题</span></td>
        </tr>
        <tr>
            <td class="field">短标题：</td>
            <td><input type="text" name="short_title" value="<?= $news['short_title'] ?>"  style="width:650px" class="input_text" />&nbsp;<span style="color:#999">用于显示在屏幕较小的手机端，不超过15个字</span></td>
        </tr>
        <tr >
            <td class="field">新闻概述：</td>
            <td><textarea name="intro" class="input_text" style="width:650px;height:60px"><?= $news['intro'] ?></textarea></td>
        </tr>

        <tr>
            <td colspan="2" style="padding:10px 20px;"  valign="top">
                <div style="height: 380px;">
<textarea id="content" name="content" style="width:99%;height:320px"><?= @$news['content'] ?></textarea>
                </div>
                <p style="margin:10px 0">
                    说明：按回车(Enter)为添加段落，段落开始位置自动增加标准缩进，请勿再次添加空格，按Shift+Enter为换行。
                </p>
            </td>
        </tr>

        <tr>
            <td style="text-align: center">焦点新闻</td>
            <td>
                <input type="hidden" name="img_path" id="filepath" value="<?= $news['img_path'] ?>" />
                <iframe  id="upfileframe" name="upfileframe" scrolling="no" style="width:500px; height:30px; display:inline" frameborder="0" src="<?= URL::site('upload/frameimg?return_file_size=original') ?>"></iframe>
                <div id="uploading" style="display:none; color:#3993E0;width:600px; height:30px;"><img src="/static/images/loading4.gif"  hspace="4" align="absmiddle"  />正在上传中，请稍候...</div>
            </td>
        </tr>

        <tr>
            <td class="field">新闻作者：</td>
            <td><input type="text" name="author_name" value="<?= $news['author_name'] ?>"  style="width:450px" class="input_text" /></td>
        </tr>
        <tr>
            <td class="field">关键字：</td>
            <td><input type="text" name="tag" value="<?= Model_News::tagNames(@$news['id']) ?>"  style="width:450px" class="input_text" /><span style="color:#999"> 多个请以半角空格隔开</span></td>
        </tr>
        <tr>
            <td class="field">跳转地址：</td>
            <td><input type="text" name="redirect" value="<?= $news['redirect'] ?>"  style="width:450px" class="input_text" /><span style="color:#999"> 如需跳转至其他网页，请填写URL地址</span></td>
        </tr>
        <tr>
            <td class="field">发布时间：</td>
            <td><input type="text" name="create_at" value="<?= $news['create_at'] ? $news['create_at'] : date('Y-m-d H:i:s') ?>"  style="width:450px" class="input_text" /><span style="color:#999"> </span></td>
        </tr>
        <tr>
            <td class="field">同时发布到：</td>
            <td>
                <input type="checkbox" name="is_people_news" id="is_people_news" value="1" style="vertical-align:middle;" > <label for="is_people_news">求是群芳</label>
            </td>
        </tr>
        <tr>
            <td class="field">新闻相关属性：</td>
            <td>
                <input type="checkbox" name="is_release" id="is_release" value="1" style="vertical-align:middle;" <?= !$news || $news['is_release'] ? 'checked' : '' ?>> <label for="is_release">通过审核</label>
                <input type="checkbox" name="is_focus" value="1" style="vertical-align:middle;" id="is_focus"  <?= $news['is_focus'] ? 'checked' : '' ?>> <label for="is_focus">首页焦点新闻</label>
                <input type="checkbox" name="is_top" value="1" style="vertical-align:middle;" id="is_top"  <?= $news['is_top'] ? 'checked' : '' ?>> <label for="is_top">头条新闻(大标题新闻)</label>
                <input type="checkbox" name="is_comment" id="is_comment" value="1" style="vertical-align:middle;" <?= !$news || $news['is_comment'] ? 'checked' : '' ?>> <label for="is_comment">开放评论</label>
            </td>
        </tr>



        <tr>
            <td colspan="2" class="td_title">温馨提示</td>
        </tr>

        <tr>
            <td colspan="2" style="padding-left:20px">1、首页图片，请上传：663*185px 规格大小图片 <br>2、头条新闻缩略图，将自动使用正文中上传的第一张图片，请勿直接从其他地方拷贝新闻图片</td>
        </tr>

        <tr>
            <td style="padding:20px; text-align: center" colspan="2" >

                <input type="hidden" name="news_id" value="<?= @$news['id'] ?>" />
                <input type="hidden" name="user_id" value="<?= @$news['user_id'] ? $news['user_id'] : $_UID ?>" />
                <input type="hidden" id="is_draft" name="is_draft" value="0" />

                <?php if ($news): ?>
                    <input type="button" id="submit_button" value="保存修改" name="button" class="button_blue" onclick="post_news()" />
                <?php else: ?>
                    <input type="button" id="submit_button" value="立即发布" name="button" class="button_blue" onclick="post_news()" />
                <?php endif; ?>

                <input type="button" value="取消" onclick="window.history.back()" class="button_gray">
                <input type="hidden" value="sys_admin" name="create_from">
            </td>
        </tr>

    </table><br>
    <?php if ($err): ?>
        <div class="notice"><?= $err; ?></div>
    <?php endif; ?>
</form>
<?php if ($news['img_path']): ?>
    <div style="padding:20px">
        首页图片：<br>
        <img src="/<?= $news['img_path'] ?>" style="margin-top:10px">
    </div>

<?php endif; ?>

<?=
View::ueditor('content', array(
    'toolbars' => Kohana::config('ueditor.common'),
    'minFrameHeight' => 450,
    'autoHeightEnabled' => 'false',
    'enterTag'=>'p',
    'initialStyle' => '"body{font-size:14px;} p{text-indent:28px;margin-bottom:15px}"'
));
?>

<script type="text/javascript">
    function post_news(){
        if(!ueditor.hasContents()){ueditor.setContent('');}
        ueditor.sync();
        var form = new ajaxForm('news_form', {
            textSending: '发送中',
            textError: '发布失败',
            loading:true,
            textSuccess: '发布成功',
            callback:function(id){
                window.location.href='/admin_news/index';
            }});
        form.send();
    }

    function save_news(is_draft){
        if(!ueditor.hasContents()){ueditor.setContent('');}
        ueditor.sync();
        if($defined(is_draft)){
            news_form.setOptions({textSuccess:'在'+(new Date()).fmt("hh:mm:ss")+'保存为草稿'})
            $('is_draft').set('value', 1);
            news_form.setOptions({btnSubmit:'save_as_draft'});
        }
        news_form.send();
    }
</script>


