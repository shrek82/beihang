<div id="admin950" style="padding-bottom:150px">
<?php if ($album): ?>
<form id="album_form" method="post" action="<?= URL::site('aa_admin_album/category?id=' . $_ID . '&album_id=' . $album['id']) ?>">
    <table width=500 border="0" cellspacing="3" cellpadding="0" style="margin-top:0px;">
        <tr>
            <td  style="width:20%;text-align: right;padding-right: 5px;">相册名称:</td>
            <td ><input name="name" value="<?= $album['name'] ?>" class="input_text" style=" width: 350px"></td>
        </tr>
        <tr>
            <td  style="width:20%;text-align: right;padding-right: 5px;">相册排序:</td>
            <td style="color:#999"><input name="order_num" value="<?= $album['order_num'] ?>" class="input_text" style=" width: 100px">&nbsp;&nbsp;排序数字，越小越靠前，留空为自动排序</td>
        </tr>
        <tr>
            <td  style="width:20%;text-align: right;padding-right: 5px;"></td>
            <td ><input type="button"  value="保存修改"  id="submit_button" class="button_blue"  onclick="new ajaxForm('album_form',{redirect:'/aa_admin_album/aa?id=<?=$_ID?>'}).send()"/></td>
        </tr>
    </table>
</form>
 <?php else: ?>
            <form id="album_form" method="post" action="<?= URL::site('aa_admin_album/category?id=' . $_ID) ?>">
    <table width=500 border="0" cellspacing="3" cellpadding="0" style="margin-top:0px;">

        <tr>
            <td  style="width:20%;text-align: right;padding-right: 5px;">相册名称:</td>
            <td ><input name="name" value="<?= $album['name'] ?>" class="input_text" style=" width: 350px"></td>
        </tr>
        <tr>
            <td  style="width:20%;text-align: right;padding-right: 5px;">相册排序:</td>
            <td style="color:#999"><input name="order_num"  class="input_text" style=" width: 100px">&nbsp;&nbsp;排序数字，越小越靠前，留空为自动排序</td>
        </tr>
        <tr>
            <td  style="width:20%;text-align: right;padding-right: 5px;"></td>
            <td ><input type="button"  value="立即添加" id="submit_button"  class="button_blue"  onclick="new ajaxForm('album_form',{redirect:'/aa_admin_album/aa?id=<?=$_ID?>'}).send()"/></td>
        </tr>

    </table>
</form>
 <?php endif; ?>
</div>