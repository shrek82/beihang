<form action="/test/formsub" method="POST" id="test_form">
    <input name="name" class="input_text" size="50">
    <input id="submit_button" class="button_blue" type="button" onclick="save()" value="submit">
    <input  class="button_blue" type="button" onclick="del()" value="del">
    <input class="button_blue" type="button" onclick="facebox()" value="facebox">
    <input class="button_blue" type="button" onclick="request()" value="request">
</form>


<script type="text/javascript" >
    
    function save(){
        new ajaxForm('test_form',{textSending: '发送中',textError: '重试',textSuccess: '修改成功',callback:function(id){
                window.location.reload();    
            }}).send();
    }
    
    function del(){
        new candyConfirm({
            message:'确定要删除该内容吗？',
            url:'/test/formsub',
            callback:function(){alert('ok')}
        }).open();
    }
    
    function facebox(){
        new Facebox({
            title: '填写报名信息',
            url: '/user/msgform',
            cancelFunction:true,
            ok: function(){
                //提交表单
                new ajaxForm('msg_form', {
                    callback:function(data){
                    }
                }).send();
            }
        }).show();
    }
    
    function request(){
        new Request({
            url: '/test/formsub',
            type: 'post',
            success: function(data){
                alert(data);
            }
        }).send();
    }
</script>