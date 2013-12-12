<!-- user_info/base:_body -->
<div id="big_right">
    <div id="plugin_title">编辑资料</div>

<div class="tab_gray" id="user_topbar" style="margin-top:10px">
   <ul>
       <li><a href="<?= URL::site('user_info/base') ?>" class="cur" style="width:50px">基本信息</a></li>
       <li><a href="<?= URL::site('user_info/work') ?>" style="width:50px">工作信息</a></li>
       <li><a href="<?= URL::site('user_info/account') ?>" style="width:50px">账号设置</a></li>
       <?php if($_CONFIG->modules['binding']):?>
       <li><a href="<?= URL::site('user_info/binding') ?>" style="width:50px">微博绑定</a></li>
       <?php endif;?>
    </ul>
</div>
    <div id="user_info">
      <form action="<?= URL::site($_URI) ?>" id="user_base" method="post">
        <div><label>性别</label>
            <input type="radio" name="sex" value="男" <?= $user['sex'] == '男' ? 'checked':'' ?> /> 男
            <input type="radio" name="sex" value="女" <?= $user['sex'] == '女' ? 'checked':'' ?> /> 女
        </div>
          <div><label>入学、毕业年份</label><br />
            <input size="22" type="text" name="start_year" value="<?= $user['start_year'] ?>" class="input_text"  maxlength="4" /> ~ <input size="22" type="text" name="finish_year" value="<?= $user['finish_year'] ?>" class="input_text"  maxlength="4"/> 年
            <span style="color:#999">请填写年份</span>
        </div>
        <div><label>毕业专业</label><br />
            <input size="57" type="text" name="speciality" value="<?= $user['speciality'] ?>" class="input_text" />
            <span style="color:#999">请尽量填写完整</span>
        </div>
        <div><label>移动电话</label><br />
            <input size="30" type="text" name="mobile" value="<?= $user['Contact']['mobile'] ?>" class="input_text" />
            <?= Model_User::privateSelector($private, 'mobile') ?>&nbsp;&nbsp;<span style="color:#999">例如:13630026618</span>
        </div>
        <div><label>固定电话</label><br />
            <input size="30" type="text" name="tel" value="<?= $user['Contact']['tel'] ?>"  class="input_text"/>
            <?= Model_User::privateSelector($private, 'tel') ?>&nbsp;&nbsp;<span style="color:#999">例如:0571-87951019</span>
        </div>
        <div><label>邮箱(账号)</label><br />
            <input size="30" type="text" name="email" value="<?= $user['account'] ?>" class="input_text" readonly="readonly"/>
            <?= Model_User::privateSelector($private, 'email') ?>
        </div>
        <div><label>QQ</label><br />
            <input size="30" type="text" name="qq" value="<?= $user['Contact']['qq'] ?>"  class="input_text"/>
            <?= Model_User::privateSelector($private, 'qq') ?>
        </div>
        <div><label>所在城市</label><br />
            <input type="text" size="30" name="city" value="<?= $user['city'] ?>"  class="input_text"/>
	<?= Model_User::privateSelector($private, 'city') ?>&nbsp;&nbsp;<span style="color:#999">例如:杭州</span>
        </div>

        <div><label>工作经历</label><br />
	    <span style="color:#999;margin-right:95px">请在单独页面编辑</span> <?= Model_User::privateSelector($private, 'work') ?>
        </div>

        <div><label>详细地址</label><br />
            <input size="60" type="text" name="address" value="<?= $user['Contact']['address'] ?>" class="input_text" />
            <?= Model_User::privateSelector($private, 'address') ?>
        </div>

        <div><label>个人微博或网站地址</label><br />
            <textarea   name="homepage" class="input_text" style="width:500px;height:50px; font-family: verdana"/><?= $user['homepage'] ?></textarea>
        <br><span style="color:#999">例如：http://www.weibo.com/123456，多个地址请换行，总字符不超过255个</span>
        </div>

        <div><label>个性签名档</label><br />
            <textarea style="width:500px;height:80px; " id="intro" name="intro" class="input_text"><?= $user['intro'] ?></textarea>
            <br><span style="color:#999">签名档，字符总数不超过255</span>
        </div>
        <div><input type="button" id="submit_button" onclick="save()" value="保存" class="input_submit" /><input type="button"  value="取消" class="input_submit gray"  onclick="window.history.back()"/></div>
    </form>
    </div>

</div>

<script type="text/javascript">
function save(){
            new ajaxForm('user_base',{callback:function(){
                alert('资料保存成功!');
            }}).send();
}
</script>