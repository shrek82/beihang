<!-- admin_event/static:_body -->
<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" >
<tr >
<td height="29" class="td_title"  colspan="4"><b>已有专题：</b></td>
</tr>
<tr>
    <td  style="text-align: center;width:50px">ID</td>
    <td width="70%" >专题活动标题</td>
    <td width="10%" style="text-align: center">显示顺序</td>
    <td width="10%" style="text-align: center">操作</td>
</tr>
<?php foreach($events as $ix=>$e): ?>
<tr  class="<?php if(($ix)%2==0){echo'even_tr';} ?>" id="con_<?=$e['id']?>">
    <td style="text-align: center"><?=$e['id']?></td>
<td><a href="?id=<?= $e['id'] ?>"><?= $e['title'] ?></a></td>
<td height="25" style="text-align: center" >
        <select onchange="change_order(<?= $e['id'] ?>, this.value)">
            <option>-</option>
            <?php for($i=1; $i<=count($events); $i++): ?>
            <option value="<?= $i ?>" <?= $i == $e['order_num']?'selected':'' ?>>
                <?= $i ?>
            </option>
            <?php endfor; ?>
        </select>
</td>
<td style="text-align: center"><a href="javascript:del(<?=$e['id']?>)">删除</a></td>
</tr>
<?php endforeach;?>
<?php if(!$events):?>
  <tr><td colspan="4">暂无专题活动。<td></tr>
<?php endif;?>
</table>


<!-- 活动专题表单 -->
<form action="<?= $_URL ?>"
          id="es_form"
          method="post"
          enctype="multipart/form-data">
<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" >
<tr >
<td height="29" class="td_title" colspan="2"><b><?=$event?'修改专题':'新增专题'; ?></b></td>
</tr>
<tr>
<td height="25"  class="field">图标展示：</td>
<td><input type="file" name="banner" /><span style="color:#999">建议最佳尺寸：220*70px</span></td>
</tr>

<tr>
<td height="25"  class="field">活动标题： </td>
<td><input size="60" type="text" name="title" value="<?= $event['title'] ?>"  class="input_text"/>
            <input type="checkbox" id="is_closed" name="is_closed" value="1" <?= $event['is_closed']==TRUE ? 'checked':'' ?> />
            <label for="is_closed">隐藏</label></td>
</tr>


<tr>
    <td class="field">跳转地址：</td>
    <td><input type="text" class="input_text" name="redirect" size="60" value="<?= $event['redirect'] ?>">&nbsp;&nbsp;<span style="color:#999">如需跳转至其他网址，请填写URL，否则请留空</span></td>
</tr>
<tr>
    <td colspan="2" style="padding:10px">
	<textarea name="content" id="content"><?= $event['content'] ?></textarea>
    </td>
</tr>

<tr>
    <td></td>
    <td><input type="hidden" name="id" value="<?= $event['id'] ?>" />
        <?php if( ! $event): ?>
        <input type="hidden" name="order_num" value="<?= count($events)+1 ?>" />
        <?php endif; ?>
        <div><input type="submit" value="保存" class="button_blue" /></div>
        <?php if($err): ?>
        <div class="notice"><?= $err; ?></div>
        <?php endif; ?>
    </td>
</tr>

</table>
    </form>
<?php $ke = View::keditor('content', array(
            'height' => '300px',
            'width' => '100%',
            'cssPath'=>'/static/css/keditor.css',
            'allowUpload' => true,
            'items' => Kohana::config('ke.full')
));?>
<?= $ke['init']; ?>
<?= $ke['show']; ?>

<script type="text/javascript">
    function change_order(id, order){
        var r = new Request({
            url: '<?= URL::site('admin_event/static') ?>?id='+id+'&order='+order
        });
        r.send();
    }

function del(cid){
    var b = new Facebox({
        title: '删除确认！',
        message: '确定要删除此专题活动吗？注意删除后将不能再恢复。',
        icon:'question',
        ok: function(){
            new Request({
                url: '<?= URL::site('admin_event/delStatic?cid=') ?>'+cid,
                type: 'post',
                success: function(){
                    candyDel('con_'+cid);
                }
            }).send();
            b.close();
        }
    });
    b.show();
}
</script>


