<div style="padding:15px">
    <p style="height:25px; border-bottom: 1px dotted #ccc; line-height: 25px; font-size: 14px">
        <?php if ($unit): ?>
            修改话题
        <?php else: ?>
            发布新话题
        <?php endif; ?>
    </p>

    <form method="post" action="<?= URL::site('bbs/unit' . $type) ?>" id="bbs_unit_form" >
        <?php if ((!$unit AND $_SESS->get('role') == '管理员') OR ($unit AND $is_control_permission)): ?>
            <div style="margin:10px 0; color: #c00; background: #FCFCE5; padding: 10px; border: 1px solid #F0E576"><label for="hidden_btn" >管理员设置：</label><br />
                <input type="checkbox" name="is_fixed"  value="1" <?= $unit['is_fixed'] ? 'checked' : ''; ?>>置顶&nbsp;&nbsp;
                <input type="checkbox" name="is_good" value="1" <?= $unit['is_good'] ? 'checked' : ''; ?>>优秀
                &nbsp;&nbsp;&nbsp;标题颜色：<select name="title_color">
                    <option value="" <?= empty($unit['title_color']) ? 'selected' : ''; ?>>默认</option>
                    <option value="#cc0000" style= "background:#cc0000; color: #fff" <?= $unit['title_color'] == '#cc0000' ? 'selected' : ''; ?>>朱红</option>
                    <option value="#ff3300" style= "background:#ff3300; color: #fff" <?= $unit['title_color'] == '#ff3300' ? 'selected' : ''; ?>>橙色</option>
                    <option value="#01478F" style= "background:#01478F; color: #fff" <?= $unit['title_color'] == '#01478F' ? 'selected' : ''; ?>>深蓝</option>
                    <option value="#0A6F0A" style= "background:#0A6F0A; color: #fff" <?= $unit['title_color'] == '#0A6F0A' ? 'selected' : ''; ?>>绿色</option>

                </select>
            </div>
        <?php endif; ?>

        <div style="margin-top:10px">

            <label>所属版块：</label>

            <select name="bbs_id" style="padding:2px;">
                <?php foreach ($post_aa as $aa): ?>
                    <optgroup label="<?= $aa['name'] ?>">
                        <?php foreach ($aa['bbs_ids'] as $key => $bbs): ?>
                            <option value="<?= $bbs['id'] ?>"  <?php
                            if ($unit AND $unit['bbs_id'] == $bbs['id']) {
                                echo 'selected';
                            } elseif ($bbs_id == $bbs['id']) {
                                echo 'selected';
                            } elseif ($bbs['aa_id'] == $aa_id AND $bbs['club_id'] == $club_id AND $club_id > 0 AND empty($bbs_id)) {
                                echo 'selected';
                            } elseif ($bbs['aa_id'] == $aa_id AND $key === 0 AND empty($club_id) AND empty($bbs_id)) {
                                echo 'selected';
                            } elseif ($key == 0) {
                                
                            }
                            ?>  ><?= $bbs['name'] ?></option>
                                <?php endforeach; ?>
                    </optgroup>
                <?php endforeach; ?>
            </select>

        </div>

        <div style="margin:10px 0">
            <label>内容标题：</label>
            <?php $subject = Kohana::config('bbs.subject'); ?>
            <select name="subject" style="padding:2px;vertical-align: middle">
                <?php foreach ($subject AS $key => $name): ?>
                    <option value="<?= $key ?>" <?= $unit['subject'] == $key ? 'selected' : ''; ?>><?= $name ?></option>
                <?php endforeach; ?>
            </select>&nbsp;<span style="vertical-align: middle"><input type="text" name="title" value="<?= $unit['title'] ?>" size="120" style="font-size:14px; height:22px; padding: 2px 4px" class="input_text" /></span>
        </div>

        <div style="margin:10px 0 5px 0;height:410px">
            <textarea name="content" id="content" style="width:99%;height:350px"><?= $unit[$type]['content'] ?></textarea>
            <?=
            View::ueditor('content', array(
                'toolbars' => Kohana::config('ueditor.common'),
                'autoHeightEnabled' => 'false',
                'enterTag' => 'br',
                'autoFloatEnabled'=>'true',
                'initialStyle' => '"body{font-size:14px;}"'
            ));
            ?>
        </div>
        <input type="hidden" name="vote_id" value="<?= $unit['Vote'] ? $unit['Vote']['id'] : '' ?>">

        <div><input type="checkbox" name="addvote" value="yes" id="hidden_votebox" onclick="this.checked ? $('#votebox').css('display', 'block') : $('#votebox').css('display', 'none')"  <?= $unit['Vote'] ? 'checked="checked"' : '' ?>/>
            <label for="hidden_votebox">发起对以上内容的投票</label><br /></div>
        <div id="votebox" class="<?= $unit['Vote'] ? '' : 'hide' ?>" style="margin:5px 0">
            <table border="0" cellpadding="0" cellspacing="1" width="100%" style="margin:10px 0">
                <tr>
                    <td style="width:70px">投票方式：</td>
                    <td>
                        <input type="radio" name="votetype" value="radio" id="radiovote" <?= @!$unit['Vote'] ? 'checked="checked"' : '' ?><?= @$unit['Vote']['type'] == 'radio' ? 'checked="checked"' : '' ?>/><label for="radiovote">单选</label>
                        <input type="radio" name="votetype" value="checkbox" id="checkboxvote"  onclick="this.checked ? $('#max_selectbox').cs('display', 'block') : $('#max_selectbox').css('display', 'none')"   <?= @$unit['Vote']['type'] == 'checkbox' ? 'checked="checked"' : '' ?>/><label for="checkboxvote">多选</label><span style="color:#999">（最多选择&nbsp;<input name="max_select" type="text" style="width: 30px" class="input_text" value="<?= @$unit['Vote']['max_select'] ?>">&nbsp;项）</span>
                    </td>
                </tr>

                <tr>
                    <td >结束时间：</td>
                    <td>
                        <?php
                        if ($unit['Vote']) {
                            $finish_date = $unit['Vote']['finish_date'];
                        } else {
                            $d = date('Y-m-d');
                            $finish_date = date("Y-m-d", strtotime("$d + 60 day"));
                        }
                        ?>

                        <input type="text" name="finish_date" value="<?= $finish_date ?>"  style="width:300px" class="input_text" />&nbsp;&nbsp;<span style="color:#999">不填为不限,默认为2个月</span></td>
                </tr>

                <tr>
                    <td class="field" valign="top">投票选项：</td>
                    <td>
                        <?php if ($unit['Vote']): ?>
                            <?php foreach ($vote_options AS $o): ?>
                                <p id="vote_opt_<?= $o['id'] ?>"><input type="hidden" value="<?= $o['id'] ?>" name="vote_opt_id[]"><input type="text" class="input_text" style="width:30px" name="vote_opt_order_num[]" value="<?= $o['order_num'] ?>">&nbsp;.&nbsp;<input type="text" class="input_text" name="vote_opt_title[]" value="<?= $o['title'] ?>" style="width:500px">&nbsp;&nbsp;<a href="javascript:delVoteOption(<?= $unit['Vote']['id'] ?>,<?= $o['id'] ?>)" style="color:#999;" title="删除选项">×</a> <span style="color:#999"> {option:<?= $o['id'] ?>}</span></p>
                            <?php endforeach; ?>
                            <p id="addVoteOption">
                                <a href="javascript:candyCloneInput()">添加选项</a>
                            </p>
                        <?php else: ?>
                            <textarea name="vote_options_textarea" style="width:600px; height:100px" class="input_text"></textarea><br><span style="color:#999">提示：每行填写一条投票选项，无需添加序号。</span>
                        <?php endif; ?>
                    </td>
                </tr>

                </tbody>
            </table>
        </div>

        <script type="text/javascript">
            //追加选项
            function candyCloneInput() {
                $("#addVoteOption").before('<p><input type="hidden" value="" name="vote_opt_id[]"><input type="hidden" value="" name="vote_opt_order_num[]"><input type="text" class="input_text" name="vote_opt_title[]" value="" style="width:540px">&nbsp;&nbsp;<span style="color:#999">追加一项，留空为忽略</span></p>');
            }

            function save() {
                new CandyForm('form_id', {textSending: '发送中', textError: '重试', textSuccess: '修改成功', callback: function(id) {
                        window.location.reload();
                    }}).send();
            }

            function delVoteOption(vid, oid) {
                new candyConfirm({
                    message: '确定要删除此选项吗？注意删除后将同时删除该项统计结果！',
                    url: '<?= URL::site('bbs/delVoteOpiton?vid=') ?>' + vid + '&oid=' + oid,
                    removeDom: 'vote_opt_' + oid
                }).open();
            }
        </script>

        <?php if ($type == 'Post'): ?>
            <div class="hide"><input type="checkbox" id="hidden_btn" onclick="this.checked ? $('#hidetext').css('display', 'block') : $('#hidetext').css('display', 'none')" />
                <label for="hidden_btn">添加回复可见内容</label><br /></div>
            <div id="hidetext" class="hide">
                <textarea name="hidden" id="hidden_content" style="width:100%;height:100px" class="input_text"><?= $unit[$type]['hidden'] ?></textarea>
            </div>

            <script type="text/javascript">
    <?php if ($unit['Post']['hidden']): ?>
                $('#hidden_btn').attr('checked', true);
                $('#hidetext').attr('display', 'block')
    <?php endif; ?>
            </script>
        <?php endif; ?>

        <div style=" text-align:  center">
            <input type="hidden" name="id" value="<?= $unit['id'] ?>" />
            <input type="hidden" name="ios" value="" />
            <?php if ($unit): ?>
                <input type="button"   value="保存修改"   onclick="unitModify();" class="button_blue" id="submit_button" />
            <?php else: ?>
                <input type="button" value="发布话题"    onclick="unitSave();" class="button_blue" id="submit_button" />
            <?php endif; ?>

            <input type="button" onclick="history.back()" value="取消"  class="button_gray" />
        </div>
    </form>
