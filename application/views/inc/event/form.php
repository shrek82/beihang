<?php
    echo Candy::import('datepicker');
    $etype = Kohana::config('icon.etype');
    $restart = Arr::get($_GET, 'restart');
    $event_num = 1;
    if($restart){
        $event_num = $event['num'];
        $event['start'] = '';
        $event['id'] = '';
        $event['finish'] = '';
        $event['sign_start'] = '';
        $event['sign_finish'] = '';
        if(strstr($event['title'], $event['num'])){
            $event['title'] = str_replace($event['num'], $event_num, $event['title']);
        } else {
            $event['title'] .= '(第'.$event_num.'期)';
        }
    } else {
        $event_num = $event['num'];
    }

?>
<!-- news/form:_body -->
<script type="text/javascript">
    var event_form = new ajaxForm('event_form', {
        redirect: '<?= URL::site('user_event/index') ?>'
    });
    function etype(name){
        $$('.event_type').setStyle('backgroundColor','#fff');
        $('etype').set('value', name);
        $$('.event_type:contains('+name+')').setStyle('backgroundColor', '#f3f3f3');
    }
</script>
<form method="post" action="<?= URL::site('event/publish') ?>" id="event_form">
    <div><label>活动类型</label><span style="color: #999">(没有符合的可以不选)</span></div>
    <div id="event_type" style="margin-top: 10px;">
        <?php foreach($etype['icons'] as $name => $ico): ?>
        <a class="event_type" href="javascript:etype('<?= $name ?>')"
        style="background: #fff url(<?= $etype['url'].$ico ?>) no-repeat center top;">
            <?= $name ?>
        </a>
        <?php endforeach; ?>
        <input type="hidden" id="etype" name="type" value="<?= @$event['type'] ?>" />
        <script type="text/javascript">
            <?php if(isset($event['type'])): ?>
            etype('<?= $event['type'] ?>');
            <?php endif; ?>
        </script>
    </div>
    <div class="clear"></div>
    <div><label>发布在</label><br />
        <?= View::factory('inc/event/belongs_to', compact('aa_id','club_id')); ?>
    </div>

    <div><label>活动标题</label><br />
        <input size="70" type="text" name="title" value="<?= @$event['title'] ?>"  class="input_text"/>
    </div>

    <div><label>活动地址</label><br />
        <input size="70" type="text" name="address" value="<?= @$event['address'] ?>"  class="input_text"/>
    </div>

    <div><label>开始时间</label><br />
        <input type="text" name="start" size="50"  value="<?=$event['start']?date('Y-m-d H:i',  strtotime($event['start'])):''; ?>" class="start input_text" id="estart" />
    </div>

    <div><label>结束时间</label><br />
 <input type="text" name="finish" size="50"  value="<?=$event['finish']?date('Y-m-d H:i',  strtotime($event['finish'])):''; ?>" class="start input_text" id="finish" />
    </div>

    <div><label>参加人数</label> <span style="color:#999">(留空为不限)</span><br />
        <input size="30" type="text" name="sign_limit" value="<?= @$event['sign_limit']>0?$event['sign_limit']:'';?>" class="input_text" />
    </div>

    <div><textarea id="event_content" name="content"><?= @$event['content'] ?></textarea></div>

    <input type="hidden" name="num" value="<?= $event_num ?>" />
    <input type="hidden" name="id" value="<?= @$event['id'] ?>" />

    <div><input type="button" id="submit_button"  value="<?=$event?'保存修改':'立即发起';?>" class="input_button"  onclick="submit_event()"/></div>
</form>

<?php $ke = View::keditor('event_content', array(
            'height' => '200px',
            'width' => '95%',
            'allowUpload' => true,
            'items' => Kohana::config('ke.base')
));?>
<?= $ke['init']; ?>
<?= $ke['show']; ?>

<script type="text/javascript">
function submit_event(){
    $('event_content').set('value', KE.html('event_content'));
    event_form.send();
}

//candyDatePicker('estart', true, 'Y-m-d');
//new DatePicker('.estart', {
//    allowEmpty: true,
//    months: _date_month,
//    days: _date_days,
//    timePicker: true,
//    inputOutputFormat: 'Y-m-d H:i',
//    format: 'Y-m-d H:i',
//    onClose: function(){
//        if($('estart').get('value') == '')
//        $('estart').set('value', $('efinish').get('value'));
//    }
//});
<?php if((!$event) OR $restart):?>
candyDatePicker('start', true, 'Y-m-d H:i');//true还可以增加具体时间
candyDatePicker('finish', true, 'Y-m-d H');//ture还可以增加具体时间
 <?php endif;?>
</script>