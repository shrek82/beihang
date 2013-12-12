			    <!--left -->
			    <style type="text/css">
				#main_left{background: #F8FCFF;}
				.content_title{ text-align: left; font-size: 18px; font-weight: bold;height: 30px; line-height: 30px; padding: 15px; }
				.content_body{ padding: 20px; line-height: 1.6em; }
			    </style>
			    <div id="main_left">
			   <div class="content_title"><?=$content['title']?></div>
			   <div class=" dotted_line" style="margin:0px 15px"></div>
                           <div class="content_body"><?=$content['content']?></div>
			    </div>

			    <!--//left -->

			    <!--right -->
<div id="sidebar_right">
				<!-- 热门点击-->
				<p class="sidebar_title" ><?=$category['name']?></p>
				<div class="sidebar_box">
				    <?php if(count($menus)===0) :?>
				    <span class="nodata">暂无分类</span>
				    <?php else: ?>
				    <ul class="con_small_list">
					<?php foreach($menus as $m): ?>
					<li><a href="<?= URL::site('help?type='.$m['type'].'&id='.$m['id']) ?>"  title="<?=$m['title'] ?>"><?= Text::limit_chars($m['title'],13, '..') ?></a></li>
				       <?php endforeach; ?>
				    </ul>
				    <?php endif ?>
				</div>
				<!-- //热门点击-->

			    </div>
			    <!--//right -->

			    <div class="clear"></div>