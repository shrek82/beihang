<!-- user_pub/index:_body -->
<div id="big_right">
    <div id="plugin_title">刊物</div>

    <div class="tab_gray" id="user_topbar" style="margin-top:10px">
	<ul>
	    <li><a href="<?= URL::site('user_publication/index') ?>" class="cur" style="width:50px">我的投稿</a></li>
	    <li><a href="<?= URL::site('user_publication/form') ?>" style="width:50px">我要投稿</a></li>
	</ul>
    </div>

    <?php if (count($pub) == 0): ?>
        <p class="ico_info icon">
    	您还没有文章。
        </p>
    <?php else: ?>

	    <table width="100%" id="bbs_table">
		<thead>
		    <tr >
			<td>文章标题</td>
			<td style=" text-align: center">更新日期</td>
			<td style=" text-align: center">状态</td>
			<td style=" text-align: center">修改</td>
		    </tr>
		</thead>
		<tbody>
	    <?php foreach ($pub as $p): ?>
    	    <tr style="border-bottom: 1px dotted #eee" onMouseOver=this.style.backgroundColor='#f9f9f9' onMouseOut=this.style.backgroundColor=''>
    		<td class="title">
    		    <a href="<?= URL::site('user_publication/view?pub_id=' . $p['id']) ?>" title="点击预览"><?= $p['title'] ?></a>
    		</td>
    		<td class="date" width="150"><?= date('Y-n-d', strtotime($p['create_at'])); ?></td>
		<td style=" text-align: center"><?php if ($p['is_read'] == TRUE): ?><span style="color:green">已查阅</span><?php endif; ?>
		    <?php if ($p['is_read'] == FALSE): ?><span style="color:#ff6600">未查阅</span><?php endif; ?></td>
		<td style=" text-align: center"><a href="<?= URL::site('user_publication/form?pub_id=' . $p['id']) ?>">修改</a>
    	    </tr>
	    <?php endforeach; ?>
	    	</tbody>
	        </table>

    <?= $pager ?>
    <?php endif; ?>

</div>
