<form action="<?=URL::Base()?>user/doauth" method="post">
<?php if(count($resp) > 0): ?>
    <span style="color:#339900">找到如下匹配数据，请选择您的档案：</span>
<dl>
    <?php foreach($resp as $r): ?>
    <dd>
        <input name="archive" id="a<?=$r['id']?>" type="radio" value="<?=$r['id']?>" <?=$r['is_reged']?'disabled':''?> />
        <label for="a<?=$r['id']?>">
        <?=$r['name']?$r['name']:''?>
	<?=$r['education']?'('.$r['education'].')':''?>
            <?=$r['begin_year']?>年入学 , <?=$r['graduation_year']?$r['graduation_year']:'?' ?>年毕业
            <?=$r['school'].$r['institute'].$r['speciality']?>。
        </label>
        <?=$r['is_reged']?'(网站已注册)':''?>
    </dd>
    <?php endforeach; ?>
    <dd>
        <input name="archive" id="a0" type="radio" value="0"  />
        <label for="a0" style="color:#999">很遗憾，以上没有符合我的档案信息，跳过本步骤。</label>
    </dd>
</dl>
<?php else: ?>
<span style="color:#f30">很抱歉，暂时没有找到您的档案数据，可能是我们的档案数据尚不完整，如果您确实为<?=$_CONFIG->base['alumni_name']?>，别忘了跳过本步骤继续注册。</span>
<?php endif; ?>
<div class="candy-tac" style=" text-align: center">
    <?php if(count($resp) > 0): ?>
    <input type="submit" value="下一步" style="padding:4px 10px"/>
    <?php else: ?>
     <input type="submit" value="跳过继续注册" style="padding:4px 10px"/>
    <?php endif; ?>

</div>
</form>