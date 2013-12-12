<form id="bbs_form" method="post"
      action="<?= URL::site('aa_admin_bbs/form').
                  URL::query(array('bbs_id'=>$bbs['id'])) ?>">

    版块名称：<br><input type="text" name="name" value="<?= $bbs['name'] ?>" size="12" class="input_text" /><br>
    版块介绍：<br><textarea name="intro" style="height:80px; width: 60%" class="input_text" ><?= $bbs['intro'] ?></textarea>
    <br><input type="button" id="submit_button" onclick="new ajaxForm('bbs_form',{redirect:'<?= URL::query() ?>'}).send()" value="确认"  class="button_blue" />
</form>