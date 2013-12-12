<form id="album_form" method="post" action="<?= URL::site('bbstemp/saveToAlbum') ?>" style="width: 500px">
    <table>
        <tr>
            <td>
                相册ID：<input type="text" name="album_id" style="width:200px"; class="input_text">
            </td>
        </tr>
        <tr>
            <td>
                或活动：<select name="event_id" style="padding:4px">
                    <option>请选择...</option>
                    <?php foreach ($event as $e):?>
                    <option value="<?=$e['id']?>"><?=  Text::limit_chars($e['title'],25)?></option>
                    <?php endforeach;?>
                </select>
            </td>
        </tr>
        <tr>
            <td style=" color: #999">
                温馨提示：请至少填写一处ID
                <input type="hidden" name="bbs_unit_id" value="<?= @$bbs_unit_id ?>" />
                <input type="hidden" name="cmt_id" value="<?= @$cmt_id?>" />
            </td>
        </tr>
    </table>
</form>

