<!-- class/index:_body -->
<?= Html::script('static/autocomplate/mt_addons.js'); ?>
<?= Html::script('static/autocomplate/BGIframe.js'); ?>
<?= Html::script('static/autocomplate/Meio.Autocomplete.js'); ?>
<?= Html::style('static/autocomplate/style.css'); ?>

<div>
    <div id="main_left">
<p><img src="/static/images/class_title.gif"></p>
<div style="padding:4px 10px">
    <form method="get" action="<?=$_URL?>">
	<select name="start_year" style="padding:1px 4px;">
	    <option value="" > 入学年份 </option>
	    <?php for($i=date('Y');$i>=1900;$i--):?>
	    <option value="<?=$i?>" <?=$start_year==$i?'selected':''?>><?=$i?></option>
	    <?php endfor?>
	  
	</select>
        <input type="text" value="<?=$name?>" id="name" name="name" placeholder="输入名称或专业关键字" size="50" class="input_text" />
    <input type="submit" value="查找"  class="button_blue"/>
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
		<th style="text-align:center">入学~毕业</th>
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
		<td style="text-align:center"><?= $class['start_year'] ? $class['start_year']:'?' ?>~<?= $class['finish_year'] ? $class['finish_year']:'?' ?>年</td>
		<td style="text-align:left"><a href="<?= URL::site('classroom_home/index?id='.$class['id']) ?>"><?= $class['name'] ?></a></td>
		<td style="text-align:center"><?=$class['membercount'] ?>人</td>
		<td style="text-align:center"><?=$class['realname']?></td>
		<td style="text-align:center"><?=$class['create_at']?></td>

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
	 <ul class="ranking">
	<?php foreach($hot_classroom as $c): ?>
	     <li><a href="<?= URL::site('event/static?id='.$c['id']) ?>" target="_blank" title="<?=$c['start_year']?>级<?=$c['name']?>"><?= substr($c['start_year'],-2) ?>级 <?= Text::limit_chars($c['name'],8, '') ?></a><span>(<?=$c['membercount']?>人)</span></li>
	<?php endforeach; ?>
	</ul>
</div>
</div>


    <div class="clear"></div>
</div>


<script type="text/javascript">
    candyPlaceholder('#aaa');
    auto_data = [
        <?php foreach($classnames as $name): ?>
        { 'value':'<?=$name?>', 'text':'<?=$name?>' },
        <?php endforeach; ?>
    ];
    var instance = new Meio.Autocomplete($('name'), auto_data, {
            selectOnTab: false,
            filter: {
                type: 'contains',
                path: 'text'
            }
    });

</script>