<!-- user_publication/view:_body -->

<div id="big_right">
    <div id="plugin_title">刊物</div>

    <div class="tab_gray" id="user_topbar" style="margin-top:10px">
	<ul>
	    <li><a href="<?= URL::site('user_publication/index') ?>" style="width:50px">我的投稿</a></li>
	    <li><a href="<?= URL::site('user_publication/form') ?>" style="width:50px">我要投稿</a></li>
	     <li><a href="" style="width:50px" class="cur" >文章预览</a></li>
	</ul>
    </div>

    <h3 style="text-align:center"><?=$pub['title']?></h3>
    <div class="line_dotted"></div>
    <div style="line-height: 1.6em;"><?=$pub['content']?></div>
    <?php if($pub['reply']):?>
    <br>
    <p><b>管理员回复：</b></p>
    <p style="color:#f30;"><?=$pub['reply']?></p>
    <?php endif;?>
    <div style="margin: 20px; text-align: center"><input type="button" value="返回" onclick="window.history.back()" class="button_gray"></div>


</div>