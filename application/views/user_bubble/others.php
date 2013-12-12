<!-- user_bubble/index:_body -->
<script type="text/javascript" src="/static/js/bubble_comment.js"></script>
<div id="big_right">

<div id="plugin_title">记录</div>

<div class="tab_gray" id="user_topbar" style="margin-top:10px">
   <ul>
       <li><a href="<?= URL::site('user_bubble/index') ?>"  style="width:50px">我自己的</a></li>
       <li><a href="<?= URL::site('user_bubble/others') ?>" class="cur" style="width:50px">其他人的</a></li>
    </ul>
</div>

     <?php if(count($bubbles) == 0): ?>
    <div>可以通过关注校友来获得他们的最新状态。</div>
    <?php endif; ?>

    <!--状态列表 -->
    <div id="bubble_list" class="blue_link">
    <?php foreach($bubbles as $bubble): ?>
	<div class="a_bubble">
	    <div class="bu_left"><?= View::factory('inc/user/avatar', array('id'=>$bubble['user_id'],'size'=>48)); ?></div>
	    <div class="bu_right">
	 <div class="font14"><a href="<?= URL::site('user_home?id'.$bubble['user_id']) ?>" style="font-weight:bold; font-size: 14px"><?= $bubble['User']['realname'] ?></a>：<?= $bubble['content'] ?></div>
	 <div class="gray_text" style=" margin-top:15px">
	     <p class="date"><?= Date::span_str(strtotime($bubble['blow_at'])) ?>前</p>
	     <p class="action"> </p>
	 </div>
  <div class="clear"></div>
      <div   style=" width:650px" >
      <?= View::factory('/inc/comment/bubble_list_form',	array('row'=>$bubble)) ?>
      </div>
	    </div>
	    <div class="clear"></div>
	    </div>
    <?php endforeach; ?>

    </div>

    <?= $pager ?>


</div>