<!-- club_admin_bbs/index:_body -->
<?php 
$subject = Kohana::config('bbs.subject');
?>
<div id="admin950">
    <form method="post" style="text-align: right; margin-top:-5px">
        <input type="text" name="q" class="input_text"/>
        <input type="submit" value="搜索" class="button_blue"/>
    </form>
    <?php if(count($units) == 0): ?>
    <div class="nodata">还没有任何话题。</div>
    <?php else: ?>
<table id="bench_list" width="100%"  border="0"  cellpadding="0" cellspacing="0">
    <tr>
        <th style="text-align:left;width:600px">标题</th>
        <th class="center">发布人</th>
        <th class="center" style="width:70px">最后更新</th>
        <th>推荐</th>
        <th>顶置</th>
        <th>屏蔽</th>
    </tr>
    <?php foreach($units as $key=>$u): ?>
    <tr class="<?=(($key+1)%2)==0?'even_tr':'odd_tr';?>">
        <td style="padding:8px 2px">
            <span style="color:#999">[<?=$subject[$u['subject']]?>]</span>&nbsp;<a target="_blank" href="<?= URL::site('bbs/view'.$u['type'].'?id='.$u['id']) ?>" <?= $u['is_fixed'] ? 'style="color:#f00"':'' ?>><?=Text::limit_chars($u['title'],35,'...'); ?></a>			<?php if ($u['is_good']): ?>
	                    <img src="/static/ico/recommend_1.gif"  border="0" class="middle" title="推荐帖子"/>
			<?php endif; ?>
        </td>
        <td class="center"><a href="<?= URL::site('user_home?id='.$u['user_id']) ?>"><?= $u['realname'] ?></a></td>
        <td class="center"><?=$u['comment_at']?Date::ueTime($u['comment_at']):Date::ueTime($u['create_at']) ?></td>
        <td class="center"><input type="checkbox" onclick="setBool(<?= $u['id'] ?>,'is_good')" <?= $u['is_good'] ? 'checked':'' ?> /></td>
        <td class="center"><input type="checkbox" onclick="setBool(<?= $u['id'] ?>,'is_fixed')" <?= $u['is_fixed'] ? 'checked':'' ?> /></td>
        <td class="center"><input type="checkbox" onclick="setBool(<?= $u['id'] ?>,'is_closed')" <?= $u['is_closed'] ? 'checked':'' ?> /></td>
    </tr>
    <?php endforeach; ?>
    </table>
    <?= $pager ?>
    <?php endif; ?>

</div>
<script type="text/javascript">
function setBool(id,field){
    new Request({
        type:'post',
        url:'<?= URL::site('aa_admin_bbs/set').URL::query() ?>',
        data: 'cid='+id+'&bool_field='+field
    }).send();
}
</script>