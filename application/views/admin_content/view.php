    <table class="admin_table" border="0" cellpadding="0" cellspacing="1" width="100%">

        <tr>
            <td style="padding:5px;text-align: left;">使用帮助</td>
        </tr>

        <tr>
            <td style="padding:5px;text-align: left; background-color: #fff">
                <p style="font-size: 14px;font-weight: bold;"><?= $content['title'] ?></p>
                 <p style="font-size: 12px;color: #999">发布日期：<?= $content['create_at']  ?></p>
                
                <div style="line-height: 1.6em;color: #333;padding: 0;min-height: 300px"><?= @$content['content'] ?></div>
                    
            </td>
        </tr>

        <?php if($content['img_path']):?>
        <tr>
            <td style="padding:5px;text-align: left">
                <img src="<?= $content['img_path'] ?>" style="margin: 10px" />
            </td>
        </tr>
        <?php endif;?>

        <tr>
            <td class="center" style="padding:20px">
                <input type="button" value="返回" onclick="window.history.back()" class="button_gray">
            </td>
        </tr>
        </tbody>
    </table>

