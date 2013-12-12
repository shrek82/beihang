<style type="text/css">
    #comments_list .one_cmt_right{width:780px}
    #comment_form_box .comment_form{width:700px;_width:600px}
    #cmt_content{width:790px}
</style>
<div id="admin950">
    <?= View::factory('inc/comment/newform', array('params'=>array(
        'class_room_id'=>$_CLASSROOM['id']
    ))) ?>
</div>