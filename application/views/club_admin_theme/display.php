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