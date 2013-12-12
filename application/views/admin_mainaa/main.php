<!-- admin_zaa/main:_body -->

<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" style="margin-top:0px ">
    <tr>
        <td colspan="2" class="td_title">基本介绍</td>
    </tr>
</table>

<form id="main_aa" action="<?= URL::site($_URI); ?>" method="post">
    <table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" style="margin-top:0px ">
        <tr>
            <td class="field">全称：</td>
            <td><input size="80" type="text" name="name" value="<?= $main['name'] ?>"   class="input_text"/></td>
        </tr>
        <tr>
            <td class="field">办公地址：</td>
            <td><input size="80" type="text" name="address" value="<?= $main['address'] ?>" class="input_text"/></td>
        </tr>
        <tr>
            <td class="field">邮政编码：</td>
            <td><input type="text" name="zip" value="<?= $main['zip'] ?>" class="input_text"/></td>
        </tr>
        <tr>
            <td class="field">电话：</td>
            <td>
                (例 办公:0571-8808111)
                <?php
                $tel_arr = explode(';', $main['tel']);
                if (count($tel_arr) > 0):
                    foreach ($tel_arr as $tel):
                        ?>
                        <div style="margin:5px 0">
                            <input size="10" type="text" name="tel_key[]" value="<?= substr($tel, 0, strpos($tel, ':')) ?>"  class="input_text"/>:
                            <input type="text" name="tel_num[]" value="<?= substr($tel, strpos($tel, ':') + 1) ?>"  class="input_text"/>
                        </div>
                    <?php endforeach;
                endif; ?>
                <div id="clone_box">
                    <input size="10" type="text" name="tel_key[]" value="" class="input_text"/>:
                    <input type="text" name="tel_num[]" value="" class="input_text"/>
                </div>
                <p>
                    <a href="javascript:candyCloneInput('clone_box')">添加号码</a>
                </p>
            </td>
        </tr>
        <tr>
            <td class="field">邮箱：</td>
            <td><input type="text" name="mail" value="<?= $main['mail'] ?>" class="input_text"/></td>
        </tr>
        <tr>
            <td class="field">传真：</td>
            <td><input type="text" name="fax" value="<?= $main['fax'] ?>" class="input_text"/></td>
        </tr>
        <tr>
            <td colspan="2"><textarea name="intro" id="intro" style="width:100%;height:300px"><?= $main['intro'] ?></textarea></td>
        </tr>
        <tr>
            <td colspan="2"><p class="center"><input onclick="save()" type="button" id="submit_button" value="确认保存"  class="button_blue"/></p></td>
        </tr>
    </table>
</form>

<?=
View::ueditor('intro', array(
    'toolbars' => Kohana::config('ueditor.common'),
    'minFrameHeight' => 300,
    'autoHeightEnabled' => 'false',
));
?>


<script type="text/javascript">
    function save(){
       if(!ueditor.hasContents()){ueditor.setContent('');}
        ueditor.sync();
        new ajaxForm('main_aa',{textSending: '发送中',textError: '重试',textSuccess: '发送成功',callback:function(id){
                 window.location.reload();
            }}).send();
    }
</script>

<script type="text/javascript">
//    function save(){
//        var main_aa = new ajaxForm('main_aa');
//        if(!ueditor.hasContents()){ueditor.setContent('');}
//        ueditor.sync();
//        main_aa.send();
//    }
</script>