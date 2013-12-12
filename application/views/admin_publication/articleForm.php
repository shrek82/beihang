<form action="" method="post" enctype="multipart/form-data">
        <table class="admin_table" border="0" cellpadding="0" cellspacing="1" width="100%">
                <tr>
                        <td height="29" class="td_title" colspan="6"><b>文章管理</b></td>
                </tr>
                <tbody>
                        <tr>
                                <td class="field">所属刊号：</td>
                                <td><input type="text" name="pub_id" value="<?= $content['pub_id'] ?>"  style="width:300px" class="input_text" /></td>
                        </tr>
                        <tr>
                                <td class="field">所属栏目：</td>
                                <td><input type="text" name="col_id" value="<?= $content['col_id'] ?>"  style="width:300px" class="input_text" /></td>
                        </tr>
                        <tr>
                                <td class="field">文章排序：</td>
                                <td style="padding:15px"><input type="text" name="order_num" value="<?= $content['order_num'] ?>"  style="width:300px" class="input_text"/>&nbsp;&nbsp;<span style="color:#999"></span></td>
                        </tr>
                        <tr>
                                <td class="field">文章标题：</td>
                                <td><input type="text" name="title" value="<?= $content['title'] ?>"  style="width:500px" class="input_text" /></td>
                        </tr>

                        <tr>
                                <td class="field" valign="top" style="padding:5px">详细介绍： </td>
                                <td>
                                    
                            <div style="height:300px">
                                <textarea id="content" name="content" style="width:99%;height:240px"><?= @$content['content'] ?></textarea>
                            </div>
                                    
                             </td>
                        </tr>
                        <tr>
                                <td class="field">作者：</td>
                                <td style="pading:15px"><input type="text" name="author" value="<?= $content['author'] ?>"  style="width:300px" class="input_text"/>&nbsp;&nbsp;<span style="color:#999"></span></td>
                        </tr>
                        <tr>
                                <td class="field">页码：</td>
                                <td style="pading:15px"><input type="text" name="page" value="<?= $content['page'] ?>"  style="width:300px" class="input_text"/>&nbsp;&nbsp;<span style="color:#999"></span></td>
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
    'minFrameHeight' => 300,
    'autoHeightEnabled' => 'false',
    'elementPathEnabled' => 'false',
    'focus' => 'true',
));
?>