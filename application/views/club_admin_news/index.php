<!-- club_admin_news/index:_body -->
<div id="admin950">
    <?php if(count($news) == 0): ?>
    <p class="nodata">
        没有任何投稿的新闻信息。
    </p>
    <?php else: ?>
    <table id="bench_list" width="100%"  border="0"  cellpadding="0" cellspacing="0">
        <tr>
            <th width="50%" style="text-align:left">新闻标题</th>
            <th style="text-align:center">所属分类</th>
<th style="text-align:center">发布时间</th>
            <th style="text-align:center">评论/浏览</th>
            <th style="text-align:center">置顶</th>
            <th style="text-align:center">通过审核</th>

	    <th>修改</th>
	    <th>删除</th>
        </tr>
            <?php foreach($news as $key=>$n): ?>
        <tr id="news_<?=$n['id']?>" class="<?=(($key+1)%2)==0?'even_tr':'odd_tr';?>" >
            <td style="padding:5px 0">
                <a href="<?= URL::site('news/view?id='.$n['id']) ?>" <?=$n['is_fixed']?'style="color:#f30"':'';?> target="_blank"><?= Text::limit_chars($n['title'], 25, '...') ?></a><?php if($n['is_fixed']):?>&nbsp;&nbsp;<span style="color:#f60">[置顶]</span><?php endif;?>
            </td>
            <td style="text-align:center">
                        <?=$n['category_name']?>
            </td>

	      <td class="timestamp" style="text-align:center"><?= Date::ueTime($n['create_at']); ?></td>
          <td class="timestamp" style="text-align:center;color: #999"><span style="color:#008000"><?=$n['comments_num']?></span>/<span style="color:#666"><?=$n['hit']?></span></td>
            <td style="text-align:center">
              <input type="checkbox" value="true" onclick="setFixed(<?= $n['id'] ?>)" <?= $n['is_fixed'] == true ? 'checked':'' ?> />
            </td>
            <td style="text-align:center">
                <!-- 是否对外发布 -->
                <input type="checkbox" value="true" onclick="cvis(<?= $n['id'] ?>)" <?= $n['is_release'] == true ? 'checked':'' ?> />
            </td>


            <td class="handler" style="text-align:center">
                <a href="<?= URL::site('club_admin_news/form?id='.$_ID.'&news_id='.$n['id']) ?>">修改</a>
            </td>
            <td style="text-align:center">
                <a href="javascript:del(<?=$n['id']?>)">删除</a>
            </td>
        </tr>
            <?php endforeach; ?>
    </table>
    <?= $pager ?>
    <?php endif; ?>
</div>

<script type="text/javascript">
    function setFixed(nid){
        new Request({
            url: '<?= URL::site('club_admin_news/fixed').URL::query() ?>',
            type: 'post',
            data: 'cid='+nid
        }).send();
    }
    function cvis(id){
        new Request({
            url: '<?= URL::site('news/release').URL::query() ?>',
            type: 'post',
            data: 'id='+id
        }).send();
    }
    function set_top(cid){
        new Request({
            url: '<?= URL::site('club_admin_news/top').URL::query() ?>',
            type: 'post',
	    data: 'cid='+cid
        }).send();
    }
function del(cid){
    var b = new Facebox({
        title: '删除确认！',
        icon:'question',
        message: '确定要删除此条新闻吗？注意删除后将不能再恢复。',
        ok: function(){
            new Request({
                url: '<?= URL::site('club_admin_news/del?id='.$_ID.'&cid=') ?>'+cid,
                type: 'post',
                success: function(){
                    candyDel('news_'+cid);
                }
            }).send();
            b.close();
        }
    });
    b.show();
}
</script>