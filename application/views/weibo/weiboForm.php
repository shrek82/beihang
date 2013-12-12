<link href="/uploadify/uploadify.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="/uploadify/swfobject.js"></script>
<script type="text/javascript" src="/uploadify/jquery.uploadify.v2.1.4.min.js"></script>
<script type="text/javascript">
    $jquery(document).ready(function() {
    	$jquery('#file_upload').uploadify({
	    'uploader'    : '/uploadify/uploadify.swf',
	    'script'      : '/api_album/uploadWeibo',
	    'cancelImg'   : '/uploadify/cancel.png',
	    'folder'      : '/uploads',
	    'auto'        : true,
	    'multi'       : false,
	    'fileExt'     : '*.jpg;*.gif;*.png',
	    'sizeLimit'   : 2*1024*1024+100,
	    'buttonImg'   : '/uploadify/pic_button.png',
	    'onComplete' : function(event, ID, fileObj, response, data) {
		if(response){
		    document.getElementById('weibo_image_path').value=response;
		    document.getElementById('uploadImageMsg').innerHTML='图片上传成功！';
		}
	    }
	});
    });
</script>

<?php if ($_SESS->get('id') AND $_MEMBER): ?>
    <p style="background: url(/static/aa/images/write_title.gif) no-repeat 0 10px; color: #999; padding:5px 10px; text-align: right">您还可以输入<span style="font-size:22px; font-family: 'Footlight MT Light'" id="textcount">250</span>&nbsp;字</p>
    <form method="POST" action="<?= URL::site('weibo/post?id=' . $_ID) ?>" id="weiboform">
        <p style="margin:4px 0">
    	<textarea class="textarea" name="content" accesskey="1" tabindex="1" id="weibo_content"  onkeydown='countChar("weibo_content","textcount",250);' onkeyup='countChar("weibo_content","textcount",250);'><?= isset($topic) ? '#' . $topic . '#' : ''; ?></textarea></p>
        <div class="home_form_tool">
    	<div class="form_tool_left" id="form_tool_left" style=" position: relative">
    	    <a href="javascript:;" onclick="open_expression('form_tool_left','weibo_content')" class="tool_ico_face">表情</a>
    	    <a href="javascript:;" onclick="inTopic('weibo_content','请修改话题名称')" class="tool_ico_unit">话题</a>
    	    <a href="javascript:;" onclick="open_eventbox('form_tool_left')" class="tool_ico_event">活动</a>
    	    <a href="javascript:;" onclick="open_userbox('form_tool_left')" class="tool_ico_user">校友</a>
    	    <p style=" position: absolute;top:0px;left:210px"><input type="file" id="file_upload" name="file_upload"  /></p>
    	</div>
    	<div class="form_tool_right">
    	    <span style="color:#509A1A" id="uploadImageMsg"></span>
    	    <input type="hidden" name="img_path" id="weibo_image_path" value="">
    	    <input type="submit" class="yellow_button" value="" onclick="weibopost()" style="vertical-align:middle;">
    	</div>
    	<div class="clear" ></div>
        </div>
    </form>
<?php endif; ?>