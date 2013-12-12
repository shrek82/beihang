<!-- aa_admin_notice/index:_body -->
<div id="admin950">
<form id="aa_base" action="<?= URL::query(); ?>" method="post">
<textarea name="notice" id="notice" style="width: 95%; height: 200px"><?= $_AA['notice'] ?></textarea>
<p style="color:#999;margin:10px 0">最多不超过200个字符</p>
<p style="margin:10px 0"><input onclick="save()" type="button" id="submit_button" value="确认修改" class="button_blue" /></p>

</form>
</div>
<script type="text/javascript">
    function save(){
        var aa_base = new ajaxForm('aa_base');
        aa_base.send();
    }
</script>