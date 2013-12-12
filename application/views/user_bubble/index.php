<!-- user_bubble/index:_body -->
<script type="text/javascript" src="/static/js/bubble_comment.js"></script>
<div id="big_right">

<div id="plugin_title">记录</div>

   <!--最近的状态 -->
    <div id="home_bubble">
	<form action="<?= URL::site('user_bubble/blow') ?>" id="blow_form" method="post">
	    <div>
		<textarea class="textarea_bubble" name="content" onclick="if(this.value=='说说正在发生的事情吧...'){this.value='';this.style.color='#333';};" style="vertical-align:middle;">说说正在发生的事情吧...</textarea>
		<input type="button" id="button_bubble" onclick="new ajaxForm('blow_form', {redirect:'<?= URL::site($_URI) ?>'}).send()" value="发表" style="vertical-align:middle; margin-top: 30px; cursor: pointer">
	    </div>

	</form>

    </div>
    <!--//最近状态 -->

<div class="tab_gray" id="user_topbar" style="margin-top:10px">
   <ul>
       <li><a href="<?= URL::site('user_bubble/index') ?>" class="cur" style="width:50px">我自己的</a></li>
       <li><a href="<?= URL::site('user_bubble/others') ?>" style="width:50px">其他人的</a></li>
    </ul>
</div>

    <!--状态列表 -->
    <div id="bubble_list">
    <?php if(!$bubbles):?>
	<div class="nodata">您还没有记录呢！</div>
   <?php endif; ?>
    <?php foreach($bubbles as $bubble): ?>
	<div class="a_bubble" id="b_<?=$bubble['id'] ?>">
     <div class="bu_left">
         <?= View::factory('inc/user/avatar',	array('id' => $bubble['user_id'], 'size' => 48,'sex'=>$bubble['sex'])) ?>
     </div>
	    <div class="bu_right">
	 <div class="font14"><?= $bubble['content'] ?></div>
	 <div class="gray_text blue_link" style=" margin-top:15px">
	     <p class="date"><?= Date::span_str(strtotime($bubble['blow_at'])) ?>前</p>
	     <p class="action">
           <a href="javascript:del(<?= $bubble['id'] ?>)">删除</a>
	     </p>
      <div class="clear"></div>
      <div   style=" width:655px;margin:10px 0" >
      <?= View::factory('/inc/comment/bubble_list_form',	array('row'=>$bubble)) ?>
      </div>
     

	 </div>
	    </div>
	    <div class="clear"></div>
	    </div>
    <?php endforeach; ?>

    </div>

    <?= $pager ?>


</div>

<script type="text/javascript">
function del(cid){
        new Request({
            url: '/user_bubble/del?cid='+cid,
            success: function(){
                candyDel('b_'+cid);
            }
        }).send();
    }
</script>

<script type="text/javascript">
function bubble_onfoucs(id){
    document.getElementById('b_textarea_'+id).style.height='40px';
    document.getElementById('b_button_'+id).style.display='';
}

function bubble_onblur(id){
    var obj=document.getElementById('b_textarea_'+id);
   if(obj.value==''){
        obj.style.height='15px';
        document.getElementById('b_button_'+id).style.display='none';
   }
}

function bubble_comment_post(id){
      var comment_bubble = new ajaxForm('bubble_form_'+id, {
           callback:function(){
                reload_bubble_comment(id);
            }
      });
      comment_bubble.send();
}
//重载
function reload_bubble_comment(id){
	var list = new Request({
				    	    url: '<?= URL::site('comment/bubbleList?id=') ?>'+id+'&limit=50',
				    	    type: 'post',
				    	    success: function(data){
				    		        document.getElementById('buttle_comments_'+id).innerHTML=data;
                  document.getElementById('b_textarea_'+id).value='';
                  document.getElementById('b_textarea_'+id).style.height='15px';
                  document.getElementById('b_button_'+id).style.display='none';
				    	    }
				    	});
         list.send();
}
</script>