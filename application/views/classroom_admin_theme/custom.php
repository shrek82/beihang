<!-- aa_admin_theme/custom:_body -->
<div id="admin950">
    <p style="height:30px;color:#f60" >在基本样式之上修改背景图片及颜色：</p>
<form id="aa_base" action="/aa_admin_theme/custom?id=<?=$_ID?>" method="post" enctype="multipart/form-data">
    <p style="height:30px" >开启自定义：<input type="radio" name="usercustom" value="1" <?=$aaTheme['usercustom']?'checked':'';?> />开启 <input type="radio" name="usercustom" value="0"  <?=!$aaTheme['usercustom']?'checked':'';?>/>关闭</p>
    <p style="height:30px">自定义背景图片：<input type="file" name="file">  <span style="color:#999">建议使用1400*600渐变图片</span>&nbsp;&nbsp;&nbsp;<?php if($aaTheme['background_image']):?><a href="/static/aa/background/<?=$aaTheme['background_image']?>" target="_blank" >查看已经上传图片</a><?php endif;?></p>
    <p style="height:30px">自定义背景颜色：<input type="text" name="background_color" value="<?=$aaTheme['background_color']?>"> <span style="color:#999">例如：#FFFFFF</span></p>
<p style="margin:10px 0"><input type="submit" value="保存修改" class="button_blue" /></p>
</form>
</div>