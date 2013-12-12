<form action="<?= URL::site('club_admin_member/set').URL::query() ?>" 
      id="member_set"
      method="post">
    <?php if( ! $member['chairman']): ?>
    <div><label><?= $member['User']['realname'] ?>的头衔</label><br />
        <input type="text" name="title" value="<?= $member['title'] ?>" />
    </div>
    <div>
        <input type="hidden" name="manager" value="0" />
        <input type="checkbox" id="is_manager" name="manager" value="1" <?= $member['manager'] == TRUE ? 'checked':'' ?> />
        <label for="is_manager">俱乐部管理员</label>
    </div>
    <div><input type="button" id="submit_button" onclick="new ajaxForm('member_set').send()" value="确认" /></div>
    <?php else: ?>
    此成员由校友会管理员授权，不能进行修改。
    <?php endif; ?>
</form>