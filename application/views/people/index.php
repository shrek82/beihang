<style type="text/css">
    .peole_title{ color: #0D5194; font-size: 14px;font-weight: bold; background:#EAF2F7  url(/static/images/dotted_line_bg2.gif) repeat-x bottom; height: 28px; line-height:28px; padding-left: 15px }
.people_intro{padding: 20px 40px; height: 135px; color: #c00;  margin:0px 0;line-height: 1.6em;background: url(/static/images/peopletopbg.gif) no-repeat}
</style>
<div id="main_left">
    <p><img src="/static/images/people_title.gif" /></p>
    <div class=" people_intro">
	&nbsp;&nbsp;&nbsp;&nbsp;各行各业的北航校友，如灿烂的星辰，在世界各地熠熠闪光，他们的名字，构成了新世纪北京航空航天大学新的荣耀！让我们记住他们的名字，承继他们的辉煌，让我们每一位北航学子都为这些光荣的名字而骄傲，北京航空航天大学将因为这些名字而更加光彩夺目！
    </div>
<p class="peole_title">相关报道</p>
    <div class="con_list a14">
        <?php if (!$news): ?>
            <span class="nodata">暂时还没有任何报道。</span>
        <?php endif; ?>
            <ul>
            <?php foreach ($news as $n): ?>
                <li><a href="<?= URL::site('people/newsView?id='.$n['id']) ?>"  target="_blank"><?= $n['title'] ?></a><span><?= date('Y-m-d', strtotime($n['create_at'])); ?></span></li>
            <?php endforeach; ?>
            </ul>
            <div class="more"> <p class="more" style="float:right;padding: 6px 10px 0 0"><a href="<?= URL::site('/people/news') ?>">更多报道&raquo;</a></p></div>
        </div>
</div>


<?php
include 'sidebar.php';
?>
<div class="clear"></div>