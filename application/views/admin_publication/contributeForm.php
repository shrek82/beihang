<form action="" method="post" >
        <table class="admin_table" border="0" cellpadding="0" cellspacing="1" width="100%">
                <tr>
                        <td height="29" class="td_title" colspan="6"><b>投稿预览</b></td>
                </tr>
                <tbody>
                        <tr>
                                <td class="field" width="15%">文章标题：</td>
                                <td><?= $content['title'] ?></td>
                        </tr>
                        <tr>
                                <td class="field">投稿时间：</td>
                                <td style="padding:0 15px"><?= $content['create_at'] ?></td>
                        </tr>

                        <tr>
                                <td colspan="2" style="padding:15px; line-height: 1.6em; background: #fff"><?= @$content['content'] ?></td>
                        </tr>

                        <tr>
                                <td class="field">回复并发送站内信通知：</td>
                                <td style="padding:15px">
                                        <textarea id="reply" name="reply" style="width:95%; height: 100px"><?= @$content['reply'] ?></textarea>
                                </td>
                        </tr>


                        <tr>
                                <td class="field"></td>
                                <td class="center" style="padding:20px">

                                        <?php if ($content): ?>
                                                <input type="submit" value="保存修改" name="button" class="button_blue" />
                                        <?php else: ?>
                                                <input type="submit" value="确定添加" name="button" class="button_blue"/>
                                        <?php endif; ?>

                                        <input type="button" value="取消" onclick="window.history.back()" class="button_gray">

                                </td>
                        </tr>


                </tbody>
        </table><br>

        <input type="hidden" name="id" value="<?= $content['id'] ?>" />

        <?php if ($err): ?>
                <div class="notice"><?= $err; ?></div>
        <?php endif; ?>
</form>
                                <?=View::ueditor('content', array(
                                    'toolbars' => Kohana::config('ueditor.common'),
                                    'minFrameHeight' => 350,
                                    'autoHeightEnabled' => 'false',
                                ));
                                ?>