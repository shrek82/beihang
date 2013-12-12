<!-- admin_sina/comments:_body -->
<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" >
        <tr >
                <td height="29" class="td_title" >
                        <div class="title_name">
                                <b>按属性查看：</b>
                        </div>
                        <div class="title_search">
                                <form name="search" action="" method="get">
                                        <select name="search_type" style="padding:1px 4px;">
                                                <option value="text" <?php if ($search_type == 'text') {
        echo 'selected';
} ?>>按评论</option>
                                                <option value="username" <?php if ($search_type == 'username') {
        echo 'selected';
} ?>>按作者</option>
                                        </select>
                                        <input name="q" type="text" style="width:200px" class="keyinput">
                                        <input type="submit" value="搜索">
                                </form>
                        </div>
                </td>
        </tr>
        <tr>
                <td height="25" style="padding:0px 10px" >
                        快速检索：<a href="<?= URL::site('admin_sina/comments') ?>" style="<?= empty($verify) ? 'font-weight:bold' : '' ?>">所有</a> &nbsp;|&nbsp;
                        <a href="<?= URL::site('admin_sina/comments?verify=yes'); ?>"  style="<?= $verify == 'aa' ? 'font-weight:bold' : '' ?>">已审核</a>&nbsp;&nbsp;
                        <a href="<?= URL::site('admin_sina/comments?verify=no'); ?>"  style="<?= $verify == 'aa' ? 'font-weight:bold' : '' ?>">未审核</a>&nbsp;&nbsp;
                </td>
        </tr>
</table>


<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" style="margin-top:0px " id="table1">
        <tr>
                <td colspan="6" class="td_title">评论管理</td>
        </tr>
        <tr>
                <td width="5%" style="text-align:center">id</td>
                <td width="10%" style="text-align:center">作者</td>
                <td width="50%" >评论内容</td>
                
                <td width="15%" style="text-align:center">发布时间</td>
                <td width="10%"  style="text-align:center">审核</td>
        </tr>

        <?php if (count($comments) == 0): ?>
                <tr>
                        <td colspan="7" style="background-color:#fff;padding:10px; text-align: left; color: #999">没有任何评论信息。</td>
                </tr>
<?php else: ?>

        <?php foreach ($comments as $key => $c): ?>
                        <tr id="news_<?= $c['id'] ?>"   class="<?php if (($key) % 2 == 0) {echo'even_tr';} ?>">
                                <td style="text-align: center"><?= $c['id'] ?></td>
                                <td style="text-align: center"><a href="http://weibo.com/u/<?=$c['cmt_uid']?>" target="_blank" title="浏览微博"><?= $c['cmt_name'] ?></a></td>
                                <td class="news_title"><a href="http://api.t.sina.com.cn/2173025362/statuses/<?=$c['weibo_id']?>" target="_blank" title="浏览微博评论"><?= Text::limit_chars($c['text'],40, '...') ?></a></td>
                                <td class="timestamp" style="text-align: center"><?= $c['created_at'] ?></td>
                                <td style="text-align: center"><input type="checkbox" value="true" onclick="verify(<?= $c['id'] ?>)" <?= $c['is_verify'] ? 'checked' : '' ?> /></td>
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
        function verify(id){
                new Request({
                        url: '<?= URL::site('admin_sina/commentVerify') . URL::query() ?>',
                        type: 'post',
                        data: 'id='+id
                }).send();
        }
</script>