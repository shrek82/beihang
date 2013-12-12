<!-- aa_admin_club/index:_body -->
<script src="/static/js/jquery.tablednd.js"></script>
<style type="text/css">
    #bench_list tr.myDragClass{background-color: #E5EDF5;border-bottom:1px dotted #9DB2C6;}
</style>
<div id="admin950">
    <?php if (count($club) == 0): ?>
        <span class="nodata">暂时没有创建任何俱乐部。</span>
    <?php else: ?>
        <div style="margin:10px 0;color: #999" id="tuodonghang">拖动行进行排序:</div>
        <table id="bench_list" width="100%"  border="0"  cellpadding="0" cellspacing="0">
            <tr>
                <th width="5%" style="text-align:left">&nbsp;ID</th>
                <th width="30%" style="text-align:left">俱乐部名称</th>
                <th width="10%" style="text-align:center">成员数</th>
                <th width="15%" style="text-align:center">创建时间</th>
                <th width="7%" style="text-align:center">编辑</th>
                <th width="10%" style="text-align:center">管理</th>

            </tr>


            <?php if (count($club) > 0): ?>
                <?php foreach ($club as $key => $c): ?>

                    <tr id="c_<?= $c['id'] ?>" class="<?= (($key + 1) % 2) == 0 ? 'even_tr' : 'odd_tr'; ?>">
                        <td style="text-align:left">&nbsp;<?= $c['id'] ?></td>
                        <td style="text-align:left;padding:5px"><a href="<?= URL::site('club_home') . URL::query(array('id' => $c['id'])) ?>" target="_blank"><?= $c['name'] ?></a></td>
                        <td style=" text-align: center" >
                            <?= $c['total_member'] ?>
                        </td>
                        <td style="text-align:center"><?= $c['create_at'] ?></td>
                        <td style="text-align:center"><a href="<?= URL::site('aa_admin_club/form') . URL::query(array('club_id' => $c['id'])) ?>">修改</a></td>
                        <td style="text-align:center"><a href="<?= URL::site('club_admin_base/index') . URL::query(array('id' => $c['id'])) ?>" target="_blank">管理</a></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td height="25" style="padding:0px 10px" colspan="4" >
                        <span class="nodata">暂时还没有创建任何俱乐部!</span>
                    </td>
                </tr>
            <?php endif; ?>
        </table>
    <?php endif; ?>
</div>


<script type="text/javascript">
    $(document).ready(function() {
        $("#bench_list").tableDnD({
            onDragClass: "myDragClass",
            onDrop: function(table, row) {
                var rows = table.tBodies[0].rows;
                var getstr='';
                for (var i =1; i < rows.length; i++) {
                       getstr=getstr?getstr+'||'+rows[i].id+'-'+i:'&order='+rows[i].id+'-'+i;
                };
                saveorder(getstr);
                $('#tuodonghang').html('<span style="color:blue"><img src="/static/images/loading.gif" style="border-width: 0;vertical-align: middle"> 正在保存排序，请稍候...</span>');
                //console.log("结束行：" + row.id);
                //console.log(debugStr);
            },
            onDragStart: function(table, row) {
                $('#tuodonghang').html('<span style="color:#666">正在拖动排序...</span>');
                //console.log("开始拖动行：" + row.id);
            }
        });
    });

    function saveorder(getstr) {
        if(!getstr){
            return false;
        }
        new Request({
            url: '/aa_admin_club/setOrder?id=<?=$_ID?>&order='+getstr,
            type: 'post',
            success: function(data) {
                        $('#tuodonghang').html('<span style="color:green"><img src="/static/images/ico_yes.gif" style="border-width: 0;vertical-align: middle"> 排序修改成功!</span>');
            },
            error: function() {
                    $('#tuodonghang').html('<span style="color:red"><img src="/static/images/static3.gif" style="border-width: 0;vertical-align: middle"> 很遗憾，排序失败，请重试或与管理员联系!</span>');
            }
        }).send();
    }
</script>