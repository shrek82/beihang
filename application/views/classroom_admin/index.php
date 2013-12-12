<!-- classroom_admin/index:_body -->
<div id="admin950">
    <div id="aa_home_left">
    <?php if(count($apply) == 0): ?>
<span class="nodata">暂时还没有待审成员信息。</span>
    <?php endif; ?>

<?php if(count($apply) >0): ?>
<!-- 待审核通过的申请 -->
<table id="bench_list" width="100%" >
     <tr>
        <th colspan="2" height="30" class="left">正在申请加入的校友：</th>
        <th width="50%"></th>
        <th width="20%">当前总会认证状态</th>
        <th style="text-align:center">操作</th>
    </tr>
    <?php foreach($apply as $ap): ?>
    <tr id="apply_<?= $ap['id'] ?>" style="border-bottom: 1px dotted #ccc">
        <td width="48">
         <a href="javascript:userDetail(<?= $ap['user_id'] ?>)" title="浏览详细信息"><?= View::factory('inc/user/avatar', array('id'=>$ap['user_id'],'size'=>48,'sex'=>$ap['User']['sex'])) ?></a>
        </td>
        <td width="70">
            <a href="<?= URL::site('user_home?id='.$ap['user_id']) ?>" title="进入其主页">
                <?= $ap['realname'] ?></a>
            <br />
                <?= Date::span_str(strtotime($ap['apply_at'])) ?>前
        </td>
        <td>
            “<?= $ap['content'] ?>”
        </td>
         <td style="text-align:center"><span style="<?php if($ap['User']['role']=='校友(已认证)') :?>color:#007219<?php endif;?>"><?= $ap['User']['role'] ?></span></td>
        <td align="center">
            <a href="javascript:accept(<?= $ap['id'] ?>)">批准</a>
            <?php if($ap['is_reject']): ?>
            <span class="quiet">已被拒绝</span>
            <?php else: ?>
            <a style="color:red" href="javascript:reject(<?= $ap['id'] ?>)">拒绝</a>
            <?php endif; ?>

        </td>
    </tr>
    <?php endforeach; ?>
</table>
 <?php endif; ?>
</div>
    </div>

<script type="text/javascript">
function accept(id){
    var r = new Request({
        url: '<?= URL::site('classroom_admin/apply/accept').URL::query() ?>&apply_id='+id,
        success: function(){
            candyFadeout('apply_'+id);
        }
    });
    r.send();
}
function reject(id){
    var box = new Facebox({
        title: '拒绝原因',
        url: '<?= URL::site('classroom_admin/apply/reject').URL::query() ?>&apply_id='+id,
        icon:'question',
        ok: function(){
            new ajaxForm('reason_form', {callback:function(){
                box.close();
            }}).send();
        }
    });

    box.show();
}

function userDetail(uid){
    var box = new Facebox({
        url: '<?= URL::site('user/userDetail?id=') ?>'+uid,
	width:500
    });
    box.show();
}
</script>