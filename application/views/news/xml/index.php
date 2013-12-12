<?php
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
?>
<data>
    <info>新闻动态</info>
    <description>各地校友会新闻</description>
	<?php foreach ($news as $i => $n): ?>
    	<item>
    	    <title><?= $n['title'] ?></title>
    	    <id><?= $n['id'] ?></id>
    	    <aa><?= $n['aa_name'] ? $n['aa_name'] . '校友会' : '校友总会'; ?></aa>
	    <from><?= $n['from']; ?></from>
    	    <author><?= $n['author_name'] ?></author>
    	    <image><?= $n['small_img_path'] ?'http://zuaa.zju.edu.cn'.$n['small_img_path']:'';?></image>
	    <hits><?=$n['hit']?></hits>
	    <istop><?=$n['is_top']?'yes':'no';?></istop>
	    <ispic><?=$n['is_pic']?'yes':'no';?></ispic>
	    <iscomment><?=$n['is_comment']?'yes':'no';?></iscomment>
    	    <create_date><?= $n['create_at'] ?></create_date>
    	</item>
	<?php endforeach; ?>
</data>