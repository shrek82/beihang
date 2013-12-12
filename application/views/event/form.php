<style type="text/css">
    #eventTable{}
    #eventTable .field{ width: 160px; padding: 0px 4px; text-align: right; height: 34px; }
    #eventTable .dotted_line{height:30px; line-height: 30px; color: #1A5FAF; font-weight: bold; font-size: 14px}
    #eventTable .must{ color: #f00}
</style>
<p><a href="<?= URL::site('event') ?>" title="回活动首页"><img src="/static/images/event_title.gif" ></a></p>

<div style="padding:20px 25px">
    <div class="blue_tab" style="margin-bottom:20px">
        <ul>
            <li><a href="<?= URL::site('event/form') ?>" id="one1"   class="cur" style="width:100px;font-size: 14px; font-weight: bold"><?= !$event || $restart == 'y' ? '发起活动' : '修改活动' ?></a></li>
        </ul>
    </div>
    <?php
    $etype = Kohana::config('icon.etype');
    $restart = Arr::get($_GET, 'restart');
    $event_num = 1;
    if ($restart) {
        $event_num = $event['num'];
        $event['start'] = '';
        $event['id'] = '';
        $event['finish'] = '';
        $event['sign_start'] = '';
        $event['sign_finish'] = '';
        if (strstr($event['title'], $event['num'])) {
            $event['title'] = str_replace($event['num'], $event_num, $event['title']);
        } else {
            $event['title'] .= '(第' . $event_num . '期)';
        }
    } else {
        $event_num = $event['num'];
    }
    ?>
    <!-- news/form:_body -->
    <form method="post" action="<?= URL::site('event/publish') ?>" id="event_form" >
        <table cellpadding="0" align="left" border="0" cellspacing="0" id="eventTable">
            <tr>
                <td colspan="2" class="dotted_line" >基本信息</td>
            </tr>
            <tr>
                <td class="field">发起人：</td>
                <td style="width:790px"><?= $event ? $event['User']['realname'] : $_SESS->get('realname'); ?></td>
            </tr>
            <tr>
                <td class="field"><span class="must">*</span> 活动类型：</td>
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
                <td class="field"><span class="must">*</span> 发布在：</td>
                <td><?= View::factory('inc/event/belongs_to', compact('aa_id', 'club_id')); ?></td>
            </tr>
            <tr>
                <td class="field"><span class="must">*</span> 活动名称：</td>
                <td><input style="width:500px;" type="text" name="title" value="<?= @$event['title'] ?>"  class="input_text"/>&nbsp;<input type="hidden" name="is_vcert" id="vcert" value="<?= @$event['is_vcert'] ? '1' : '0'; ?>"><span><img src="/static/images/ico_vcert<?= @$event['is_vcert'] ? '' : '2'; ?>.png" id="ico_vcert" title="校友会认证活动" onclick="set_vcert()" style=" cursor: pointer"></span></td>
            </tr>
            <tr>
                <td class="field"><span class="must">*</span> 开始时间：</td>
                <td><div><input type="text" name="start" style="width:200px;"  value="<?= $event['start'] ? date('Y-m-d H:i', strtotime($event['start'])) : ''; ?>" class="start input_text" id="estart"  onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm'})" /> ~ <input type="text" name="finish" style="width:200px;"   value="<?= $event['finish'] ? date('Y-m-d H:i', strtotime($event['finish'])) : ''; ?>" class="start input_text" id="finish"  onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm'})"/></div></td>
            </tr>
            <tr>
                <td class="field"><span class="must">*</span> 活动地址：</td>
                <td><input style="width:500px;" type="text" name="address" value="<?= @$event['address'] ?>"  class="input_text"/></td>
            </tr>


            <tr>
                <td class="field"><span class="must">*</span> 活动标签：</td>
                <td><input style="width:500px;" type="text" name="tags" value="<?= @$event['tags'] ?>"  class="input_text"/>&nbsp;&nbsp;<span style="color:#999">多个请用空格隔开，例如 羽毛球,联谊会</span></td>
            </tr>
            <?php if ($permission['is_control_permission']): ?>
                <tr>
                    <td class="field" >海报图片：</td>
                    <td ><div id="uploading" style="display:none; color:#3993E0;width:600px; height:30px;"><img src="/static/images/loading4.gif"  hspace="4" align="absmiddle"  />正在上传中，请稍候...</div><input type="hidden" name="poster_path" id="filepath" value="<?= $event['poster_path'] ?>" /><iframe  id="upfileframe" name="upfileframe" scrolling="no" style="width:500px; height:30px; display:inline" frameborder="0" src="<?= URL::site('upload/frame?msg=图片大小640x340px') ?>"></iframe></td>
                </tr>
            <?php endif; ?>
            <tr>
                <td colspan="2" style="height:340px">
                    <textarea id="event_content" name="content" style="width:880px;height:300px"><?= @$event['content'] ?></textarea>
            <?=
            View::ueditor('event_content', array(
                'toolbars' => Kohana::config('ueditor.common'),
                'autoHeightEnabled' => 'false',
                'enterTag' => 'br',
                'autoFloatEnabled'=>'true',
                'initialStyle' => '"body{font-size:14px;}"'
            ));
            ?>
                </td>
            </tr>

            <tr>
                <td colspan="2" class="dotted_line" >报名设置</td>
            </tr>
            <tr>
                <td class="field" style="padding-top:10px">活动名额：</td>
                <td style="padding-top:10px"><input style="width:200px;"   type="text" name="sign_limit" value="<?= @$event['sign_limit'] > 0 ? $event['sign_limit'] : ''; ?>" class="input_text" /> 人&nbsp;&nbsp;<span style="color:#999">(留空为不限)</span></td>
            </tr>
            <tr>
                <td class="field">每位最多报名：</td>
                <td><input style="width:200px;"   type="text" name="maximum_entourage" value="<?= @$event['maximum_entourage'] > 0 ? $event['maximum_entourage'] : '1'; ?>" class="input_text" /> 人&nbsp;&nbsp;积分至少： <input style="width:200px;"   type="text" name="points_at_least" value="<?= @$event['points_at_least'] > 0 ? $event['points_at_least'] : ''; ?>" class="input_text" /> 分&nbsp;&nbsp;<span style="color:#999">(留空均为为不限)</span></td>
            </tr>
            <tr>
                <td class="field" >设置选择标签：</td>
                <td><input style="width:200px;"   type="text" name="category_label" value="<?= @$event['category_label']?$event['category_label'] : ''; ?>" class="input_text" />&nbsp;&nbsp;<span style="color:#999">(例如：选择队伍 ，<a href="javascript:signCategorys(<?= @$event['id']?$event['id']:'0'?>)" style="color:#999">点击设置</a></span>)</td>
            </tr>

            <tr>
                <td colspan="2" class="dotted_line" style="height:30px; line-height: 30px; color: #1A5FAF; font-weight: bold">门票及发放设置</td>
            </tr>
            <tr>
                <td class="field" style="padding-top:10px">需持门票入馆</td>
                <td style="padding-top:10px"><input type="radio" name="need_tickets" value="0"  <?= !$event['need_tickets'] ? 'checked="checked"' : ''; ?>  onclick="more_options('more_options','hidden')"/>不需要 <input type="radio" name="need_tickets" value="1"  <?= $event['need_tickets'] ? 'checked="checked"' : ''; ?> onclick="more_options('more_options','show')"/>需要
                </td>
            </tr>
            <tr id="more_options" style="padding:0px; display: <?= $event['need_tickets'] ? '' : 'none'; ?>;">
                <td class="field"></td>
                <td><div>最多可发放门票总数：<input style="width:200px;" type="text" name="total_tickets" value="<?= @$event['total_tickets'] ?>"  class="input_text"/>  <span style="color:#999">张</span><br /></div>
                    <div>每位最多可领取门票：<input style="width:200px;" type="text" name="maximum_receive" value="<?= @$event['maximum_receive'] ?>"  class="input_text"/>  <span style="color:#999">张</span><br /></div>
                    <div>领票位置：<span style="color:#999">(每行填写一处)</span><br />
                        <textarea name="receive_address" style="width:500px;height:80px" class="input_text"><?= $event['receive_address'] ?></textarea>
                    </div></td>
            </tr>
        </table>

        <div style=" text-align: center; margin: 20px; clear: both">
            <input type="hidden" name="eventicon" value="auto" />
            <input type="hidden" name="num" value="<?= $event_num ?>" />
            <input type="hidden" name="id" value="<?= @$event['id'] ?>" />
            <input type="button" id="submit_button" value="<?= !$event || $restart == 'y' ? '立即发起' : '保存修改'; ?>" class="button_blue"  onclick="publish_event()" /><input type="button" onclick="history.back()" value="取消"  class="button_gray" />
        </div>

    </form>
</div>

<script type="text/javascript">
    function more_options(objbox,display){
        if(display=='hidden'){
            document.getElementById(objbox).style.display='none';
        }
        else{
            document.getElementById(objbox).style.display='';
        }
    }

    //打开分类设置窗口
    function signCategorys(event_id){
        if(event_id===0){
            alert('可选队伍或队长名称只能在活动发布后设置，谢谢！');
            return false;
        }
        window.location.href='/event/signCategorys?event_id='+event_id;
    }
</script>
