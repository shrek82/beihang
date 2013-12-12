<!-- club_admin_base/logo:_body -->
<div id="club_logo">
    <div class="span-10">
        <img src="<?= Model_Club::logo($_ID); ?>" />
        <form action="<?= $_URL ?>" method="post" enctype="multipart/form-data">
            <h5>可以选择格式为(*.jpg, *.png, *gif)的图片更换LOGO</h5>
            <p><input type="file" name="logo" /></p>
            <p><input type="submit" value="上传，更换LOGO" /></p>
            <?php if(isset($error)): ?>
            <div class="notice"><?= $error ?></div>
            <?php endif; ?>
        </form>
    </div>
</div>