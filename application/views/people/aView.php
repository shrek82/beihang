<!-- people/index:_body -->
<!--body -->
<!--left -->
<div id="main_left">
    <p><img src="/static/images/academician_title.gif" /></p>
    <table>
        <tr>
            <td valgin="top"><img style="padding: 5px; border: 1px solid #eee" src="<?=empty($people['pic'])?'/static/images/default_people.jpg':$people['pic']; ?>" width="190" /></td>
            <td valign="top" style="padding:0px 20px">
                <h3><?= $people['name'] ?><span style="font-weight:normal">（<?=$people['birth']?$people['birth']:'?'?> ~ <?=$people['leave']?$people['leave']:'' ?>）</span></h3>
                <div style="line-height: 1.6em"><?=nl2br(htmlspecialchars( $people['intro'])) ?></div>
		<div style="text-align:left;margin:20px 0"><a href="javascript:window.history.back()">返回上一页</a></div>
            </td>
        </tr>
    </table>
    
</div>
<!--//left -->

<!--right -->
<?php
include 'sidebar.php';
?>
<!--//right -->

<div class="clear"></div>
<!--//body -->