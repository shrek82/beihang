<!-- donate/reports:_body -->
<div id="main_left">
    <p><img src="/static/images/donate_project.png" /></p>
<div style="margin:10px">
    <form method="get" action="<?=$_URL?>">
        <input type="text" value="<?=$name?>" id="name" name="name" placeholder="输入关键字搜索" size="50" class="input_text" />
    <input type="submit" value="搜索"  class="button_blue"/>
    </form>
</div>
    <div>
	<ul class="con_list">
	    <?php foreach($reports as $n):?>
	    <li><a href="<?=URL::site('donate/view?id='.$n['id'])?>"><?=$n['title']?></a><span><?=date('Y-m-d',  strtotime($n['create_at']))?></span></li>
	    <?php endforeach;?>
	</ul>
    </div>

 	<?php if(!$reports):?>
	<div class="nodata" style="padding:10px">很抱歉，暂时还没有该类内容。</div>
	 <?php endif;?>

    <?= $pager ?>
    
</div>
<?php
include 'sidebar.php';
?>
<div class="clear"></div>