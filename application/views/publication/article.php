			    <!--left -->
			    <div id="main_left" style="background-color:#F8FCFF; " >
				<div class="path">当前位置：<a href="<?=URL::site('publication/list?pub_id='.$article['pub_id'])?>"><?=$article['Publication']['name'] ?><?=$article['Publication']['issue'] ?></a>  &gt;  <?=$article['PubColumn']['name']?></div>
                                <h1><?=$article['title'] ?></h1>
				<div class="news_info">作者:<?= $article['author'] ?></div>
				<div class=" dotted_line" style="margin:0px 15px"></div>
				<div class="news_content" id="content">
				    <p>
                        <? $content=   preg_replace('/　　/','',$article['content']) ?></p>
                        <?=  preg_replace('/<br \/>/','</p><p>',$content) ?></p>
</div>

 </div>
<!--//left -->

			    <!--right -->
			    <div id="view_right" >
				<!-- 热门点击-->
				<p class="sidebar_title2" ><span style="color:#c00"><?=$article['PubColumn']['name']?></span>其他文章</p>
				<div class="sidebar_box2">
				    <?php if(!$more_articles):?>
				    <p class="nodata">本栏目暂无其他文章</p>
				    <?php endif;?>
				    <ul class="con_small_list" >
					<?php foreach($more_articles as $a): ?>
					<li><a href="<?= URL::site('publication/article?id='.$a['id']) ?>" title="<?=$a['title'] ?>" ><?= Text::limit_chars($a['title'],13, '..') ?></a></li>
				       <?php endforeach; ?>
				    </ul>
				</div>
				<!-- //热门点击-->

			    </div>
			    <!--//right -->

			    <div class="clear"></div>