<div id="admin950">
    <form action="<?= $_URL ?>" id="classroom_form" method="post">
        <table width="100%">
            <tr>
                <td ><textarea name="intro" id="intro" style="width: 100%; height: 380px;"  ><?= $_CLASSROOM['intro'] ?></textarea></td>
            </tr>

            <tr>
                <td align="center">
                    <input type="button" value="保存修改" id="submit_button"
                           onclick="save()" class="button_blue" />
                </td>
            </tr>
        </table>
    </form>
</div>

<script type="text/javascript">
    function save(){
        if(!ueditor.hasContents()){ueditor.setContent('');}
        ueditor.sync();
        new ajaxForm('classroom_form',{textSending: '发送中',textError: '重试',textSuccess: '修改成功',callback:function(id){
                okAlert('基本介绍修改成功!');
            }}).send();
    }
</script>

<?=
View::ueditor('intro', array(
    'toolbars' => Kohana::config('ueditor.common'),
    'minFrameHeight' => 300,
    'autoHeightEnabled' => 'false',
    'elementPathEnabled' => 'false'
));
?>
