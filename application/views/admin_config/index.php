<!-- admin_config/index:_body -->

<form id="user_form"  action="/admin_config/form" method="post"  >
    <table class="admin_table" border="0" cellpadding="0" cellspacing="1" width="100%">
        <tr>
            <td colspan="2" class="td_title">网站设置</td>
        </tr>
        <?php foreach ($configs as $key => $c): ?>
        <tr>
            <td class="field" width="15%"><?=$c['name']?>：</td>
            <td>
                <?php if($c['is_boolean']):?>
                <label><input type="radio" name="<?=$c['key']?>" value="0" <?=(int)$c['value']==0? 'checked' : '' ?> /> 否</label>
                <label><input type="radio" name="<?=$c['key']?>" value="1" <?=(int)$c['value']? 'checked' : '' ?> /> 是</label>
                <?php else:?>
                <input name="<?=$c['key']?>" value="<?= $c['value'] ?>" style='padding:2px;width: 550px'>
                <?php endif;?>
                
                
            </td>
        </tr>
        <?php endforeach; ?>



        <tr>
            <td style="padding:20px; text-align: center" colspan="2" >
                <input type="button" onclick="save()" value="保存设置" name="button" class="button_blue"  id="submit_button"/>
            </td>
        </tr>

    </table><br>

</form>

<script type="text/javascript">
    function save(){
        new ajaxForm('user_form',{textSending: '发送中',textError: '重试',textSuccess: '修改成功',callback:function(id){
            }}).send();
    }
</script>