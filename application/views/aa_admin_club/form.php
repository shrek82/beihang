<!-- aa_admin_club/form:_body -->
<div id="admin950">
    <form method="post" action="<?= URL::query() ?>" id="aa_club">
        <div><label>名称：</label><br />
            <input size="60" type="text" name="name" value="<?= @$club['name'] ?>"  class="input_text" />
        </div>
        <div><label>分类(如体育、金融等)：</label><br />
            <input size="60" type="text" name="type" value="<?= @$club['type'] ?>" class="input_text"/>
        </div>
        <div><label>负责人ID / 头衔：</label><br />
            <input size="10" type="text" name="user_id" value="<?= @$club['Members'][0]['user_id'] ?>" class="input_text"/> /
            <input size="20" type="text" name="title" value="<?= @$club['Members'][0]['title'] ?>" class="input_text"/>
        </div>
        <div><label>简介(选填)：</label><br />
            <textarea name="intro" style="width:600px;height: 150px" class="input_text"><?= @$club['intro'] ?></textarea>
        </div>
        <div style="margin:10px 0"><input type="button" id="submit_button" onclick="new ajaxForm('aa_club',{redirect:'<?= URL::site('aa_admin_club?id='.$_ID) ?>'}).send()" value="提交"  class="button_blue"/></div>
    </form>
</fieldset>