<!-- vote/view:_body -->
<div id="main_left" style="background-color:#F8FCFF; " >
    <div ><img src="/static/images/vote_title.gif"></div>

    <div class="news_content" id="content">
        <?= $vote['content'] ?>
    </div>

    <div id="voteInfo">参与人数：<span style="color:#f30"><?= $vote['total_user'] ?></span>&nbsp;人，发布于：<?= date('Y-m-d', strtotime($vote['create_at'])); ?></div>

    <form action="" method="post" onSubmit="return checkform()">
        <div id="voteBox">

            <p style="margin:10px 0 20px 0;border-bottom:1px solid #eee;height:30px; line-height: 30px;color:#333;font-size: 16px; font-weight: bold"><?= $vote['title'] ?></p>
            <p style="color:#999;font-size:12px;margin:15px 0 ">最多可选择： <?= $vote['max_select'] ? $vote['max_select'] . '项' : '不限' ?></p>

            <!--查看投票结果：登录并已经投票 或 投票已经结束 -->
            <?php if ($vote_user OR $vote['is_finish']): ?>

                <?php foreach ($options as $key => $o): ?>
                    <label for="option_<?= $o['id'] ?>"><?= $key + 1 ?>、<?= $o['title'] ?></label>
                    <div class="optiondiv" ><p class='no<?= $key + 1 ?>'></p><?= $vote['total_votes'] ? round(($o['votes'] / $vote['total_votes']) * 100, 0) . '%' : '0%'; ?>&nbsp;&nbsp;<?= $o['votes'] ?>票</div>
                <?php endforeach; ?>

                <!--查看并投票：登录浏览并选择投票或未登录选择投票 -->

            <?php elseif ((!$vote_user AND $action == 'view') OR !$_UID): ?>
                <?php foreach ($options as $key => $o): ?>
                    <input type="<?= $vote['type'] ?>" name="option[]" value="<?= $o['id'] ?>" id="option_<?= $o['id'] ?>" style="vertical-align:middle;margin-bottom:4px">&nbsp;<label for="option_<?= $o['id'] ?>"><?= $o['title'] ?></label>
                    <div class="optiondiv" style="margin-left:24px"><p class='no<?= $key + 1 ?>'></p><?= $vote['total_votes'] ? round(($o['votes'] / $vote['total_votes']) * 100, 0) . '%' : '0%'; ?>&nbsp;&nbsp;<?= $o['votes'] ?>票</div>
                <?php endforeach; ?>

                <!--默认只显示投票选项 -->
            <?php else: ?>

                <?php if ($vote['max_select'] AND $vote['type'] == 'checkbox'): ?>

                <?php endif; ?>
                <ul style="margin:10px 0">
                    <?php foreach ($options as $key => $o): ?>
                        <li style="margin-bottom:5px;color:#666"><input type="<?= $vote['type'] ?>" id="option_<?= $o['id'] ?>" name="option[]" value="<?= $o['id'] ?>"><label for="option_<?= $o['id'] ?>">&nbsp;&nbsp;<?= $o['title'] ?></label></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>



            <p style="border-top:0px solid #eee;height:30px; line-height: 30px;margin:20px 0">
                <!--用户已经登录 -->
                <?php if ($_UID): ?>
                    <?php if (date('Y-m-d H:i:s') >= $vote['start_date'] AND !$vote['is_finish']): ?>
                        <?php if (!$vote_user): ?><input type="submit" value="投票" ><?php endif; ?>
                        <?php if (!$vote_user AND !$action): ?>
                            <input type="button" value="查看结果" onclick="window.location.href='<?= URL::site('vote/view?id=' . $vote['id']) . '&action=view' ?>'">
                        <?php endif; ?>
                    <?php elseif (date('Y-m-d H:i:s') < $vote['start_date']): ?>
                        <input type="submit" disabled="disabled" value="投票尚未开始">
                    <?php elseif ($vote['is_finish']): ?>
                        <input type="submit" disabled="disabled" value="投票已结束">
                    <?php else: ?>
                        <input type="submit" value="投票" >ss
                    <?php endif; ?>

                    <!--用户未登录 -->
                <?php else: ?>
                    <input type="submit" disabled="disabled" value="登录后投票">
                <?php endif; ?>

            </p>


        </div>
    </form>

    <?php if ($action == 'view' OR $vote_user OR !$_UID OR $vote['is_finish']): ?>
        <script language="javascript">
            var voteList = new Array();
    <?php foreach ($options as $key => $o): ?>
            voteList[<?= $key ?>] = <?= $vote['total_votes'] ? round(($o['votes'] / $vote['total_votes']) * 100, 0) : '-'; ?>;
    <?php endforeach; ?>
        $(document).ready(function(){
            //$("#voteBox dd:first").animate({width:"80%"},800);//伸展动画
            for(var i=0;i<<?= count($options) ?>;i++){
                $("#voteBox .optiondiv p").eq(i).animate({width:voteList[i]+"%"},800);
            }
        });
        </script>
    <?php endif; ?>

    <?php if ($_UID): ?>
        <?php if (!$vote_user): ?>
            <div style="margin:20px; color: #666;">您尚未投票。</div>
        <?php else: ?>
            <div style="margin:20px; color: #333;">您选择了：
                <?php foreach ($selected_options AS $o): ?>
                    <?= $o['title'] ?>；
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>


    <div class="clear"></div>

    <div class=" dotted_line" style="margin:15px"></div>
    <p class="comments_title">评论</p>
    <div style="padding:10px">
        <!--回复及评论 -->
        <?= View::factory('inc/comment/newform', array('params' => array('vote_id' => $vote['id']))) ?>
        <!--//回复及评论 -->
    </div>
</div>
<!--//left -->

<!--right -->
<div id="view_right" >
    <!-- 热门点击-->
    <p class="sidebar_title2" ><span style="color:#c00">更多</span>调查</p>
    <div class="sidebar_box2">
        <?php if (!$more_vote): ?>
            <p class="nodata">暂无投票调查</p>
        <?php endif; ?>
        <ul class="con_small_list" >
            <?php foreach ($more_vote as $v): ?>
                <li><a href="<?= URL::site('vote/view?id=' . $v['id']) ?>" title="<?= $v['title'] ?>" ><?= Text::limit_chars($v['title'], 13, '..') ?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <!-- //热门点击-->

</div>
<!--//right -->

<div class="clear"></div>

<script language="javascript">
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

        if(max_select>0 && selected_option>max_select){
            alert('很抱歉，您最多只能选择'+max_select+'项!');
            return false;
        }
	
	
    }

</script>
