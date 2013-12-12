<form id="bbs_form" method="post"
      action="<?= URL::site('club_admin_bbs/form').
                  URL::query(array('bbs_id'=>$bbs['id'])) ?>">

    <input type="text" name="name" value="<?= $bbs['name'] ?>" size="12" />
    <textarea name="intro" style="height:80px; width: 90%"><?= $bbs['intro'] ?></textarea>
    <input type="button" id="submit_button" onclick="new ajaxForm('bbs_form',{redirect:'<?= URL::query() ?>'}).send()" value="确认" />
</form>