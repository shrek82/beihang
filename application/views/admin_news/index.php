<!-- aa_admin_news/index:_body -->
<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1">
<tr >
<td height="29" class="td_title" >
    <div class="title_name">
    <b>按属性查看：</b>
    </div>
    <div class="title_search">
	<form name="search" action="" method="get">
<select name="search_type" style="padding:1px 4px;">
    			<option value="title" <?php if($search_type=='title'){echo 'selected';} ?>>按标题</option>
<option value="author" <?php if($search_type=='author'){echo 'selected';} ?>>按作者</option>
    		    </select>
	    <input name="q" type="text" style="width:200px" class="keyinput">
	    <input type="submit" value="搜索">
	</form>
    </div>
</td>
</tr>
<tr>
<td height="25" style="padding:0px 10px" >
快速检索：<a href="<?=URL::site('admin_news/index')?>" style="<?=empty($_GET)?'font-weight:bold':''?>">所有</a> &nbsp;|&nbsp;
<a href="<?=URL::site('admin_news/index?release=1');?>"  style="color:green;<?=$release?'font-weight:bold':''?>">已审核</a>&nbsp;|&nbsp;
<a href="<?=URL::site('admin_news/index?release=0');?>"  style="color:#ff6600;<?=$release=='0'?'font-weight:bold':''?>">待审核</a>&nbsp;|&nbsp;
<a href="<?=URL::site('admin_news/index?from=home');?>"  style="<?=$from=='home'?'font-weight:bold':''?>">首页新闻</a>&nbsp;|&nbsp;
<a href="<?=URL::site('admin_news/index?from=main');?>"  style="<?=$from=='main'?'font-weight:bold':''?>">总会新闻</a>&nbsp;|&nbsp;
<a href="<?=URL::site('admin_news/index?from=aa');?>"  style="<?=$from=='aa'?'font-weight:bold':''?>">地方校友会</a>&nbsp;|&nbsp;
<a href="<?=URL::site('admin_news/index?from=club');?>"  style="<?=$from=='club'?'font-weight:bold':''?>">俱乐部新闻</a>&nbsp;&nbsp;
<br>
新闻分类：
</td>
</tr>
</table>


<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" style="margin-top:0px " id="table1">
    <tr>
	<td colspan="8" class="td_title">新闻管理</td>
    </tr>
    <tr>
<td width="5%" style="text-align:center">审核</td>
        <td width="50%" >新闻标题</td>
	<td width="7%" style="text-align:center">分类</td>
	<td width="10%" style="text-align:center">发布人</td>
        <td width="13%" style="text-align:center">发布时间</td>
        	<td width="5%"  style="text-align:center">头条</td>
	<td width="5%"  style="text-align:center">首页显示</td>
        <td width="7%"  style="text-align:center">删除</td>
    </tr>

    <?php if(count($news) == 0): ?>
    <tr>
        <td colspan="8" style="background-color:#fff;padding:10px; text-align: left; color: #999">没有任何新闻信息。</td>
    </tr>
    <?php else: ?>

    <?php foreach($news as $key=>$n): ?>
    <tr id="news_<?=$n['id']?>"   class="<?php if(($key)%2==0){echo'even_tr';} ?>">
<td style="text-align: center"><input type="checkbox" value="true" onclick="cvis(<?= $n['id'] ?>)" <?= $n['is_release'] == true ? 'checked':'' ?> /></td>
	<td class="news_title" style="padding:4px 0">
<span style="color:#999"><?php if($from == 'aa'): ?> [ <?=$n['aa_name']?>]&nbsp;<?php endif; ?><?php if($from == 'club'): ?> [ <?=$n['club_name']?>]&nbsp;<?php endif; ?></span>
            <a href="<?= URL::site('admin_news/form?id='.$n['id']) ?>" title="点击修改"><?= $n['title'] ?></a>
	    <?=$n['is_pic']?'&nbsp;<font><img src="/static/images/imgs.gif" title="图片新闻"></font>':'';?>
	    <?=$n['is_top']?'<span style="color:#f30">&nbsp;[头条]</span>':''?>
	    <?=$n['is_focus']?'<span style="color:blue">&nbsp;[图片幻灯片]</span>':''?>
        </td>
	<td style="text-align:center"><?= Text::limit_chars($n['category_name'],4, '...') ?></td>
	<td style="text-align: center"><a href="javascript:userDetail(<?= $n['user_id'] ?>)" title="查看详细信息"><?= $n['realname'] ?></a></td>
        <td class="timestamp" style="text-align: center"><?= date('Y-n-d',strtotime($n['create_at'])) ?></td>
	<td style="text-align: center"><input type="checkbox" value="1" onclick="settop(<?= $n['id'] ?>);" <?= $n['is_top'] ? 'checked':'' ?> /></td>
	<td style="text-align: center"><input type="checkbox" value="1" onclick="dig2hp(<?= $n['id'] ?>,1);" <?= $n['is_home'] ? 'checked':'' ?> /></td>
        <td class="handler" style="text-align: center"><a href="javascript:del(<?=$n['id']?>)">删除</a>  </td>
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
    function cfix(cid,nid){
        new Request({
            url: '<?= URL::site('news/fix').URL::query() ?>',
            type: 'post',
            data: 'cid='+cid+'&id='+nid
        }).send();
    }
    function settop(cid){
        new Request({
            url: '<?= URL::site('admin_news/top').URL::query() ?>',
            type: 'post',
            data: 'cid='+cid
        }).send();
    }
    function cvis(id){
        new Request({
            url: '<?= URL::site('news/release').URL::query() ?>',
            type: 'post',
            data: 'id='+id
        }).send();
    }
    function dig2hp(id, n){
        new Request({
            url: '<?= URL::site('admin_news/homepage').URL::query() ?>',
            type: 'post',
            data: 'id='+id+'&n='+n
        }).send();
    }

function del(cid){
    var b = new Facebox({
        title: '删除确认！',
        message: '确定要删除此条新闻吗？注意删除后将不能再恢复。',
        icon:'question',
        ok: function(){
            new Request({
                url: '<?= URL::site('admin_news/del?cid=') ?>'+cid,
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

function userDetail(uid){
    var box = new Facebox({
        url: '<?= URL::site('user/userDetail?webmanager=1&id=') ?>'+uid,
	width:500
    });
    box.show();
}


</script>