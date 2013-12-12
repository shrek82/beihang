<?php
/**
  +-----------------------------------------------------------------
 * 名称：论坛话题投票视图
 * 版本：1.0
 * 版权：www.usho.cn
 * 作者：zhaojiangang
 * 最后更新日期：12-10-11 下午3:20
  +-----------------------------------------------------------------
 */
?>
<div id="voteInfo">
    已有<span style="color:#f30"><?= $vote['total_user'] ?></span>人数参与
</div>
<style type="text/css">
    #voteBox div{ background-color:#f9f9f9;height:14px;margin:4px 0px 15px 0;font-size:11px}
</style>
<form action="<?= URL::site('bbs/viewPost?id=' . $unit['id'] . '&action=vote') ?>" method="post" onSubmit="return checkform()">
    <div id="voteBox" style="background:#f9f9f9">

        <p style="margin:0px 0 15px 0;border-bottom:1px solid #eee;height:25px; line-height: 25px;color:#333;font-size: 14px; font-weight: bold"><?= $unit['title'] ?></p>
        <?php if ($vote['type'] == 'checkbox'): ?>
            <p style="color:#999;font-size:12px;margin:5px 0 ">最多可选择： <?= $vote['max_select'] ? $vote['max_select'] . '项' : '不限' ?></p>
        <?php endif; ?>
        <!--已经参与投票 或 投票已经结束 -->
        <?php if ($vote_user OR $vote['is_finish']): ?>

            <?php foreach ($options as $key => $o): ?>
                <label for="option_<?= $o['id'] ?>"><?= $key + 1 ?>、<?= Text::limit_chars($o['title'], 40, '...') ?></label>
                <div class="optiondiv"  title="<?= $o['votes'] ?>票" ><p class='no<?= $key + 1 ?>'></p><?= $vote['total_votes'] ? round(($o['votes'] / $vote['total_votes']) * 100, 0) . '%' : '0%'; ?></div>
            <?php endforeach; ?>

            <!--查看并投票：登录浏览并选择投票或未登录选择投票 -->

        <?php elseif ((!$vote_user AND $action == 'vv') OR !$_UID): ?>
            <?php foreach ($options as $key => $o): ?>
                <input type="<?= $vote['type'] ?>" name="option[]" value="<?= $o['id'] ?>" id="option_<?= $o['id'] ?>" style="vertical-align:middle;margin-bottom:4px">&nbsp;<label for="option_<?= $o['id'] ?>"><?= Text::limit_chars($o['title'], 40, '...') ?></label>
                <div class="optiondiv" style="margin-left:24px" title="<?= $o['votes'] ?>票" ><p class='no<?= $key + 1 ?>'></p><?= $vote['total_votes'] ? round(($o['votes'] / $vote['total_votes']) * 100, 0) . '%' : '0%'; ?></div>
            <?php endforeach; ?>

            <!--默认只显示投票选项 -->
        <?php else: ?>

            <?php if ($vote['max_select'] AND $vote['type'] == 'checkbox'): ?>

            <?php endif; ?>
            <ul style="margin:10px 0">
                <?php foreach ($options as $key => $o): ?>
                    <li style="margin-bottom:5px;color:#666"><input type="<?= $vote['type'] ?>" id="option_<?= $o['id'] ?>" name="option[]" value="<?= $o['id'] ?>"><label for="option_<?= $o['id'] ?>">&nbsp;&nbsp;<?= Text::limit_chars($o['title'], 40, '...') ?></label></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>



        <p style="border-top:0px solid #eee;height:30px; line-height: 30px;margin:20px 0">
            <!--用户已经登录 -->
            <?php if ($_UID): ?>
                <?php if (date('Y-m-d H:i:s') >= $vote['start_date'] AND !$vote['is_finish']): ?>
                    <?php if (!$vote_user): ?><input type="submit" value="投票" ><?php endif; ?>
                    <?php if (!$vote_user AND !$action): ?>
                        <input type="button" value="查看结果" onclick="window.location.href='<?= URL::site('bbs/viewPost?id=' . $unit['id']) . '&action=vv' ?>'">
                    <?php endif; ?>
                <?php elseif (date('Y-m-d H:i:s') < $vote['start_date']): ?>
                    <input type="submit" disabled="disabled" value="投票尚未开始">
                <?php elseif ($vote['is_finish']): ?>
                    <input type="submit" disabled="disabled" value="投票已结束">
                <?php else: ?>
                    <input type="submit" value="投票" >
                <?php endif; ?>

                <!--用户未登录 -->
            <?php else: ?>
                <input type="submit" disabled="disabled" value="登录后投票">
            <?php endif; ?>

        </p>


    </div>
</form>

<?php if ($action == 'vv' OR $vote_user OR !$_UID OR $vote['is_finish']): ?>
    <script type="text/javascript">
        var voteList = new Array();
    <?php foreach ($options as $key => $o): ?>
            voteList[<?= $key ?>] = <?= $vote['total_votes'] ? round(($o['votes'] / $vote['total_votes']) * 100, 0) : '-'; ?>;
    <?php endforeach; ?>
        $(document).ready(function(){
            for(var i=0;i<<?= count($options) ?>;i++){
                $("#voteBox .optiondiv p").eq(i).animate({width:voteList[i]+"%"},500);
            }
        });
    </script>
<?php endif; ?>

<?php if ($_UID): ?>
    <?php if (!$vote_user): ?>
        <div style="margin:20px; color: #666;font-size:12px">您尚未投票。</div>
    <?php else: ?>
        <div style="margin:20px; color: #666;font-size:12px">您已经参与并选择了：
            <?php foreach ($selected_options AS $o): ?>
                <?= $o['title'] ?>；
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
<?php endif; ?>

<script type="text/javascript">
    function checkform(){
        var max_select=<?= $vote['max_select'] ? $vote['max_select'] : -1; ?>;
        var option=document.getElementsByName("option[]");
        var selected_option=0;
        for (var i=0;i<option.length;i++)
        {
            if(option[i].checked){
                selected_option+=1;
            }
        }

        if(selected_option<1){
            alert('您还没选择选项！');
            return false;
        }

        if(max_select>0 && selected_option>max_select){
            alert('很抱歉，您最多只能选择'+max_select+'项!');
            return false;
        }
    }

    function checkselected(){
        var max_select=<?= $vote['max_select'] ? $vote['max_select'] : -1; ?>;
        var option=document.getElementsByName("option[]");
        var selected_option=0;
        for (var i=0;i<option.length;i++)
        {
            if(option[i].checked){
                selected_option+=1;
            }
        }
        if(selected_option>=max_select){
            alert('很抱歉，您最多只能选择'+max_select+'项!');
            return false;
        }
        else{
            return true;
        }
    }

    function selectOption(id){
        var unitoption=$('#unitOption_'+id);
        var opt=$('#option_'+id);
        if(unitoption.attr('checked')){
            if(checkselected()){
                opt.attr("checked",'true');
            }
            else{
                unitoption.removeAttr("checked");
            }
        }
        else{
            opt.removeAttr("checked");
            unitoption.removeAttr("checked");
        }
    }
</script>