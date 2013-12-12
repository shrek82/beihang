<!-- classroom/search:_body -->
<?php if(count($classroom)==0):?>
<span class="nodata">很抱歉，暂时还没有您要找的班级。</span>
<?else:?>

<p class="search_count">共找到 <?= $pager->total_items ?> 条符合条件的班级：</p>

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
   <?php if(count($classroom)<=0):?>
	    <tr>
		<td colspan="5"><span style="color:#999">&nbsp;很抱歉，没有找到相关班。</span></td>
	    </tr>
	   <?php endif;?>
    <?php foreach($classroom as $class): ?>
	    <tr>
		<td style="text-align:center"><?= $class['start_year'] ?>年</td>
		<td style="text-align:left"><a href="<?= URL::site('classroom_home/index?id='.$class['id']) ?>"><?= $class['speciality'] ?$class['speciality']:$class['name'] ?></a></td>
		<td style="text-align:center"><?=$class['membercount'] ?>人</td>
		<td style="text-align:center"><?=$class['realname']?></td>
		<td style="text-align:center"><?= date('Y-n-d', strtotime($class['create_at'])); ?></td>

	    </tr>
    <?php endforeach; ?>
	</tbody>
    </table>

    <?= $pager ?>
<?php endif;?>