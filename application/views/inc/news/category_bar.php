<?php

$main = Doctrine_Query::create()
            ->from('NewsCategory c')
            ->where('c.aa_id = ?', 0)
            ->andWhere('c.is_public = ?', TRUE)
            ->orderBy('c.order_num ASC')
            ->fetchArray();

$aa = Doctrine_Query::create()
        ->from('Aa a')
        ->leftJoin('a.NewsCategorys c')
        ->where('c.is_public = ?', TRUE)
        ->orderBy('a.id ASC, c.order_num ASC')
        ->fetchArray();

?>

<style type="text/css">
.n_c{ margin: 0; padding: 0; list-style: none }
.n_c li{ padding: 3px; margin: 0 }
.aa_n{ font-size: 12px; font-weight: bold; padding: 3px; }
.aa_cur{ background: #eee; border-left: 3px solid #ccc; }
.cat_cur{ background: #f5f5f5; padding:3px; border-left: 3px solid #eee; }
</style>

<div class="aa_n <?= $aa_id == null ? 'aa_cur':'' ?>">
    <a href="<?= URL::site('news') ?>">全部新闻</a>
</div>

<div class="aa_n" id="aa_0">
    <a href="<?= URL::site('news?aa_id=0') ?>">校友总会</a>
</div>
<ul id="c_f_0" class="n_c">
    <?php foreach($main as $c): ?>
    <li id="c_<?= $c['id'] ?>">
        <a href="<?= URL::site('news?aa_id=0&cid='.$c['id']) ?>"><?= $c['name'] ?></a>
    </li>
    <?php endforeach; ?>
</ul>

<?php foreach($aa as $a): ?>
<div class="aa_n" id="aa_<?= $a['id'] ?>">
    <a href="<?= URL::site('news?aa_id='.$a['id']) ?>"><?= $a['name'] ?></a>
</div>
<ul id="c_f_<?= $a['id'] ?>" class="n_c">
    <?php foreach($a['NewsCategorys'] as $c): ?>
    <li id="c_<?= $c['id'] ?>">
        <a href="<?= URL::site('news?aa_id='.$a['id'].'&cid='.$c['id']) ?>"><?= $c['name'] ?></a>
    </li>
    <?php endforeach; ?>
</ul>
<?php endforeach; ?>

<script type="text/javascript">
    $$('.n_c').addClass('hide');
    $('aa_<?= $aa_id ?>').addClass('aa_cur');
    $('c_f_<?= $aa_id ?>').removeClass('hide');
    $('c_<?= $cid ?>').addClass('cat_cur');
</script>