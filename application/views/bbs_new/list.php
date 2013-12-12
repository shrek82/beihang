    <div style="margin:10px">
当前位置：<?php if($from>0):?><a href="<?=URL::site('aa_home?id='.$this_aa['id'])?>" title="返回校友会主页"><?=$this_aa['sname']?>校友会</a><?php else:?><a href="<?=URL::site('aa')?>" title="返回校友总会会主页">校友总会</a><?php endif;?> > <a href="<?=URL::site('bbs?f='.$from)?>">交流园地</a> <?php if($this_bbs):?>> <a href="<?=URL::site('bbs?f='.$from.'&b='.$this_bbs['id'])?>"><?=$this_bbs['name']?></a><?php endif;?>：
    </div>

    <div style="text-align:right; padding-right:20px; margin:5px 0"><a href="<?= URL::site('bbs/unitForm?aa_id='.$aa_id.'&club_id='.@$club_id.'&b='.$bbs_id) ?>"><img src="/static/images/post.png"></a></div>

    <?php if(isset($bbs) AND count($bbs)<9):?>
    <div class="blue_tab">
	    <ul>
		<li><a href="<?= URL::site('bbs/list?f='.$from) ?>" class="<?= isset($bbs_id) ? '':'cur' ?>">所有帖子</a></li>
    <?php if(isset($bbs)): ?>
    <?php foreach($bbs as $b): ?>
<li style="width:<?=(strlen($b['name']))/3*20?>px"><a class="<?= $bbs_id == $b['id'] ? 'cur':'' ?>" href="<?= URL::site('bbs/list?f='.$from.'&b='.$b['id']) ?>" ><?= $b['name'] ?></a></li>
    <?php endforeach; ?>
    <?php endif; ?>
	    </ul>
	</div>
     <?php endif;?>
    <?php if(count($units) == 0): ?>
    <div class="nodata" style="padding:10px">还没有任何话题。</div>
    <?php else: ?>
    <table width="100%" id="bbs_table" border="0"  cellpadding="0" cellspacing="0" style="margin-top:10px">
    <thead>
         <tr>
            <th style="text-align:left;padding:0px 10px;width:450px">标题</th>
            <th style="width:80px">发布人</th>
            <th style="width:80px">评论 / 点击</th>
            <th style="width:100px">最后回复</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($units as $i=>$u): ?>
        <tr style="<?= $i%2==1?'background-color:#F5FAFF':'' ?>">
            <td style="font-size: 1.1em; color:#999; text-align: left"> &nbsp;
<span style="vertical-align: 5px">
<a href="<?= URL::site('bbs/view'.$u['type'].'?id='.$u['id']) ?>" target="_blank">
<?php if($u['is_fixed']): ?>
<img src="/static/images/headtopic_1.gif" border="0" align="absmiddle"  title="新窗口打开 置顶帖子"/><?php else: ?>
<?php if (strtotime(date('Y-m-d H:i:s'))-strtotime($u['comment_at'])<=86400 OR strtotime(date('Y-m-d H:i:s'))-strtotime($u['create_at'])<=86400):?>
<img src="/static/ico/folder_new.gif"  border="0" align="absmiddle"  title="新窗口打开  新帖或有新回复"/><?php else:?>
<img src="/static/images/topicnew.gif"  border="0" align="absmiddle"  title="新窗口打开"/><?php endif;?>
<?php endif; ?></a>
</span>
                &nbsp;&nbsp;
 <a href="<?= URL::site('bbs/view'.$u['type'].'?id='.$u['id']) ?>" class="title" <? if($u['is_fixed'] OR $u['is_good']):?>style="font-weight:bold;color:<?=$u['title_color']?$u['title_color']:'#f30';?>"<?php else:?> <?php if(!empty($u['title_color'])):?>style="font-weight:bold;color:<?=$u['title_color']?>"<?php endif;?><?php endif;?>><?=Text::limit_chars($u['title'], 30, '...')  ?></a>
<?=$u['is_pic']?'&nbsp;<font><img src="/static/ico/image_s.gif" title="包含图片" class="middle"></font>':'';?>

<?php if($u['reply_num']>=10): ?>
<img src="/static/ico/hot_1.gif"  border="0" class="middle" title="热门帖子"/>
<?php endif; ?>
<?php if($u['is_good']): ?>
<img src="/static/ico/recommend_1.gif"  border="0" class="middle" title="推荐帖子"/>
<?php endif; ?>

        </td>
        <td class="center"><a href="<?= URL::site('user_home?id='.$u['user_id']) ?>" ><?= $u['User']['realname'] ?></a><br><span style="color:#777;font-size: 11px"><?= date('Y-n-d', strtotime($u['create_at'])); ?></span></td>
            <td class="center"><span style="color:green"><?= $u['reply_num'].'</span>/'.$u['hit'] ?></td>
           
            <td class="center">
                <?php if($u['comment_at']): ?>
                <?= Date::span_str(strtotime($u['comment_at'])) ?>前
                <?php else:?>
                &nbsp;-
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
    <div style="text-align:right; padding-right:20px; margin:25px 20px 10px 0"><a href="<?= URL::site('bbs/unitForm?aa_id='.$aa_id.'&club_id='.@$club_id.'&b='.$bbs_id) ?>"><img src="/static/images/post.png"></a></div>
    <div style="margin: -55px 10px 0 0"><?= $pager ?></div>