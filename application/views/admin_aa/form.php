<form action="/admin_aa/form?id=<?= $aa['id'] ?>" method="post" id="aa_form">
    <table class="admin_table" border="0" cellpadding="0" cellspacing="1" width="100%">
        <tr>
            <td colspan="2" class="td_title"><?= $aa ? '编辑' : '添加'; ?>校友会(学院)：</td>
        </tr>

        <tr>
            <td class="field" style="width:15%">校友会分类：</td>
            <td><input name="class" type="radio" value="地方校友会" <?= $aa['class'] == '地方校友会' ? 'checked' : ''; ?>> 地方校友会&nbsp;&nbsp;&nbsp;&nbsp;<input name="class" type="radio" value="学院" <?= $aa['class'] == '学院' ? 'checked' : ''; ?>>学院</td>
        </tr>

        <tr>
            <td class="field">所属地区：</td>
            <td>
                <select name="group" ">
                    <option value="">----</option>
                    <?php foreach ($aa_group as $g): ?>
                        <option value="<?= $g['id'] ?>" <?= $g['id'] == $aa['group'] ? 'selected' : ''; ?>><?= $g['name'] ?></option>
                    <?php endforeach; ?>
                </select>

                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#999">学院可不选择</span></td>
        </tr>

        <tr>
            <td class="field">校友会(学院)全称：</td>
            <td><input type="text" name="name" value="<?= $aa['name'] ?>"  style="width:500px" class="input_text" />&nbsp;&nbsp;<span style="color:#999">(如：纽约校友会)</span></td>
        </tr>


        <tr>
            <td class="field">所在省份或城市：</td>
            <td><input type="text" name="sname" value="<?= $aa['sname'] ?>"  style="width:500px" class="input_text" />&nbsp;&nbsp;<span style="color:#999">(如：纽约，学院校友会直接填写学院名称)</span></td>
        </tr>

        <tr>
            <td class="field">成立时间：</td>
            <td><input type="text" name="found_at" value="<?= $aa['found_at'] ? $aa['found_at'] : date('Y-m-d'); ?>"  style="width:500px" class="input_text" />&nbsp;&nbsp;<span style="color:#999">(如：2011-05-15)</span></td>
        </tr>

        <tr>
            <td class="field">联系人：</td>
            <td><input type="text" name="contacts" value="<?= $aa['contacts'] ?>"  style="width:500px" class="input_text" />&nbsp;&nbsp;<span style="color:#999"></span></td>
        </tr>

        <tr>
            <td class="field">联系电话：</td>
            <td><input type="text" name="tel" value="<?= $aa['tel'] ?>"  style="width:500px" class="input_text" />&nbsp;&nbsp;<span style="color:#999"></span></td>
        </tr>

        <tr>
            <td class="field">邮件地址：</td>
            <td><input type="text" name="email" value="<?= $aa['email'] ?>"  style="width:500px" class="input_text" />&nbsp;&nbsp;<span style="color:#999"></span></td>
        </tr>

        <tr>
            <td class="field">通讯地址：</td>
            <td><input type="text" name="address" value="<?= $aa['address'] ?>"  style="width:500px" class="input_text" />&nbsp;&nbsp;<span style="color:#999"></span></td>
        </tr>

        <tr>
            <td class="field">管理员帐号：</td>
            <td><input type="hidden" name="old_chairman" value="<?= $manager ? $manager['User']['account'] : ''; ?>"><input type="text" name="chairman" value="<?= $manager ? $manager['User']['account'] : ''; ?>"  style="width:500px" class="input_text" />&nbsp;&nbsp;<span style="color:#999">(如：donghua154@163.com，填写1位，更多管理员由该校友指定)</span></td>
        </tr>

        <tr>
            <td class="field">校友会排序：</td>
            <td ><input type="text" name="order_num" value="<?= $aa['order_num'] ?>"  style="width:500px" class="input_text"/>&nbsp;&nbsp;<span style="color:#999">(填写数字，值越小越靠前,可不填)</span></td>
        </tr>

        <tr>
            <td colspan="2" style="padding:10px 20px;height:300px" valign="top"><textarea id="content" name="intro" style="width:99%;height:240px"><?= $aa['intro'] ? $aa['intro'] : '暂无介绍'; ?></textarea></td>
        </tr>

        <tr>
            <td class="field">学院专业关键词：</td>
            <td><input type="text" name="institute_speciality_key" value="<?= $aa['institute_speciality_key'] ?>"  style="width:700px" class="input_text" /><br>
                <span style="">说明：校友注册时，如所填专业关键词包含于以上关键词，则自动推荐加入本学院校友会，地方校友会可不填。最多225字符。多个以空格隔开。</span>
            </td>
        </tr>

        <tr>
            <td class="center" style="padding:20px;" colspan="2">
<input type="button" id="submit_button"  value="<?=$aa?'保存修改':'确定添加'?>" name="button" class="button_blue"  onclick="save()"/>
<input type="button" value="回首页" onclick="window.location.href='/admin_aa/index'" class="button_gray">

            </td>
        </tr>

    </table><br>

    <input type="hidden" name="id" value="<?= $aa['id'] ?>" />

    <?php if ($err): ?>
        <div class="notice"><?= $err; ?></div>
    <?php endif; ?>
    <?=
    View::ueditor('content', array(
        'toolbars' => Kohana::config('ueditor.common'),
        'minFrameHeight' => 400,
        'autoHeightEnabled' => 'false',
    ));
    ?>

    <script type="text/javascript">
        function save(){
            new ajaxForm('aa_form',{textSending: '发送中',textError: '重试',textSuccess: '设置成功',callback:function(id){
                    window.location.href='/admin_aa/index';
                }}).send();
        }
    </script>
