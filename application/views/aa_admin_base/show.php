<!-- aa_admin_base/show:_body -->
<div id="admin950">
<div style="margin:0px 20px">
<div style="padding:20px 0">
    <div class="admin_native_nav">
    <?php foreach($formats as $key=>$fmt): ?>
    <a href="<?= URL::query(array('format'=>$key,'show_id'=>null)) ?>"
       class="<?= $format==$key?'cur':'' ?>"><?= $fmt ?></a>
    <?php endforeach; ?>
    </div>
</div>

<div class="span-19 last">
    <table style="margin:0px auto">
    <?php foreach($shows as $s): ?>
    <tr id="show_<?= $s['id'] ?>">
        <td>
            <a href="<?= URL::query(array('show_id'=>$s['id'])) ?>#show_form">
            <img src="<?= $src.$s['filename']; ?>" /></a><br>
            <p style="color:#666;margin:10px 0">标题：<?= $s['title'] ?></p>
            <br>
	    <br>
        </td>
        <td class="vm"><input onclick="del_show(<?= $s['id'] ?>)" type="button" value="删除" /></td>
    </tr>
    <?php endforeach; ?>
    </table>

    <form action="" method="post" enctype="multipart/form-data" id="show_form">
        <table>
            <tr>
                <td>图片</td>
                <td><input type="file" name="<?= $format ?>" />&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#999">图片尺寸:676*160px</span></td>
            </tr>
            <tr>
                <td>标题</td>
                <td><input type="text" name="title" size="80" value='<?= $show['title'] ?>' class="input_text" /></td>
            </tr>
            <tr>
                <td>链接地址</td>
                <td><input type="text" name="url" size="80" value='<?= $show['url'] ?>' class="input_text" /></td>
            </tr>
            <tr>
                <td>排列顺序</td>
                <td>
                    <select name="order_num">
                        <?php for($i=1;$i<=(count($shows)+1);$i++): ?>
                        <option value="<?= $i ?>" <?= $show['order_num']==$i?'selected':'' ?>><?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                </td>
            </tr>
	    <tr>
		<td></td>
		<td><input type="submit" value="提交" class="button_blue" /></td>

	    </tr>
        </table>
        <?php if(isset($error)): ?>
        <div class="error"><?= $error ?></div>
        <?php endif; ?>
    </form>
</div>
</div>
</div>
<script type="text/javascript">
function del_show(id){
    var box = new Facebox({
        message: '确定删除此记录？',
        submitValue: '确定删除',
        submitFunction: function(){
            new Request({
                url: '<?= URL::base() ?>aa_admin_base/show?id=<?= $_AA['id'] ?>&show_id='+id+'&del=y',
                type: 'post',
                success: function(data){
                    if( data == 'done' ){
                        candyFadeout('show_'+id);
                    } else { alert(data); }
                }
            }).send();
            box.close();
        }
    });
    box.show();
}
</script>