<a href="javascript:test()">测试</a>
<a href="javascript:test()">测试</a>

<script type="text/javascript">
    function test() {
        new Request({
            url: '/user_msg/faceboxmsg',
            type: 'post',
            success:function(data){
                if(data){
                    var jsondata=eval('('+data+')');
                    faceboxNotice({
                        'title':jsondata.title,
                        'width':250,
                        'okVal':'查看或回复',
                        'icon':'social_email',
                        'ok':function(){
                            sendMsg(jsondata.user_id,jsondata.msg_id);
                        },
                        'time':3600,
                        'message':jsondata.msg_content
                    })
                }
            }
        }).send();
    }
</script>
