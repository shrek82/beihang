<?php if ($_UID AND $_MEMBER): ?>
    <?php
    //帐号绑定信息
    $binding = Model_Binding::getBinding(array(
                'user_id' => $_UID,
                'service' => 'sina',
            ));
    ?>
    <p class="write_weibo_title">您还可以输入<span style="font-size:22px; font-family: 'Footlight MT Light'" id="textcount">250</span>&nbsp;字</p>
    <form method="POST" action="<?= URL::site('weibo/post?id=' . $_ID) ?>" id="weiboform">
        <p style="margin:4px 0">
            <textarea class="textarea" name="content" accesskey="1" tabindex="1" id="weibo_content"  onkeydown='countChar("weibo_content","textcount",250);' onkeyup='countChar("weibo_content","textcount",250);'><?php if (isset($topic)): ?>#<?= $topic ?>#<?php else: ?><?= $_THEME['weibo_topic'] ?><?php endif; ?></textarea></p>
        <div class="home_form_tool">
            <div class="form_tool_left" id="form_tool_left" style=" position: relative">
                <a href="javascript:;" onclick="open_expression('form_tool_left','weibo_content')" class="tool_ico_face">表情</a>
                <a href="javascript:;" onclick="inTopic('weibo_content','请修改话题名称')" class="tool_ico_unit">话题</a>
                <a href="javascript:;" onclick="open_eventbox('form_tool_left')" class="tool_ico_event">活动</a>
                <a href="javascript:;" onclick="open_userbox('form_tool_left')" class="tool_ico_user">点名</a>
                <p style=" position: absolute;top:0px;left:210px;width:50px"><input id="file_upload" name="file_upload" type="file"  /></p>
            </div>
            <div class="form_tool_right">
                <span style="color:#509A1A" id="uploadImageMsg"></span>
                <?php if ($binding): ?><img src="/static/logo/sina/16x16_.png" style="vertical-align: middle;" title="同步到新浪微博，点击选择或取消" onclick="if(document.getElementById('share_sina').value=='yes'){this.src='/static/logo/sina/16x16_.png';document.getElementById('share_sina').value='no'}else{this.src='/static/logo/sina/16x16.png';document.getElementById('share_sina').value='yes'}"/>&nbsp;
                    <input type="hidden" value="no" name="share_sina" id="share_sina"><?php else: ?><?php endif; ?>
                <?php if (($_MEMBER && $_MEMBER['manager'])): ?><span style="color:#999" style="vertical-align: middle"><input type="checkbox" name="about_org" value="1" style="vertical-align: middle" id="about_org"/><label style="vertical-align: middle" for="about_org" title="有关校友会的新鲜事">校友会的</label></span><?php endif; ?>
                <input type="hidden" name="img_paths" id="weibo_image_paths" value="">
                <input type="button" class="weiboPostButton" id="weiboPostButton" value="" onclick="weibopost()" style="vertical-align:middle;">
            </div>
            <div class="clear" ></div>
        </div>
    </form>
    <script type="text/javascript">
        var uploadedNum=0;
        $(document).ready(function() {
            $('#file_upload').uploadify({
                'uploader'    : '/uploadify/uploadify.swf',
                'script'      : '/api_album/uploadify',
                'cancelImg'   : '/uploadify/cancel.png',
                'folder'      : '/uploads',
                'auto'        : true,
                'multi'       : true,
                'fileExt'     : '*.jpg;*.gif;*.png',
                'sizeLimit'   : 2*1024*1024+100,
                'buttonImg'   : '/uploadify/pic_button.png',
                'scriptData': {'apikey':'test','uid': '<?= $_UID ?>','token':'<?= $_SESS->get('token') ? $_SESS->get('token') : ''; ?>'},
                'onComplete' : function(event, ID, fileObj, response, data) {
                    if(response){
                        var json = eval("(" + response + ")");
                        if(json.status==1){
                            var imagePaths=document.getElementById('weibo_image_paths');
                            imagePaths.value=imagePaths.value.length>5?imagePaths.value+'||'+json['smail_path']:json['smail_path'];
                            uploadedNum=uploadedNum+1;
                            document.getElementById('uploadImageMsg').innerHTML='成功上传'+uploadedNum+'张照片！';
                        }
                        else{
                            alert('很抱歉，照片上传失败，原因：\n\n'+json.error);
                        }
                    }
                }
            });
        });

    </script>
<?php endif; ?>
