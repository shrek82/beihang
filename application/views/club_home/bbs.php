<!-- club_home/bbs:_body -->

<!-- 论坛分类 -->
<form action="<?= URL::site($_URI) ?>" style="padding-bottom: 10px;">
    <div style="text-align:right;padding:0px 20px">
    <input type="text" name="q" onclick="this.value=''" value="<?= $q ?>" class="input_text" size="50" />
    <input type="hidden" name="id" value="<?= $_ID ?>" />
    <input type="hidden" name="club_id" value="<?= $_ID ?>" />
    <input type="submit" value="搜索"  class="button_blue"/>
    <?php if($q): ?>
    <input type="button" onclick="location.href='<?= URL::query(array('q'=>null)) ?>'" value="全部"  class="button_blue"/>
    <?php endif; ?>
    <input type="button" onclick="location.href='<?= URL::site('bbs/unitForm?club_id='.$_ID.'&b='.$bbs_id) ?>'" value="发布新帖"  class="button_blue"/>
    </div>
    <span class="linkspace">
	话题分类：
	<a href="<?= URL::site('club_home/bbs?id='.$_ID.'&club_id='.$_ID) ?>">所有分类</a>&nbsp;&nbsp;
    <?php if(count($bbs_ids) > 0): foreach($bbs_ids as $id=>$name): ?>
    <a href="<?= URL::site('club_home/bbs?id='.$_ID.'&club_id='.$_ID.'&bbs_id='.$id) ?>"><?= preg_replace('/\(.*\)/', '', $name) ?></a>&nbsp;&nbsp;
    <?php endforeach; endif; ?>
    </span>
    <br>

</form>

<!-- 列表 -->
<div>
    <?= $unit_list ?>
    <?= $pager ?>
</div>