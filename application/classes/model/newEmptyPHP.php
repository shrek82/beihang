<div class="topic_postdate">
    <p style="float:left"><span style="color:#f60; font-weight: bold">楼主</span>&nbsp;&nbsp;发布于<?= $unit['create_at'] ?></p>
    <p style="float:right">
        <?php if ($reply): ?>
            <a href="<?= URL::site('bbs/viewPost?id=' . $unit['id']) ?>" style="color:#999" >查看全部</a>
        <?php else: ?>
            <a href="<?= URL::site('bbs/viewPost?id=' . $unit['id']) ?>&reply=<?= $unit['user_id'] ?>" style="color:#999" title="只查看作者评论">只看该作者</a>
        <?php endif; ?>
    </p>
</div>

<div class="comment_content" style="word-break:break-all">

    <?php if ($unit['is_closed']): ?>
        <div style="color:#f60; background: #FAFAD6; border: 1px solid #F0EAAA; padding:5px 10px; margin-bottom:15px">提示：该贴已被屏蔽，目前只有管理员或作者可见:)</div>
    <?php endif; ?>

    <?php if ($event): ?>
        <div >
            <p style=" margin: 5px 0;font-weight: bold; height:30px; line-height: 30px; color: #CC0000; position: relative;font-family:'Microsoft YaHei','Microsoft JhengHei';font-size: 20px"><?= $event['title'] ?></p>
            <b>时间：</b><?php if (date('Y-m-d H:i', strtotime($event['start'])) == date('Y-m-d H:i', strtotime($event['start']))): ?><?= date('Y年m月d日', strtotime($event['start'])) ?>&nbsp;<?= date('H:i', strtotime($event['start'])) ?>~<?= date('H:i', strtotime($event['finish'])) ?><?php else: ?><?= date('Y年m月d日 H:i', strtotime($event['start'])) ?> 至 <?= date('m月d日 H:i', strtotime($event['finish'])) ?><?php endif; ?><br>
            <b>所属：</b><?php if ($event['aa_id'] == '-1'): ?><a href="<?= URL::site('aa') ?>">浙江大学校友总会</a><?php else: ?><a href="<?= URL::site('aa_home?id=' . $event['aa_id']) ?>" title="进入校友会主页"><?= $event['Aa']['name'] ?></a><?php endif; ?>
            <?php if ($event['Club']): ?>
                &raquo; <a href="<?= URL::site('club_home/event?id=' . $event['aa_id'] . '&club_id=' . $event['club_id']) ?>"><?= $event['Club']['name'] ?></a>
            <?php endif; ?><br>
            <b>地址：</b><?= $event['address'] ?><br>
            <b>人数：</b><?= $event['sign_limit'] ? $event['sign_limit'] . '人' : '不限'; ?><br>
            <?php if (!empty($tags)): ?><b>标签：</b>
                <?php foreach ($tags as $name): ?>
                    <a href="<?= URL::site('event?tag=' . urlencode($name)) ?>"><?= $name ?></a>&nbsp;&nbsp;
                <?php endforeach; ?><br>
            <?php endif; ?>
            <b>状态：</b><?= Model_Event::status($event) ?>
            <?php if ($event['votes'] > 0): ?><br><b>体验：</b><?php for ($i = 1; $i <= $event['score']; $i++): ?><img src="/static/images/knewstuff.png" style="margin-right:2px;vertical-align: middle; margin: 0"><?php endfor; ?><?php for ($i = 1; $i <= 5 - $event['score']; $i++): ?><img src="/static/images/knewstuff2.png" style="margin-right:2px;vertical-align: middle"><?php endfor; ?>&nbsp;&nbsp;<span style="color:#999"><?= $event['votes'] ?>人评分</span><?php endif; ?>
        </div><br>
    <?php endif; ?>

    <?php
    //设置大图自动缩小js
    //$content = preg_replace('/<img/im', '<img onload="javascript:DrawImage(this);" ', $unit['Post']['content']);
    //链接为新窗口打开
    //$content = preg_replace("/<a([^\>]*)(\starget\=\"?\w+\"?)|<a([^\>]*)/i", "<a$1$3 target=_blank", $content);
    $content = preg_replace('/(<img[^>]+src\s*=\s*"?\/static\/upload\/attached\/([^>"\s]+)"?[^>]*>)/im', '<a href="/static/upload/attached/$2?v=1" target="_blank"  class="colorboxPic" style="cursor:url(/static/big.cur),pointer">$1</a>', $unit['Post']['content']);
    $content = preg_replace('/_bmiddle\.(jpg|gif|jpeg|png)\?v=1/i', '.$1', $content);
    echo $content;
    ?>

    <?php if ($event): ?>
        <?php if (count($signs) > 0): ?>
            <div style="padding:10px; background: #f6f6f6; color: #333">
                <b>已报名(<?= count($signs) ?>人)：</b>
                <?php foreach ($signs AS $s): ?><a href="/user_home?id=<?= $s['user_id'] ?>" target="_blank" style="color:#333"><?= $s['User']['realname'] ?></a>&nbsp;<?php endforeach; ?></div>
            <?php endif; ?>

        <?php if ($eventAlbum AND count($eventPhotos) > 0): ?>
            <b>【活动掠影】</b><br>
            <div>
                <?php foreach ($eventPhotos as $p): ?>
                    <?php $link = URL::site('album/picView?id=' . $p['id']) ?>
                    <a href="<?= $link ?>" title="点击放大" target="_blank"><img src="<?= URL::base() . $p['img_path'] ?>" style="border-width:0px;margin:0;width:120px;height:80px"/></a>&nbsp;
                <?php endforeach; ?>
                <div class="clear"></div>
            </div>
        <?php endif; ?>

        <br><span><a href="/event/view?id=<?= $unit['event_id'] ?>" class="moreEventInfo" target="_blank"></a></span>
    <?php endif; ?>

    <?php if ($vote): ?><?php include "unit_vote.php"; ?><?php endif; ?>

    <?php if ($unit['is_good']): ?>
        <div style="margin-top:30px;background:#FCFAE3 url(/static/ico/agree.gif) no-repeat 10px 12px;font-weight: bold;font-size:14px;width:50px;height:40px; line-height: 40px; color: #f60; padding-left: 30px" title="积分+<?= $point['good_unit'] ?>">推荐</div>
    <?php endif; ?>

