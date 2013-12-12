<!-- mobile/index:_body -->
<div class="g  one-whole"  c2="desk-six-tenths">
    <section class="posts" >
        <?php if ($news): ?>
            <ul class="block-list">
                <?php foreach ($news AS $key => $n): ?>
                    <li>
                        <span class="post_time" style="color:#666"><span style="vertical-align: middle"><img src="/static/app_imag/zuaa/ico_date@2x.png" style="width:15px;height:15px"> </span><?= date('Y-n-d', strtotime($n['create_at'])); ?></span>
                        <h4 class="post_title"><a href="/mobile/newsview?<?=$_AIDSTR?>&id=<?= $n['id'] ?>"><?= Text::limit_chars($n['title'], 30) ?></a></h4>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </section>

    <div>
        <?= $pager ?>
    </div>

</div>


