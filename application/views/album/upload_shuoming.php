<link rel="stylesheet" type="text/css" href="/static/uploadify/uploadify.css">
<script type="text/javascript" src="/static/uploadify/jquery.uploadify-3.1.js"></script>
<style type="text/css">
    #upload-browse{float: left;background:url(/static/images/album_button_select.gif) no-repeat;display: block;width:135px;height:34px;margin-right: 5px}
    #upload-clear{float: left;background:url(/static/images/album_button_delete.gif) no-repeat;display: block;width:135px;height:34px;margin-right: 5px}
    #upload-upload{float: left;background:url(/static/images/album_button_upload.gif) no-repeat;display: block;width:135px;height:34px;margin-right: 5px}
</style>
<script type="text/javascript">
    var uploadedNum=0;
    var uploaded_id=null;
    $(document).ready(function() {
        $(function() {
            $("#file_upload").uploadify({
                'auto'     : false,
                'fileTypeExts' : '*.gif; *.jpg; *.png',
                'removeCompleted' : true,
                'fileSizeLimit' : '3MB',
                'width'    : 160,
                'queueSizeLimit':50,
                'uploadLimit' : 50,
                'method'   : 'post',
                'multi'    : true,
                'removeTimeout':1,
                'successTimeout' :30,
                'fileObjName':'',
                'buttonText' : '选择本地文件...',
                'swf'          : '/static/uploadify/uploadify.swf',
                'uploader'      : '/api_album/uploadify',
                'formData': {'apikey':'test','uid': '<?= $_UID ?>','token':'<?= $_SESS->get('token') ? $_SESS->get('token') : ''; ?>'},
                //当一个文件从队列中移除时触发；
                'onCancel' : function(file) {
                    //alert('The file ' + file.name + ' was cancelled.');
                },
                //选择文件错误时触发
                'onSelectError' : function() {
                    alert('The file ' + file.name + ' returned an error and was not added to the queue.');
                },
                'onUploadError' : function(file, errorCode, errorMsg, errorString) {
                    alert('The file ' + file.name + ' could not be uploaded: ' + errorString);
                },
                //当清空队列时触发；
                'onClearQueue' : function(queueItemCount) {
                    //alert(queueItemCount + ' file(s) were removed from the queue');
                },
                //当文件(单个)上传成功时触发，不管成功还是失败
                'onUploadComplete' : function(file) {
                    $('#file_upload').uploadify('upload');
                    //alert('The file ' + file.name + ' finished processing.');
                },
                //当上传单个发生错误时触发
                'onUploadError' : function(file) {
                    //alert('The file ' + file.name + ' finished processing.');
                },
                'onUploadStart' : function(file) {
                    //$('#start_button').attr('value','正在上传...');
                },
                //当falsh boject加载完毕时触发；
                'onSWFReady':function(){
                },
                //当每一个文件上传成功时触发
                'onUploadSuccess' : function(file, data, response) {
                    if(data){
                        var json = eval("(" + data + ")");
                        if(json.status==1){
                            uploadedNum=uploadedNum+1;
                            uploaded_id=uploaded_id?uploaded_id+','+json['pic_id']:json['pic_id'];
                        }
                        else{
                            alert('很抱歉，照片上传失败，原因：\n\n'+json.error);
                        }
                    }
                },
                //队列完成时触发
                'onQueueComplete' : function(queueData) {
                    new Facebox({
                        title:'确认上传提示',
                        icon:'succeed',
                        message:'已成功上传'+queueData.uploadsSuccessful+'张照片，是否现在就去添加备注？',
                        okVal:'添加备注',
                        ok: function(){
                            window.location.href='/album/picEdit?id=<?= $_GET['id'] ?>&uploaded_id='+uploaded_id;
                        },
                        cancelVal:'返回相册',
                        cancel:function(){
                            window.location.href='/album/picIndex?id=<?= $_GET['id'] ?>';
                        }
                    }).show();
                }
            });
        });
    });

</script>

<div style="padding: 30px;">
    <div><a href="<?= URL::site('album/picIndex?id=' . $id) ?>">相册<?= $album['name'] ?></a> | 批量上传</div>
    <div style="color:#529214;margin:10px 0">技巧及说明：您可以一次选择一张或多张照片同时上传；允许您上传jpg、gif、png类型图片，且单张图片大小不大于2M。 </div>
    <form action="<?= URL::site('album/uploadPic') . URL::query(array('id' => $id, 'SESSID' => session_id())) ?>"    method="post" enctype="multipart/form-data" id="pics_upload_form">
        <input type="file" name="file_upload" id="file_upload" />
    </form><br><br>
    <div style=" text-align: center; margin: 10px">
        <input type="button" onclick="upload_pic()" class="button_blue" value="开始上传" id="start_button">
        <input type="button" onclick="clear_upload()" class="button_gray" value="全部清除">
    </div>
</div>
<script type="text/javascript">
    function upload_pic() {
        $('#file_upload').uploadify('upload');
    }
    function clear_upload(){
        $('#file_upload').uploadify('cancel','*');
    }
</script>



