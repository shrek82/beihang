    <p class="sidebar_title">刊物分类</p>
    <div class="sidebar_box" style="height:650px">
<ul class="sidebar_menus">
<?php foreach(Model_Publication::$pub_type AS $key=>$t) :?>
    <li><a href="<?=URL::site('publication?type='.$key) ?>" style="<?=$_A=='index'?'font-weight:bold':''?>"><?=$t?></a></li>
<?php endforeach; ?>

</ul>
    </div>