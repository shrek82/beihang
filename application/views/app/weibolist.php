<!-- app/weibolist:_body -->
<h3 style="padding:0px 20px; text-align: right; margin:10px 0; color: #999; font-weight: normal">本操作用于申请新浪微博接口预备工作，谢谢支持！</h3>
<h2 align="left">发送新浪微博</h2>
<form action="/app/post" method="POST" id="weiboform">
                <textarea name="content" id="content" style="width:650px;height:60px" class="input_text" ></textarea>
                <input type="button" id="submit_button" value="发送" onclick="post()" class="button_blue" />
        </form>
<h2>我最近发布的微博：</h2>
<div id="weibolist"></div>

<script type="text/javascript">
        function post(){
                var form = new ajaxForm('weiboform', {callback:function(id){document.getElementById('content').value='';getweibolist();}});
            form.send();
        }
        
        function getweibolist(){
                var url = '<?= URL::site('app/weibolistdata') ?>';
                var req = new Request({
                        url: url,
                        data: null,
                        type: 'post',
                        beforeSend: function(){
                                $('weibolist').set('html','<div style="text-align:center;margin:20px; color:#B7D6F2">正在获取，请稍候<br><span class="middle"><img src="<?= URL::base() . 'static/images/loading4.gif' ?>"  /></span></div>');
                        },
                        success: function(html){
                                $('weibolist').set('html', html);
                        }
                });
                req.send();
        }
        
        function del(cid){
                var b = new Facebox({
                        title: '删除确认！',
                        message: '确定要删除此微博信息吗？',
                        icon:'question',
                        ok: function(){
                                new Request({
                                        url: '<?= URL::site('app/delete?cid=') ?>'+cid,
                                        type: 'post',
                                        success: function(){
                                                candyDel('weibo_'+cid);
                                        }
                                }).send();
                                b.close();
                        }
                });
                b.show();
        }
        
       getweibolist();
</script>