<form id="user_password" action="<?= URL::site('admin_user/set/password?id='.$user['id']) ?>" method="post">
    <h5>用户<?= $user['realname'] ?>的登录密码修改为：</h5>
    <input type="password" name="password" placeholder="输入新密码" />
    <input type="submit" value="保存" style="display: none" />
</form>