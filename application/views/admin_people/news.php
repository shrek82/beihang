<!-- aa_admin_news/index:_body -->
<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" >
<tr >
<td height="29" class="td_title" ><b>按属性查看：</b>
    <div style="float:right; font-weight: normal;margin:0px 10px">
	<form name="search" action="" method="get">
	    <input name="q" type="text" style="width:200px" class="keyinput">
	    <input type="submit" value="搜索">
	</form>
    </div>
</td>
</tr>
<tr>
<td height="25" style="padding:0px 10px" >
快速检索：<a href="<?=URL::site('admin_people/index')?>" style="<?=empty($_GET)?'font-weight:bold':''?>">所有</a>
</td>
</tr>
</table>


<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" style="margin-top:0px " id="table1">
    <tr>
	<td colspan="3" class="td_title">新闻管理</td>
    </tr>
    <tr>
        <td width="50%" style="text-align:left">&nbsp;&nbsp;&nbsp;&nbsp;新闻标题</td>
        <td width="13%" style="text-align:center">更新时间</td>
        <td width="7%"  style="text-align:center">删除</td>
    </tr>

    <?php if(count($news) == 0): ?>
    <tr>
        <td colspan="7" style="background-color:#fff;padding:10px; text-align: left; color: #999">没有任何新闻信息。</td>
    </tr>
    <?php else: ?>

    <?php foreach($news as $key=>$n): ?>
    <tr id="news_<?=$n['id']?>"   class="<?php if(($key)%2==0){echo'even_tr';} ?>">

	<td class="news_title">
          &nbsp;&nbsp;&nbsp;&nbsp;<a href="<?= URL::site('admin_people/newsForm?id='.$n['id']) ?>" title="点击修改"><?= $n['title'] ?></a>
        </td>
	<td style="text-align:center"><?= $n['create_at'] ?></td>
        <td class="handler" style="text-align: center">
            <a href="javascript:del(<?=$n['id']?>)">删除</a>
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

function del(cid){
    var b = new Facebox({
        title: '删除确认！',
        message: '确定要删除此条新闻吗？注意删除后将不能再恢复。',
        icon:'question',
        ok: function(){
            new Request({
                url: '<?= URL::site('admin_people/delNews?cid=') ?>'+cid,
                type: 'post',
                success: function(){
                    candyDel('news_'+cid);
                }
            }).send();
            b.close();
        }
    });
    b.show();
}
</script>