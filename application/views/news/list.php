<!--left -->
<div id="main_left">
    <div style="border-bottom:1px solid #E1ECFA">
        <?php if ($aa_id == 0) : ?>
            <img src="/static/images/news_zonghui.gif" />
        <?php else: ?>
                <img src="/static/images/news_difang.gif" />
        <?php endif ?>

            </div>
             <?php if (isset($category)) : ?>
    <p style="padding:10px 15px;font-size:12px; color: #666">总会新闻  > <?= $category['name'] ?>：</p>
             <?php endif ?>
            <div class="con_list a14">
        <?php if (!$news): ?>
                    <div class="nodata">暂时还没有新闻</div>
        <?php endif; ?>
                    <ul>

        <?php if ($aa_id == 0) : ?>
            <?php foreach ($news as $n): ?>
                        <li>
                            <a href="<?= URL::site('news/view?id='.$n['id']) ?>" ><?= Text::limit_chars($n['title'], 35, '...') ?></a><?= $n['is_pic'] ? '&nbsp;<font><img src="/static/images/imgs.gif" title="图片新闻"></font>' : ''; ?><span><?= date('Y-n-d', strtotime($n['create_at'])); ?></span></li>
            <?php endforeach; ?>
        <?php else: ?>
            <?php foreach ($news as $n): ?>
                        <li style="background:none;"><font style="font-size:14px;color:#999" title="<?= $n['aa_name'] ?>">[<?= Text::limit_chars($n['aa_name'], 4, '...') ?>]</font>&nbsp;&nbsp;
                            <a href="<?= URL::site('news/view?id='.$n['id']) ?>" ><?= Text::limit_chars($n['title'], 30, '...') ?></a><?= $n['is_pic'] ? '&nbsp;<font><img src="/static/images/imgs.gif" title="图片新闻"></font>' : ''; ?><span><?= date('Y-n-d', strtotime($n['create_at'])); ?></span></li>
            <?php endforeach; ?>
        <?php endif ?>


                    </ul>

                </div>
                <div style=" text-align: center">
        <?= $pager ?>
                    </div>
                </div>

                <!--//left -->
                
                <!--right -->
                <div id="sidebar_right">

		    <?php if(isset($all_category)):?>
                <p class="sidebar_title">新闻分类</p>
                <div class="sidebar_box">
                    <ul class="sidebar_menus">
            <?php foreach ($all_category as $c): ?>
                            <li><a href="<?= URL::site('news/list?aa_id=0&cid='.$c['id']) ?>" ><?=$c['name'] ?></a></li>
            <?php endforeach ?>
                        </ul>
                    </div>
		<?php endif;?>

                    <!-- 热门点击-->
                    <p class="sidebar_title" style="<?=isset($all_category)?'border-top:0':''?>">一周推荐</p>
                    <div class="sidebar_box">
        <?php if (count($dig_news) === 0) : ?>
                            <span class="nodata">暂无新闻</span>
        <?php else: ?>
                                <ul class="ranking">
            <?php foreach ($dig_news as $n): ?>
                                    <li><a href="<?= URL::site('news/view?id='.$n['id']) ?>"  title="<?= $n['title'] ?>"><?= Text::limit_chars($n['title'], 13, '..') ?></a></li>
            <?php endforeach; ?>
                                </ul>
        <?php endif ?>
    </div>
    <!-- //热门点击-->

</div>
<!--//right -->

<div class="clear"></div>