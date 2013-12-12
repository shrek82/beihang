<style type="text/css">
    #content_table td{ height: 24px}
    #content_table .field{ text-align: right; padding: 0px 4px; width: 80px}
</style>
<form action="" method="post" >
    <table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" >
        <tr>
            <td class="td_title" colspan="2"><b>
                    <?= $publication['id'] ? '修改期刊' : '新增期刊' ?>：
                </b></td>
            <td></td>
        </tr>
        <tr>
            <td class="field">刊物类型：</td>
            <td>
                <select name="type">
                    <?php foreach ($pub_type AS $key => $t): ?>
                        <option value="<?= $key ?>" <?= $publication['type'] == $key ? 'selected' : '' ?>><?= $t ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>

        <tr>
            <td class="field">刊物名称：</td>
            <td><input type="text" name="name"   style="width:300px" class="input_text"  value="<?= $publication['name'] ?>"/>&nbsp;&nbsp;<span style="color:#999">例如：<?= $_CONFIG->base['alumni_name'] ?></span></td>
        </tr>

        <tr>
            <td class="field">刊物期号：</td>
            <td ><input type="text" name="issue"   style="width:300px" class="input_text"  value="<?= $publication['issue'] ?>"/>&nbsp;&nbsp;<span style="color:#999">例如：2010年2</span></td>
        </tr>

        <tr>
            <td class="field">封面图片：</td>
            <td>
                <input type="hidden" name="cover" id="filepath" value="<?= $publication['cover'] ?>" />
                <iframe  id="upfileframe" name="upfileframe" scrolling="no" style="width:500px; height:30px; display:inline" frameborder="0" src="<?= URL::site('upload/frameimg?return_file_size=thumbnail&prefix_path=/') ?>"></iframe>
                <div id="uploading" style="display:none; color:#3993E0;width:600px; height:30px;"><img src="/static/images/loading4.gif"  hspace="4" align="absmiddle"  />正在上传中，请稍候...</div>
            </td>
        </tr>

        <?php if ($publication['id']): ?>
            <tr>
                <td class="field">封面图片：</td>
                <td >
                    <?php if ($publication['cover']): ?>
                        <a href="<?= $publication['cover'] ?>" target="_blank" style="color:green">封面已经上传</a>
                    <?php else: ?>
                        <span style="color:red">封面还未上传</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endif; ?>

        <tr>
            <td class="field">上传pdf：</td>
            <td>
                <input type="hidden" name="pdf" id="filepath2" value="<?= $publication['pdf'] ?>" />
                <iframe  id="upfileframe2" name="upfileframe2" scrolling="no" style="width:500px; height:30px; display:inline" frameborder="0" src="<?= URL::site('upload/frame?msg=pdf文件不大于50M') ?>"></iframe>
                <div id="uploading2" style="display:none; color:#3993E0;width:600px; height:30px;"><img src="/static/images/loading4.gif"  hspace="4" align="absmiddle"  />正在上传中，请稍候...</div>
            </td>
        </tr>

        <tr>
            <td class="field"></td>
            <td style="padding:20px 0">
                <?php if ($publication): ?>
                    <input type="submit" value="保存修改" name="button" class="button_blue" />
                <?php else: ?>
                    <input type="submit" value="确定添加" name="button" class="button_blue"/>
                <?php endif; ?>
                <input type="button" value="取消" onclick="window.history.back()" class="button_gray">
            </td>
        </tr>


    </table><br>

    <input type="hidden" name="id" value="" />

    <?php if ($err): ?>
        <div class="notice"><?= $err; ?></div>
    <?php endif; ?>
</form>