<!-- alumni/people:_body -->
<style type="text/css">
#people_abc{ font-size: 1.4em; padding: 10px; }
#people_abc a{ padding: 3px 5px; }
#people_abc a.cur{ background: #eee; color:#333; }
#pplink li{ float: left; height: 30px; margin-right: 10px;width:60px}
#pplink a{ font-size: 14px; padding: 3px 5px; text-decoration: none; border-bottom: 1px dotted #ccc; margin-bottom: 5px }
#pplink a:hover, #pplink a.cur{ border-bottom: 1px solid green; }
</style>
<div id="people_abc">
    字母索引: 
    <?php for($abc='A';$abc<'Z';$abc++): ?>
    <a class="<?= $abc == Arr::get($_GET, 'abc') ? 'cur':'' ?>" href="<?= URL::query(array('abc'=>$abc)) ?>"><?= $abc ?></a>
    <?php endfor; ?>
    <a href="<?= URL::query(array('abc'=>'Z')) ?>">Z</a>
    <a href="<?= URL::site('alumni/people') ?>">全部</a>
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
	<li><a class="<?= $p['id'] == $id ? 'cur':'' ?>" href="<?= URL::site('alumni/people/'.$p['id']) ?>"><?= $p['name'] ?></a></li>
        <?php endforeach; ?>
    </ul>
    <?php endif; ?>
</div>
<Br>
<div class="clear"></div>
<Br>
<div class="span-19 last">
    <table>
        <tr>
            <td><img style="padding: 5px; border: 1px solid #eee" src="<?= $cur_people['pic']; ?>" width="190" /></td>
            <td valign="top" style="padding:0px 20px">
                <h3><?= $cur_people['name'] ?><span style="font-weight:normal">（<?=$cur_people['birth']?$cur_people['birth']:'?'?> ~ <?=$cur_people['leave']?$cur_people['leave']:'' ?>）</span></h3>
                <div style="line-height: 1.6em"><?=nl2br(htmlspecialchars( $cur_people['intro'])) ?></div>
            </td>
        </tr>
    </table>
</div>
