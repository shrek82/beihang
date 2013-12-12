<?php
/**
  +-----------------------------------------------------------------
 * 名称：活动发布目标选择
 * 版本：1.0
 * 版权：www.usho.cn
 * 作者：zhaojiangang
 * 最后更新日期：12-10-11 下午4:14
  +-----------------------------------------------------------------
 */
$aa = Doctrine_Query::create()
        ->select('id, name,sname')
        ->from('Aa');
if ($_ROLE != '管理员') {
    $aa->whereIn('id', Model_User::aaIds($_UID));
}
$aa = $aa->orderBy('id ASC')->fetchArray();
?>

<select name="aa_id" id="aa_id" onchange="change_aa(this.value,0)">
    <option value="">--选择校友会--</option>
    <option value="0"  <?= $aa_id == 0 ? 'selected="selected"' : '' ?>>校友总会</option>
    <?php foreach ($aa as $a): ?>
        <option value="<?= $a['id'] ?>" <?= $aa_id == $a['id'] ? 'selected="selected"' : '' ?>><?= $a['name'] ?></option>
    <?php endforeach; ?>
</select>
<span id="club_box"></span>
<span id="loading"></span>

<script type="text/javascript">
<?php if ($aa_id >= 0 AND $club_id): ?>
        change_aa(<?= $aa_id ?>,<?= $club_id ?>);
<?php elseif ($aa_id >= 0): ?>
        change_aa(<?= $aa_id ?>,0);
<?php endif; ?>
</script>
