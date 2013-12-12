<span style="color:#f00">请完善个人资料后再次报名，谢谢！</span>
<form id="sign_form" name="sign_form" action="<?= URL::site('event/sign?event_id') ?>" method="post" >
    <table width=400 border="0" cellspacing="0" cellpadding="0" style="margin-top:10px;">
        <?php if (!$userWorks): ?>
            <tr>
                <td  style="width:18%;text-align: right;padding-right: 5px;">所属行业：</td>
                <td >
                    <select name="industry">
                        <?php foreach ($industry AS $i): ?>
                            <option value="<?= $i ?>"><?= $i ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td  style="width:18%;text-align: right;padding-right: 5px;">工作单位：</td>
                <td ><input name="company" type="text" style="width:300px"></td>
            </tr>
            <tr>
                <td  style="width:18%;text-align: right;padding-right: 5px;">职务：</td>
                <td ><input name="job" type="text" style="width:300px"></td>
            </tr>
        <?php else: ?>
            <?php if (empty($userWorks['industry'])): ?>
                <tr>
                    <td  style="width:18%;text-align: right;padding-right: 5px;">所属行业：</td>
                    <td >
                        <select name="industry">
                            <?php foreach ($industry AS $i): ?>
                                <option value="<?= $i ?>"><?= $i ?></option>
                            <?php endforeach; ?>
                        </select></td>
                </tr>
            <?php endif; ?>
            <?php if (empty($userWorks['compay'])): ?>
                <tr>
                    <td  style="width:18%;text-align: right;padding-right: 5px;">工作单位：</td>
                    <td ><input name="company" type="text" style="width:300px"></td>
                </tr>
            <?php endif; ?>
            <?php if (empty($userWorks['job'])): ?>
                <tr>
                    <td  style="width:18%;text-align: right;padding-right: 5px;">职务：</td>
                    <td ><input name="job" type="text" style="width:300px"></td>
                </tr>
            <?php endif; ?>
        <?php endif; ?>
        <?php if (empty($user['Contact']['mobile'])): ?>
            <tr>
                <td  style="width:18%;text-align: right;padding-right: 5px;">手机：</td>
                <td >
                    &nbsp;&nbsp;<span style="color:#999">人</span></td>
            </tr>
        <?php endif; ?>
        <table>
            </form>