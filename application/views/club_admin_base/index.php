<!-- club_admin_base/index:_body -->
<div id="admin950">
<form id="club_base" action="<?= URL::query(); ?>" method="post">

名称：<input size="60" type="text" name="name" value="<?= $club['name'] ?>"  class="input_text" /><br>
行业：<input size="60" type="text" name="type" value="<?= $club['type'] ?>" class="input_text"/><br>
LOGO：
<div id="uploading" style="display:none; color:#3993E0;width:600px; height:30px;">
    <img src="/static/images/loading4.gif"  hspace="4" align="absmiddle"  />正在上传中，请稍候...</div>
<input type="hidden" name="logo_path" id="filepath" value="<?= $club['logo_path'] ?>" />
<iframe  id="upfileframe" name="upfileframe" scrolling="no" style="width:500px; height:30px; display:inline" frameborder="0" src="<?= URL::site('upload/frame?msg=固定大小80*80px') ?>"></iframe>
<textarea name="intro" id="intro" style="height:300px;width:100%"><?= $club['intro'] ?></textarea>
<p class="left" style="margin:10px 0"><input onclick="save()" type="submit" id="submit_button" value="确认保存" class="button_blue" /></p>
</form>
</div>
<?=View::ueditor('intro', array(
    'toolbars' => Kohana::config('ueditor.common'),
    'minFrameHeight' => 200,
    'autoHeightEnabled' => 'false',
));
?>
<script type="text/javascript">
    function save(){
        var aa_base = new ajaxForm('club_base',{textSending: '发送中',textError: '重试',textSuccess: '修改成功'});
       if(!ueditor.hasContents()){ueditor.setContent('');}
        ueditor.sync();
        aa_base.send();
    }
</script>