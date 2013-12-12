<!-- classroom_home/index:_body -->
<div>
    <!--left -->
 <div id="aa_home_left">

     <br>
<div class="photo_list">
	<?php if (!$photos): ?>
			<div class="nodata">暂时还没有任何成员照片。<br><br>温馨提示：在个人主页上传照片即可显示在班级相册集当中，<a href="<?=URL::site('user_album')?>">进入我的相册</a>。</div>
	<?php endif; ?>

<?php foreach($photos as $key=>$p): ?>
	<div class="pbox">
		<a href="<?= URL::site('album/picView?id='.$p['id']) ?>" target="_blank">
		<img src="<?= URL::base() . $p['img_path']  ?>" />
		</a>
		<br />
		<a href="<?= URL::site('user_home?id='.$p['user_id']) ?>">
		<?= $p['realname'] ?>
		</a>

		<span style="color:#999"><?= Date::span_str(strtotime($p['upload_at'])) ?>前</span>
	</div>
       <?php if(($key+1) % 4==0):?>
	   <div class="clear"></div>
	   <?php endif;?>

    <?php endforeach; ?><div class="clear"></div>
		   </div>

<div><?=$pager?></div>
</div>
    <!--left -->

<!--right -->
	<div id="aa_home_right">

</div>
</div>