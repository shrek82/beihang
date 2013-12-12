<!-- mobile/index:_body -->
<div class="g  one-whole"  c2="desk-six-tenths">
    <section class="posts" >
        <?php if ($events): ?>
            <ul class="block-list">
                <?php foreach ($events AS $key => $e): ?>
                    <li>
                        <span class="post_time" style="color:#666"><span style="vertical-align: middle"><img src="/static/app_imag/zuaa/ico_date@2x.png" style="width:15px;height:15px"> </span><?= Model_Event::SatusFinish2($e['start'], $e['finish']); ?></span>

                        <h4 class="post_title"><a href="/mobile/eview?<?= $_AIDSTR ?>&id=<?= $e['id'] ?>"><?= Text::limit_chars($e['title'], 30) ?></a></h4>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </section>

        <?= $pager ?>

</div>


