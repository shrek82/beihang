<!-- user_news/index:_body -->
<div id="big_right">
    <div id="plugin_title">新闻</div>

    <div class="tab_gray" id="user_topbar" style="margin-top:10px">
        <ul>
            <li><a href="<?= URL::site('user_news/index') ?>" class="cur" style="width:50px">我的投稿</a></li>
            <li><a href="<?= URL::site('user_news/form') ?>" style="width:50px">我要投稿</a></li>
        </ul>
    </div>

    <?php if (count($news) == 0): ?>
        <p class="ico_info icon">
            没有任何您投稿的新闻信息。
        </p>
    <?php else: ?>

        <table width="100%" id="bbs_table">
            <thead>
                <tr >
                    <td>标题</td>
                    <td style=" text-align: center" >浏览次数</td>
                    <td style=" text-align: center">更新日期</td>
                    <td style=" text-align: center">修改</td>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($news as $n): ?>
                    <tr style="border-bottom: 1px dotted #eee" >
                        <td class="title">
                            <a href="<?= URL::site('news/view?id=' . $n['id']) ?>" style="color:<?= $n['title_color'] ?>"  target="_blank"><?= $n['title'] ?></a>
                            <?php if ($n['is_draft'] == TRUE): ?>[草稿]<?php endif; ?>
                            <?php if ($n['is_release'] == FALSE): ?><span style="color:#f60">[审核中]</span><?php endif; ?>

                            <!--<a href="">删除</a>-->
                        </td>
                        <td class="hit"><?= $n['hit'] ?></td>
                        <td class="date" width="150"><?= date('Y-n-d', strtotime($n['update_at'])); ?></td>
                        <td class="date" width="150"><a href="<?= URL::site('user_news/form?news_id=' . $n['id']) ?>">修改</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?= $pager ?>
    <?php endif; ?>

</div>
