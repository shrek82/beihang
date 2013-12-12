<!-- inc/news -->
<?php
$main = Doctrine_Query::create()
        ->from('NewsCategory')
        ->where('aa_id = ?', 0)
        ->addWhere('is_public=?', TRUE)
        ->orderBy('order_num ASC')
        ->fetchArray();

$aa = Doctrine_Query::create()
        ->select('a.id,a.name,a.sname,c.*')
        ->from('Aa a')
        ->leftJoin('a.NewsCategorys c')
        ->where('c.is_public=?', TRUE)
        ->whereIn('a.id', Model_User::aaIds($_UID));
$aa = $aa->orderBy('c.order_num ASC')->fetchArray();

$ids = array();
$selected_id = null;
if (isset($news_id)) {
    $news = Doctrine_Query::create()
            ->select('id,category_id')
            ->from('News')
            ->where('id = ?', $news_id)
            ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
    $selected_id=$news['category_id'];
}
$aa_id = Arr::get($_GET, 'aa');
?>

<select name="category_id" id="category">
    <option value="">选择新闻分类</option>
    <optgroup label="校友总会">
        <?php foreach ($main as $c): ?>
            <option value="<?= $c['id'] ?>"  <?php if ($selected_id == $c['id']): ?>selected<?php endif; ?>><?= $c['name'] ?></option>
        <?php endforeach; ?>
    </optgroup>

    <?php foreach ($aa as $a): ?>
        <optgroup label="<?= $a['name'] ?>">
            <?php foreach ($a['NewsCategorys'] as $key => $c): ?>
                <option value="<?= $c['id'] ?>"  <?php if (empty($selected_id) AND $aa_id == $a['id'] AND $key == 0): ?>selected<?php endif; ?><?php if ($selected_id == $c['id']): ?>selected<?php endif; ?>><?= $c['name'] ?></option>
            <?php endforeach; ?>
        </optgroup>

    <?php endforeach; ?>
</select>