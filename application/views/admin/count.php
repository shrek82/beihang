<!-- admin/count:_body -->
<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" >
    <tr>
        <td colspan="4" class="td_title" ><b>新闻统计</b></td>
    </tr>
    <tr>
        <td>新闻总数：<a href="<?= URL::site('admin_news/index') ?>" title="点击浏览" ><?= $news_count['all'] ?>条</a></td>
        <td>已审核：<a href="<?= URL::site('admin_news/index?release=1') ?>" title="点击浏览" style="color:#216F05"><?= $news_count['isRelease'] ?>条</a></td>
        <td>未审核：<a href="<?= URL::site('admin_news/index?release=0') ?>" title="点击浏览" style="color:#c00"><?= $news_count['notRelease'] ?>条</a></td>
        <td>今日发布：<a href="<?= URL::site('admin_news/index?today=1') ?>" title="点击浏览" style="color:#0A36A7"><?= $news_count['today'] ?>条</a></td>
    </tr>
    <tr>
        <td colspan="4" class="td_title" ><b>注册用户</b></td>
    </tr>
    <tr>
        <td>今日注册：<a href="<?= URL::site('admin_user/index') ?>" title="点击浏览" style="color:#0A36A7"><?= $user_count['today'] ?>人</a></td>
        <td>本月注册：<a href="<?= URL::site('admin_user/index') ?>" title="点击浏览" style="color:#216F05"><?= $user_count['benyue'] ?>人</a></td>
        <td>上月注册：<a href="<?= URL::site('admin_user/index') ?>" title="点击浏览" style="color:#c00"><?= $user_count['shangyue'] ?>人</a></td>
        <td>所有用户：<a href="<?= URL::site('admin_user/index') ?>" title="点击浏览" ><?= $user_count['all'] ?>人</a></td>
    </tr>

</table>


<!-- 使用帮助 -->
<?php if (!$admin_help): ?>
<?php else: ?>

    <table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" style="margin-top:0px ">
        <tr>
            <td colspan="2" class="td_title">使用帮助</td>
        </tr>
    </table>

    <table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" style="margin-top:0px ">
        <tr>
            <td width="5%" style="text-align:center">序号</td>
            <td style="text-align:left;">标题</td>
            <td width="15%" style="text-align:center">发布日期</td>
        </tr>

        <?php foreach ($admin_help as $key => $i) : ?>
            <tr  id="info_<?= $i['id'] ?>" class="<?php
            if (($key) % 2 == 0) {
                echo'even_tr';
            }
            ?>">
                <td style="text-align:center"><?= $i['id'] ?></td>
                <td style="text-align:left"><a href="/admin_content/view?id=<?= $i['id'] ?>" ><?= $i['title'] ?></a></td>
                <td style="text-align:center"><?= $i['create_at'] ?></td>
            </tr>
    <?php endforeach; ?>
            
    <tr>
        <td colspan="3" class="td_title" ></td>
    </tr>
    </table>

<?php endif; ?>
