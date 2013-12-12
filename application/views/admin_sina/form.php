<!-- admin_news/form:_body -->
<form action="/admin_sina/form" id="weibo_form" method="post"  >
        <table class="admin_table" border="0" cellpadding="0" cellspacing="1" width="100%" >
                <tr>
                        <td  class="td_title" colspan="2">发布新浪微博</td>
                </tr>

                <tr >
                        <td style="padding:10px" colspan="2"><textarea name="text" class="input_text" style="width:100%;height:80px"></textarea></td>
                </tr>
                <tr>
                        <td style="text-align: center">图片</td>
                        <td>
          <input type="hidden" name="img_path" id="filepath" value="" />
         <iframe  id="upfileframe" name="upfileframe" scrolling="no" style="width:500px; height:30px; display:inline" frameborder="0" src="<?=URL::site('admin_upload')?>"></iframe>
<div id="uploading" style="display:none; color:#3993E0;width:600px; height:30px;"><img src="/static/images/loading4.gif"  hspace="4" align="absmiddle"  />正在上传中，请稍候...</div>
                        </td>
                </tr>
                <tr>
                        <td style="padding:20px; text-align: center" colspan="2">
                                <input type="button" value="立即发布" name="button" class="button_blue" onclick="post_weibo()" id="submit_button" />
                                <input type="button" value="取消" onclick="window.history.back()" class="button_gray">
                        </td>
                </tr>

        </table>
</form>

<script type="text/javascript">
        function post_weibo(){
                var form = new ajaxForm('weibo_form', {callback:function(id){
                            window.location.href='/admin_sina/index';
                }});
                form.send();
        }
</script>


