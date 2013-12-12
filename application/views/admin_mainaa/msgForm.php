<!-- admin_zaa/msgForm:_body -->
<?php
    echo Candy::import('datepicker');
?>
<div style="padding: 20px;">
    <form action="<?= URL::site($_URI).URL::query() ?>" id="sys_msg" method="post">
    <div><label>发送对象</label><br />
        <select name="object" style="padding:2px">
	    <option value="所有" selected>所有校友</option>
	    <option value="校友会管理员">校友会管理员</option>
	</select>
    </div>
    <div><label>标题</label><br />
        <input type="text" name="title" size="60" value="<?= $msg['title'] ?>"  class="input_text" />
    </div>
    <div><label>有效期限</label><br />
        <input readonly="true" type="text" name="start_at" value="<?= $msg['start_at'] ?>" class="start_at input_text" style="width:150px" />
        到
        <input readonly="true" type="text" name="expire_at" value="<?= $msg['expire_at'] ?>" class="expire_at input_text" style="width:150px"/>
    </div>
    <div>
        <textarea id="content" name="content"><?= $msg['content'] ?></textarea>
    </div>
    <div class="center" style="padding:20px">
	        <input type="button" id="submit_button" onclick="post()" value="发送"  class="button_blue"/>
        <input type="button" onclick="location.href='<?= URL::site('admin_sys/index') ?>'" value="返回"  class="button_gray" />

    </div>
</form>
</div>
<?=View::ueditor('content', array(
    'toolbars' => Kohana::config('ueditor.common'),
    'minFrameHeight' => 300,
    'autoHeightEnabled' => 'false',
));
?>

<script type="text/javascript">

    function post(){
        if(!ueditor.hasContents()){ueditor.setContent('');}
        ueditor.sync();
        new ajaxForm('sys_msg', {redirect:'<?= URL::site('admin_sys/index') ?>'}).send();
    }

    candyDatePicker('start_at', false, 'Y-m-d');
    candyDatePicker('expire_at', false, 'Y-m-d');
</script>