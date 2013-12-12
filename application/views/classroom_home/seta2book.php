<!-- classroom/setAbook:_body -->
<div>
    <!--left -->
    <div id="aa_home_left">
	<?php if( ! $abook OR $setAbook): ?>
<div class="notice">
    温馨提示：您还没有完善好班级通讯录中的个人信息（可与注册信息不同），请先填写，谢谢！
</div>
<?php endif; ?>

<form id="abookform" action="<?= $_URL ?>" method="post">
    <table>
        <tr>
            <td style="text-align: right">* 手机号码</td>
            <td><input type="text" name="mobile" value="<?=$abook['mobile']?>" class="input_text" style="width:200px"/></td>
        </tr>
        <tr>
            <td style="text-align: right">固定电话</td>
            <td><input type="text" name="tel" value="<?=$abook['tel']?>" class="input_text" style="width:200px"/></td>
        </tr>
        <tr>
            <td style="text-align: right">QQ</td>
            <td><input type="text" name="qq" value="<?=$abook['qq']?>" class="input_text" style="width:200px"/></td>
        </tr>
        <tr>
            <td style="text-align: right">MSN</td>
            <td><input type="text" name="msn" value="<?=$abook['msn']?>" class="input_text" style="width:200px"/></td>
        </tr>
        <tr>
            <td style="text-align: right">* 联系地址</td>
            <td><input type="text" name="address"  value="<?=$abook['address']?>" class="input_text" style="width:400px"/></td>
        </tr>
        <tr>
	    <td></td>
            <td>
                
                <input type="button" id="submit_button" onclick="new ajaxForm('abookform', {
                    textSuccess:'保存成功',
                    callback: function(){
                        javascript:window.localhost.url='<?= $_URL ?>';
                    }
                }).send()" value="保存"  class="button_blue"/>
		<input type="button" onclick="javascript:history.back()" value="返回"  class="button_gray"/>
            </td>
        </tr>
    </table>
</form>
    </div>
    <!--right -->
    <div id="aa_home_right"></div>
</div>
