<!-- aa_admin_base/info:_body -->
<div id="admin950">
        <form id="info_form" method="post" action="<?= URL::query() ?>">
            <div><label>标题</label>
                <input type="text" name="title" value="<?= @$info['title'] ?>" class="input_text"  size="60"/>
                <input type="hidden" name="is_public" value="0" />
                <input type="checkbox" name="is_public" value="1" <?= (isset($info['is_public']) && $info['is_public'] == false) ? '' : 'checked' ?> /> 可见
            </div>
            <div style="height:400px">
                    <textarea id="content" name="content" style="width:99%;height:260px"><?= @$info['content'] ?></textarea>
            </div>
            <div><label>排序</label>
                <input type="text" name="order_num" value="<?= @$info['order_num'] ?>" class="input_text"  size="20"/>
            </div>
            <div style="margin:10px 0">
                <input type="hidden" name="cid" value="<?= @$info['id']?>" />
                <input type="button" id="submit_button" value="<?= $info?'修改修改':'立即添加'?>" onclick="save()" class="button_blue"/>
            </div>
        </form>

</div>
<script type="text/javascript">
    function save(){
       if(!ueditor.hasContents()){ueditor.setContent('');}
        ueditor.sync();
        new ajaxForm('info_form',{textSending: '发送中',textError: '重试',textSuccess: '发送成功',callback:function(id){
                 window.location.href='<?= URL::site('aa_admin_base/info?id=' . $_ID) ?>';
            }}).send();
    }
</script>

<?=
View::ueditor('content', array(
    'toolbars' => Kohana::config('ueditor.common'),
    'minFrameHeight' => 300,
    'autoHeightEnabled' => 'false'
));
?>

