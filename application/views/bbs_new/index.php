<!-- bbs/index:_body -->
<!--row 1-->
<div>
    <?php if ($aa_id): ?>
        <p><img src="/static/images/bbs_title.jpg"></p>
    <?php else: ?>
	    <div id="focus">
		<p><img src="/static/images/bbs_title.jpg"></p>

		<!--top_topic -->
		<div id="top_topic"></div>
	<?php
	    $focus_title = null;
	    $focus_url = null;
	    $focus_imgs = null;
	    //设置焦点新闻图片及标题
	    foreach ($bbs_focus as $f) {
  $f['title']=Text::limit_chars(preg_replace('/\s+/', ',', trim($f['title'])),12,'...');
		$focus_title = empty($focus_title) ? $f['title'] : $focus_title . '|' . $f['title'];
		$focus_url = empty($focus_url) ? URL::site('bbs/view' . $f['type'] . '?id=' . $f['id']) : $focus_url . '|' . URL::site('bbs/view' . $f['type'] . '?id=' . $f['id']);
		$focus_imgs = empty($focus_imgs) ? $f['img_path'] : $focus_imgs . '|' . $f['img_path'];
	    }
	?>
	<?php if ($bbs_focus): ?>
		<script type="text/javascript" src="/static/swfobject_source.js"></script>
		<script language="javascript" type="text/javascript">
		   var titles = '<?= $focus_title ?>';
	    var imgs='<?= $focus_imgs ?>';
		    var urls='<?= $focus_url ?>';
		    var pw = 366;
		    var ph = 244;
		    var sizes = 14;
		    var Times = 3000;
		    var umcolor = 0xFFFFFF;
		    var btnbg =0xFF7E00;
		    var txtcolor =0xFFFFFF;
		    var txtoutcolor = 0x000000;
		    var flash = new SWFObject('/static/focus.swf', 'mymovie', pw, ph, '7', '');
		    flash.addParam('allowFullScreen', 'true');
		    flash.addParam('allowScriptAccess', 'always');
		    flash.addParam('quality', 'high');
		    flash.addParam('wmode', 'Transparent');
		    flash.addVariable('pw', pw);
		    flash.addVariable('ph', ph);
		    flash.addVariable('sizes', sizes);
		    flash.addVariable('umcolor', umcolor);
		    flash.addVariable('btnbg', btnbg);
		    flash.addVariable('txtcolor', txtcolor);
		    flash.addVariable('txtoutcolor', txtoutcolor);
		    flash.addVariable('urls', urls);
		    flash.addVariable('Times', Times);
		    flash.addVariable('titles', titles);
		    flash.addVariable('imgs', imgs);
		    flash.write('top_topic');
		</script>
	<? else: ?>
	    	<p class="nodata" style="padding:5px">精彩话题，稍后呈现!</p>
	<?php endif; ?>
	    	<!--//top_topic -->
	        </div>
	        <div id="hot_topic">
	    	<p class="title">热门话题</p>
	<?php if (!$hot_topic): ?>
			<p class="nodata" style="padding:5px">暂时还没有任何话题!</p>
	<?php else: ?>
		    	<ul>
	    <?php foreach ($hot_topic as $un): ?>
		    	    <li><span class="address">[<?= $un['aa_city'] ? '<a href="' . URL::site('bbs?f=' . $un['Bbs']['aa_id']) . '&b=' . $un['bbs_id'] . '">' . Text::limit_chars($un['aa_city'], 2, '') . '</a>' : '<a href="' . URL::site('bbs?f=0&b=' . $un['bbs_id']) . '">公共</a>' ?>]</span><a href="<?= URL::site('bbs/view' . $un['type'] . '?id=' . $un['id']) ?>"><?= Text::limit_chars($un['title'], 25, '...') ?></a><span class="date"><?= Date::span_str(strtotime($un['create_at'])) ?>前by<?= $un['user_name'] ?></span></li>
	    <?php endforeach; ?>
		    	</ul>
	<?php endif; ?>
			    </div>
			    <div class="clear"></div>
    <?php endif; ?>
			    </div>
			    <!--//row1 -->

			    <!-- //row2-->
			    <div style="margin:10px 0">

    <?php if ($from > 0): ?>
				    <div style="color:#666">
					您的位置：<a href='<?= URL::site('aa_home?id=' . $from) ?>' title="进入校友会主页"><?= $bbs_info['sname'] ?>校友会</a>&nbsp;> 交流论坛：
				    </div>
    <?php else: ?>
				        <div id="notice">
				    	<select onchange="location.href='?f='+this.value" style="padding:1px 4px">
				    	    <option value="0">校友公共论坛</option>
	    <?php foreach ($aa as $a): ?>
					    <option <?= $from == $a['id'] ? 'selected' : '' ?> value="<?= $a['id'] ?>" style="font-weight: bold;"><?= $a['name'] ?></option>
	    <?php endforeach; ?>
					</select>
				    </div>
    <?php endif; ?>


					    <div id="post_button">
						<a href="<?= URL::site('bbs/unitForm?aa_id=' . $bbs_info['id']) ?>"><img src="/static/images/post.png"></a>
					    </div>
					    <div class="clear"></div>
					</div>
					<!--//row2 -->

					<!--论坛版块 -->
<?php if (isset($parent_bbs)): //总会论坛?>
	<?php foreach ($parent_bbs as $key => $p): ?>
		<!--forum title -->
	         <div class="forum_title"><?= $p['name'] ?></div>

	       <!--forum box list -->
	      <?php include 'inc_bbs_box.php'?>
               <!--//forum box list -->

        <?php endforeach; ?>
		
<?php else://分会论坛?>
	<!--forum title -->
	<div class="forum_title">所有版块</div>
       
	<!--forum box list -->
         <?php include 'inc_bbs_box.php'?>
<?php endif;?>

<!--//论坛版块 -->

		<!--onine user -->
		<div class="forum_title">在线校友<span style="font-weight: normal">(共<?= count($online) ?>人)</span></div>
<div class="online_user">
    <?php if (!$online): ?><div class="nodata">暂时还没有人在线。</div><?php endif; ?>
    <?php foreach ($online as $u): ?>
											    <a href="<?= URL::site('user_home?id=' . $u['id']) ?>"><?= $u['realname'] ?></a>
    <?php endforeach; ?>
</div>

<!--//on onine user -->