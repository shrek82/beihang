<form action="<?= URL::query(); ?>" id="news_special_form" method="post">
    <div><label>专题名称</label>(不可重复)<br />
        <input size="50" type="text" name="name" value="<?= $special['name'] ?>" class="input_text"/>
    </div>
    <div><label>首页显示微博数目</label><br />
        <input size="50" type="text" name="weibo_pagesize" value="<?= $special['weibo_pagesize'] ?>" class="input_text"/>
    </div>
    <div><label>显示包含以下话题的微博</label><br />
        <input size="50" type="text" name="weibo_topic" value="<?= $special['weibo_topic'] ?>" class="input_text"/>
    </div>
    <div><label>专题介绍</label><br />
        <textarea style="width:600px; height:80px" name="intro"  class="input_text"><?= $special['intro'] ?></textarea>
    </div>
    <div style=" padding: 10px ">
       <input type="checkbox" name="is_displayweibo_on_home" value="1" <?= $special['is_displayweibo_on_home'] ? 'checked':'' ?> />  在首页显示微博直播<br>
       <input type="checkbox" name="is_displaycomment_on_home" value="1" <?= $special['is_displaycomment_on_home'] ? 'checked':'' ?> />  在首页显示微博评论<br>
    </div>
    <div>
        <input type="hidden" name="id" value="<?= $special['id'] ?>" />

        <input type="submit" id="submit_button" value="<?= $btn ?>"  class="button_blue" />
    </div>
</form>