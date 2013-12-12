<!-- user_news/form:_body -->
<div id="big_right">
        <div id="plugin_title">文章</div>
        <div class="tab_gray" id="user_topbar" style="margin-top:10px">
                <ul>
                        <li><a href="<?= URL::site('user_publication/index') ?>" style="width:50px">我的投稿</a></li>
                        <li><a href="<?= URL::site('user_publication/form') ?>" class="cur" style="width:50px">我要投稿</a></li>
                </ul>
        </div>
        <form action="" method="post" >
                <table border="0" width="98%" id="content_table">
                        <tbody>
                                <tr>
                                        <td class="field">文章标题：</td>
                                        <td><input type="text" name="title" value="<?= $pub['title'] ?>"  style="width:500px" class="input_text" /></td>
                                </tr>

                                <tr>
                                        <td colspan="2">文章内容：</td>
                                </tr>

                                <tr>
                                        <td colspan="2"><textarea id="content" name="content" ><?= @$pub['content'] ?></textarea></td>
                                </tr>
                                <tr>
                                        <td class="center" style="padding:5px 0; text-align: center" colspan="2">
                                                <?php if ($pub): ?>
                                                        <input type="submit" value="保存修改" name="button" class="button_blue" />
                                                <?php else: ?>
                                                        <input type="submit" value="确定添加" name="button" class="button_blue"/>
                                                <?php endif; ?>
                                                <input type="button" value="取消" onclick="window.history.back()" class="button_gray">
                                        </td>
                                </tr>
                        </tbody>
                </table><br>
                <input type="hidden" name="id" value="<?= $pub['id'] ?>" />
        </form>

        <br>
        <?php if ($err): ?>
                <div class="notice"><?= $err; ?></div>
        <?php endif; ?>
</div>

<?=View::ueditor('content', array(
    'toolbars' => Kohana::config('ueditor.common'),
    'minFrameHeight' => 350,
    'autoHeightEnabled' => 'false',
));
?>
