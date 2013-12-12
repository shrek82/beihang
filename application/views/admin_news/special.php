<!-- admin_news/special:_body -->
<!-- admin_news/special:_body -->
<style type="text/css">
#news_special_form{ padding: 10px; background: #fcfcfc; border: 1px solid #eee; }
</style>

<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" style="margin-top:9px ">
<tr >
<td height="29" class="td_title" colspan="6"><b>新闻专题：</b>
</td>
</tr>
<?php if(count($all_special) == 0): ?>
<tr>
<td height="25" style="padding:0px 10px" colspan="6">
<span class="nodata">没有任何新闻专题。</span>
</td>
</tr>
<?php else: ?>
    <tr>
         <td style="width:50px; text-align: center">ID</td>
         <td style="width:450px;">专题名称</td>
         <td style="text-align: center">上传照片</td>
         <td style="text-align: center">直播微博关键字</td>
          <td style="text-align: center">首页显示</td>
          <td style="text-align: center">删除</td>
    </tr>
<?php foreach($all_special as $c): ?>
<tr>
        <td style="width:20px; text-align: center"><?= $c['id'] ?></td>
        <td><a href="/admin_news/special?special_id=<?=$c['id']?>" title="点击修改"><?= $c['name'] ?></a></td>
        <td style="text-align: center"><?php if(isset($c['album_id'])):?><a href="<?= URL::site('album/uploadPic?id='.$c['album_id'].'&enc='.base64_encode(date('d'))) ?>" target="_blank">上传照片</a><?php else:?>没有相册<?php endif;?></td>
        <td style="text-align: center"><a href="/admin_sina/index?q=<?=$c['weibo_topic']?>" title="点击查看话题"><?= $c['weibo_topic']?$c['weibo_topic']:'-';?></a></td>
        <td style="text-align: center"><?=$c['is_displayweibo_on_home']?'已显示':'不显示';?></td>
        <td style="text-align: center">删除</td>
</tr>
<?php endforeach; ?>
<?php endif; ?>
</table>

<table class="admin_table" border="0" cellpadding="0" cellspacing="1" width="100%">
    <tr>
<td>
<form action="<?= URL::query(); ?>" id="news_special_form" method="post">
    <div><label>专题名称</label><br />
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
        <input type="checkbox" name="is_displayweibo_on_home" value="1" <?= $special['is_displayweibo_on_home'] ? 'checked':'' ?> id="display1"/>  <label for="display1">在首页显示微博直播</label><br>
       <input type="checkbox" name="is_displaycomment_on_home" value="1" <?= $special['is_displaycomment_on_home'] ? 'checked':'' ?> id="display2"/>  <label for="display2">在首页显示微博评论</label>
    </div>
    <div>
        <input type="hidden" name="id" value="<?= $special['id'] ?>" />
        <input type="submit" value="<?= $btn ?>"  class="button_blue" />
    </div>
</form>
</td>
    </tr>
</table>
