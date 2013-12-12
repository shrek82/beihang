<!-- mobile/index:_body -->
<div class="g  one-whole" >
                <?php if (!$_SETTING['close_bbs']): ?>

                    
    <section class="posts" >
        <?php if ($list): ?>
            <ul class="block-list">
                <?php foreach ($list AS $key => $u): ?>
                    <li style="padding-left: 47px;position: relative">
                        <img src="<?= $u['updater_avatar'] ?>" style="border-width: 0;vertical-align: middle;position: absolute;left: 1px;width:40px;-webkit-border-radius: 5px;border-radius: 5px;">
                        <h5 class="post_title"><a href="/mobile/bbsview?<?=$_AIDSTR?>&id=<?= $u['id'] ?>"><?=Db_Event::replaceTitle($u['title'])?></a></h5>
<span class="post_time" style="color:#666"><span style="vertical-align: middle"><img src="/static/app_imag/zuaa/ico_date@2x.png" style="width:15px;height:15px"></span> <?= $u['statuses'] ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </section>
    <div>
        <?= $pager ?>
    </div>
                <?php else: ?>
    <div style='color:#999;line-height: 50px'>很抱歉，论坛临时关闭中，请稍候访问，谢谢！</div>
                <?php endif; ?>
</div>