<!-- user_info/account:_body -->

<div id="big_right">
    <div id="plugin_title">编辑资料</div>

<div class="tab_gray" id="user_topbar" style="margin-top:10px">
   <ul>
       <li><a href="<?= URL::site('user_info/base') ?>"  style="width:50px">基本信息</a></li>
       <li><a href="<?= URL::site('user_info/work') ?>" style="width:50px">工作信息</a></li>
       <li><a href="<?= URL::site('user_info/account') ?>" class="cur" style="width:50px">账号设置</a></li>
       <?php if($_CONFIG->modules['binding']):?>
       <li><a href="<?= URL::site('user_info/binding') ?>" style="width:50px">微博绑定</a></li>
       <?php endif;?>
    </ul>
</div>

  <div id="user_info">
      <form action="<?= URL::site('user_info/account') ?>" id="account_form" method="post">
        <div><label>登录帐号</label><br />
            <input size="30" type="text" name="account" value="<?=@$user['account']?>" class="input_text" style="width:250px"/>
            <input size="30" type="hidden" name="old_account" value="<?=@$user['account']?>" />
            <span style="color:#999;margin-right:95px">例如：yourname@163.com</span>
        </div>
        <div><label>新密码</label><br />
            <input size="30" type="password" name="password" value="" class="input_text" style="width:250px"/>
            <span style="color:#999;margin-right:95px">留空为不修改密码</span>
        </div>
        <div><label>再次输入密码</label><br />
            <input size="30" type="password" name="password2" value=""  class="input_text" style="width:250px"/>
        </div>

        <div><input type="button" id="submit_button" onclick="edit_account()"  value="保存修改" class="input_submit" /><input type="button"  value="取消" class="input_submit gray"  onclick="window.history.back()"/></div>
    </form>
  </div>
</div>
<script type="text/javascript">
     function edit_account(){
        new ajaxForm('account_form',{callback:function(){
                alert('恭喜您，资料修改成功，请返回重新登录，谢谢！');
                location.href='/user/logout?redirect=/user/login/';
            }}).send();
     }
</script>
