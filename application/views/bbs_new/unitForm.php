<!-- bbs/unitForm:_body -->
<?php
//判断是否是ios系统
$agent = $_SERVER['HTTP_USER_AGENT'];
$is_ios = strpos($agent, 'iPhone') || strpos($agent, 'iPad') ? True : False;
?>
<div style="padding:15px">
        <p style="height:25px; border-bottom: 1px dotted #ccc; line-height: 25px; font-size: 14px">
                <?php if ($unit): ?>
                        修改话题
                <?php else: ?>
                        发布新话题
                <?php endif; ?>
        </p>
        <form method="post" action="<?= URL::site('bbs/unit' . $type) ?>" id="bbs_unit_form">

                <?php if ($_SESS->get('role') == '管理员'): ?>
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
                <div style="margin-top:20px"><label>发布到</label><br />
                        <select name="bbs_id" style="padding:2px;">
                                <?php foreach ($bbs_ids as $bid => $name): ?>
                                        <option value="<?= $bid ?>" <?= ($bbs_id == $bid || $unit['bbs_id'] == $bid) ? 'selected' : '' ?>>
                                                <?= $name ?>
                                        </option>
                                <?php endforeach; ?>
                        </select>
                        <?php if ($set_limit): ?>
                                <input type="hidden" name="is_limit" value="0" />
                                <input id="is_limit" type="checkbox" name="is_limit" value="1" <?= $unit['is_limit'] == true ? 'checked' : '' ?> />
                                <label for="is_limit">只允许组织成员阅读</label>
                        <?php endif; ?>
                </div>
                <div style="margin:10px 0"><label>标题</label><br />
                        <input type="text" name="title" value="<?= $unit['title'] ?>" size="100" style="font-size:14px; height:22px; padding: 2px 4px" class="input_text"/>
                </div>

                <div style="margin:10px 0"><label>详细内容</label><br />
                        <textarea name="content" id="content" style="width:90%;height:150px"><?= $unit[$type]['content'] ?></textarea>
                        <?php if ($is_ios): ?>
                                <p style="color:#999;margin:10px 0">检测到您的客户端为iPod或iPhone，更多编辑功能请使用pc客户端，谢谢！</p>
                        <?php endif; ?>
                        <?php if (!$is_ios): ?>
<?=View::ueditor('content', array(
    'toolbars' => Kohana::config('ueditor.common'),
    'minFrameHeight' => 300,
    'autoHeightEnabled' => 'false',
));
?>
                <?php endif; ?>
                </div>
<?php if ($type == 'Post'): ?>
                        <div><input type="checkbox" id="hidden_btn" onclick="this.checked ? $('hidetext').setStyle('display', 'block') : $('hidetext').setStyle('display', 'none')" />
                                <label for="hidden_btn">回复可见内容</label><br /></div>
                        <div id="hidetext" class="hide">
                                <textarea name="hidden" id="hidden_content" style="width:90%;height:100px"><?= $unit[$type]['hidden'] ?></textarea>
                        </div>
                        <script type="text/javascript">

                        <?php if ($unit['Post']['hidden']): ?>
                                            $('hidden_btn').set('checked', true);
                                            $('hidetext').setStyle('display', 'block')
        <?php endif; ?>
                        </script>
<?php endif; ?>

                <div class="center">
                        <input type="hidden" name="id" value="<?= $id ?>" />

                        <input type="hidden" name="ios" value="<?= $is_ios ? 'yes' : ''; ?>" />
                        <?php if ($unit): ?>
                                <input type="submit"  onclick="unitSave()" value="保存修改" class="button_blue" />
<?php else: ?>
                                <input type="submit" onclick="unitSave()" value="发布话题" class="button_blue" />
<?php endif; ?>

                        <input type="button" onclick="history.back()" value="返回"  class="button_gray" />
                </div>
        </form>
</div>
<script type="text/javascript">
<?php if (!$is_ios): ?>
                    //富文本编辑器
                    function unitSave(){
                            var unit_form = new CandyForm('bbs_unit_form', {callback:function(id){location.href='<?= URL::site('bbs/view' . $type . '?id=') ?>'+id}});
                        if(!ueditor.hasContents()){ueditor.setContent('');}
                        ueditor.sync();
        <?php if ($type == 'Post'): ?>
                                        $('hidden_content').set('value', editor.html());
        <?php endif; ?>
                                unit_form.send();
                        }
                        //ipod客户端
<?php else: ?>
                function unitSave(){
                        document.getElementById('bbs_unit_form').submit();
                }
<?php endif; ?>
</script>