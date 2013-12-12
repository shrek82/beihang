<!-- classroom_home/bbsUnit:_body -->
<style type="text/css">
    #unit_content{padding:0px 20px 20px 20px;font-size:14px;}
    #unit_content img{margin: 15px 0}
</style>
<?php
/**
  +-----------------------------------------------------------------
 * 名称：地方校友会活动详细页面
 * 版本：1.0
 * 版权：www.usho.cn
 * 作者：zhaojiangang
 * 最后更新日期：12-10-11 下午4:57
  +-----------------------------------------------------------------
 */
?>
<script type="text/javascript" >
    $(document).ready(function(){
        $("#event_pics .colorboxPic").colorbox({rel:'colorboxPic'});
    });
    function colorboxShow(href) {
        $.colorbox({href:href});
    }
</script>
<style type="text/css">
    #comments_list .one_cmt_right{width:780px}
    #comment_form_box .comment_form{width:700px;_width:600px}
    #cmt_content{width:790px}
</style>
<div id="admin950" style=" color: #333">
    <div style="text-align: right;padding: 0px 20px">
        <a href="<?= URL::site('classroom_home/bbsPost?id=' . $_CLASSROOM['id']) ?>"  /><img src="/static/images/post.png" /></a>
    </div>

    <p style="height:30px;font-size:16px;font-weight: bold; color: #c00;margin:10px 20px 0px 0px">
        主题：<?= $unit['title'] ?>
    </p>
    <div class="dotted_line"></div>
    <table  width="100%" border="0" cellspacing="0" cellpadding="0">
        <td valign="top" style="padding:15px 0; width:50px"><?= View::factory('inc/user/avatar', array('id' => $unit['user_id'], 'size' => 48, 'sex' => $unit['sex']))
?>
        </td>
        <td valign="top" >
            <div style="padding: 15px 15px 15px 10px"><a href="<?= URL::site('user_home?id=' . $unit['user_id']) ?>"><?= $unit['realname'] ?></a>&nbsp;<span style="color:#999"><?= Date::span_str(strtotime($unit['update_at'])) ?>前发表：</span></div>
            <div id="unit_content">
                <?= $unit['content'] ?>
                <?php if ($unit['update_at'] > $unit['create_at']): ?>
                    <div style="color:#999;padding:10px 0;font-size: 12px">作者在<?= $unit['update_at'] ?> 做了修改 </div>
                <?php endif; ?>
            </div>



            <div style=" margin: 10px 18px;text-align:right;padding-right:20px">
                <?php if ($unit['user_id'] == $_UID): // 能修改自己 ?>
                    <a href="<?= URL::site('classroom_home/bbsPost?id=' . $_CLASSROOM['id'] . '&unit_id=' . $unit['id']); ?>" class="ico_edit">修改</a>&nbsp;
                <?php endif; ?>

                <?php if ($_IS_MANAGER): ?>
                    <a href="javascript:fix(<?= $unit['id'] ?>)" class="<?= $unit['is_fixed'] ? 'ico_yes' : 'ico_no' ?>"  id="fix_link">置顶</a>&nbsp;
                    <a href="javascript:setgood(<?= $unit['id'] ?>)" class="<?= $unit['is_good'] ? 'ico_yes' : 'ico_no' ?>"  id="good_link">推荐</a>&nbsp;
                    <a href="javascript:del(<?= $unit['id'] ?>)" class="ico_del" title="删除该话题">删除</a>&nbsp;
                    <script type="text/javascript">
                        function fix(cid){
                            new Request({
                                url: '<?= URL::site('classroom_admin/setFix?id=' . $_CLASSROOM['id'] . '&cid=') ?>'+cid,
                                type: 'post',
                                success: function(){
                                    var fix_link=document.getElementById('fix_link');
                                    fix_link.className=fix_link.className=='ico_yes'?'ico_no':'ico_yes';
                                }
                            }).send();
                        }

                        function setgood(cid){
                            new Request({
                                url: '<?= URL::site('classroom_admin/setGood?id=' . $_CLASSROOM['id'] . '&cid=') ?>'+cid,
                                type: 'post',
                                success: function(){
                                    var fix_link=document.getElementById('good_link');
                                    fix_link.className=fix_link.className=='ico_yes'?'ico_no':'ico_yes';
                                }
                            }).send();
                        }

                        function del(cid){
                            var b = new Facebox({
                                title: '删除确认！',
                                message: '确定要删除该话题及所有对它的回复吗？',
                                icon:'question',
                                ok: function(){
                                    new Request({
                                        url: '<?= URL::site('classroom_admin/unitDel?id=' . $_CLASSROOM['id'] . '&cid=') ?>'+cid,
                                        type: 'post',
                                        success: function(){
                                            window.location.href='<?= URL::site('classroom_home/bbs?id=' . $_CLASSROOM['id']) ?>';
                                        }
                                    }).send();
                                    b.close();
                                }
                            });
                            b.show();
                        }
                    </script>
                <?php endif; ?>
            </div>


        </td>
    </table>
    <div class="view_title" ><span class="middle"><img src="/static/images/comments.png"></span>&nbsp;评论</div>

    <div style="margin:20px 0">
        <?= View::factory('inc/comment/newform', array('params' => array('class_unit_id' => $unit['id']))) ?>
    </div>
</div>
