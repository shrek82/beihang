<!-- people/index:_body -->
<!--body -->
<!--left -->
<style type="text/css">
#people_abc{ font-size: 14px; padding: 10px; }
#people_abc a{ padding: 3px 2px; }
#people_abc a.cur{ background: #eee; color:#333; }
#pplink li{ float: left; height: 30px; margin-right: 10px;width:60px}
#pplink a{ font-size: 14px; padding: 3px 5px; text-decoration: none; border-bottom: 1px dotted #ccc; margin-bottom: 5px }
#pplink a:hover, #pplink a.cur{ border-bottom: 1px solid green; }
</style>
<div id="main_left">
    <p><img src="/static/images/academician_title.gif" /></p>
    <div class="con_list a14">
<div id="people_abc">
    字母索引:
    <?php for($abc='A';$abc<'Z';$abc++): ?>
    <a class="<?= $abc == Arr::get($_GET, 'abc') ? 'cur':'' ?>" href="<?= URL::query(array('abc'=>$abc)) ?>"><?= $abc ?></a>
    <?php endfor; ?>
    <a href="<?= URL::query(array('abc'=>'Z')) ?>">Z</a>
    <a href="<?= URL::site('people/academician') ?>">全部</a>
</div>
<hr />
<div>
    <?php if(count($people) == 0): ?>
    <p class="ico_info icon">
        没有找到相关的院士名单。
    </p>
    <?php else: ?>
    <ul id="pplink">
        <?php foreach($people as $p): ?>
	<li><a href="<?= URL::site('people/aView?id='.$p['id']) ?>"><?= $p['name'] ?></a></li>
        <?php endforeach; ?>
    </ul>
    <?php endif; ?>
</div>
        </div>

            </div>
            <!--//left -->

            <!--right -->
<?php
include 'sidebar.php';
?>
<!--//right -->

<div class="clear"></div>
<!--//body -->