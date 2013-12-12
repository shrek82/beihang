<div id="left">
        <!--face -->
        <div class="user_face">
                <div class="face">

                        <?php if ($_MASTER): ?>
                                <a href="<?= URL::site('user_home') ?>" title="返回我的个人主页首页"><img src="<?= Model_User::avatar($_ID, 128, $_SESS->get('sex')) . '?gen_at=' . time() ?>" width="128" height="128" border="0"></a>
                        <?php else: ?>
                                <a href="<?= URL::site('user_home?id=' . $_ID) ?>" ><img src="<?= Model_User::avatar($_ID, 128, $_SEX) . '?gen_at=' . time() ?>" width="128" height="128" border="0"></a>
                        <?php endif; ?>

                </div>

                <div class="edit blue_link">
                        <?php if ($_MASTER): ?>
                                <a href="<?= URL::site('user_info/base') ?>">个人资料</a>&nbsp;&nbsp;&nbsp;<a href="<?= URL::site('user_home/avatar') ?>" >编辑头像</a>
                        <?php else: ?>
                                <div style="margin:5px"><?=View::factory('user/mark',array('uid'=>$_ID));?></div>
                                <a href="javascript:;" onclick="sendMsg(<?=$_ID?>)" class="ico_message">发送消息</a>&nbsp;&nbsp;&nbsp;<a href="<?= URL::site('user_album/index?id=' . $_ID) ?>" class="ico_photo">TA的相册</a>
                        <?php endif; ?>
                </div>

        </div>
        <div class="clear"></div>
        <!--//face -->

        <?php if ($_MASTER): ?>

                <!--未激活 -->
                <?php if (!$_SESS->get('actived')): ?>
                        <div style="text-align:center">
                                <a id="user_reactive" href="javascript:void(0)" onclick="sendActiveMail()">发送激活邮件</a>
                                <script type="text/javascript">
                                        function sendActiveMail(){
                                                new Request({
                                                        url: '<?= URL::site('user/reactive') ?>',
                                                        beforeSend: function(){
                                                                $('#user_reactive').html('发送中请稍等..');
                                                        },
                                                        success: function(resp){
                                                                $('#user_reactive').html(resp);
                                                        }
                                                }).send();
                                        }
                                </script>
                        </div>
                <?php else: ?>
                        <div style="height: 10px;"></div>
                <?php endif; ?>
                <!--//未激活 -->

                <!--plugins -->
                <p class="title">应用：</p>
                <div id="plugins">
                        <ul>
                                <li><a href="<?= URL::site('user_home') ?>" class="<?= $_C == 'user_home' ? 'cur' : '' ?>"><img src="/static/images/user/ico_house.gif">主页</a></li>
                                <li><a href="<?= URL::site('user_bbs/index') ?>" class="<?= $_C == 'user_bbs' ? 'cur' : '' ?>"><img src="/static/images/user/ico_bbs.gif">论坛</a></li>
                                <li><a href="<?= URL::site('user_album/index') ?>" class="<?= $_C == 'user_album' ? 'cur' : '' ?>"><img src="/static/images/user/ico_album.gif">相册</a></li>
                                <li><a href="<?= URL::site('user_event/index') ?>" class="<?= $_C == 'user_event' ? 'cur' : '' ?>"><img src="/static/images/user/ico_event.gif">活动</a></li>
                                <li><a href="<?= URL::site('user_msg/index') ?>" class="<?= $_C == 'user_msg' ? 'cur' : '' ?>"><img src="/static/images/user/ico_message.gif">消息</a></li>
                                <?php if($_CONFIG->modules['invite']):?><li><a href="<?= URL::site('user_invite/sended') ?>" class="<?= $_C == 'user_invite' ? 'cur' : '' ?>"><img src="/static/images/user/simpy.png">邀请</a></li> <?php endif;?>
                                <?php if($_CONFIG->modules['news_contribute']):?><li><a href="<?= URL::site('user_news/index') ?>" class="<?= $_C == 'user_news' ? 'cur' : '' ?>"><img src="/static/images/user/ico_news.gif">新闻</a></li> <?php endif;?>
                                <?php if($_CONFIG->modules['publication_contribute']):?><li><a href="<?= URL::site('user_publication/index') ?>" class="<?= $_C == 'user_publication' ? 'cur' : '' ?>"><img src="/static/images/topicnew.gif">文章</a></li> <?php endif;?>
                                <?php if ($_MASTER AND $_CONFIG->modules['register_mail']): ?><li><a href="<?= URL::site('user_mail/index') ?>" class="<?= $_C == 'user_mail' ? 'cur' : '' ?>"><img src="/static/images/mailbox.gif">邮箱</a></li><?php endif; ?>
                        </ul>
                        <div class="clear"></div>
                </div>
                <!--//plugins -->
        <?php endif; ?>


        <!--classroom -->
        <p class="title">班级：</p>
        <?= View::factory('user_global/qclassroom'); ?>
        <!--//classroom -->

        <!--aa -->
        <p class="title">校友会：</p>
        <?= View::factory('user_global/qlink'); ?>
        <!--//aa -->

        <?php if($_CONFIG->modules['binding']):?>
        <!--weibo -->
        <p class="title">微博绑定：</p>
        <?= View::factory('user_global/binding'); ?>
        <!--//weibo -->
        <?php endif;?>

        <?php if ($_C != 'user_point'): ?>
                <?php
                $div_point = Common_Point::divPoint();
                $user_temp = Common_Point::getTemp($div_point, $_USER['point']);
                ?>
                <!--temp -->
                <p class="title" >热心度：</p>
                <div style="padding:10px 0">
                        <span class="middle"><img src="/static/images/heart.gif" class="middle"></span><a href="/user_point?id=<?= $_ID ?>" style="color:#f40" title="点击查看详情"><?= $user_temp ?>°</a>&nbsp;<span style="color:#1776BC" title="积分"><?= $_USER['point'] ?>pt</span></div>
                <!--//temp -->
        <?php endif; ?>

</div>
