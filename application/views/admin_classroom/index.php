<!-- admin_classroom/index:_body -->

<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1"  >
    <tr >
        <td height="29" class="td_title" >
            <div class="title_name">
                <b>按属性查看：</b></div>

            <div class="title_search">
                <form name="search" action="" method="get">
                    搜索：<select name="start_year" style="padding:1px 4px;">
                        <option value="" > 入学年份 </option>
                        <?php for ($i = date('Y'); $i >= 1900; $i--): ?>
                            <option value="<?= $i ?>" ><?= $i ?></option>
                        <?php endfor ?>

                    </select>
                    <input name="q" type="text" style="width:150px" class="keyinput">
                    <input type="submit" value="搜索">
                </form>
            </div>
        </td>
    </tr>
    <tr>
        <td height="25" style="padding:0px 10px" >
            快速检索：<a href="<?= URL::site('admin_classroom/index') ?>" style="<?= empty($_GET) ? 'font-weight:bold' : '' ?>">所有</a> &nbsp;|&nbsp;
            <a href="<?= URL::site('admin_classroom/index?verify=1'); ?>"  style="color:green;<?= $verify ? 'font-weight:bold' : '' ?>">已审核</a>&nbsp;|&nbsp;
            <a href="<?= URL::site('admin_classroom/index?verify=0'); ?>"  style="color:#ff6600;<?= $verify == '0' ? 'font-weight:bold' : '' ?>">待审核</a>&nbsp;|&nbsp;
        </td>
    </tr>
</table>

<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" style="margin-top:0px " >
    <tr >
        <td height="29" class="td_title" colspan="8" ><b>按属性查看：</b>
    </tr>
    <tr>

        <td width="15%">&nbsp;&nbsp;&nbsp;入学~毕业年份</td>
        <td width="25%" class="left">班级名称</td>
        <td width="15%" class="left">所属学院(系)</td>
        <td width="10%" class="center">人数</td>
        <td width="10%" class="center">创建日期</td>
        <td  class="center">审核状态</td>
        <td width="5%" class="center">设定审核</td>
        <td width="10%" class="center">删除</td>
    </tr>

    <?php if (count($classroom) == 0): ?>
        <tr>
        <td colspan="8" style="background-color:#fff;padding:10px; text-align: left; color: #999">暂无班级信息</td>
        </tr>
    <?php else: ?>

        <?php foreach ($classroom as $key => $c): ?>
            <tr  id="classroom_<?= $c['id'] ?>" class="<?php if (($key) % 2 == 0) {
            echo'even_tr';
        } ?>">

                <td class="news_title">
                    &nbsp;&nbsp;&nbsp;&nbsp;<?= $c['start_year'] ? $c['start_year'] : '?' ?> ~ <?= $c['finish_year'] ? $c['finish_year'] : '?' ?>年
                </td>
                <td> <a href="<?= URL::site('classroom_home?id=' . $c['id']) ?>"  target="_blank"><?= $c['speciality'] ? $c['speciality'] : $c['name'] ?></a></td>
                <td><?= $c['institute'] ?></td>

                <td class="timestamp" style="text-align: center"><?= $c['member_num'] ?>人</td>
                <td style="text-align: center"><?= date('Y-n-d', strtotime($c['create_at'])); ?></td>
                <td class="center">
                    <?php if ($c['verify']): ?>
                        <img src="/static/images/li_ok.gif" />
                    <?php else: ?>
                        <img src="/static/images/static3.gif" />
        <?php endif ?>
                </td>
                <td class="center"><input type="checkbox" value="true" onclick="verify(<?= $c['id'] ?>,1)" <?= $c['verify'] ? 'checked' : '' ?> /></td>
                <td class="handler" style="text-align: center">
                    <a href="javascript:del(<?= $c['id'] ?>)">删除</a>
                </td>

            </tr>
    <?php endforeach; ?>
<?php endif; ?>
</table>
<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" style="margin-top:-1px " >
    <tr>
        <td style="height: 50px"><?= $pager ?></td>
    </tr>
</table>
<script type="text/javascript">
    function verify(cid){
        new Request({
            url: '<?= URL::site('admin_classroom/verify') . URL::query() ?>',
            type: 'post',
            data: 'cid='+cid
        }).send();
    }

    function del(cid){
        var b = new Facebox({
            title: '删除确认！',
            icon:'question',
            message: '确定要删除此班级吗？<Br>注意删除本班级将同时删除本班级的所有相关信息！',
            ok: function(){
                new Request({
                    url: '<?= URL::site('admin_classroom/del?cid=') ?>'+cid,
                    type: 'post',
                    success: function(){
                        candyDel('classroom_'+cid);
                    }
                }).send();
                b.close();
            }
        });
        b.show();
    }
</script>