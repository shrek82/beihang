<!-- aa_admin_theme/index:_body -->
<style type="text/css">
        .themeul li{width:150px;height: 150px; float: left; margin-right: 15px; color: #999; text-align: center}
        .themeul .box{width:150px;height: 110px;margin:5px 0}
</style>
<div id="admin950">
        <form id="aa_base" action="<?= URL::query(); ?>" method="post" enctype="multipart/form-data">
                <p style="height:30px" >默认背景：</p>
                <ul class="themeul">
                        <?php foreach ($themes as $key => $t): ?>
                                <li >
                                        <label for="theme_<?= $key ?>"><img src="/static/aa/<?= $key ?>/demo.jpg" ></label><br>
                                        <input type="radio" name="theme" value="<?= $key ?>"  id="theme_<?= $key ?>"  <?= $aaTheme['theme'] == $key ? 'checked' : ''; ?>/><label for="theme_<?= $key ?>"><?= $t['name'] ?></label>
                                </li>
                        <?php endforeach; ?>
                </ul>
                <div class="clear"></div>

                <p style="height:30px" >自定义：<input type="radio" name="usercustom" value="1" <?= $aaTheme['usercustom'] ? 'checked' : ''; ?> />开启 <input type="radio" name="usercustom" value="0"  <?= !$aaTheme['usercustom'] ? 'checked' : ''; ?>/>关闭&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#999">开启后将使用自定义背景</span></p>
                <p style="height:30px">背景图片：<input type="file" name="file">  <span style="color:#999">建议使用1400*600渐变图片</span>&nbsp;&nbsp;&nbsp;<?php if ($aaTheme['background_image']): ?><a href="/static/aa/background/<?= $aaTheme['background_image'] ?>" target="_blank" >查看已经上传图片</a><?php endif; ?></p>
                <p style="height:30px">背景颜色：<input type="text" name="background_color" value="<?= $aaTheme['background_color'] ?>"> <span style="color:#999">例如：#FFFFFF</span></p>

                <p style="margin:10px 0px"><input type="submit" value="修改主题" class="button_blue" /></p>

        </form>
</div>