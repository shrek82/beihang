<form id="user_memo" action="<?= URL::site('admin_user/set/memo?id='.$user['id']) ?>" method="post">
    <h5>用户<?= $user['realname'] ?>的备注：</h5>
    <textarea name="memo" style="width:90%; height: 100px;"><?= $user['memo'] ?></textarea>
    <input type="submit" value="保存" style="display: none" />
</form>