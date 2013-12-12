<?php
//版本：1.0
?>
<a href="<?= URL::site('user_msg/sys') ?>" id="sysmsg" class="ico_sound_off icon-i" title="系统消息">0</a>
<script type="text/javascript">
    var sound_anim;
    var sound = function(){
        var state;
        if($('sysmsg').hasClass('ico_sound_off')){
            state = 'ico_sound_on';
            $('sysmsg').removeClass('ico_sound_off');
        } else {
            state = 'ico_sound_off';
            $('sysmsg').removeClass('ico_sound_on');
        }
        $('sysmsg').addClass(state);
    }
    var sysmsg = new Request({
        type: 'post',
        url: '<?= URL::site('user_msg/check/sys') ?>',
        success: function(msgcount){
            $('sysmsg').set('html', msgcount);
            if(msgcount > 0){
                sound_anim = window.setInterval(sound, 1000);
            }else{
                $clear(sound_anim);
            }
        }
    }).send();
</script>