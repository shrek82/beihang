<!-- mobile_home/index:_body -->
<div class="g  one-whole  desk-six-tenths" >
    <section class="posts" >
        <?php if($event):?>
        <ul class="block-list">
<?php foreach ($event AS $key => $e): ?>
            <li>

            <h4 class="post_title"><a href="/mobile_home/eview?id=<?= $_ID ?>&eid=<?= $e['id'] ?>"><?= Text::limit_chars($e['title'], 30) ?></a></h4>
<span class="post_time" style="color:#666"><span style="vertical-align: middle"><img src="/static/app_imag/zuaa/ico_date@2x.png" style="width:15px;height:15px"> </span><?= date('Y-m-d', strtotime($e['start'])); ?> 星期六</span>
            <span class="post_time"><span style="vertical-align: middle"><img src="/static/app_imag/zuaa/ico_event_view_address@2x.png" style="width:15px;height:15px"> </span><?= $e['address'] ?></span>
            </li>
 <?php endforeach; ?>
        </ul>
        <?php endif;?>
    </section>

    <ol class="nav  pagination">
        <li class="pagination_prev">Previous</li>
        <li class="pagination_next"><a href="/page2">Next</a></li>
    </ol>
</div>
