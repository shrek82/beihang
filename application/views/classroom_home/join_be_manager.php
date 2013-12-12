<form id="apply_form" method="post" action="<?= URL::site('user/joinClassBeManager?total_member='.$total_member) ?>">
<div style="padding:10px 20px">
    <input type="hidden" name="class_room_id" value="<?= @$class_room_id ?>" />
    <?php if($total_member>0):?>
    确定加入到本班级吗？
    <?php else:?>
    您好，本班级暂时还没有成员，是否加入并成为首位管理员？
    <?php endif;?>

</div>
    <input type="submit" value="确定" style="display: none" />
</form>
