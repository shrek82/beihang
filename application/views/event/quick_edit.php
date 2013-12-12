<form method="post" action="<?= URL::site('event/publish') ?>" id="event_form" >
    <div>
        <input type="hidden" name="id" value="<?= @$event['id'] ?>" />
        <input type="hidden" name="is_quick_edit" value="1" />

        <table cellpadding="0" align="left" border="0" cellspacing="0" id="eventTable">
            <tr>
                <td class="field" style="text-align: right"><span class="must">*</span> 活动类型：</td>
                <td >
                    <select name="etype">
                        <option value="">--选择分类--</option>
                        <?php foreach ($etype['icons'] as $name => $ico): ?>
                            <option value="<?= $name ?>" <?= $name == @$event['type'] ? 'selected' : ''; ?>><?= $name ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="field" style="text-align: right"><span class="must">*</span> 发布在：</td>
                <td><?= View::factory('inc/event/belongs_to', compact('aa_id', 'club_id')); ?></td>
            </tr>
            <tr>
                <td class="field" style="text-align: right"><span class="must">*</span> 活动名称：</td>
                <td><input style="width:500px;" type="text" name="title" value="<?= @$event['title'] ?>"  class="input_text"/>&nbsp;<input type="hidden" name="is_vcert" id="vcert" value="<?= @$event['is_vcert'] ? '1' : '0'; ?>"><span><img src="/static/images/ico_vcert<?= @$event['is_vcert'] ? '' : '2'; ?>.png" id="ico_vcert" title="校友会认证活动" onclick="set_vcert()" style=" cursor: pointer"></span></td>
            </tr>
            <tr>
                <td class="field" style="text-align: right"><span class="must">*</span> 开始时间：</td>
                <td><div><input type="text" name="start" style="width:200px;"  value="<?= $event['start'] ? date('Y-m-d H:i', strtotime($event['start'])) : ''; ?>" class="start input_text" id="estart"  onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm'})" /> ~ <input type="text" name="finish" style="width:200px;"   value="<?= $event['finish'] ? date('Y-m-d H:i', strtotime($event['finish'])) : ''; ?>" class="start input_text" id="finish"  onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm'})"/></div></td>
            </tr>
            <tr>
                <td class="field" style="text-align: right"><span class="must">*</span> 活动地址：</td>
                <td><input style="width:500px;" type="text" name="address" value="<?= @$event['address'] ?>"  class="input_text"/></td>
            </tr>
            <tr>
                <td class="field" style="text-align: right"><span class="must">*</span> 活动标签：</td>
                <td><input style="width:500px;" type="text" name="tags" value="<?= @$event['tags'] ?>"  class="input_text"/>&nbsp;&nbsp;<span style="color:#999">多个请用空格隔开</span></td>
            </tr>
            <tr>
                <td class="field" style="text-align: right">活动名额：</td>
                <td ><input style="width:200px;"   type="text" name="sign_limit" value="<?= @$event['sign_limit'] > 0 ? $event['sign_limit'] : ''; ?>" class="input_text" /> 人&nbsp;&nbsp;<span style="color:#999">(留空为不限)</span></td>
            </tr>
            <tr>
                <td class="field" style="text-align: right">每位最多报名：</td>
                <td><input style="width:200px;"   type="text" name="maximum_entourage" value="<?= @$event['maximum_entourage'] > 0 ? $event['maximum_entourage'] : '1'; ?>" class="input_text" /> 人&nbsp;&nbsp;积分至少： <input style="width:200px;"   type="text" name="points_at_least" value="<?= @$event['points_at_least'] > 0 ? $event['points_at_least'] : ''; ?>" class="input_text" /> 分&nbsp;&nbsp;<span style="color:#999">(留空均为为不限)</span></td>
            </tr>
            <tr>
                <td class="field" style="text-align: right">设置选择标签：</td>
                <td><input style="width:200px;"   type="text" name="category_label" value="<?= @$event['category_label'] ? $event['category_label'] : ''; ?>" class="input_text" />&nbsp;&nbsp;<span style="color:#999">(例如：选择队伍 ，<a href="/event/signCategorys?event_id=<?= @$event['id'] ?>" style="color:#999">点击设置</a></span>)</td>
            </tr>
            <tr>
                <td class="field" style="text-align: right">暂停活动：</td>
                <td><span style="vertical-align: middle"><input type="checkbox" name="is_suspend" id="is_suspend" value="1"   <?=@$event['is_suspend']?'checked':'';?>></span><label for="is_suspend">是</label>&nbsp;&nbsp;<span style="color:#999">(将同时关闭报名、置顶、后续评分等功能)</span></td>
            </tr>
            <tr>
                <td class="field" style="text-align: right">关闭报名：</td>
                <td><span style="vertical-align: middle"><input type="checkbox" name="is_stop_sign" id="is_stop_sign" value="1"   <?=@$event['is_stop_sign']?'checked':'';?>></span><label for="is_stop_sign">是</label>&nbsp;&nbsp;<span style="color:#999">(仅关闭报名，不影响已报名信息等其他功能)</span></td>
            </tr>
        </table>
    </div>
</form>
