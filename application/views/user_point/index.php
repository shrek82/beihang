<!-- user_mail/index:_body -->
<style type="text/css">
    .point_table td{ text-align: center;padding:10px 5px}
    #temp_bg{ background: url(/static/images/deg.gif) no-repeat; width:122px;height:15px;margin:0px auto;padding:4px 10px}
</style>
<div id="big_right">
     <div id="plugin_title">热心度</div>
<div >

    <table border="0"  class="point_table" width="95%" cellspacing="0" cellpadding="0">
            <tr style="background:#f8f8f8">
                <td style="width:200px;"><?=$who?>的积分</td>
                <td style="width:300px"><?=$who?>的热心度</td>
                <td style="width:200px">当前排行</td>
            </tr>
            <tr>
                <td style="color:#1776BC;font-weight: bold;font-size:22px"><?=$my_point?><span style="font-size:12px; font-weight: normal">pt</span></td>
                <td style="color:#666;font-size:14px"><div id="temp_bg"><p style="padding:0px;margin:0px;width:<?=$my_temp?>%;background: url(/static/images/deg_b.gif) repeat-x">&nbsp;</p></div>
                    <span style="color:#f60;font-weight: bold;font-size:20px"><?=$my_temp?>°</span></td>
                <td><span style="color:#5EAF0A;font-weight: bold;font-size:22px"><?=$my_point_top?></span> <span style="color:#666">位</span></td>
            </tr>
    </table>

    <table border="0"  class="point_table" width="95%" cellspacing="0" cellpadding="0">
            <tr style="background:#f8f8f8">
                <td style="width:50%"><?=$who?>紧跟着</td>
                <td style="width:50%">追赶<?=$who?>的</td>
            </tr>
            <tr>
                <td style="color:#666;padding:10px 10px 2px 10px">
<div style="width:50px;margin:0px auto; text-align: center">
<?= View::factory('inc/user/avatar',
									    array('id' => $previous['id'], 'size' => 48,'sex'=>$previous['sex'])) ?>
					    	    </div>
                </td>
                <td style="color:#666;padding:10px 10px 2px 10px">
<div style="width:50px;margin:0px auto; text-align: center">
<?= View::factory('inc/user/avatar',
									    array('id' => $back['id'], 'size' => 48,'sex'=>$back['sex'])) ?>
					    	    </div>
                </td>
            </tr>
<tr>
                <td style="color:#0B60AF;padding:2px 10px">
                <a href="<?= URL::site('user_home?id=' . $previous['id']) ?>" style="color:#0B60AF"><?= $previous['realname'] ?></a> / <?=$previous['point']?>pt
                </td>
                <td style="color:#0B60AF;padding:2px 10px">
<a href="<?= URL::site('user_home?id=' . $back['id']) ?>" style="color:#0B60AF"><?= $back['realname'] ?></a> / <?=$back['point']?>pt
                </td>
            </tr>
    </table>

</div>

</div>