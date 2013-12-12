<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>上传图片</title>
        <script language="javascript">
            //提交表单时
            function submitform() {
                parent.document.getElementById("uploading").style.display = "";
                parent.document.getElementById("upfileframe").style.display = "none";
                document.getElementById("uploadform").submit();
            }
<?php if ($error): ?>
                //错误提示
                parent.document.getElementById("uploading").style.display = "none";
                parent.document.getElementById("upfileframe").style.display = "";
                alert('<?= $error ?>');
<?php endif ?>
        </script>


        <style type="text/css">
            body{ margin:0px;padding: 0; font-size:12px; font-family:Verdana; }
        </style>
    </head>
    <body>

        <?php if (!$file_path): ?>
            <form id="uploadform" name="uploadform" enctype="multipart/form-data" method="post" action="<?= URL::site('aa_admin/uploadAttachment?id=' . $_ID) ?>" style="margin:0px" onchange="submitform()">
                <input type="file" name="file"   />
                <input type="submit" name="button" id="button" value="上传" />
                <span style="color:#999">

                    （文件不大于5M)</span>

            </form>
        <?php else: ?>
            <script language="javascript">
                var ext = '<?= $file_extend ?>';
                var img;
                switch (ext)
                {
                    case "doc":
                        img = "page_word.png";
                        break;
                    case "xls":
                        img = "page_white_excel.png";
                        break;
                    case "docx":
                        img = "page_word.png";
                        break;
                    case "xlsx":
                        img = "page_white_excel.png";
                        break;
                    case "ppt":
                        img = "file-ppt.png";
                        break;
                    case "rar":
                        img = "file_rar.gif";
                        break;
                    case "zip":
                        img = "file_rar.gif";
                        break;
                    case "txt":
                        img = "file-txt.png";
                        break;
                    case "pdf":
                        img = "doc_pdf.png";
                        break;
                    default:
                        img = "att.png";
                }
                //向表单返回数据
                var insert_html = '<p><span style="vertical-align:middle" ><img src="/static/ico/' + img + '" border="0" style="margin:0px 5px;border-width:0;vertical-align: middle" ></span><a href="<?= $file_path ?>" title="<?= $old_file_name ?>" /><?= $old_file_name . '.' . $file_extend ?></a></p>';
                parent.insertHtml('content', insert_html);
                parent.document.getElementById("uploading").style.display = "none";//隐藏loging
                parent.document.getElementById("upfileframe").style.display = "";//显示上传窗口
                setTimeout(function() {
                    window.location.href = "<?= 'aa_admin/uploadAttachment?id=' . $_ID; ?>";
                }, 500);

            </script>
            <span style="font-size:12px; color:#393;">附件上传成功！<a href="<?= URL::site('aa_admin/uploadAttachment?id=' . $_ID) ?>" style="font-size:12px;text-decoration:none; color:#393">点击继续上传</a></span>
        <?php endif; ?>
    </body></html>