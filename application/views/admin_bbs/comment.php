<!-- admin_bbs/comment:_body -->
<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" >
<tr >
<td height="29" class="td_title" >
    <div class="title_name">
    <b>按内容分类查看</b></div>
    <div class="title_search">
	<form name="search" action="" method="get">
<select name="search_type" style="padding:1px 4px;">
    			<option value="title" <?php if($search_type=='title'){echo 'selected';} ?>>按内容</option>
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
<a href="<?=URL::site('admin_bbs/comment')?>" style="<?=empty($_GET)?'font-weight:bold':''?>">所有</a> &nbsp;|&nbsp;
<a href="<?=URL::site('admin_bbs/comment?object=news')?>" style="<?=$object=='news'?'font-weight:bold':''?>">新闻</a> &nbsp;|&nbsp;
<a href="<?=URL::site('admin_bbs/comment?object=bbs_unit')?>" style="<?=$object=='bbs_unit'?'font-weight:bold':''?>">话题</a> &nbsp;|&nbsp;
<a href="<?=URL::site('admin_bbs/comment?object=event')?>" style="<?=$object=='event'?'font-weight:bold':''?>">活动</a> &nbsp;|&nbsp;
<a href="<?=URL::site('admin_bbs/comment?object=pic')?>" style="<?=$object=='pic'?'font-weight:bold':''?>">照片</a> &nbsp;|&nbsp;
<a href="<?=URL::site('admin_bbs/comment?object=vote')?>" style="<?=$object=='vote'?'font-weight:bold':''?>">投票</a> &nbsp;|&nbsp;
<a href="<?=URL::site('admin_bbs/comment?object=class_unit')?>" style="<?=$object=='class_unit'?'font-weight:bold':''?>">班级话题</a> &nbsp;|&nbsp;
<a href="<?=URL::site('admin_bbs/comment?object=class_room')?>" style="<?=$object=='class_room'?'font-weight:bold':''?>">班级留言</a> &nbsp;|&nbsp;
</td>
</tr>
</table>


<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" >
<tr >
<td height="29" class="td_title" colspan="8"><b>评论</b>
</td></tr>
    <tr>
        <td width="50" class="center">时间</td>
	<td class="center">作者</td>
	<td>评论内容</td>
	<td class="center">浏览</td>
	<td class="center">删除</td>
    </tr>
    <?php if(count($comments) == 0): ?>
    <tr>
	<td colspan="8">暂时还没有任何话题</td>
    </tr>
    <?php else: ?>

    <?php foreach($comments as $key=>$c): ?>
    <tr class="<?php if(($key)%2==0){echo'even_tr';} ?>" id="comment_<?=$c['id']?>">
        <td  class="center" style="width:70px">
            <?= Date::span_str(strtotime($c['post_at'])) ?>前
        </td>
	<td class="center"><a href="<?= URL::site('user_home?id='.$c['user_id']) ?>"><?= $c['realname'] ?></a></td>
        <td style="width:75%">
    <?=strip_tags($c['content']) ?>

        </td>
	<td class="center">
<?php if($c['news_id']>0):?>
     <a href="<?=URL::site('news/view?id='.$c['news_id'])?>"  target="_blank">新闻</a>
	    <?php elseif($c['event_id']>0):?>
     <a href="<?=URL::site('event/view?id='.$c['event_id'])?>" target="_blank">活动</a>
	    <?php elseif($c['bbs_unit_id']>0):?>
     <a href="<?=URL::site('bbs/viewPost?id='.$c['bbs_unit_id'])?>" target="_blank">论坛</a>
	    <?php elseif($c['class_unit_id']>0):?>
     班级话题
	    <?php elseif($c['pic_id']>0):?>
         <a href="<?=URL::site('album/picView??id='.$c['pic_id'])?>" target="_blank">照片</a>
	    <?php elseif($c['class_room_id']>0):?>
         <a href="<?=URL::site('classroom_home/guestbook?id='.$c['class_room_id'])?>" target="_blank">班级留言</a>
	    <?php else:?>其他
	    <?php endif;?>



 </td>
        <td class="center"><a href="javascript:del(<?=$c['id']?>)">删除</a></td>
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
function del(cid){
    var b = new Facebox({
        title: '删除确认！',
        icon:'question',
        message: '确定要删除此条评论吗？注意删除后将不能再恢复。',
        ok: function(){
            new Request({
                url: '<?= URL::site('admin_bbs/delComment?cid=') ?>'+cid,
                type: 'post',
                success: function(){
                    candyDel('comment_'+cid);
                }
            }).send();
            b.close();
        }
    });
    b.show();
}
    </script>