</div>

<?php if ($unit['Post']['hidden']): ?>
    <div class="hidden" style="word-break:break-all">
        <?php if (Model_Bbs::isReply($unit['id'], $_SESS->get('id'))): ?>
            <?= $unit['Post']['hidden'] ?>
        <?php else: ?>
            有部分内容评论后可见。已回复？请<a href="javascript:history.go(0)">刷新一下</a>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php if ($unit['is_fixed']): ?>
    <div class="unit_other_property"><img src="/static/images/yz_is_top.gif"></div>
<?php elseif ($unit['is_good']): ?>
    <div class="unit_other_property"><img src="/static/images/is_recommend.gif" title="积分+<?= $point['good_unit'] ?>"></div>
<?php elseif ($unit['reply_num'] >= 15): ?>
    <div class="unit_other_property"><img src="/static/images/yz_is_hot.gif"></div>
<?php endif; ?>

<div style="height:50px"></div>
<div class="bbs_bottom_tools">
    <div style="text-align:right" class="manage_tool">
        <?php if ($is_edit_permission): // 拥有编辑权限   ?>
            <?php if ((time() - strtotime($unit['create_at']) < 3600 * 24 * 30 * 12) OR $is_system_permission): ?>
                <?php if ($unit['event_id']): ?>
                    <a href="<?= URL::site('event/form?id=' . $unit['event_id']); ?>" class="ico_edit">编辑活动</a>&nbsp;
                <?php else: ?>
                    <a href="<?= URL::site('bbs/unitForm?id=' . $unit['id']); ?>" class="ico_edit">修改</a>&nbsp;
                <?php endif; ?>

            <?php endif; ?>
        <?php endif; ?>
        <?php if ($is_system_permission)://总会管理员?>
            <a href="javascript:homepage(<?= $unit['id'] ?>)" class="<?= $unit['SysFilter']['id'] ? 'ico_yes' : 'ico_no' ?>" title="在网站首页显示该话题" id="homepage_link">首页显示</a>&nbsp;
            <a href="<?= URL::site('admin_bbs/focusForm?id=' . $unit['id']); ?>" class="ico_television" title="增加到论坛首页幻灯片播放">幻灯片</a>&nbsp;
            <script type="text/javascript">
                function homepage(cid){
                    new Request({
                        url: '<?= URL::site('admin_bbs/homepage?cid=') ?>'+cid,
                        method: 'post',
                        onSuccess: function(){
                            var homepage_link=document.getElementById('homepage_link');
                            homepage_link.className=homepage_link.className=='ico_yes'?'ico_no':'ico_yes';
                        }
                    }).send();
                }
            </script>
        <?php endif; ?>

        <?php if ($is_control_permission): // 地方校友会或或俱乐部权限   ?>
            <a href="javascript:setProperty('is_fixed',<?= $unit['id'] ?>)" class="<?= $unit['is_fixed'] ? 'ico_yes' : 'ico_no' ?>" title="推荐话题" id="is_fixed_link">置顶</a>&nbsp;
            <a href="javascript:setProperty('is_good',<?= $unit['id'] ?>)" class="<?= $unit['is_good'] ? 'ico_yes' : 'ico_no' ?>" title="置顶话题" id="is_good_link">推荐</a>&nbsp;
            <a href="javascript:close(<?= $unit['id'] ?>)" class="ico_del" title="屏蔽该话题">屏蔽</a>&nbsp;
            <script type="text/javascript">
                function setProperty(property,cid){
                    new Request({
                        url: '<?= URL::site('bbs/setProperty?cid=') ?>'+cid+'&property='+property,
                        method: 'post',
                        onSuccess: function(data){
                            if(data!=''){
                                alert(data);
                                return false;
                            }
                            else{
                                var link=document.getElementById(property+'_link');
                                link.className=link.className=='ico_yes'?'ico_no':'ico_yes';
                            }

                        }
                    }).send();
                }

                function close(cid){
                    var b = new Facebox({
                        title: '屏蔽确认！',
                        message: '确定要屏蔽该话题吗？屏蔽后您还可以通过后台再次显示！',
                        submitValue: '确定',
                        submitFunction: function(){
                            new Request({
                                url: '<?= URL::site('bbs/close?cid=') ?>'+cid,
                                method: 'post',
                                onSuccess: function(){
                                    window.location.href='<?= URL::site($bbs_url) ?>';
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
    <div style="margin:8px 0; float: right;">
        <!-- JiaThis Button BEGIN -->
        <div id="ckepop">
            <span class="jiathis_txt">分享到：</span>
            <a class="jiathis_button_qzone"></a>
            <a class="jiathis_button_tsina"></a>
            <a class="jiathis_button_renren"></a>
            <a class="jiathis_button_kaixin001"></a>
            <a href="http://www.jiathis.com/share/" class="jiathis jiathis_txt jtico jtico_jiathis" target="_blank"></a>
            <a class="jiathis_counter_style"></a>
        </div>
        <script type="text/javascript" src="http://v2.jiathis.com/code/jia.js" charset="utf-8"></script>
        <!-- JiaThis Button END -->
    </div>
    <div class="clear"></div>
    <?php if ($unit['update_at']): ?>
        <div class="update">作者在 <?= $unit['update_at'] ?> 做了修改 </div>
    <?php endif; ?>

    <?php if ($unit['bubble']): ?>
        <div class="dotted_line"></div>
        <div class="sigline"><img src="/static/images/pen_alt_fill_12x12.gif" title="最新记录" style="vertical-align:middle;margin:0px 4px"><?= Text::limit_chars($unit['bubble'], 50, '...') ?></div>
        <?php endif; ?>
</div>
