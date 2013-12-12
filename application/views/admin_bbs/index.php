<!-- admin_bbs/index:_body -->
<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" >
<tr >
<td height="29" class="td_title" >
    <div class="title_name">
    <b>按分类查看</b></div>
   <div class="title_search">
	<form name="search" action="" method="get">
<select name="search_type" style="padding:1px 4px;">
    			<option value="title" <?php if($search_type=='title'){echo 'selected';} ?>>按标题</option>
<option value="author" <?php if($search_type=='author'){echo 'selected';} ?>>按作者</option>
    		    </select>
	    <input name="q" type="text" style="width:200px" class="keyinput" value="<?=$q?>">
	    <input type="submit" value="搜索">
	</form>
    </div>
</td>
</tr>
<tr>
<td height="25" style="padding:0px 10px" >
<a href="<?=URL::site('admin_bbs/index')?>" style="<?=empty($_GET)?'font-weight:bold':''?>">所有</a> &nbsp;|&nbsp;
<a href="<?=URL::site('admin_bbs/index?aa=0')?>" style="<?=$aa=='0'?'font-weight:bold':''?>">公共论坛</a> &nbsp;|&nbsp;
<a href="<?=URL::site('admin_bbs/index?aa=1')?>" style="<?=$aa?'font-weight:bold':''?>">地方论坛</a> &nbsp;|&nbsp;
</td>
</tr>
</table>


<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" >
<tr >
<td height="29" class="td_title" colspan="9"><b>按分类查看</b>
</td></tr>
    <tr>
        <td width="80" class="center">版块</td>
       <td>标题</td>
        <td class="center">发布时间</td>
        <td class="center">最后评论</td>
        <td class="center">发布于</td>
        <td class="center">回复/点击</td>
        <td class="center" width="50" >首页</td>
       <td class="center" width="50" >顶置</td>
        <td class="center" width="50" >屏蔽</td>

    </tr>
    <?php if(count($units) == 0): ?>
    <tr>
        <td colspan="9" style="background-color:#fff;padding:10px; text-align: left; color: #999">暂时还没有任何话题</td>
    </tr>
    <?php else: ?>

    <?php foreach($units as $key=>$u): ?>
    <tr class="<?php if(($key)%2==0){echo'even_tr';} ?>">
        <td style="color:#999" class="center">
            <?=$u['aa_name']?$u['aa_name']:'公共' ?>
        </td>
        <td>
            <a target="_blank" href="<?= URL::site('bbs/view'.$u['type'].'?id='.$u['id']) ?>"   <? if ($u['is_fixed'] OR $u['is_good']): ?>style="font-weight:bold;color:<?= $u['title_color'] ? $u['title_color'] : '#f30'; ?>"<?php else: ?> <?php if (!empty($u['title_color'])): ?>style="font-weight:bold;color:<?= $u['title_color'] ?>"<?php endif; ?><?php endif; ?>><?= $u['title'] ?></a>

<?=$u['is_pic']?'&nbsp;<font><img src="/static/images/imgs.gif" title="包含图片"></font>':'';?>


<?php if ($u['reply_num'] >= 10): ?>
                                                                            <img src="/static/ico/hot_1.gif"  border="0" class="middle" title="热门帖子"/>
                <?php endif; ?>
                <?php if ($u['is_good']): ?>
                                                                                <img src="/static/ico/recommend_1.gif"  border="0" class="middle" title="推荐帖子"/>
                <?php endif; ?>
        </td>
        <td class="center"><?= Date::span_str(strtotime($u['create_at'])) ?>前</td>
        <td class="center"><?php if ($u['comment_at']): ?>
                <?= Date::span_str(strtotime($u['comment_at'])) ?>前
                <?php else: ?>
                                                                                        &nbsp;-
                <?php endif; ?></td>
        <td class="center"><a href="<?= URL::site('user_home?id='.$u['user_id']) ?>" target="_blank"><?= $u['User']['realname'] ?></a></td>
        <td class="center"><span style="color:#090"><?=$u['reply_num']?></span>/<?=$u['hit']?></td>
        <td class="center"><input type="checkbox" onclick="homepage(<?= $u['id'] ?>,1)" <?= $u['is_home'] ? 'checked':'' ?> /></td>
	<td class="center"><input type="checkbox" onclick="setUnitBool(<?= $u['id'] ?>,'is_fixed')" <?= $u['is_fixed'] ? 'checked':'' ?> /></td>
        <td class="center"><input type="checkbox" onclick="setUnitBool(<?= $u['id'] ?>,'is_closed')" <?= $u['is_closed'] ? 'checked':'' ?> /></td>
        
    </tr>
    <?php endforeach; ?>
    </table>

<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" style="margin-top:-1px " >
     <tr>
	<td style="height: 50px"><?= $pager ?></td>
    </tr>
</table>
    <?php endif; ?>

<script type="text/javascript">
function getBbsForm(id){
    $('bbs_form_loader').load('<?= URL::site('admin_bbs/form') ?>?bbs_id='+id);
}
function mvUnit(id, bbs_id){
    new Request({
        type:'post',
        url:'<?= URL::site('admin_bbs/unit') ?>',
        data: 'unit_id='+id+'&mv='+bbs_id
    }).send();
}
function setUnitBool(id, type){
    new Request({
        type:'post',
        url:'<?= URL::site('admin_bbs/unit') ?>',
        data: 'unit_id='+id+'&bool='+type
    }).send();
}

function homepage(cid){
        new Request({
            url: '<?= URL::site('admin_bbs/homepage').URL::query() ?>',
            type: 'post',
            data: 'cid='+cid
        }).send();
    }
</script>

<?php
 function commentPage($id,$reply_num) {
      $pages = ceil($reply_num/8);
            if ($pages >= 2) {
                 $links = '...';
                   for($i=1;$i<=$pages;$i++) {
                      $links.='<a href="'.URL::site('bbs/viewPost?id='.$id.'&page='.$i).'#comment"   target="_blank" title="浏览评论">'.$i.'</a>&nbsp;';
            }
             return $links;
 }
 }
 ?>
