<!-- aa_admin_bbs/index:_body -->
<div id="admin950">

    <div class="span-4">
        <?php if (count($all_bbs) > 0): ?>
            <?php foreach ($all_bbs as $b): ?>
                <a href="<?= URL::site('aa_admin_bbs/category?id=' . $aa_id . '&bbs_id=' . $b['id']) ?>" title="点击修改"><?= $b['name'] ?></a>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="nodata">暂时还没有创建任何版块!</p>

        <?php endif; ?>
        <br>
        <br>


        <?php if ($bbs): ?>
            <form id="bbs_form" method="post" action="<?= URL::site('aa_admin_bbs/category?id=' . $aa_id . '&bbs_id=' . $bbs['id']) ?>">
                版块名称：<br><input type="text" name="name" value="<?= $bbs['name'] ?>" size="40" class="input_text" /><br>
                版块介绍：<br><textarea name="intro" style="height:80px; width: 60%" class="input_text" ><?= $bbs['intro'] ?></textarea><br>
                版块排序<span style="color:#999">（越小越靠前）</span>：<br><input type="text" name="order_num" value="<?= $bbs['order_num'] ?>" size="40" class="input_text" /><br>
                <br><input type="submit" value="保存修改"  class="button_blue" />
            </form>
        <?php else: ?>
            <form id="bbs_form" method="post" action="<?= URL::site('aa_admin_bbs/category?id=' . $aa_id) ?>">
                版块名称：<br><input type="text" name="name" value="" size="40" class="input_text" /><br>
                版块介绍：<br><textarea name="intro" style="height:80px; width: 60%" class="input_text" ></textarea><br>
                版块排序<span style="color:#999">（越小越靠前）</span>：<br><input type="text" name="order_num" value="" size="40" class="input_text" /><br>
                <br><input type="submit"  value="确认"  class="button_blue" />
            </form>
        <?php endif; ?>


    </div>

</div>
