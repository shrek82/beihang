<?php if($album):?>
<form id="alubm_form" action="<?=@$action_url?>">
    <table width=500 border="0" cellspacing="3" cellpadding="0" style="margin-top:0px;">
        <tr>
            <td  style="width:20%;text-align: right;padding-right: 5px;">相册排序:</td>
            <td style="color:#999"><input name="order_num" value="<?= $album['order_num'] ?>" class="input_text" style=" width: 100px">&nbsp;&nbsp;排序数字，越小越靠前，留空为自动排序</td>
        </tr>
        <tr>
            <td  style="width:20%;text-align: right;padding-right: 5px;">相册名称:</td>
            <td ><input name="name" value="<?= $album['name'] ?>" class="input_text" style=" width: 350px"></td>
        </tr>
    </table>
    <input type="hidden" name="alubm_id" value="<?= $album['id'] ?>">
</form>
<?php else:?>
很抱歉，相册不存在或已被删除！
<?php endif; ?>
