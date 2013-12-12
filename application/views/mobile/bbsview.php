<div class="g  one-whole" >
    <article class="post" >
        <h4 style="font-weight: bold;clear: both"><?= $u['title'] ?></h4>
        <div style="word-wrap: break-word; word-break: break-all;overflow:hidden;"><?= $u['content'] ?></div>
    </article>

<?= View::factory('mobile/commentlist', array('search' => array('bbs_unit_id' => $u['id'], 'order' => 'DESC', 'limit' =>5))); ?>
</div>
