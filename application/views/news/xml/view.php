<?php
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
?>
<data>
    <title><?= $news['title'] ?></title>
    <id><?= $news['id'] ?></id>
    <aa><?= $news['aa'] ?></aa>
	<author><?= $news['author_name'] ?></author>
	<hits><?= $news['hit'] ?></hits>
	<reply><?= $news['reply_num'] ?></reply>
	<istop><?= $news['is_top'] ? 'yes' : 'no'; ?></istop>
	<ispic><?= $news['is_pic'] ? 'yes' : 'no'; ?></ispic>
	<iscomment><?= $news['is_comment'] ? 'yes' : 'no'; ?></iscomment>
	<create_date><?= $news['create_at'] ?></create_date>
	<intro><?= Common_Global::mobileText($news['intro']) ?></intro>
	<content><?= Common_Global::mobileText($news['content']) ?></content>
	<?php if ($news['images']): ?>
            <images>
	    <?php foreach ($news['images'] as $image): ?>
    	    <image><?= $image ?></image>
	    <?php endforeach; ?>
            </images>
	<?php endif; ?>
</data>