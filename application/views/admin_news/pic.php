<!-- admin_news/pic:_body -->
<?php if(count($newspics) == 0): ?>
<p class="ico_info icon">
    还没有任何图片新闻展示内容
</p>
<?php else: ?>
<div id="newspics_box">
    <div class="span-16">
        <img id="newspic" src="<?= URL::site('index/image/'.$newspics[0]['id']) ?>" />
    </div>
    <div class="span-7 last">
    <?php foreach($newspics as $np): ?>
    <a href="<?= $np['url'] ?>" class="newspics_links" rel="<?= $np['id'] ?>"><?= $np['title'] ?></a>
    <a href="?del=<?= $np['id'] ?>">删除</a><br />
    <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<script type="text/javascript">
    $$('.newspics_links').addEvent('mouseenter', function(){
        var id = this.get('rel');
        $('newspic').set('src', '<?= URL::site('index/image') ?>/'+id);
    });
</script>

<div class="clear"></div>

<form action="" method="post" enctype="multipart/form-data">
    <fieldset>
        <legend>首页新闻图片</legend>
        <div><label>标题</label><br />
            <input type="text" name="title" size="40" value="<?= $news['title'] ?>" />
        </div>
        <div><label>指向链接</label><br />
            <input type="text" name="url" size="80" value="<?= $news ? URL::site('news/view?id='.$news['id']):'' ?>" />
        </div>
        <div><label>图片</label>(尺寸最好为663x185px)：<br />
            <input type="file" name="pic" /></div>
        <div>
            <input type="hidden" name="news_id" value="<?= $news['id'] ?>" />
            <input type="submit" value="提交" />
        </div>
        <?php if(isset($err)): ?>
        <div class="error"><?= $err; ?></div>
        <?php endif; ?>
    </fieldset>
</form>