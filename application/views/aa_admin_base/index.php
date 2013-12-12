<!-- aa_admin_base/index:_body -->
<div id="admin950">
    <form id="aa_base" action="<?= URL::query(); ?>" method="post">
        <div style="height:320px">
        <textarea name="intro" id="intro" style="width:99%;height:260px"><?= $aa['intro'] ?></textarea>
            </div>
        <p class="left" style="margin:10px 0"><input onclick="save()" type="button" id="submit_button" value="确认保存" class="button_blue" /></p>
    </form>
</div>
<?=
View::ueditor('intro', array(
    'toolbars' => Kohana::config('ueditor.common'),
    'minFrameHeight' => 400,
    'autoHeightEnabled' => 'false',
));
?>

<script type="text/javascript">
    function save(){
        if(!ueditor.hasContents()){ueditor.setContent('');}
        ueditor.sync();
        new ajaxForm('aa_base',{textSending: '提交中',textError: '重试',textSuccess: '修改成功',callback:function(){okAlert('基本介绍修改成功!')}}).send();
    }


</script>