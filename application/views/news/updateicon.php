<!-- news/updateicon:_body -->

<h1><?=$news['title']?></h1>

<?php foreach($images AS $i):?>
<p><a href="/news/updateicon?id=<?=$news['id']?>&icon=<?=$i?>"><img src="/<?=$i?>" /></a></p><br><br><br>
<?php endforeach; ?>

