<!-- admin_sina/comments:_body -->
<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" >
        <tr >
                <td height="29" class="td_title" >
                        <div class="title_name">
                                <b>新浪微博：</b>
                        </div>
                        <div class="title_search">
                                <form name="search" action="" method="get">
                                        <select name="search_type" style="padding:1px 4px;">
<option value="text" <?php if (isset($search_type) AND $search_type == 'text') {echo 'selected';} ?>>按内容</option></select>
                                        <input name="q" type="text" style="width:200px" class="keyinput">
                                        <input type="submit" value="搜索">
                                </form>
                        </div>
                </td>
        </tr>
        <tr>
                <td height="25" style="padding:0px 10px" >
                        快速检索：<a href="<?= URL::site('admin_sina/index') ?>" style="<?= empty($verify) ? 'font-weight:bold' : '' ?>">所有</a>
                </td>
        </tr>
</table>


<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" style="margin-top:0px " id="table1">
        <tr>
                <td colspan="6" class="td_title">新浪微博</td>
        </tr>
        <tr>
                <td width="5%" style="text-align:center">id</td>
                <td width="55%" >内容</td>
                <td width="5%" style="text-align:center">浏览</td>
                <td width="10%" style="text-align:center">来源</td>
                <td width="15%" style="text-align:center">发布时间</td>
                <td width="5%" style="text-align:center">操作</td>
        </tr>

        <?php if (count($weibo) == 0): ?>
                <tr>
                        <td colspan="7" style="background-color:#fff;padding:10px; text-align: left; color: #999">没有任何评论信息。</td>
                </tr>
<?php else: ?>

        <?php foreach ($weibo as $key => $w): ?>
                        <tr id="news_<?= $w['id'] ?>"   class="<?php if (($key) % 2 == 0) {echo'even_tr';} ?>">
                                <td style="text-align: center"><?= $w['id'] ?></td>
                                <td class="news_title" style="padding:5px"><?= $w['text']?></td>
                                <td style="text-align: center"><a href="http://api.t.sina.com.cn/2173025362/statuses/<?=$w['idstr']?>" target="_blank" title="浏览微博详细页面">浏览</a></td>
                                <td style="text-align: center"><?= $w['source'] ?></td>
                                <td class="timestamp" style="text-align: center"><?= $w['created_at'] ?></td>
                                  <td style="text-align: center"> <a href="javascript:del(<?=$w['id']?>)">删除</a></td>
                        </tr>
        <?php endforeach; ?>
<?php endif; ?>


</table>

<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" style="margin-top:-1px " >
        <tr>
                <td style="height: 50px"><?= $pager ?></td>
        </tr>
</table>

<script type="text/javascript">
function del(cid){
    var b = new Facebox({
        title: '删除确认！',
        message: '确定要删除此条微博吗？',
        icon:'question',
        ok: function(){
            new Request({
                url: '<?= URL::site('admin_sina/delweibo?cid=') ?>'+cid,
                type: 'post',
                success: function(){
                    candyDel('news_'+cid);
                }
            }).send();
            b.close();
        }
    });
    b.show();
}
</script>