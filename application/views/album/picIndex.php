<script type="text/javascript" >
    $(document).ready(function(){
        $(".colorboxPic").colorbox({rel:'colorboxPic'});
    });
</script>
<style type="text/css">
    .pbox{ float:left; text-align: center; padding: 3px; margin:5px; height: 175px; overflow: hidden }
    .pbox img{ border:1px solid #ccc; padding:2px; }
    #album_title{ padding:5px; color:#333; margin-top:10px; }
</style>

<div id="pics_list_box" style="padding:10px 20px;">

    <div style="font-size:14px;">
        <a href="<?= $url ?>">&lt;&lt;返回<?= $url_name ?></a>
<?php if ($is_allowed_upload): ?>
&nbsp;&nbsp;<a href="<?= URL::site('album/uploadPic?id=' . $album['id'] . '&enc=' . base64_encode(date('d'))) ?>"><img src="<?= URL::base() . 'static/images/user/orderbytopico.gif' ?>"  />上传照片</a>
<?php elseif(!$album['user_id']):?>
&nbsp;&nbsp;<a href="javascript:;" onclick="errorAlert('<?=$banned_upload?>')" style="color:#999"><img src="<?= URL::base() . 'static/images/user/orderbytopico_.gif' ?>"  />上传照片</a>
<?php else:?>
<?php endif; ?>
</div>

    <?php if (count($pics) == 0): ?>
        <div class="nodata" style=" text-align: center; padding: 40px">暂时没有相片。</div>
    <?php else: ?>

        <div id="album_title">
            <h3><?= $url_name ?> > <?= $album['name'] ?>&nbsp;&nbsp;<span style="color:#999;font-weight:normal;font-size:12px">(共<?= $total_pics ?>张)</span></h3>
        </div>
        <?php foreach ($pics as $pic): ?>
            <?php $link = URL::site('album/picView?id=' . $pic['id']) ?>
            <div class="pbox">
                <a href="<?= URL::base().str_replace('_mini','',  str_replace('resize/','', $pic['img_path'])) ?>" title="<?= Text::limit_chars($pic['name'], 10) ?> - <?=$pic['realname']?>上传于 <?=  Date::ueTime($pic['upload_at'])?>" class="colorboxPic" style="cursor:url(/static/big.cur),pointer"><img src="<?= URL::base() . $pic['img_path'] ?>" /></a>
                <br />
                <a href="<?= $link ?>" title="单独打开照片" ><?=$pic['realname']?> 于 <?=  Date::ueTime($pic['upload_at'])?></a>
            </div>
        <?php endforeach; ?>
        <div class="clear"></div>
        <?= $pager ?>

<div style="margin-top: 25px">
    <?php
    //获取和发布可以不一样
    $get_params['album_id'] = $album['id'];
    $params['album_id'] = $album['id'];
    if ($album['event_id']>0 AND isset($event) AND $event AND $event['bbs_unit_id']>0) {
        $params['event_id'] = $album['event_id'];
        $params['bbs_unit_id'] = $event['bbs_unit_id'];
    }
    ?>
    <?= View::factory('inc/comment/newform', array('get_params'=>$get_params,'params' =>$params)) ?>
</div>
    <?php endif; ?>
</div>



<?php if ($upload == 'new' AND $album['aa_id'] === '0'): ?>
    <script type="text/javascript">
        new Facebox({
            title: '温馨提示',
            message: '感谢您的投稿，您的照片将在审核后显示。',
        }).show();
    </script>
<?php endif; ?>