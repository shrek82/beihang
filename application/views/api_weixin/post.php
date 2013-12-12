<!-- api_weixin/post:_body -->
模拟发送消息：
<form name="weixin" method="POST" action="/api_weixin/" id="testform">
<input type="submit" name="d" value="提交"><br>
<input type="button" name="d" value="ajax" id="submit_button" onclick="send()"><br>
</form>

<script type="text/javascript">
function send(){
        new ajaxForm('testform',{textSending: '发送中',textError: '重试',textSuccess: '发送成功',callback:function(xml){
                    console.log(xml);
                }}).send();
}
</script>
