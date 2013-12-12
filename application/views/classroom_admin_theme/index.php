<!-- aa_admin_theme/index:_body -->
<style type="text/css">
    .themeul li{width:150px;height: 150px; float: left; margin-right: 15px; color: #999; text-align: center}
    .themeul .box{width:150px;height: 110px;margin:5px 0}
</style>
<script type="text/javascript" src="/static/jscolor/jscolor.js"></script>
<script type="text/javascript" >
    function changeTheme(theme){
        $('#customCssPath').attr('href','/static/homepage/'+theme+'/style.css');
        $('#theme'+theme).attr('checked',true);
    }
</script>
<?php
$themes = Kohana::config('aa_theme');
?>
<div id="admin950">
    <form id="aa_base" action="<?= URL::query(); ?>" method="post" >
        <p style="height:30px" >页面配色：</p>
        <ul class="themeul">
            <?php foreach ($themes as $key => $t): ?>
                <li >
                    <label for="theme_<?= $key ?>"><img src="/static/homepage/<?= $key ?>/demo.jpg"  onClick="changeTheme('<?= $key ?>')" title="点击图片预览效果"></label><br>
                    <input type="radio" name="theme" value="<?= $key ?>"  id="theme_<?= $key ?>"  <?= $theme['theme'] == $key ? 'checked="checked"' : ''; ?>  /><label for="theme_<?= $key ?>"><?= $t['name'] ?></label>
                </li>
            <?php endforeach; ?>
        </ul>
        <div class="clear"></div>

        <table>
            <tr>
                <td style="width:80px">使用背景图：</td>
                <td><input type="radio" name="usercustom" value="1" <?= $theme['usercustom'] ? 'checked' : ''; ?> />开启 <input type="radio" name="usercustom" value="0"  <?= !$theme['usercustom'] ? 'checked' : ''; ?>/>关闭&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#999">开启后将使用自定义背景</span></td>
            </tr>
            <tr>
                <td>背景图片：</td>
                <td><div id="uploading" style="display:none; color:#3993E0;width:600px; height:30px;"><img src="/static/images/loading4.gif"  hspace="4" align="absmiddle"  />正在上传中，请稍候...</div><input type="hidden" name="file" id="filepath" value="<?= $theme['background_image'] ?>" /><iframe  id="upfileframe" name="upfileframe" scrolling="no" style="width:500px; height:30px; display:inline" frameborder="0" src="<?= URL::site('upload/frame?msg=建议使用1400*600渐变图片') ?>"></iframe>
                    <?php if ($theme['background_image']): ?><a href="<?= $theme['background_image'] ?>" target="_blank" >查看已经上传图片</a><?php endif; ?></td>
            </tr>
            <tr>
                <td>背景颜色：</td>
                <td><input type="text" name="background_color" value="<?= $theme['background_color'] ?>" class="color"> <span style="color:#999">例如：#FFFFFF</span></td>
            </tr>
            <tr>
                <td></td>
            </tr>
        </table>
        <p style="margin:10px 0px"><input type="button" onclick="save()" id="submit_button" value="保存修改" class="button_blue" /></p>

    </form>
</div>

<script type="text/javascript">
    function save(){
        new ajaxForm('aa_base',{textSending: '发送中',textError: '重试',textSuccess: '修改成功',callback:function(){
                window.location.reload();
            }}).send();
    }
</script>