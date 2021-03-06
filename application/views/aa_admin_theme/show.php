<!-- aa_admin_base/show:_body -->
<div id="admin950">

    <?php if(count($shows)==0):?>
    <div class="nodata">还没有添加任何图片展示内容。</div>
    <?php else:?>

    <table id="bench_list" width="100%"  border="0"  cellpadding="0" cellspacing="0">
        <tr>
            <th style="text-align:left">预览</th>
            <th style="text-align:left">名称或标题</th>
            <th style="text-align:center">在网页显示</th>
            <th style="text-align:center">修改</th>
            <th style="text-align:center">删除</th>
        </tr>
        <?php foreach ($shows as $key => $s): ?>
            <tr id="s_<?= $s['id'] ?>" class="<?= (($key + 1) % 2) == 0 ? 'even_tr' : 'odd_tr'; ?>">
                <td style="text-align:left;padding: 10px"><a href="<?= $s['url'] ?>" title="点击测试链接地址" target="_blank"><img src="/<?= $s['filename']; ?>" style="width:150px;border-width:0" /></a></td>
                <td style="text-align:left"><a href="<?= $s['url'] ?>" title="点击测试链接地址" target="_blank"><?= $s['title'] ?></a></td>
                <td style="text-align:center"><input type="checkbox" onclick="setDisplay(<?= $s['id'] ?>)" <?= !empty($s['is_display']) ? 'checked' : '' ?>  /></td>
                <td style="text-align:center"><a href="<?= URL::query(array('show_id' => $s['id'])) ?>">修改</a></td>
                <td style="text-align:center"><a href="javascript:;" onclick="del(<?= $s['id'] ?>)" />删除</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <div><?= $pager ?></div>
    <?php endif;?>
 <br><br>
    <form action="/aa_admin_theme/saveshow?id=<?= $_ID ?>" method="post" id="show_form">
        <table>
            <tr>
                <td style="width:90px; ">图片</td>
                <td>
                    <div id="uploading" style="display:none; color:#3993E0;width:600px; height:30px;"><img src="/static/images/loading4.gif"  hspace="4" align="absmiddle"  />正在上传中，请稍候...</div><input type="hidden" name="filename" id="filepath" value="<?= $show['filename'] ?>" /><iframe  id="upfileframe" name="upfileframe" scrolling="no" style="width:600px; height:30px; display:inline" frameborder="0" src="<?= URL::site('upload/frame?msg=尺寸见下面') ?>"></iframe>
                    <?php if ($show['filename']): ?><a href="<?= $show['filename'] ?>" target="_blank" >查看已经上传图片</a><?php endif; ?>
                </td>
            </tr>
            <tr>
                <td style="">标题</td>
                <td><input type="text" name="title" size="80" value='<?= $show['title'] ?>' class="input_text" /></td>
            </tr>
            <tr>
                <td style="">链接地址</td>
                <td><input type="text" name="url" size="80" value='<?= $show['url'] ?>' class="input_text" /></td>
            </tr>
            <tr>
                <td style="">同步显示在</td>
                <td>
                    <select name="club_id">
                        <option value="0" >选择俱乐部...</option>
                        <?php foreach ($clubs AS $c): ?>
                            <option value="<?= $c['id'] ?>" <?= $show['club_id'] == $c['id'] ? 'selected' : '' ?>><?= $c['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td style="">排列顺序</td>
                <td>
                    <select name="order_num">
                        <?php for ($i = 1; $i <= (count($shows) + 1); $i++): ?>
                            <option value="<?= $i ?>" <?= $show['order_num'] == $i ? 'selected' : '' ?>><?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2"><input type="button" value="<?= $show['id'] ? '保存修改' : '添加'; ?>" class="button_blue" id="submit_button" onclick="save()" /></td>

            </tr>

            <tr>
                <td colspan="2" style="color: #666;padding-top:25px"><b>图片尺寸</b>：首页banner:676×160px，logo:100×50px，侧栏图片：210×(50至200)px<br>
                </td>
            </tr>
        </table>
        <input type="hidden" name="show_id" value="<?= $show['id'] ?>"/>
        <input type="hidden" name="format" value="<?= $format ?>"/>
    </form>
</div>

<script type="text/javascript">

    function save(){
        new ajaxForm('show_form',{textSending: '发送中',textError: '重试',textSuccess: '操作成功',callback:function(id){
                window.location.reload();
            }}).send();
    }

    function del(id){
        new candyConfirm({
            message:'确定要删除该图片展示吗？',
            url: '<?= URL::base() ?>aa_admin_theme/delshow?id=<?= $_AA['id'] ?>&show_id='+id,
            removeDom:'s_'+id
        }).open();
    }

    function setDisplay(id){
        new Request({
            url: '<?= URL::base() ?>aa_admin_theme/setHidden?id=<?= $_AA['id'] ?>&show_id='+id,
            type:'post'
        }).send();
    }

</script>