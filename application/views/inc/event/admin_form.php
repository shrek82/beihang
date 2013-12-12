<?php
    $etype = Kohana::config('icon.etype');
?>
<table width="100%" border="0" cellpadding="0" cellspacing="1" >
<tr>
<td height="29" class="td_title" >
    <div class="title_name">
    <b>按时间查看：</b>
    </div>

    <div class="title_search">
	<form action="<?= URL::site('admin_user/index') ?>" method="get">
	    <input name="q" type="text" style="width:200px" class="keyinput" value="">
	    <input type="submit" value="搜索">
	</form>
    </div>
</td>
</tr>
<tr>
<td height="25" style="padding:0px 10px" >
<a  href="<?= URL::site('admin_event/index') ?>">所有</a>
</td>
</tr>
</table>


<?php if($pager->total_items > 0): ?>
<table width="100%" border="0" cellpadding="0" cellspacing="1" style="margin-top:0px ">
    <tr>
	<td class="td_title" colspan="8" >
校友活动
	</td>
    </tr>
    <tr>
        <td class="center">类型</td>
	<td>基本信息</td>
        <td class="center">状态</td>
 <td class="center" width="120">修改分类</td>
        <td width="60" class="center">首页显示</td>
        <td width="60" class="center">顶置</td>
	<td class="center">屏蔽</td>
        <td class="center">下载名单</td>
    </tr>
    <?php foreach($events as $key=>$e): ?>
    <tr style="border-bottom:1px dotted #ccc;" class="<?php if(($key)%2==0){echo'even_tr';} ?>">
        <td width="50" style="padding:10px">

            <?php $type_icon = $e['type'] ? $etype['icons'][$e['type']] : 'undefined.png'; ?>
            <div style="height:48px; width:48px; background: #fff url(<?= $etype['url'].$type_icon ?>) no-repeat center top;"></div>
        </td>
        <td style="height:90px;">
            <strong style="font-size: 1.1em">
                <a href="<?= Db_Event::getLink($e['id'],$e['aa_id'],$e['club_id']) ?>" target="_blank"><?= $e['title'] ?></a>
            </strong><br />
	    发起：<?=$e['aa_name']?> - <?=$e['realname']?><br>
            地址：<?= $e['address'] ?><br>
	    发布：<?=$e['publish_at']?>
        </td>

        <td class="quiet" width="150" style="padding:10px; text-align: center">
             <?php if(time()>=strtotime($e['start']) AND  time()<=strtotime($e['finish'])):?>
            <span style="color:#4D7E05">进行中</span>
         <?php elseif(time()<=strtotime($e['start'])): ?>
         <span style="color:#4D7E05"><?= Date::span_str(strtotime($e['start'])) ?>后</span>
         <?php else:?>
         <span style="color:#f60">已结束</span>
         <?php endif;?>
        </td>

         <td class="center">
             <select name="etype"  onchange="setType(<?= $e['id'] ?>, this.value)" id="etype_<?= $e['id'] ?>">
              <option <?= empty($e['type']) ? 'selected' : '' ?>>无</option>
             <?php foreach($etype['icons'] as $key=>$ico_path):?>
    <option value="<?=$key?>" <?= $e['type'] == $key ? 'selected' : '' ?>><?=$key?></option>
             <?php endforeach;?>
    </select>
         </td>

        <td class="center">
            <input type="checkbox" onclick="homepage(<?= $e['id'] ?>,1)" <?= $e['is_home'] == true ? 'checked':'' ?> />
        </td>

        <td class="center">
            <input type="checkbox" onclick="setEventBool(<?= $e['id'] ?>,'is_fixed')" <?= $e['is_fixed'] == true ? 'checked':'' ?> />
        </td>

        <td class="center">
            <input type="checkbox" onclick="setEventBool(<?= $e['id'] ?>,'is_closed')" <?= $e['is_closed'] == true ? 'checked':'' ?> />
        </td>
        <td class="center">
            <a href="<?= URL::site('event/signDownload?id='.$e['id'].'&num='.$e['num']) ?>">下载名单</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<table width="100%" border="0" cellpadding="0" cellspacing="1" style="margin-top:-1px " >
     <tr>
	<td style="height: 50px"><?= $pager ?></td>
    </tr>
</table>

<script type="text/javascript">
function setEventBool(id, field){
    new Request({
        url:'<?= URL::site('event/set') ?>',
        data:'event_id='+id+'&field='+field,
        method:'post'
    }).send();
}

    function homepage(id,n){
        new Request({
            url: '<?= URL::site('admin_event/homepage').URL::query() ?>',
            type: 'post',
            data: 'id='+id+'&n='+n
        }).send();
    }

    function setType(eid, val){
        if(val!=''){
            new Request({
                url: '<?= URL::site('admin_event/setType') ?>?id='+eid+'&val='+val,
                success: function(){}
            }).send();
        }
    }
</script>

<?php endif; ?>