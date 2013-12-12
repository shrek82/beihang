<!-- user_news/form:_body -->
<div id="big_right">
    <div id="plugin_title">新闻</div>

    <div class="tab_gray" id="user_topbar" style="margin-top:10px">
	<ul>
	    <li><a href="<?= URL::site('user_news/index') ?>" style="width:50px">我的投稿</a></li>
	    <li><a href="<?= URL::site('user_news/form') ?>" class="cur" style="width:50px">我要投稿</a></li>
	</ul>
    </div>

    <?= View::factory('inc/news/form', array('news' => $news)) ?>
</div>