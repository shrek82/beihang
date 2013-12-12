<!-- user_info/binding:_body -->
<div id="big_right">
        <div id="plugin_title">编辑资料</div>

        <div class="tab_gray" id="user_topbar" style="margin-top:10px">
                <ul>
                        <li><a href="<?= URL::site('user_info/base') ?>" style="width:50px">基本信息</a></li>
                        <li><a href="<?= URL::site('user_info/work') ?>" style="width:50px">工作信息</a></li>
                        <li><a href="<?= URL::site('user_info/account') ?>" style="width:50px">账号设置</a></li>
                        <li><a href="<?= URL::site('user_info/binding') ?>" style="width:50px" class="cur" >微博绑定</a></li>
                </ul>
        </div>

        <div id="user_info">
                <?php if (count($binding) == 0): ?>
                <table width="100%" id="bbs_table">
                        <tr>
                               <td style=" text-align: left;font-size: 14px; color: #666" ><img src="/static/logo/sina/32x32.png" style="vertical-align: middle" /> 您还没有绑定新浪微博 </td>
                               <td style=" text-align: right;width: 60%; padding: 10px" ><a href="<?=@$bindingUrl?>" class="binding" style="float:right"></a></td>
                        </tr>
                </table>
                <?php else: ?>
                        <table width="100%" id="bbs_table">
                                <thead>
                                        <tr >
                                                <td style=" text-align: left" >服务商</td>
                                                <td style=" text-align: center" >微博昵称</td>
                                                <td style=" text-align: center">绑定时间</td>
                                                <td style=" text-align: center">操作</td>
                                        </tr>
                                </thead>
                                <tbody>
                                        <?php if (!$binding): ?>
                                                <tr >
                                                        <td colspan="3" style="border:0"><div class="nodata">你暂时还没有填写任何绑定信息！</div></td>
                                                </tr>
                                        <?php endif; ?>
                                        <?php foreach ($binding as $b): ?>
                                                <tr style="border-bottom: 1px dotted #eee" onMouseOver=this.style.backgroundColor='#f9f9f9' onMouseOut=this.style.backgroundColor='' id="binding_<?= $b['id'] ?>">
                                                        <td ><img src="/static/logo/<?=$b['service']?>/24x24.png" style="vertical-align: middle" /> 微博</td>
                                                        <td style=" text-align: center"><?=$b['screen_name']?></td>
                                                        <td style=" text-align: center"><?=$b['create_at']?></td>
                                                        <td class="date"><a href="javascript:del(<?=$b['id']?>)">取消</a></td>
                                                </tr>
                                        <?php endforeach; ?>
                                </tbody>
                        </table>

                <?php endif; ?>

        </div>
</div>

<script type="text/javascript">
        function del(cid){
                var b = new Facebox({
                        title: '删除确认！',
                        message: '确定要删除此微博绑定吗？',
                        icon:'question',
                        ok: function(){
                                new Request({
                                        url: '<?= URL::site('user_info/delBinding?cid=') ?>'+cid,
                                        type: 'post',
                                        success: function(){
                                                window.location.reload();
                                        }
                                }).send();
                                b.close();
                        }
                });
                b.show();
        }
</script>