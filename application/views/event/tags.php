			    <!--left -->
			    <div id="main_left">
				<p><img src="/static/images/event_title.gif"></p>
<div class="event_title">
<?php $etype = Kohana::config('icon.etype'); ?>
    <div style="float: left">
                <select name="etype" onchange="location.href='/event?type='+this.value">
                    <option value="">全部活动</option>
                    <?php foreach($etype['icons'] as $name => $ico): ?>
                    <option value="<?= urlencode($name) ?>"><?= $name ?></option>
                    <?php endforeach; ?>
                </select>
    </div>

    <div style="float:right;padding:0px 15px">
<input onclick="location.href='/event/form'" type="button" value="我要发起活动" class="button_blue"  />
    </div>

</div>

  			 <div class="blue_tab" style="margin: 15px 20px">
			     <ul>
				 <li><a href="<?=URL::site('event')?>"    style="width:80px">所有活动</a></li>
            <?php if($_UID>0):?>
            <li><a href="<?= URL::site('event?list=aa') ?>"    class="<?= $list=='aa' ? 'cur' : '' ?>" style="width:80px">我加入的组织</a></li>
            <li><a href="<?= URL::site('event?list=joined') ?>"    class="<?= $list=='joined' ? 'cur' : '' ?>" style="width:80px">我参加过的</a></li>
            <?php endif;?>
<li><a href="<?=URL::site('event')?>"   style="width:60px" class="cur">标签</a></li>
			     </ul>
			 </div>
<?php
function fontsize($num){
    if($num>=1 AND $num<10){
       $style='font-size:12px;';
    }
    elseif($num>=10 AND $num<20){
       $style='font-size:14px;';
    }
    elseif($num>=20 AND $num<30){
       $style='font-size:16px;font-weight:bold';
    }
    elseif($num>=30 AND $num<40){
       $style='font-size:18px;font-weight:bold';
    }
    elseif($num>=40 AND $num<60){
       $style='font-size:20px;font-weight:bold';
    }
    elseif($num>=60 AND $num<80){
       $style='font-size:22px;font-weight:bold';
    }
    elseif($num>=80){
       $style='font-size:24px;font-weight:bold';
    }
    else{
        $style='';
    }
    return $style;
}
?>
<!--活动列表开始-->
<div style="padding:5px 20px; line-height: 2.4em" id="event_tags">
<?php foreach($tags AS $tag):?>
    <a href="/event?tag=<?=$tag['name']?>" style="<?=fontsize($tag['num'])?>"><?=$tag['name']?></a>&nbsp;&nbsp;
<?php endforeach;?>
</div>
<!--//活动列表结束-->
			    </div>
			    <!--//left -->

			    <!--right -->
			    <div id="sidebar_right">
           <div style="">
    <p class="sidebar_title">快速查找</p>
    <div class="sidebar_box">
        <ul class="sidebar_menus">
<li><a href="<?=URL::site('event?list=week')?>" <?=$list=='week'?'class="cur"':'';?>>未来7天</a></li>
<li><a href="<?=URL::site('event?list=today')?>" <?=$list=='today'?'class="cur"':'';?>>今天</a></li>
<li><a href="<?=URL::site('event?list=weeken')?>" <?=$list=='weeken'?'class="cur"':'';?>>周末</a></li>
<li><a href="<?=URL::site('event/tags')?>" <?=!$list?'class="cur"':'';?>>所有活动</a></li>
        </ul>
    </div></div>
				<p class="sidebar_title" style="border-top-width:0">主题活动</p>
				<div class="sidebar_box">
				    <?php if(!$static):?>
				    <span class="nodata">暂无主题活动</span>
				    <?php else:?>
				    <ul class="sidebar_menus">
					<?php foreach($static as $e): ?>
					<li>
<?php if (empty($e['redirect'])): ?><a href="<?= URL::site('event/static?id=' . $e['id']) ?>" ><?php else: ?><a href="<?= $e['redirect'] ?>" target="_blank" title="<?=$e['title']?>"><?php endif; ?><?= Text::limit_chars($e['title'], 16, '..') ?></a>
					</li>
					<?php endforeach; ?>
				    </ul>
				    	<?php endif;?>
				</div>

				<p class="sidebar_title" style="border-top-width:0">最新活动掠影</p>
				<div class="sidebar_box">

			    <?php if(!$event_pic):?>
						    <div class="nodata">暂无活动照片</div>
						    <?php else:?>
						    <ul class="sidebar_photo img_border">
							<?php foreach($event_pic as $p): ?>
							<li><a href="<?= URL::site('album/picView?id='.$p['id']) ?>" target="_blank"><img src="<?= URL::base().$p['img_path'] ?>" width="80" height="60" /></a></li>
						        <?php endforeach; ?>
						    </ul>
						    <?php endif;?>
				    <div class="clear"></div>
				</div>

			    </div>
			    <!--//right -->

			    <div class="clear"></div>

