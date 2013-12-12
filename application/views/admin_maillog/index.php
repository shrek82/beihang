<!-- admin_maillog/index:_body -->
<?php if (!$mail): ?>
    <div class="nodata">暂无注册记录。</div>
<?php else: ?>

	<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" style="margin-top:0px ">
	    <tr>
		<td colspan="2" class="td_title">详细介绍</td>
	    </tr>
	</table>

	<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" style="margin-top:0px ">
	    <tr>
		<td width="10%" style="text-align:center">序号</td>
		<td style="text-align:center;width:50px">真是姓名</td>
	        <td width="60%">注册邮箱</td>
		<td style="text-align:center;width:20%">申请日期</td>
	    </tr>

    <?php foreach ($mail as $key => $i) : ?>

	    <tr  id="info_<?= $i['id'] ?>" class="<?php if (($key) % 2 == 0) {
		echo'even_tr';
	    } ?>">
		<td style="text-align:center"><?= $i['id'] ?></td>
		<td style="text-align:center"><a href="/user_home?id=<?=$i['user_id']?>" title="浏览详细信息" target="_blank"><?= $i['realname'] ?></a></td>
		<td style="color:#999;"><a href="<?= URL::site('admin_mainaa/form?id=' . $i['id']) ?>" title="点击修改"><?= $i['username'] ?>@mail.zuaa.zju.edu.cn</a></td>
		<td style="text-align:center"><?= $i['create_at'] ?></td>
	    </tr>
<?php endforeach; ?>
	</table>

<?php endif; ?>

	    <table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" style="margin-top:-1px " >
	        <tr>
	    	<td style="height: 50px"><?= $pager ?></td>
	        </tr>
	    </table>

	    <script type="text/javascript">
	        function del(cid){
	    	var b = new Facebox({
	    	    title: '删除确认！',
	    	    message: '确定要删除此内容吗？注意删除后将不能再恢复。',
	    	    icon:'question',
	    	    ok: function(){
	    		new Request({
	    		    url: '<?= URL::site('admin_mainaa/del?cid=') ?>'+cid,
	    		    type: 'post',
	    		    success: function(){
	    			candyDel('info_'+cid);
	    		    }
	    		}).send();
	    		b.close();
	    	    }
	    	});
	    	b.show();
	        }
			
</script>