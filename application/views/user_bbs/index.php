<!-- user_bbs/index:_body -->
<div id="big_right">
<div id="plugin_title">论坛</div>

<div class="tab_gray" id="user_topbar" style="margin-top:10px">
   <ul>
       <li><a href="<?= URL::site('user_bbs/index') ?>" class="cur" style="width:50px">我的主题</a></li>
       <li><a href="<?= URL::site('user_bbs/reply') ?>" style="width:50px">我的回复</a></li>
    </ul>
</div>

    <table width="100%" id="bbs_table">
        <thead>
	<tr >
            <td>标题</td>
            <td style=" text-align: center" >回复/浏览</td>
	     <td style=" text-align: center">最后发表</td>
	</tr>
	</thead>
	<tbody>
    <?php if(!$units): ?>
	    <tr>
		<td colspan="3" style="border:0"><div class="nodata">您还没有发布过任何话题呢！</div></td>
	    </tr>
   <?php endif;?>
    <?php foreach($units as $unit): ?>
        <tr style="border-bottom: 1px dotted #eee" onMouseOver=this.style.backgroundColor='#f9f9f9' onMouseOut=this.style.backgroundColor=''>
            <td class="title"><a href="<?= URL::site('bbs/view'.$unit['type'].'?id='.$unit['id']) ?>"><?= $unit['title'] ?></a></td>
	    <td class="hit"><span style="color:#407B00"><?= $unit['reply_num'] ?></span>&nbsp;/&nbsp;<?=$unit['hit'] ?></td>
	    <td class="date"><?= Date::span_str(strtotime($unit['create_at'])) ?>前</td>
	</tr>
    <?php endforeach; ?>
	</tbody>
    </table>
<?= $pager ?>


</div>