<!-- test/upload:_body -->

<form action="http://zuaa.zju.edu.cn/api_album/post?apikey=test&uid=18119&token=6615b1bfddb8fa21ddcd66a02ef7cf43" method="POST" enctype="multipart/form-data">
上传照片到相册(APP到正式网站)：<input type="file" name="upload_file" value="" />
<input type="text" name="album_id" value="267">
<input type="text" name="content" value="text">
<input type="submit" value="提交" />
</form>

<form action="http://localhost/api_album/post?apikey=test&uid=18119&token=bc788e47bb35e54005e65096d94d6d22" method="POST" enctype="multipart/form-data">
上传照片到相册(APP到本地网站)：<input type="file" name="upload_file" value="" />
<input type="text" name="album_id" value="267">
<input type="text" name="content" value="text">
<input type="submit" value="提交" />
</form>


<form action="http://localhost/upload/uploadAttachedImage" method="POST" enctype="multipart/form-data">
本地上传图片到编辑器：<input type="file" name="picdata" value="" />
<input type="submit" value="提交" />
</form>

<form action="http://localhost/album/uploadPic?id=267&enc=MDg=" method="POST" enctype="multipart/form-data">
本地上传照片到相册：<input type="file" name="Filedata" value="" />
<input type="submit" value="提交" />
</form>

<form action="http://localhost/api_album/uploadify?apikey=test&uid=18119&token=6615b1bfddb8fa21ddcd66a02ef7cf43" method="POST" enctype="multipart/form-data">
<input type="file" name="Filedata" value="" />
<input type="submit" value="提交" />
</form>

<form action="http://localhost/api_album/post?apikey=test&uid=18119&token=6615b1bfddb8fa21ddcd66a02ef7cf43" method="POST" enctype="multipart/form-data">
<input type="file" name="upload_file" value="" />
<input type="text" name="content" value="描述" />
<input type="text" name="album_id" value="描述" />
<input type="submit" value="提交" />
</form>

