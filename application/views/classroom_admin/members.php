<div id="admin950">

    <table width="100%" class="hp_table" id="bench_list"  cellspacing="0" cellpadding="0">
    <tr>
        <th colspan="2" width="400">
           姓名
        </th>
        <th width="50">管理员</th>
	<th style="text-align: center">主页</th>
        <th width="100">最后访问</th>
	<th style="text-align: center">请出班级</th>
    </tr>

    <?php if(count($members) == 0): ?>
    <tr>
        <td colspan="5">没有找到相关的成员。</td>
    </tr>
    <?php endif; ?>

    <?php foreach($members as $key=>$m): ?>
    <tr id="m_<?= $m['id'] ?>" class="<?=(($key+1)%2)==0?'even_tr':'odd_tr';?>">
        <td width="60" align="center" style="padding:10px">
<a href="javascript:userDetail(<?= $m['user_id'] ?>)" title="浏览详细信息"><?= View::factory('inc/user/avatar', array('id' => $m['user_id'], 'size'=>48)) ?></a>
        </td>
        <td width="120">
            <a href="<?= URL::site('user_home?id='.$m['user_id']) ?>"><?= $m['User']['realname'] ?></a>
<br />
                        <a href="javascript:;" onclick="sendMsg(<?=$m['user_id']?>)"  title="发送站内信" ><img src="/static/images/user/email.gif" style="vertical-align: middle"></a>
        </td>
        <td align="center">
            <input type="checkbox" onclick="setMan(<?=$m['id']?>, this.checked)" name="is_manager" <?= $m['is_manager']?'checked':'' ?> <?php if($m['user_id']==$_UID AND $m['is_manager']):?>disabled="disabled"<?php endif;?>/>
        </td>
	<td style="text-align: center"><a href="<?= URL::site('user_home?id='.$m['user_id']) ?>" target="_blank">浏览</a></td>
        <td style="text-align: center">
            <?= $m['visit_at']?Date::span_str(strtotime($m['visit_at'])).'前':'尚未' ?>访问
        </td>
	<td style="text-align: center">
            <?php if(!$m['is_manager']): ?>
            <a href="javascript:kout(<?=$m['id']?>)">请出班级</a>
            <?php else: ?>
            <span style="color:#999">无权限</span>
            <?php endif; ?>
        </td>
	    </tr>
    <?php endforeach; ?>
    </table>
 <?=$pager?>
</div>


<script type="text/javascript">
    function setMan(mid, val){
        var v = val == true ? 1 : 0;
        var r = new Request({
            type: 'post',
            url: '<?= URL::site('classroom_admin/member/is_manager?id='.$_CLASSROOM['id']) ?>&mid='+mid,
            data: 'val='+v
        });
        r.send();
    }

    function setTitle(mid, title){
        var r = new Request({
            type: 'post',
            url: '<?= URL::site('classroom_admin/member/title?id='.$_CLASSROOM['id']) ?>&mid='+mid,
            data: 'val='+title
        });
        r.send();
    }

    function kout(mid){
        var b = new Facebox({
            title: '危险操作！',
            message: '确定要将此成员踢出本班？相关数据将会删除。',
            icon:'question',
            ok: function(){
                new Request({
                    url: '<?= URL::site('classroom_admin/member/kout?id='.$_CLASSROOM['id'].'&mid=') ?>'+mid,
                    type: 'post',
                    success: function(d){
                        if(d){ alert(d); }
                        else candyFadeout('m_'+id);
                    }
                }).send();
                b.close();
            }
        });
        b.show();
    }

function userDetail(uid){
    var box = new Facebox({
        url: '<?= URL::site('user/userDetail?id=') ?>'+uid,
	width:500
    });
    box.show();
}
</script>