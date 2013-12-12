<div id="search">
    <form id="search_form" method="get">
        <?php
        $tabs = array(
            'news' => '新闻', 'user' => '校友', 'org' => '校友会', 'classroom' => '班级录', 'event' => '活动', 'bbs' => '论坛'
                )
        ?>
        <div id="search_tab" class="mid_tab">
            <?php foreach ($tabs as $t => $name): ?>
                <a href="javascript:search('<?= $t ?>', 1)" id="tab_for_<?= $t ?>" class="<?= $t == $for ? 'cur' : '' ?>" title="点击搜索"><?= $name ?></a>
<?php endforeach; ?>
        </div>
        <table style="border:0px; margin:-13px 20px 0px 20px">
            <tr>
                <td style="width: 50px">关键字：</td>
                <td><input type="text" id="q" name="q" value="<?= $q ?>"  class="input_text" style="width:500px"/>
                    <input type="hidden" name="user_id" id="user_id" value="<?= @$user_id ?>" />
                    <input type="hidden" name="for" id="for" value="<?= $for ?>" /></td>
                <td><input type="button" class="button_blue" value="搜索" onclick="search(document.getElementById('for').value)"></td>
                <td> <span id="search_quering"></span></td>
            </tr>
        </table>
        <div id="search_input">


        </div>
        <div id="search_result" style="padding:10px 15px"></div>
    </form>
</div>

<script type="text/javascript">
    function search(t, p){
        //reset
        $('#search_tab a').removeClass('cur');
        $('#tab_for_'+t).addClass('cur');

        $('#for').attr('value', t);
        var url = '/search/'+t;
        new Request({
            url: url,
            data: 'page='+p+'&q='+$('#q').val(),
            type: 'post',
            beforeSend: function(){
                $('#search_quering').html('<img src="<?= URL::base() . 'candybox/Media/image/loading.gif' ?>" width="18" />');
            },
            success: function(d){
                $('#search_quering').html('');
                $('#search_result').html(d);
            }
        }).send();
    }
    search('<?= $for ?>', 1);
</script>