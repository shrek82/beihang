<p class="search_count">共找到 <?= count($org) ?> 条符合条件的校友会：</p>
<div style="padding:10px 0; line-height: 1.6em">
<?php if(!$org):?>
<div class="nodata">很抱歉，没有您要查找的内容。</div>
<?php else:?>
<?php foreach($org as $o): ?>
<a href="<?=URL::site('aa_home?id='.$o['id'])?>" target="_blank"><?=$o['sname']?>校友会</a><span style="color:#999">(<?=$o['mcount']?>位成员)</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?php endforeach;?>
<?php endif;?>
</div>

