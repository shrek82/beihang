<!--forum box list -->
<div class="forum_box_list">
    <?php if (!count($aa_bbs)) : ?>
        <div class="nodata" style="padding:20px">很抱歉，该组织暂时还没有开设版块!</div>
    <?php endif ?>

    <?php if (isset($parent_bbs)): ?>
	<?php $display=0;?>
    <?php foreach ($aa_bbs as $key => $b): ?>
    <?php if ($p['id'] == $b['parent_id']): ?>
	<?php $display=$display+1;?>
		    <div class="forum_box">
			<div class="left"><a href="<?= URL::site('bbs/list?aid=' . $from) . '&bid=' . $b['id'] ?>"><img src="/static/images/forum<?php if ($b['today_count'] OR date('Y-n-d', strtotime($aa_bbs_unit[$b['id']]['comment_at'])) == date('Y-n-d')): ?>_new<?php endif; ?>.gif"></a></div>
		    	<div class="right">
		    	    <a href="<?= URL::site('bbs/list?aid=' . $from) . '&bid=' . $b['id'] ?>" class="name"><?= $b['name'] ?></a><span class="today_count"><?= $b['today_count'] ? '(今日' . $b['today_count'] . ')' : '' ?></span><br>
		    		    		    	    			主题:<?= $b['un_count'] ?>&nbsp;&nbsp;帖子<?= $b['reply_count'] ?><br>
	    <?php if ($aa_bbs_unit[$b['id']]): ?>
	    <?= $aa_bbs_unit[$b['id']]['User']['realname'] ?>在<?= Date::span_str(strtotime($aa_bbs_unit[$b['id']]['create_at'])) ?>前发表<br>
			    <a href="<?= URL::site('bbs/viewPost?id=' . $aa_bbs_unit[$b['id']]['id']) ?>" class="last_topic" title="<?= $aa_bbs_unit[$b['id']]['title'] ?>"><?= Text::limit_chars($aa_bbs_unit[$b['id']]['title'], 18, '') ?>...</a><?php endif; ?><br>
			</div>
			<div class="clear"></div>
		    </div>

	            <?php if (($display) % 3 === 0 AND (($display) != $p['bbs_total'])): ?>
                       <div class="forum_line"></div>
                    <?php endif; ?>
    <?php endif; ?>



    <?php endforeach; ?>
    <?php else: ?>
		     <?php foreach ($aa_bbs as $key => $b): ?>
		    <div class="forum_box">
			<div class="left"><a href="<?= URL::site('bbs/list?aid=' . $from) . '&bid=' . $b['id'] ?>"><img src="/static/images/forum<?php if ($b['today_count'] OR date('Y-n-d', strtotime($aa_bbs_unit[$b['id']]['comment_at'])) == date('Y-n-d')): ?>_new<?php endif; ?>.gif"></a></div>
		    	<div class="right">
		    	    <a href="<?= URL::site('bbs/list?aid=' . $from) . '&bid=' . $b['id'] ?>" class="name"><?= $b['name'] ?></a><span class="today_count"><?= $b['today_count'] ? '(今日' . $b['today_count'] . ')' : '' ?></span><br>
		    		    		    	    			主题:<?= $b['un_count'] ?>&nbsp;&nbsp;帖子<?= $b['reply_count'] ?><br>
	    <?php if ($aa_bbs_unit[$b['id']]): ?>
	    <?= $aa_bbs_unit[$b['id']]['User']['realname'] ?>在<?= Date::span_str(strtotime($aa_bbs_unit[$b['id']]['create_at'])) ?>前发表<br>
			    <a href="<?= URL::site('bbs/viewPost?id=' . $aa_bbs_unit[$b['id']]['id']) ?>" class="last_topic" title="<?= $aa_bbs_unit[$b['id']]['title'] ?>"><?= Text::limit_chars($aa_bbs_unit[$b['id']]['title'], 18, '') ?>...</a><?php endif; ?><br>
			</div>
			<div class="clear"></div>
		    </div>

	            <?php if (($key + 1) % 3 === 0 AND (($key + 1) != count($aa_bbs))): ?>
                       <div class="forum_line"></div>
                    <?php endif; ?>
    <?php endforeach; ?>

    <?php endif; ?>
    <div class="clear"></div>
</div>