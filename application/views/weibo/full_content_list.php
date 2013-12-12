<div id="lastInsert"></div>
<?php if (count($weibo)>0): ?>
<?php foreach ($weibo AS $w): ?>
	<div class="weibo_content" id="weibo_del_<?= $w['id'] ?>" >
	    <div class="weibo_content_left">
		<div class="newface">
		    <a href="/weibo?id=<?=$_ID?>&uid=<?=$w['user_id']?>" title="Ta的所有话题"><img src="<?=Model_User::avatar($w['user_id'], 48, $w['sex'])?>"></a>
		</div>
	    </div>
	    <div class="weibo_content_right">
	<a href="/weibo?id=<?=$_ID?>&uid=<?=$w['user_id']?>" title="Ta的所有话题"><?= $w['realname'] ?></a> ：<?= Common_Global::weibohtml($w['content'],$_ID) ?>

	<?php if($w['img_paths']):?>
	<?php $img_path_array=  explode('||',$w['img_paths'])?>
	<div>
	<?php foreach($img_path_array AS $key=>$path):?>
	    <?php if($key<=2):?>
		<div id="insert_img_div_<?= $w['id'] ?>_<?=$key?>" class="zoom_small" >
		    <div class="imagetool" style="text-align: left"><a href="<?=str_replace('_mini','',$path)?>"  target="_blank" style="font-size:12px;color:#999">查看原图</a></div>
		    <a href="javascript:;" ><img src="<?=$path?>"  id="insert_img_<?= $w['id'] ?>_<?=$key?>" onclick="imgZoom('<?= $w['id'] ?>_<?=$key?>','<?=$path?>','<?=str_replace('_mini','_thumbnail',$path)?>','<?=str_replace('_mini','_bmiddle',$path)?>')" ></a>
		</div>
	    <?php endif;?>
	<?php endforeach;?>
	    <div class="clear"></div>
	  <?php if(count($img_path_array)>3):?>
	 <div><a href='/weibo/content?id=<?=$_ID?>&wid=<?=$w['id']?>' title="查看全文" style="font-size:12px;color: #6AADDB" >查看所有照片(<?=count($img_path_array)?>)...</a></div>
	 <?php endif;?>
	    </div>
	<?php endif;?>

       <?php if($w['from_forward']):?>
		<?= View::factory('weibo/forwardContent',array('wid'=>$w['from_forward'])) ?>
	<?php endif;?>

	<div class="weibo_content_tool">
        <p class="wct_left"><a href='/weibo/content?id=<?=$_ID?>&wid=<?=$w['id']?>' title="查看全文" class="weibo_date_link"><?= Date::ueTime($w['post_at']); ?></a>&nbsp;&nbsp;<span class="clients"><?=$w['clients']?'来自<span class="cname">'.$w['clients'].'</span>':'';?></span></p>
	    <p class="wct_right"><?php if($_UID==$w['user_id']):?><a href="javascript:;" onclick="delweibo(<?= $w['id'] ?>)" title="删除">删除</a>&nbsp;|&nbsp;<?php endif;?><a href="javascript:;"  onclick="retweet(<?=$w['id']?>)">转发<?=$w['forward_num']>0?'('.$w['forward_num'].')':'';?></a>&nbsp;|&nbsp;<a href="javascript:;" onclick="getcomment(<?= $w['id'] ?>)">评论<?=$w['reply_num']>0?'('.$w['reply_num'].')':'';?></a></p>
	</div>
    </div>
    <div class="clear"></div>
    <div id="weibo_cmtbox_<?= $w['id'] ?>" class="weibo_con_comment"></div>
</div>
<?php endforeach; ?>
<?php else: ?>
	    <div class="nodata" style="padding:15px 0">还没有任何新鲜事！</div>
<?php endif; ?>