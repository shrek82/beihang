<!-- user_event/join:_body -->
<div id="big_right">
 <div id="plugin_title">我的活动</div>

<div class="tab_gray" id="user_topbar" style="margin-top:10px">
   <ul>
       <li><a href="<?= URL::site('user_event/index') ?>" style="width:80px">我发起的活动</a></li>
       <li><a href="<?= URL::site('user_event/join') ?>" class="cur" style="width:80px">参与的活动</a></li>
       <li><a href="<?= URL::site('event/form') ?>"  style="width:80px" target="_blank">发起活动</a></li>
    </ul>
</div>
<?= View::factory('inc/event/list', compact('events','pager')); ?>
</div>