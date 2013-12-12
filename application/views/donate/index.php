<!-- publication/index:_body -->
<div id="main_left">
    <p><img src="/static/images/donate.gif"></p>
    <div class="donate_intro">
校友是北京航空航天大学最宝贵的财富和最可信赖的力量。自新的北京航空航天大学成立以来，来自社会各界捐赠已超过10亿元人民币。其中，约有一半来自于北航校友捐赠，另一半中很大比例的捐赠由北航校友牵线搭桥促成。非常感谢广大校友对母校发展事业的大力支持，母校的明天一定会因你们而更辉煌！
    </div>

    <p class="donate_title">捐赠展示</p>
    <div style="padding:10px">
	<IFRAME marginWidth=0 marginHeight=0 src="<?=URL::site('donate/scroll')?>"  frameBorder=0 width="660" scrolling="no" height="90" ></IFRAME>
    </div>
    <p class="donate_title">相关报道</p>
    <div>
	<ul class="con_list">
	    <?php foreach($news as $n):?>
	    <li><a href="<?=URL::site('donate/view?id='.$n['id'])?>"><?=$n['title']?></a><span><?=date('Y-m-d',  strtotime($n['create_at']))?></span></li>
	    <?php endforeach;?>
	</ul>
    </div>
    <p class="donate_title">捐赠感言</p>
    <div>
	<ul class="con_list">
	    <?php foreach($ganyan as $n):?>
	    <li><a href="<?=URL::site('donate/view?id='.$n['id'])?>"><?=$n['title']?></a><span><?=date('Y-m-d',  strtotime($n['create_at']))?></span></li>
	    <?php endforeach;?>
	</ul>
    </div>
</div>
<?php
include 'sidebar.php';
?>
<div class="clear"></div>