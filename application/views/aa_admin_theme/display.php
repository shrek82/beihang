<!-- aa_admin_theme/index:_body -->
<div id="admin950">
    <form id="home_display" action="<?= URL::query(); ?>" method="post" >
        <table>
            <tr>
                <td class="field">滚动Banner：</td>
                <td><select name="banner_limit">
                        <?php for ($i = 0; $i <= 10; $i++): ?>
                            <option value="<?= $i ?>" <?= $theme['banner_limit'] == $i ? 'selected' : ''; ?>><?= $i ?></option>
                        <?php endfor; ?>
                    </select>张 &nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#999">尽可能减少的图片数量，这样可以提高网页打开速度，0为不显示</span></td>
            </tr>
            <tr>
                <td class="field">发布新鲜事：</td>
                <td><input type="radio" name="allow_post_weibo" value="1" <?= $theme['allow_post_weibo'] ? 'checked' : ''; ?> />允许 <input type="radio" name="allow_post_weibo" value="0"  <?= !$theme['allow_post_weibo'] ? 'checked' : ''; ?>/>禁止&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#999">打开或关闭新鲜事发布功能</span></td>
            </tr>
            <tr>
                <td class="field">默认新鲜事话题：</td>
                <td><input type="text" name="weibo_topic" value="<?= $theme['weibo_topic'] ?>" class="input_text" style="width:200px">&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#999">默认新鲜事话题，例如“#校友毅行#”</span></td>
            </tr>
            <tr>
                <td class="field">滚动新鲜事条数：</td>
                <td><select name="weibo_limit">
                        <option value="0" <?= $theme['weibo_limit'] === 0 ? 'selected' : ''; ?>>0</option>
                        <option value="10" <?= $theme['weibo_limit'] == 10 ? 'selected' : ''; ?>>10</option>
                        <option value="15" <?= $theme['weibo_limit'] == 15 ? 'selected' : ''; ?>>15</option>
                        <option value="20" <?= $theme['weibo_limit'] == 20 ? 'selected' : ''; ?>>20</option>
                    </select>条<span style="color:#999">&nbsp;&nbsp;&nbsp;&nbsp;滚动的新鲜事条数，默认10条，0为不显示任何新鲜事</span></td>
            </tr>
            <tr>
                <td class="field">新闻动态</td>
                <td><select name="news_limit">
                        <?php for ($i = 0; $i <= 15; $i++): ?>
                            <option value="<?= $i ?>" <?= $theme['news_limit'] == $i ? 'selected' : ''; ?>><?= $i ?></option>
                        <?php endfor; ?>
                    </select>条&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#999">显示的新闻条数,0为不显示新闻</span></td>
            </tr>
            <tr>
                <td class="field">近期活动</td>
                <td><select name="event_limit">
                        <?php for ($i = 0; $i <= 15; $i++): ?>
                            <option value="<?= $i ?>" <?= $theme['event_limit'] == $i ? 'selected' : ''; ?>><?= $i ?></option>
                        <?php endfor; ?>
                    </select>条&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#999">显示的活动条数,0为不显示活动</span></td>
            </tr>
            <tr>
                <td class="field">论坛话题</td>
                <td><select name="bbsunit_limit">
                        <?php for ($i = 0; $i <= 15; $i++): ?>
                            <option value="<?= $i ?>" <?= $theme['bbsunit_limit'] == $i ? 'selected' : ''; ?>><?= $i ?></option>
                        <?php endfor; ?>
                    </select>条&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#999">显示的论坛话题总数，0为不显示论坛话题</span></td>
            </tr>
            <tr>
                <td colspan="2"><input type="button" id="submit_button" value="保存修改" class="button_blue"  onclick="save()" /></td>
            </tr>
        </table>

    </form>
</div>

<script type="text/javascript">
    function save(){
        new ajaxForm('home_display',{textSending: '提交中',textError: '发布失败',textSuccess: '修改成功',callback:function(id){
                okAlert('恭喜您，页面风格个修成功！');
            }}).send();
    }
</script>