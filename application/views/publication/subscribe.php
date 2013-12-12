<!-- publication/subscribe:_body -->
<div id="main_left">
    <p><img src="/static/images/publication_title.gif" /></p>
    <p style="text-align: right;margin: 10px">年份：
	<select name="year" id="year" onchange="location.href='?id='+this.value">
	<?php foreach($all AS $t):?>
	    <option value="<?=$t['id']?>" <?=$content['id']==$t['id']?'selected':'';?>><?=$t['title']?></option>
	<?php endforeach;?>
	   </select>
    </p>
    <div style="padding:15px;line-height: 1.8em">
	<?=$content['content']?>
    </div>
</div>
<div id="sidebar_right">
<?php include 'sidebar.php';?>
</div>
<div class="clear"></div>