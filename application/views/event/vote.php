<script type="text/javascript">
    var selectedVote=5;
    var votemsng;
</script>

<form id="apply_form" method="post" action="<?= URL::site('event/votesub?id=' . $event['id']) ?>" style="padding:0px">
    <div style="padding:5px 10px; color: #333; margin-bottom: 8px">
        亲爱的<?= $_SESS->get('realname') ?>校友，您于<?= Date::span_str(strtotime($event['start'])) ?>前参加了<a href="/event/view?id=<?= $event['id'] ?>" target="_blank" style="color:#3B5998; font-weight: bold"><?= $event['title'] ?></a>，看看大家都在说什么：<br>
        <?php if (count($comment) > 0): ?>
            <div style="margin-top: 10px">
                <?php foreach ($comment as $key => $c): ?>
                <p style="padding:2px 0px;color: #666;"><span style="color:#3B5998"><?= $c['realname'] ?></span>：<?=  Text::limit_chars($c['content'],100)  ?><span style="color:#999">( <?= Date::ueTime($c['post_at']) ?> )</span></p>
                <?php endforeach; ?>
            </div>
        <?php else:?>
        <div style="color:#999;padding-top: 5px">好像还没人分享过体会哦～</div>
        <?php endif; ?>
        <div style="margin-top:10px;height:25px">
            <p style="float:left;width:90px"><span style="vertical-align: middle;"><input type="radio" name="is_signed"  value="" id="voteandcmt" checked/></span><label for="voteandcmt" style="color:#333">活动评分 </label></p>
            <ul class="eventVote" style="float:left;width:140px" onmouseout="changevote(0)">
                <li id="vote1" onmouseover="changevote(1)" onclick="selectvote(1)" title="差" class="selected"></li>
                <li id="vote2" onmouseover="changevote(2)" onclick="selectvote(2)" title="一般" class="selected"></li>
                <li id="vote3" onmouseover="changevote(3)" onclick="selectvote(3)" title="好" class="selected"></li>
                <li id="vote4" onmouseover="changevote(4)" onclick="selectvote(4)" title="很好" class="selected"></li>
                <li id="vote5" onmouseover="changevote(5)" onclick="selectvote(5)" title="非常好" class="selected"></li>
            </ul>
            <p id="voteSelectMsg" style="float:left;width:100px;color: #f60">非常好</p>
        </div>
        <div style="height: 70px">
            <textarea style="width:99%;height: 70px;width:550px;line-height: 1.6em" class="input_text" name="feelings" id="feelings" ></textarea>
        </div>
        <div style="margin-top: 12px; text-align: right;display: none"><input type="button" value="立即分享" class="greenButton"></div>
        <input type="hidden" id="postvote" name="postvote" value="5">
        <input type="hidden" name="is_present" value="yes" >
        <input type="hidden" name="event_id" value="<?= $event['id'] ?>">
    </div>
    <div style="padding:2px 4px;">
        <span style="vertical-align: middle;"><input type="radio" name="is_signed" onclick="notsignEvent(<?= $event['id'] ?>)"  id="meiqucheckbox"  value="no" /></span><label for="meiqucheckbox" style="color:#F58B9B">很遗憾，有事没能参加 :(  </label>
        <br><span style="vertical-align: middle;"><input type="radio" name="is_signed" onclick="signedEvent(<?= $event['id'] ?>)"  id="canjiale" value="yes" /></span><label for="canjiale" style="color:green" title="其实发表活动体会有更多积分奖励哦～">参加了，但不想说什么:)  </label>
    </div>
</form>
