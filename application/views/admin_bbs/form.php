<!-- admin_bbs/form:_body -->
<form id="bbs_form" method="post"    action="<?= URL::site('admin_bbs/form').URL::query(array('bbs_id'=>$bbs['id'])) ?>">
上级目录：<br>
<select name="parent_id">
    <option <?=$bbs['parent_id']=='-1'?'selected':'';?> value="-1">作为顶级版块</option>
    <?php foreach($bbs_parent as $p):?>
    <option value="<?=$p['id']?>" <?=$bbs['parent_id']==$p['id']?'selected':'';?>><?=$p['name']?></option>
    <?php endforeach;?>
</select>
<br>
版块名称：<br>
    <input type="text" name="name" value="<?= $bbs['name'] ?>" class="input_text" style="width:200px;margin:5px 0"/><br>
版块排序：<br>
    <input type="text" name="order_num" value="<?= $bbs['order_num'] ?>" class="input_text"  style="width:200px;"/><br>


<br>
版块介绍：<br>
    <textarea name="intro" style="height:80px; width: 90%" class="input_text"><?= $bbs['intro'] ?></textarea><br>
    <input type="button" id="submit_button"  class="button_blue" style="margin:10px 0" onclick="new ajaxForm('bbs_form',{redirect:'<?= URL::query() ?>'}).send()" value="确认" />
</form>