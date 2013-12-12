<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>上传pdf</title>
        <script language="javascript">
<?php if ($error): ?>
                //错误提示
                parent.document.getElementById("uploading").style.display = "none";
                parent.document.getElementById("upfileframe").style.display = "block";
                alert('<?= $error ?>');
<?php endif ?>
        </script>

        <style type="text/css">
            body{ margin:0px; font-size:12px; font-family:Verdana; padding: 0; background: #EEF7FD}
        </style>
    </head><body bgcolor="#EEF7FD">

        <?php if (!$file_path): ?>
            <form id="uploadform" name="uploadform" enctype="multipart/form-data" method="post" action="<?= URL::site('admin_publication/uploadpdf') ?>" style="margin:0px; padding: 0" >
                <input type="file" name="pdf"   />
                <input type="submit" name="button" id="button" value="上传所选pdf文件" />
                <span style="color:#999">（文件不大于50M)</span></form>
        <?php else: ?>
            <script language="javascript">
                parent.document.getElementById("pdf").value = "<?= $file_path ?>";//显示上传窗口
                parent.document.getElementById("uploading").style.display = "none";//隐藏loging
                parent.document.getElementById("upfileframe").style.display = "block";//显示上传窗口
                setTimeout(function() {
                    window.location.href = "<?= URL::site('admin_publication/uploadpdf'); ?>";
                }, 500);
            </script>
            <p style="font-size:12px; color:#393; height:20px; line-height:20px">恭喜您，上传成功！<a href="<?= URL::site('admin_publication/uploadpdf') ?>" style="font-size:12px;text-decoration:none; color:#393">点击继续上传</a></p>
        <?php endif; ?>
    </body></html>