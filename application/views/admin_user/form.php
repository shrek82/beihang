<!-- admin_user/form:_body -->
<form id="user_form"  action="<?= URL::site('admin_user/form?id=' . $user['id']) ?>" method="post"  >
    <table class="admin_table" border="0" cellpadding="0" cellspacing="1" width="100%">
        <tr>
            <td colspan="2" class="td_title">编辑资料</td>
        </tr>
        <tr>
            <td class="field" width="15%">姓名：</td>
            <td><?= $user['realname'] ?></td>
        </tr>
        <tr>
            <td class="field" width="15%">性别：</td>
            <td><input type="radio" name="sex" value="男" <?= $user['sex'] == '男' ? 'checked' : '' ?> /> 男
                <input type="radio" name="sex" value="女" <?= $user['sex'] == '女' ? 'checked' : '' ?> /> 女</td>
        </tr>
        <tr>
            <td class="field" width="15%">新密码：</td>
            <td><input name="new_password" value=""><span>不修改请留空</span></td>
        </tr>

        <tr>
            <td class="field" width="15%">身份：</td>
            <td>
                <select name="role" >
                    <?php foreach ($roles as $res): ?>
                        <option <?= $user['role'] == $res ? 'selected' : '' ?> value="<?= $res ?>"><?= $res ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>

        <tr>
            <td class="field" width="15%">生日：</td>
            <td><input name="birthday" value="<?= $user['birthday'] ?>"></td>
        </tr>

        <tr>
            <td class="field" width="15%">学号：</td>
            <td><input name="student_no" value="<?= $user['student_no'] ?>"></td>
        </tr>

        <tr>
            <td class="field" width="15%">档案编号：</td>
            <td><input name="file_no" value="<?= $user['file_no'] ?>"></td>
        </tr>

        <tr>
            <td class="field" width="15%">所在城市：</td>
            <td><input name="city" value="<?= $user['city'] ?>"></td>
        </tr>


        <tr>
            <td class="field" width="15%">固定电话：</td>
            <td><input name="tel" value="<?= $contact['tel'] ?>"></td>
        </tr>

        <tr>
            <td class="field" width="15%">移动电话：</td>
            <td><input name="mobile" value="<?= $contact['mobile'] ?>"></td>
        </tr>

        <tr>
            <td class="field" width="15%">QQ：</td>
            <td><input name="qq" value="<?= $contact['qq'] ?>"></td>
        </tr>

        <tr>
            <td class="field" width="15%">联系地址：</td>
            <td><input name="address" value="<?= $contact['address'] ?>"></td>
        </tr>

        <tr>
            <td class="field" width="15%">自我介绍：</td>
            <td><textarea name="memo" style="width:500px;height:60px"><?= $user['memo'] ?></textarea></td>
        </tr>

        <tr>
            <td style="padding:20px; text-align: center" colspan="2" >
                <input type="button" onclick="save()" value="保存修改" name="button" class="button_blue"  id="submit_button"/>
                <input type="button" value="取消" onclick="window.history.back()" class="button_gray">
            </td>
        </tr>

    </table><br>
    <?php if ($err): ?>
        <div class="notice"><?= $err; ?></div>
    <?php endif; ?>
</form>

<script type="text/javascript">
    function save(){
        new ajaxForm('user_form',{textSending: '发送中',textError: '重试',textSuccess: '修改成功',callback:function(id){
                window.location.href='/admin_user/index';
            }}).send();
    }
</script>