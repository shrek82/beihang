<style type="text/css">
    #pics_tb{ margin: 10px 20px}
    #pics_tb td{ height: 24px; padding: 0px 5px}
    .picbox{ width: 140px; height: 230px;  background: #fff; line-height: 1.6em;padding:4px; float: left; margin-right: 10px; margin-bottom: 15px; border: 1px solid #eee;  text-align: center;}
    .picbox:hover{ background: #f7f7f7; border: 1px solid #ccc}
    .apic{ width: 120px; height: 162px; overflow: hidden; margin: 0 auto; margin-bottom: 5px }
</style>
<!--链接类型 -->
<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" >
<tr >
<td height="29" class="td_title" >
    <div class="title_name">
    
    <b>按分类查看</b></div>
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
    <?php foreach(Model_Publication::$pub_type AS $key=>$t) :?>
    <a href="/admin_publication/index?type=<?=$key?>" <?=$type==$key?'style="font-weight:bold;"':'';?>><?=$t?></a>&nbsp;&nbsp;
    <?php endforeach;?>
</td>
</tr>
</table>

<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" >
<tr >
<td height="29" class="td_title" ><b>校友相册</b></td></tr>
<?php if(!$publication):?>
<tr >
<td height="29" >    <div class="nodata">暂时还没有任何期刊。</div></td>
</tr>
    
<?php else:?>
<tr >
<td>

<div id="pics_tb" >
<?php foreach($publication as $p) : ?>
<div  id="pub_<?=$p['id']?>" class="picbox">
<?php $link = URL::site('admin_publication/article?pub_id='.$p['id']) ?>
<div class="apic">
<a href="<?=URL::site('admin_publication/pubForm?id='.$p['id'].'&page='.$page)?>" title="点击修改"><?php if($p['cover']):?><img src="/<?= $p['cover'] ?>" width="120" height="162"  border="0"/><?php else:?><img src="/static/images/nocover.gif" width="120" height="162"  border="0"/><?php endif;?></a>
</div>
<a href="<?= $link ?>" title="点击浏览文章" ><?= Text::limit_chars($p['issue'], 10) ?></a>&nbsp;
&nbsp;<br>
<a href="/admin_publication/import?pub_id=<?=$p['id']?>" title="导入文章">导入</a> | <a href="javascript:delArt(<?=$p['id']?>)" title="仅删除期刊栏目及文章，不删除期刊">清空</a> | <a href="javascript:del(<?=$p['id']?>)"  title="删除期刊及其所有文章">删除</a><br>
<?php if($p['pdf'] && is_file($_SERVER['DOCUMENT_ROOT'].'/'.$p['pdf'])):?>
<a href="/<?=$p['pdf']?>" style="color:#009600" target="_blank">点击下载PDF文件</a>
<?php else:?>
<span style="color:#f00">没有PDF文件</span>
<?php endif;?>
</div>
<?php endforeach;?>
    <div class="clear"></div>
</div>
</td></tr>
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
       if(confirm('确认要删除此期刊吗？\n\n提示：删除期刊将同时删除下属所有栏目级文章。')){
        new Request({
            url: '/admin_publication/delPub?cid='+cid,
            success: function(){
                candyDel('pub_'+cid);
            }
        }).send();
    }
}
function delArt(cid){
       if(confirm('确认要删除栏目及其文章吗？\n\n提示：删除后可以以重新导入。')){
        new Request({
            url: '/admin_publication/delColumnArt?cid='+cid,
            success: function(){
                alert('删除成功！');
            }
        }).send();
    }
}
</script>