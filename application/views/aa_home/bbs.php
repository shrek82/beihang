<!-- aa_home/bbs:_body -->
<div class="main_content">
<!-- 论坛分类 -->
<form action="<?= URL::site($_URI); ?>" id="bbs_filter" method="get" style="padding:0 10px">
    <input type="text" name="q" onclick="this.value=''" value="<?= $q ?>" class="input_text" size="50"/>
    <input type="hidden" name="id" value="<?= $_ID ?>" />
    <input type="submit" value="搜索" class="button_blue" />
    <?php if($q): ?>
    <input type="button" onclick="location.href='<?= URL::query(array('q'=>null)) ?>'" value="全部" class="button_blue"/>
    <?php endif; ?>
    <input type="button" onclick="location.href='<?= URL::site('bbs/unitForm?aa_id='.$_ID.'&b='.$bbs_id) ?>'" value="+发布新帖" class="button_blue"/>
</form>

    <div style="margin:10px">版块：
<a href="<?= URL::site('aa_home/bbs?id='.$_ID) ?>">所有的</a>
    <?php if(count($bbs_ids) > 0): foreach($bbs_ids as $id=>$name): ?>
     | <a href="<?= URL::site('aa_home/bbs?id='.$_ID.'&bbs_id='.$id) ?>">
        <?= preg_replace('/\(.*\)/', '', $name) ?>
     </a>
    <?php endforeach; endif; ?>
    </div>

<!-- 列表 -->
<div>
    <?= $unit_list ?>
    <?= $pager ?>
</div>
</div>
