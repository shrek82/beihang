<!-- class/index:_body -->
<div>
    <div id="main_left">
	<p><img src="/static/images/class_title.gif"></p>
	<div style="padding:4px 10px">
	    <form method="get" action="<?= URL::site('classroom/list') ?>">
         <table>
             <tr>
                 <td style="text-align:right">搜索方式：</td>
                 <td><input type="radio" name="searchtype" value="specialty" checked />按专业<input type="radio" name="searchtype" value="classmate" />按同学</td>
             </tr>
             <tr>
                 <td style="text-align:right">入学年份：</td>
                 <td>		<select name="start_year" style="padding:1px 2px;">
       <option value="">年份不限</option>
		    <?php for ($i = date('Y'); $i >= 1900; $i--): ?>
    		    <option value="<?= $i ?>" ><?= $i ?></option>
		    <?php endfor ?>
    		</select></td>
             </tr>
             <tr>
                 <td style="text-align:right">输入关键字：</td>
                 <td><input type="text" value="" id="name" name="keyword" size="60" class="input_text" />&nbsp;&nbsp;<span style="color:#999">输入同学姓名或专业关键字</span></td>
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
    	    #class_year li{ float: left; width: 155px;border:0px solid #ccc; margin:3px 5px; }
    	    #class_year li a{display: block;padding:4px 4px 4px 15px; }
    	    #class_year li a span{ color: #444}
    	    #class_year li a:link,#class_year li a:visited{ display: block;background-color: #F2F7FA}
    	    #class_year li a:hover{background-color: #E3EDF7}
    	</style>
    	<div style="margin:15px 25px; text-align: right">
    	    <span class="middle"><img src="/static/images/+.gif" style="margin:0px 5px"></span><a href="<?= URL::site("classroom/create") ?>">创建班级</a>
    	</div>
    	<div style="margin: 15px 10px">
    	    <h3>已创建班级：</h3>
    	    <ul id="class_year">
		<?php foreach ($classroom AS $c): ?>
	    		<li><a href="<?= URL::site('classroom/list?start_year=' . $c['start_year']) ?>"><?= $c['start_year'] ?>级  <span>(<?= $c['class_count'] ?>个班级)</span></a></li>
		<?php endforeach; ?>
	    	    </ul>

	    	</div>
	        </div>
	        <div id="sidebar_right">
	    	<p class="sidebar_title" >班级录信息</p>
	    	<div class="sidebar_box" style="line-height:1.6em">
	    	    班级总数：<?= $class_count ?> 个<br>
	    	    已加入校友：<?= $class_join_count ?> 位校友
	    	</div>
	<?php if ($myClassroom): ?>
				<p class="sidebar_title" style="border-top-width:0" >我的班级</p>
				<div class="sidebar_box">
				    <ul>
		<?php foreach ($myClassroom as $c): ?>
		    		<li><img src="/static/images/user/ico_house.gif" width="14" height="15" />&nbsp;
		    		    <a href="<?= URL::site('classroom_home/index?id=' . $c['class_room_id']) ?>" style="color:#333" targent="_blank"><?= $c['ClassRoom']['start_year'] ?>级<?= $c['ClassRoom']['speciality'] ?></a><br></li>
		<?php endforeach; ?>
		    	    </ul>
		    	</div>
	<?php endif; ?>

			    	<p class="sidebar_title" style="border-top-width:0" >人气排行榜</p>
			    	<div class="sidebar_box">

	    <?php if (count($hot_classroom)): ?>
			    	    <ul class="ranking">
		<?php foreach ($hot_classroom as $c): ?>
			    		<li><a href="<?= URL::site('classroom_home?id=' . $c['id']) ?>" target="_blank" title="<?= $c['start_year'] ?>级<?= $c['name'] ?>"><?= Text::limit_chars($c['name'],12, '') ?></a><span>(<?= $c['member_num'] ?>人)</span></li>
		<?php endforeach; ?>
			    	    </ul>
	    <?php else: ?>
				    	    <p class="nodata">暂无班级</p>
	    <?php endif; ?>
				    	</div>
				    	<p class="sidebar_title" style="border-top-width:0" >最新加入</p>
				    	<div class="sidebar_box">
				    	    <table>
		<?php foreach ($last_join as $j): ?>
				    		<tr>
				    		    <td><a href="<?= URL::site('user_home?id=' . $j['User']['id']) ?>" title="浏览其主页" target="_blank">
			    <?= View::factory('inc/user/avatar', array('id' => $j['User']['id'], 'size' => 48,'sex'=>$j['User']['sex'])) ?></a></td>
					    <td valign="top"><a href="<?= URL::site('user_home?id=' . $j['User']['id']) ?>" title="浏览其主页" target="_blank"><?= $j['User']['realname'] ?></a><br>
						<a href="<?= URL::site('classroom_home?id=' . $j['ClassRoom']['id']) ?>"><?= $j['ClassRoom']['start_year'] ?>级<?= $j['ClassRoom']['speciality'] ?></a><br>
						<span style="color:#999"><?= Date::span_str(strtotime($j['join_at'])) ?>前</span>
					    </td>
					</tr>
		<?php endforeach; ?>
	    </table>
	    <div class='clear'></div>
	</div>
    </div>
    <div class="clear"></div>
</div>