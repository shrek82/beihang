<style type="text/css">
    #pics_tb{ margin: 10px 20px}
    #pics_tb td{ height: 24px; padding: 0px 5px}
    .picbox{ width: 155px; height: 185px;  line-height: 1.6em;padding: 3px; float: left; margin-right: 10px; margin-bottom: 10px; border: 1px solid #eee;  text-align: center; background: #fff}
    .picbox:hover{ background: #f7f7f7; border: 1px solid #ccc}
    .apic{ width: 150px; height: 113px; overflow: hidden; }
</style>

<!--链接类型 -->

<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" >
<tr >
<td height="29" class="td_title" >
    <div class="title_name">
    <b>按分类查看</b>
    </div>
    <div class="title_search">
	<form name="search" action="" method="get">
	    <input name="q" type="text" style="width:200px" class="keyinput">
	    <input type="submit" value="搜索">
	</form>
    </div>
</td>
</tr>
<tr>
<td height="25" style="padding:0px 10px" >
    <a href="<?=URL::site('admin_album/index?type='.$type)?>" <?=!$type?'style="font-weight:bold;"':'';?>>所有</a>&nbsp;&nbsp;
    <a href="/admin_album/index?type=aa" <?=$type=='aa'?'style="font-weight:bold;"':'';?>>校友会</a>&nbsp;&nbsp;
    <a href="/admin_album/index?type=event" <?=$type=='event'?'style="font-weight:bold;"':'';?>>活动</a>&nbsp;&nbsp;
    <a href="/admin_album/index?type=classroom" <?=$type=='classroom'?'style="font-weight:bold;"':'';?>>班级</a>&nbsp;&nbsp;
    <a href="/admin_album/index?type=club" <?=$type=='club'?'style="font-weight:bold;"':'';?>>俱乐部</a>&nbsp;&nbsp;
    <a href="/admin_album/index?type=user" <?=$type=='user'?'style="font-weight:bold;"':'';?>>个人</a>&nbsp;&nbsp;
</td>
</tr>
</table>

<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" >
<tr >
<td height="29" class="td_title" ><b>校友相册</b></td></tr>
<?php if(!$pics):?>
<tr >
<td height="29" ><div class="nodata">暂时还没有任何照片。</div></td>
</tr>
    
<?php else:?>
<tr >
<td>
<!--链接列表 -->
<div id="pics_tb" >
<?php foreach($pics as $p) : ?>
<div  id="pic_<?=$p['id']?>" class="picbox">
<?php $link = URL::site('album/picView?id='.$p['id']) ?>
<div class="apic">
<a href="<?= $link ?>" title="点击放大" target="_blank"><img src="<?= URL::base().$p['img_path'] ?>" border="0" /></a>
</div>

<?= Text::limit_chars($p['name'], 8) ?>&nbsp;<a href="javascript:del(<?=$p['id']?>)">删除</a>
<br>
<a href="<?= URL::site('user_home?id=' . $p['user_id']) ?>" target="_blank"><?= $p['realname'] ?></a>
<?= Date::span_str(strtotime($p['upload_at'])) ?>前
<br>
<input type="checkbox" onclick="homepage(<?= $p['id'] ?>,1)" <?= $p['is_home'] ? 'checked':'' ?> id="is_home_<?=$p['id']?>"/><label for="is_home_<?=$p['id']?>">首页显示</label>
</div>
<?php endforeach;?>
    <div class="clear"></div>
</div>
</td>
</tr>
<?php endif;?>
</table>
    <!--分页 -->
<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" style="margin-top:-1px " >
     <tr>
	<td style="height: 50px"><?= $pager ?></td>
    </tr>
</table>
    
<script type="text/javascript">
function del(cid){
        new Request({
            url: '/admin_album/del?cid='+cid,
            success: function(){
                candyDel('pic_'+cid);
            }
        }).send();
    }

function homepage(id){
        new Request({
            url: '<?= URL::site('admin_album/homepage').URL::query() ?>',
            type: 'post',
            data: 'id='+id
        }).send();
    }

</script>