</div>
<script type="text/javascript">
            var bbs_unit_id = '<?= $unit['id'] ?>';
            //富文本编辑器
            function unitSave() {
                var submitButton = document.getElementById('submit_button');
                if (!ueditor.hasContents()) {
                    ueditor.setContent('');
                }
                ueditor.sync();

                submitButton.className = 'button_gray';
                submitButton.style.color = '#999';
                submitButton.disabled = true;
                new ajaxForm('bbs_unit_form', {
                    textSending: '发布中...',
                    textError: '重试发布',
                    loading: true,
                    textSuccess: '恭喜您，发送成功！',
                    callback: function(id) {
                        ueditor.setContent('');
                        submitButton.disabled = true;
                        setTimeout(function() {
                            submitButton.value = '正在跳转至页面...'
                        }, 200);
                        setTimeout(function() {
                            location.href = '<?= URL::site('bbs/view' . $type . '?id=') ?>' + id + bbs_unit_id;
                        }, 800);
                    }}).send();
            }

            //修改话题
            function unitModify() {
                if (!ueditor.hasContents()) {
                    ueditor.setContent('');
                }
                ueditor.sync();
                new ajaxForm('bbs_unit_form', {
                    callback: function(id) {
                        location.href = '<?= URL::site('bbs/view' . $type . '?id=') ?>' + bbs_unit_id;
                    }}).send();
            }
</script>
