<!-- 拒绝申请原因 -->
<form id="reason_form" action="<?= $action ?>" method="post">
    <textarea style="width:400px; height:50px;" name="reject_reason" class="input_text"><?= $reason?$reason:'很抱歉，您暂时还未满足加入条件！';?></textarea>
    <input type="submit" value="保存" style="display: none" />
</form>