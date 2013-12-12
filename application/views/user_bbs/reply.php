<!-- user_bbs/reply:_body -->
<div id="big_right">
<div id="plugin_title">论坛</div>

<div class="tab_gray" id="user_topbar" style="margin-top:10px">
   <ul>
       <li><a href="<?= URL::site('user_bbs/index') ?>" style="width:50px">我的主题</a></li>
       <li><a href="<?= URL::site('user_bbs/reply') ?>" class="cur" style="width:50px">我的回复</a></li>
    </ul>
</div>

    <table width="100%" id="bbs_table">
        <thead>
	<tr >
            <td>标题</td>
            <td style=" text-align: center" >回复条数</td>
	     <td style=" text-align: center">发布日期</td>
	</tr>
	</thead>
	<tbody>
    <?php if(!$units): ?>
	    <tr>
		<td colspan="2" style="border:0"><div class="nodata">您还没有回复或任何话题呢！</div></td>
	    </tr>
   <?php endif;?>
    <?php foreach($units as $unit): ?>
        <tr style="border-bottom: 1px dotted #eee" onMouseOver=this.style.backgroundColor='#f9f9f9' onMouseOut=this.style.backgroundColor=''>
            <td class="title"><a href="<?= URL::site('bbs/view'.$unit['type'].'?id='.$unit['id'].'&cmt=y') ?>"><?= $unit['title'] ?></a></td>
	    <td class="hit"><?= count($unit['Comments']) ?></td>
	    <td class="date"><?= Date::span_str(strtotime($unit['Comments'][0]['post_at'])) ?>前</td>
	</tr>
    <?php endforeach; ?>
	</tbody>
    </table>

<?= $pager ?>

</div>