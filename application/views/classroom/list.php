<!-- class/index:_body -->
<div>
    <div id="main_left">
<p><img src="/static/images/class_title.gif"></p>
<div style="padding:4px 10px">
	    <form method="get" action="<?= URL::site('classroom/list') ?>">
         <table>
             <tr>
                 <td style="text-align:right">搜索方式：</td>
                 <td><input type="radio" name="searchtype" value="specialty" <?=$searchtype=='specialty' || empty($searchtype)?'checked':'';?>/>按专业<input type="radio" name="searchtype" value="classmate"  <?=$searchtype=='classmate'?'checked':'';?>/>按同学</td>
             </tr>
             <tr>
                 <td style="text-align:right">入学年份：</td>
                 <td>		<select name="start_year" style="padding:1px 2px;">
       <option value="">年份不限</option>
		    <?php for ($i = date('Y'); $i >= 1900; $i--): ?>
    		    <option value="<?= $i ?>"  <?=$start_year==$i?'selected':''?>><?= $i ?></option>
		    <?php endfor ?>
    		</select></td>
             </tr>
             <tr>
                 <td style="text-align:right">输入关键字：</td>
                 <td><input type="text" value="<?=$keyword?>" id="name" name="keyword" size="60" class="input_text"  />&nbsp;&nbsp;<span style="color:#999">输入同学姓名或专业关键字</span></td>
             </tr>
             <tr>
                 <td>
                 </td>
                 <td>
                     <input type="submit" value="查找"  class="button_blue"/>
                 </td>
             </tr>
         </table>
    	    </form>


</div>
<style type="text/css">
    #class_table th{ background: #f8f8f8; border-bottom: 1px solid #ccc; height: 30px;line-height: 30px}
     #class_table td{ border-bottom: 1px solid #eee; height: 30px;line-height: 30px}
</style>

<div style="margin: 30px 10px">
    <h3>已经创建的班级：</h3>
    <table border="0" width="100%" id="class_table" cellspacing="0" cellpadding="0">
	<thead>
	    <tr>
		<th style="text-align:center">入学年份</th>
		<th  style="text-align:left">班级名称</th>
		<th style="text-align:center">人数</th>
		<th style="text-align:center">创建人</th>
		<th style="text-align:center">创建日期</th>

	    </tr>
	</thead>
	<tbody>
   <?php if(count($classrooms)<=0):?>
	    <tr>
		<td colspan="5"><span style="color:#999">&nbsp;很抱歉，没有找到相关班。</span></td>
	    </tr>
	   <?php endif;?>
    <?php foreach($classrooms as $class): ?>
	    <tr>
		<td style="text-align:center"><?= $class['start_year'] ?>年</td>
		<td style="text-align:left"><a href="<?= URL::site('classroom_home/index?id='.$class['id']) ?>" title="<?=$class['start_year'].'~'.$class['finish_year'].'年'.$class['institute']?>"><?= $class['speciality'] ?$class['speciality']:$class['name'] ?></a></td>
		<td style="text-align:center"><?=$class['member_num'] ?>人</td>
		<td style="text-align:center"><?=@$class['realname']?$class['realname']:'<span style="color:#999">-</span>';?></td>
		<td style="text-align:center"><?= date('Y-n-d', strtotime($class['create_at'])); ?></td>

	    </tr>
    <?php endforeach; ?>
	</tbody>
    </table>

    <?= $pager ?>
</div>
    </div>
    <div id="sidebar_right">

<p class="sidebar_title" >人气排行榜</p>
<div class="sidebar_box">
	    <?php if (count($hot_classroom)>0): ?>
			    	    <ul class="ranking">
		<?php foreach ($hot_classroom as $c): ?>
			    		<li><a href="<?= URL::site('classroom_home?id=' . $c['id']) ?>" target="_blank" title="<?= $c['start_year'] ?>级<?= $c['name'] ?>"><?= substr($c['start_year'], -2) ?>级 <?= Text::limit_chars($c['speciality'], 8, '') ?></a><span>(<?= $c['member_num'] ?>人)</span></li>
		<?php endforeach; ?>
			    	    </ul>
	    <?php else: ?>
				    	    <p class="nodata">暂无班级</p>
	    <?php endif; ?>
</div>
</div>


    <div class="clear"></div>
</div>
