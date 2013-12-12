<!--body -->
<!--left -->
<div id="main_left">
    <div style="border-bottom:1px solid #E1ECFA"><img src="/static/images/news_zonghui.gif"></div>
    <div class="con_list a14">
	<?php if (!$main_news): ?>
    	<span class="nodata">暂时还没有新闻内容。</span>
	<?php endif; ?>
    	<ul>
	    <?php foreach ($main_news as $n): ?>
    	    <li><a href="<?= URL::site('news/view?id=' . $n['id']) ?>" target="_blank"><?= Text::limit_chars($n['title'], 32, '...') ?></a><?= $n['is_pic'] ? '&nbsp;<font><img src="/static/images/imgs.gif" title="图片新闻"></font>' : ''; ?><span><?= date('Y-m-d', strtotime($n['create_at'])); ?></span></li>
	    <?php endforeach; ?>
    	</ul>
    	<div class="more"> <p class="more" style="float:right;padding: 6px 10px 0 0"><a href="<?= URL::site('/news/list?aa_id=0') ?>">更多总会新闻&raquo;</a></p></div>
        </div>
        <div style="border-bottom:1px solid #E1ECFA;margin-top:20px"><img src="/static/images/news_difang.gif"> </div>
        <div class="con_list a14">
	<?php if (!$aa_news): ?>
	    	<span class="nodata">暂时还没有新闻内容。</span>
	<?php endif; ?>
	    	<ul>
	    <?php foreach ($aa_news as $n): ?>
	    	    <li style="background:none;"><font style="font-size:14px;color:#999" title="<?= $n['aa_name'] ?>">[<?= Text::limit_chars($n['aa_name'], 4, '...') ?>]</font>&nbsp;&nbsp;<a href="<?= URL::site('aa_home/newsDetail?id='.$n['aa_id'].'&nid=' . $n['id']) ?>" target="_blank"><?= Text::limit_chars($n['title'], 25, '...') ?></a><?= $n['is_pic'] ? '&nbsp;<font><img src="/static/images/imgs.gif" title="图片新闻"></font>' : ''; ?><span><?= date('Y-m-d', strtotime($n['create_at'])); ?></span></li>
	    <?php endforeach; ?>
	    	</ul>
	    	<div class="more">
	    	    <p class="more" style="float:right;padding: 6px 10px 0 0"><a href="<?= URL::site('/news/list?aa_id=1') ?>">
	    		    更多地方新闻&raquo;</a>
	    	    </p></div>
	        </div>
	    </div>
	    <!--//left -->

	    <!--right -->
	    <div id="sidebar_right">


	        <!-- 热门点击-->
	        <p class="sidebar_title" >热门点击</p>
	        <div class="sidebar_box">
	<?php if (count($hit_news) == 0): ?>
		    	<span class="nodata">暂无新闻</span>
	<?php else: ?>
				<ul class="ranking">
	    <?php foreach ($hit_news as $n): ?>
				    <li><a href="<?= URL::site('news/view?id=' . $n['id']) ?>"  title="<?= $n['title'] ?>" target="_blank"><?= Text::limit_chars($n['title'], 13, '..') ?></a></li>
	    <?php endforeach; ?>
				</ul>
	<?php endif ?>
			        </div>
			        <!-- //热门点击-->

			        <!-- 热门点击-->
			        <p class="sidebar_title" style="margin-top:-1px" >投票调查</p>
			        <div class="sidebar_box">
	<?php if (!$vote): ?>
					<p class="nodata">暂无投票调查</p>
	<?php endif; ?>
					<ul class="con_small_list" >
	    <?php foreach ($vote as $v): ?>
					    <li><a href="<?= URL::site('vote/view?id=' . $v['id']) ?>" title="<?= $v['title'] ?>" ><?= Text::limit_chars($v['title'], 13, '...') ?></a></li>
	    <?php endforeach; ?>
	</ul>
    </div>
    <!-- //热门点击-->

</div>
<!--//right -->

<div class="clear"></div>
<!--//body -->