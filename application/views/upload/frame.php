<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>上传附件</title>
        <script language="javascript">
            //提交表单时
            function submitform() {
                parent.document.getElementById("uploading2").style.display = "block";
                parent.document.getElementById("upfileframe2").style.display = "none";
                document.getElementById("uploadform2").submit();
            }
<?php if ($error): ?>
                //错误提示
                alert('<?= $error ?>');
                parent.document.getElementById("uploading2").style.display = "none";
                parent.document.getElementById("upfileframe2").style.display = "block";
<?php endif ?>
        </script>
        <style type="text/css">
            body{ margin:0px; font-size:12px; font-family:Verdana;}
        </style>
    </head>
    <body>

        <?php if (!$file_path): ?>
            <form id="uploadform2" name="uploadform" enctype="multipart/form-data" method="post" action="<?= URL::site('upload/frame?msg=' . $msg) ?>" style="margin:0px" >
                <input type="file" name="file"   />
                <input type="submit" name="button" id="button" value="上传" />
                <span style="color:#999">
                    <?php if ($msg): ?><?= $msg; ?><?php else: ?>（文件不大于5M)<?php endif; ?></span>
            </form>
        <?php else: ?>
            <div style="font-size:12px; color:#393; height:25px; line-height:25px">文件上传成功！<a href="<?= URL::site('upload/frame?msg=' . $msg) ?>" style="font-size:12px;text-decoration:none; color:#393">点击重新上传</a></div>
            <script language="javascript">
                //向表单返回数据
                if (parent.document.getElementById("uploading2")) {
                    parent.document.getElementById("uploading2").style.display = "none";//隐藏loging
                    parent.document.getElementById("upfileframe2").style.display = "block";//显示上传窗口
                    parent.document.getElementById("filepath2").value = "<?= $file_path ?>";//上传成功后返回文件路径
                }
                else {
                    parent.document.getElementById("uploading").style.display = "none";//隐藏loging
                    parent.document.getElementById("upfileframe").style.display = "block";//显示上传窗口
                    parent.document.getElementById("filepath").value = "<?= $file_path ?>";//上传成功后返回文件路径
                }

                setTimeout(function() {
                    window.location.href = "<?= URL::site('upload/frame?msg=' . $msg) ?>";
                }, 900000);
            </script>
        <?php endif; ?>
    </body>
</html>