<!-- inc/club/admin_action:_body_left -->
<style type="text/css">
#club_admin_action a{ display: block; border-bottom: 1px dotted #eee; padding: 2px 5px; }
#club_admin_action a.cur{ font-weight: bold;}
</style>
<div style="padding:10px">
    <?php foreach($actions as $uri => $action): ?>
    <a href="<?= URL::site($uri.'?id='.$_ID.'&club_id='.$_ID) ?>" class="<?= $uri == $_C.'/'.$_A ? 'cur':'' ?>"><?= $action ?></a>
    <?php endforeach; ?>
</div>
