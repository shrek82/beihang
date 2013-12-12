<style type="text/css">
    #album_info{ font-size:14px; }
    #album_info a{ margin-left:10px; }
    #pic_viewer{ padding:10px; text-align:center; position:relative; }
</style>


<div id="album_info" style="padding:20px">
    <a href="<?= URL::site('album/picIndex?id=' . $pic['album_id']) ?>">&lt;&lt;返回相册 - <?= $pic['Album']['name'] ?></a>
</div>

<div id="pic_viewer" style="font-size:14px">
    <img id="oImg" src="<?= URL::base() . str_replace('_mini', '_bmiddle', str_replace('resize/', '', $pic['img_path'])) ?>" style="padding:4px; border:1px solid #eee;" />
    <br /><br />

    <strong ><?= $pic['name'] ?></strong>		<?php if ($is_allowed_modify): ?>
    <a href="<?= URL::query(array('mod' => 'm')) ?>" title="修改名称"><img src="/static/images/user/table_edit.png"></a>
    <a href="javascript:del(<?= $pic['id'] ?>)" title="删除"><img src="/static/images/no.png"></a>
    <?php endif; ?><br><br>

    第<span style="color:#22a500"><?= $num ?></span>/<?= $total_num ?>张&nbsp;&nbsp;<a href="<?= URL::base() . str_replace('_mini', '', str_replace('resize/', '', $pic['img_path'])) ?>" target="_blank">查看原图</a><br>
    <p class="quiet" style="margin:10px">


    <div style="margin:10px;font-size:12px; color: #666">
        (
        <a href="<?= URL::site('user_home?id=' . $pic['user_id']) ?>"><?= $pic['User']['realname'] ?></a> 上传于<?= $pic['upload_at'] ?>)</div></p>
<?php if ($mod == 'v'): ?>


<?php else: ?>
    名称：<input type="text" name="name" value="<?= $pic['name'] ?>" class="input_text" style="width:467px;margin-bottom:5px"/>
<?php endif; ?>

<?php if ($mod == 'v'): ?>
    <div id="pic_intro">
        <?= $pic['intro'] ?>
    </div>
<?php else: ?>
    <textarea name="intro" style="width:500px; height:100px" class="input_text"><?= $pic['intro'] ?></textarea>
    <input type="hidden" name="id" value="<?= $pic['id'] ?>"  /><br />
    <input type="button" onclick="picSave()" value="保存"  class="button_blue"/>
<?php endif; ?>
</div>


<script type="text/javascript">
    function picSave(){
        new Request({
            data: $('#pic_viewer'),
            url: '<?= URL::site('album/picSave') ?>',
            type: 'post',
            success: function(){
                location.href = "<?= URL::query(array('mod' => 'v')) ?>";
            }
        }).send();
    }


    function del(cid){
        var b = new Facebox({
            title: '删除确认！',
            message: '确定要删除此照片吗？注意删除后不可再恢复！',
            icon:'question',
            ok: function(){
                new Request({
                    url: '<?= URL::site('album/del?alubm_id=' . $pic['album_id'] . '&cid=') ?>'+cid,
                    type: 'post',
                    success: function(){
                        window.location.href='<?= URL::site('album/picIndex?id=' . $pic['album_id']) ?>';
                    }
                }).send();
                b.close();
            }
        });
        b.show();
    }
</script>