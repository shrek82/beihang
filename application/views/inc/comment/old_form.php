<?php

    echo Candy::import('ckeditor');
    echo Candy::import('ckfinder');

?>

<div class="clear"></div>

<div id="get_comment_list" style="padding:10px"></div>

<div class="bar">发表讨论</div>
<?php if( ! $_SESS->get('id')): ?>
<div class="notice">
    烦请 <a href="javascript:faceboxUserLogin()"><b>登录</b></a> 后进行回帖讨论。
</div>
<?php else: ?>
<table width="100%">
    <tr>
        <td width="48">
            <?= View::factory('inc/user/avatar', array('size'=>48)) ?>
        </td>
        <td>
            <script type="text/javascript">
            var cmt = new CandyForm('comment_form', {btnSubmit:'cmt_submit'});
            </script>
            <form onsubmit="post_comment()" action="<?= URL::site('comment/post') ?>" id="comment_form" method="post">
            <div>
                <textarea cols="100" rows="5" id="cmt_content" name="content"></textarea>
            </div>
            <div>

                <?php if(isset($params)): ?>

                <?php foreach($params as $key=>$val): ?>
                <input type="hidden" name="<?= $key ?>" value="<?= $val ?>" />
                <?php endforeach; ?>

                <?php endif; ?>

                <input type="button" id="cmt_submit" onclick="post_comment()" value="提交" />
                [Ctrl+Enter]
            </div>
            </form>
        </td>
    </tr>
</table>

<script type="text/javascript">
var comment = candyCKE('cmt_content', 'Base');
    comment.config.height = 150;
    comment.config.extraPlugins = "ajaxsave"
    comment.config.keystrokes = [[CKEDITOR.CTRL + 13 /*enter*/, 'ajaxsave']];

var wfx = new Fx.Scroll(window, {
    'duration': 200
});

<?= Candy::initCKFinder('comment') ?>

function post_comment(){
    cmt.setOptions({
        callback: function(){
            get_comment();
            comment.setData('');
        }
    });
    $('cmt_content').set('value', comment.getData());
    cmt.send();
}

function modify_comment(id){
    $('comment_form').set('action', '<?= URL::site('comment/post') ?>?id='+id);
    var html = $$('#comment_'+id+' .comment_content').get('html');
    comment.setData(''+html+'');
}


function quote_comment(id){
    var user = $$('#comment_'+id+' .commentor').get('text');
    var txt = $$('#comment_'+id+' .comment_content').get('text');
    comment.setData('<div class="cmt_quote">'+user+'【'+txt+'】</div><br />');
    wfx.toElement('comment_form');
}
</script>
<?php endif; ?>

<script type="text/javascript">
function get_comment(page){
    var list = new Request({
        url: '<?= URL::site('comment/index/old_index') ?>',
        type: 'post',
        success: function(data){
            $('get_comment_list').set('html', data);
            $$('.cmt_quote').addEvent('mouseenter', function(){
                this.setStyle('overflow', 'auto');
                this.setStyle('height', 'auto');
            });

            $$('.cmt_quote').addEvent('mouseleave', function(){
                this.setStyle('overflow', 'hidden');
                this.setStyle('height', '25px');
            });
            checkOnline();
        }
    });
    var query = '<?= http_build_query(@$params, '', '&'); ?>';
    if($defined(page)){
        query += '&page='+page;
    }
    list.send(query);
}
get_comment();
</script>