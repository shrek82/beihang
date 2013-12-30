<!-- main/index:_body -->
<!--body -->
<div id="header">
    <div class="menu"></div>
</div>

<div class="wbody">
    <ul class="gonggao">
        <li class="left"><img src="/static/images/gonggao_lf.jpg"></li>
        <li class="gongcen">
            <strong style="float:left;">网站公告：</strong>
            <div id="div1"> 
                <?php if($announcement):?>
               <?php foreach ($announcement as $i => $n): ?>
                <a href="/news/view?id=<?=$n['id']?>" target="_blank"><?=$n['title']?></a>
               <?php endforeach;?>
                <?php else:?>
                <a href="#" style=" color: #666">暂时还没有任何公告！</a>
                <?php endif;?>

            </div>
        </li>
        <li class="right"><img src="/static/images/gonggao_lr.jpg"></li>
    </ul>
</div>

<div class="row">
    <!--focus news -->
    <!--top_topic -->
    <div id="banner">
        <?php if ($fixed_img): ?>
            <?php if (isset($fixed_img['redirect']) AND $fixed_img['redirect']): ?>
                <a href="<?= $fixed_img['redirect'] ?>"><img src="<?= str_replace('_s', '', $fixed_img['img_path']) ?>"></a>
            <?php else: ?>
                <img src="<?= str_replace('_s', '', $fixed_img['img_path']) ?>">
            <?php endif; ?>
        <?php else: ?>
            <div style="color: #999">
                <img src="/static/images/loading.gif" style="border-width: 0;vertical-align: middle">
            </div>
        <?php endif; ?>
    </div>
    <!--//focus news -->

    <?php if (!$_UID): ?>
        <!--login -->
        <?= View::factory('main/index_login') ?>
        <!--//login -->
    <?php else: ?>
        <div id="qlink">
            <p class="title">我已加入</p>
            <div style="margin:10px 20px;" id="user_login">
                <?= View::factory('inc/user/main_qlink', array('_ID' => $_UID)); ?>
            </div>
        </div>
    <? endif ?>
    <div class="clear"></div>
</div>
<!--//row1 -->
<!--row2:alumni、 news... -->
<div class="row">
    <!--alumni event -->
    <div id="alumni_event">

        <div class="small_tab"  id="aa_club_tab" >
            <ul>
                <li><a href="<?= URL::site('aa') ?>" id="one1"  class="cur" >地方校友会</a></li>
                <li><a href="#" id="one2" >院系分会</a></li>
                <li><a href="#" id="one3" >俱乐部</a></li>
            </ul>
        </div>

        <div id="aa_club_content_one1" class="tab_content">
            <div id="marquee">
                <div id="scr1">
                    <ul>
                        <?php foreach ($aa as $i => $aa): if ($aa['mcount'] >= 0): ?>
                                <li><a href="<?= URL::site('aa_home?id=' . $aa['id']) ?>" target="'_blank"><?= $aa['sname'] ?>校友会</a><span><? // $aa['mcount']                                   ?></span></li>
                                <?php
                            endif;
                        endforeach;
                        ?>
                    </ul>
                </div>
            </div>
            <p class="more"><a href="/aa/branch">>>更多</a></p>
        </div>
        <div id="aa_club_content_one2" class="tab_content" style="display:none">
            <ul style="margin:4px 8px">
                <?php foreach ($institution as $i => $aa): ?>
                    <li><a href="/aa_home?id=<?=$aa['id']?>" target="'_blank"><?= $aa['name'] ?>校友会</a></li>
                <?php endforeach; ?>
            </ul>
            <p class="more"><a href="/aa/institute">>>更多</a></p>
        </div>
        <div id="aa_club_content_one3" class="tab_content" style="display:none">
            <ul style="margin:4px 8px">
                <?php foreach ($club as $i => $c): ?>
                    <li><a href="/club_home?id=<?=$c['id']?>"  target="'_blank"><?= $c['name'] ?></a></li>
                <?php endforeach; ?>
            </ul>
            </ul>
            <p class="more"><a href="/aa/club">>>更多</a></p>
        </div>

        <p class="sidebar_title" style="border-width:1px 0">推荐活动</p>
        <?php if (!$events): ?>
            <p class="nodata" style="padding:15px">暂无推荐活动</p>
        <?php endif; ?>
        <div style=" padding:10px 13px;min-height: 215px;margin-bottom: 10px">
            <?php $etype = Kohana::config('icon.etype'); ?>
            <?php foreach ($events as $key => $e): ?>
                <div class="one_event<?= $key == 3 ? ' no_bg' : ''; ?>">
                    <div class="left">
                        <?php if ($e['type']): ?>
                            <img src="<?= $etype['url'] . $etype['icons'][$e['type']] ?>" width="35" height="36" />
                        <?php else: ?>
                            <img src="<?= $etype['url'] . 'undefined.png' ?>" width="35" height="36"/>
                        <?php endif; ?>
                    </div>
                    <div class="right">
                        <a href="<?= URL::site('event/view?id=' . $e['id']) ?>" target="'_blank"><?= Text::limit_chars($e['title'], 12, '..') ?></a><br />
                        <span style="color:#666"><?= date('n月d日', strtotime($e['start'])); ?> - <?= $e['aa_id'] > 0 ? $e['aa_name'] . '校友会' : '校友总会'; ?></span>

                    </div>
                </div>
            <? endforeach; ?>
            <p class="more"><a href="/event">>>更多</a></p>
        </div>


    </div>
    <!--//alumni event-->

    <?=
    View::factory('main/home_news', array(
        'top_news' => $top_news,
        'news_list' => $news_list,
    ));
    ?>
    <div id="qiushi">
        <p class="sidebar_title" style="border-width:1px 0">北航校友</p>
        <div class="one_qiushi">
            <a href="<?= URL::site('people/aView?id=' . $people['id']) ?>" target="'_blank"><img src="<?= $people['pic'] ?>" style="width:83px; float: left" /></a>
            <a href="<?= URL::site('people/aView?id=' . $people['id']) ?>" style="font-weight:bold" target="'_blank"><?= $people['name'] ?></a><br />
            <?= nl2br(Text::limit_chars($people['intro'], 90, '...')) ?>
            <div class="clear"></div>
            <p class="more" style="margin:5px 0"><a href="<?= URL::site('people') ?>">>>更多</a></p>
        </div>
        <div class="clear"></div>

        <p class="sidebar_title" style="border-width:1px 0">校友企业</p>

        <div style="padding:10px">
            <?php if (!$enterprise): ?>
                <p class="nodata" >暂无内容</p>
            <?php endif; ?>
            <?php foreach ($enterprise as $e): ?>
                <?php if (empty($e['redirect'])): ?>
                    <a href="/people/eView?id=<?= $e['id'] ?>" target="_blank"><?php else: ?><a href="<?= $e['redirect'] ?>" target="_blank"><?php endif; ?><img src="<?= $e['img_path'] ?>" width="220" height="70" style="margin-bottom:10px"/></a>
                <?php endforeach; ?>

                <p class="more" style="margin:5px 0"><a href="/people/enterprise">>>更多</a></p>
        </div>

    </div>
    <!--//Professor alumni-->
    <div class="clear"></div>
</div>
<!--//row2 -->

<!--//row3-->
<div class="big_tab_title" id="photo_tabs">
    <ul>
        <li tag="pic"><a href="javascript:void(0)" id="photo1"  onmouseover="sHome.setMorePhoto('morephoto1')" class="cur">岁月流金</a></li>
        <li tag="pic"><a href="javascript:void(0)" id="photo2" onmouseover="sHome.setMorePhoto('morephoto2')">活动照片</a></li>
        <li style="color: #487EB2;line-height: 25px;padding:0px 50px 0 20px;margin-right: 50px;font-size:10px"><marquee style="width:400px;" scrollamount=2 >忆往昔，看今朝，分享你我的流金岁月…</marquee></li>
    </ul>
    <p style=" text-align: right; padding: 15px 20px 0 0; color: #5F9BD6" id="morephoto1"><a href="<?= URL::site('album/uploadPic?id=1&enc=' . base64_encode(date('d')) . '=') ?>" target="_blank">上传照片</a> | <a href="<?= URL::site('album/picIndex?id=1') ?>" target="_blank">更多...</a></p>
    <p style=" text-align: right; padding: 15px 20px 0 0; color: #5F9BD6; display: none" id="morephoto2"></p>
</div>
<div class="tab_content_photo" id="photocontent_photo1">
    <?php if (!$old_pic): ?>
        <div class="nodata">暂时还没有该类照片。</div>
    <?php else: ?>
        <div id="demo" style="overflow: hidden; width: 895px; height: 110px">
            <table cellpadding="0" align="left" border="0" cellspacing="0">
                <tr>
                    <td id="demo1" valign="top">
                        <table cellspacing="0" cellpadding="0" width="322"  border="0">
                            <tr align="center">
                                <?php foreach ($old_pic as $p): ?>
                                    <td width="130" height="110"><p class="a_pic"><a href="<?= URL::site('album/picView?id=' . $p['id']) ?>" target="_blank"><img src="<?= URL::base() . $p['img_path'] ?>" width="105" height="79" /></a></p></td>
                                <?php endforeach; ?>
                            </tr>
                        </table></td>
                    <td id="demo2" valign="top"></td>
                </tr>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php
$event_pic_html = '';
if ($event_pic) {
    $event_pic_html = '<table cellspacing="0" cellpadding="0" width="322"  border="0"><tr align="center">';
    foreach ($event_pic as $p) {
        $event_pic_html.='<td width="130" height="110"><p class="a_pic"><a href="/album/picView?id=' . $p['id'] . ' target="_blank"><img src="/' . $p['img_path'] . '" width="105" height="79" /></a></p></td>';
    }
    $event_pic_html.='</tr></table>';
}
?>

<div class="tab_content_photo" id="photocontent_photo2" style="display:none">
    <?php if (!$event_pic): ?>
        <div class="nodata">暂时还没有活动照片。</div>
    <?php else: ?>
        <div id="demo3" style="overflow: hidden; width: 895px; height: 110px">
            <table cellpadding="0" align="left" border="0" cellspacing="0">
                <tr>
                    <td id="demo4" valign="top" style="display:none"></td>
                    <td id="demo5" valign="top"></td>
                </tr>
            </table>
        </div>
    <?php endif; ?>
</div>
<!--//row3-->

<!--row4 -->
<div id="row4" style="margin-top:10px">
    <div class="left">
        <!--new alumni-->

        <p class="sidebar_title" style="border-width:1px">最新加入校友</p>
        <div id="new_alumni" style="height:320px">
            <?php $new_alumni_count = count($new_alumni) ?>
            <?php foreach ($new_alumni as $key => $c): ?>
                <div class="a_alumni <?= ($key + 1) == $new_alumni_count ? 'no_line' : '' ?>">
                    <div class="left"><a href="<?= URL::site('user_home?id=' . $c['id']) ?>" target="'_blank"><?= View::factory('inc/user/avatar', array('id' => $c['id'], 'size' => 48, 'sex' => $c['sex'])) ?></a></div>
                    <div class="right"><a href="<?= URL::site('user_home?id=' . $c['id']) ?>" target="'_blank"><?= $c['realname'] ?></a>
                        <span class="gray"><br /><?= $c['start_year'] ?>级<?= Text::limit_chars($c['speciality'], 6, '...') ?>
                            <br /><?= Date::span_str(strtotime($c['reg_at'])) ?>前&nbsp;<?= $c['city'] ?></span>
                    </div>
                </div>
            <?php endforeach ?>
            <p class="more" style="margin:10px 0"><a href="<?= URL::site('alumni') ?>">>>更多</a></p>
        </div>

        <!--//new alumni-->
    </div>
    <div class="fcenter">
        <div id="bbs_tabs">
            <ul>
                <li><a href="javascript:void(0)" id="bbstab1"  class="cur">最新话题</a></li>
                <li>/</li>
                <li><a href="javascript:void(0)" id="bbstab2" >热门话题</a></li>
            </ul>
        </div>
        <div id="bbscontent_bbstab1" style="height: 330px">
            <table border="0" id="home_bbs" width="100%" cellspacing="0" cellpadding="0">
                <tbody>
                    <?php foreach ($units as $key => $un): ?>
                        <tr <?= ($key + 1) % 2 == 0 ? 'class="two"' : '' ?>>
                            <td><span class="aa_bbs">[<?php if ($un['aa_id']): ?><a href="<?= URL::site('bbs/list?aid=' . $un['aa_id']) ?>"><?= Text::limit_chars($un['aa_name'], 4, '') ?></a><?php else: ?><a href="<?= URL::site('bbs/list') ?>">公共</a><?php endif; ?>]</span><a href="<?= URL::site('bbs/view' . $un['type'] . '?id=' . $un['id']) ?>" target="_blank" title="<?= $un['title'] ?>" class="unit_title"><?= Text::limit_chars($un['title'], 18, '...') ?></a></td>
                            <td class="center"><?= Text::limit_chars($un['username'], 3, '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        </div>
        <div id="bbscontent_bbstab2" style="display:none;height: 330px">
            <table border="0" id="home_bbs" width="100%" cellspacing="0" cellpadding="0">
                <tbody>
                    <?php foreach ($hot_topic as $key => $un): ?>
                        <tr <?= ($key + 1) % 2 == 0 ? 'class="two"' : '' ?>>
                            <td><span class="aa_bbs">[<?php if ($un['aa_id']): ?><a href="<?= URL::site('bbs/list?aid=' . $un['aa_id']) ?>"><?= $un['sname'] ?></a><?php else: ?><a href="<?= URL::site('bbs/list') ?>">公共</a><?php endif; ?>]</span><a href="<?= URL::site('bbs/view' . $un['type'] . '?id=' . $un['id']) ?>" target="_blank" title="<?= $un['title'] ?>" class="unit_title"><?= Text::limit_chars($un['title'], 17, '...') ?></a></td>
                            <td class="center"><span style="color:#009900"><?= $un['reply_num'] ?></span>/<?= $un['hit'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <p class="more" style="margin:10px"><a href="<?= URL::site('bbs/list') ?>">>>更多</a></p>
        <!--//bbs-->
    </div>

    <div class="fright">
        <p class="sidebar_title" style="border-width:1px">年度捐赠鸣谢</p>
        <div class="sidebar_box" style="background:#F6FAFB">
            <div id="marquee2">
                <ul class="donate_tks" >
                    <?php foreach ($statistics AS $s): ?>
                        <li><a style="float:left"><?= Text::limit_chars($s['donor'], 8, '...') ?></a><span style="float:right;"><?= $s['amount'] ?></span></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <p class="more" style="margin-top:10px"><a href="<?= URL::site('donate/thanks') ?>">>>更多</a></p>
        </div>
    </div>
    <div class="clear"></div>
</div>
<!--//row4 -->

<!--more links-->
<div id="more_links">

    <div class="logo">
        支持单位：
        <?php foreach ($logo_links as $l): ?>
            <?php $banner = Model_Links::LOGO_PATH . $l['id'] . '.jpg'; ?>
            <a href="<?= $l['url'] ?>" target="_blank"><img src="<?= URL::base() . $banner ?>" class="img_boder" style="margin-right:6px; vertical-align: middle"/></a>
        <?php endforeach; ?>
    </div>
    <div class="text">
        校内链接：<?php foreach ($text_links1 as $l): ?><a href="<?= $l['url'] ?>" target="_blank"><?= $l['name'] ?></a>&nbsp;|&nbsp;<?php endforeach; ?>
        <br />
        校外链接：<?php foreach ($text_links2 as $l): ?><a href="<?= $l['url'] ?>" target="_blank"><?= $l['name'] ?></a>&nbsp;|&nbsp;<?php endforeach; ?>
    </div>
</div>
<!--//more links-->
<!--//body -->

<?php
$focus_title = null;
if (count($news_pics) > 0 AND empty($fixed_img)) {
    //设置焦点新闻图片及标题
    foreach ($news_pics as $np) {
        $focus_title = empty($focus_title) ? $np['title'] : $focus_title . '|' . $np['title'];
        $focus_url = empty($focus_url) ? URL::site('news/view?id=' . $np['id']) : $focus_url . '|' . URL::site('news/view?id=' . $np['id']);
        $focus_imgs = empty($focus_imgs) ? $np['img_path'] : $focus_imgs . '|' . $np['img_path'];
    }
}
?>

<script type="text/javascript">

            var c = '', _ = Function;
            with (o = document.getElementById("div1")) {
                innerHTML += innerHTML;
                onmouseover = _("c=1");
                onmouseout = _("c=0");
            }
            (F = _("if(#%41||!c)#++,#%=o.scrollHeight>>1;setTimeout(F,#%41?10:4000);".replace(/#/g, "o.scrollTop")))();

            readyScript.body = function() {

                //校友会
                $('#aa_club_tab li').mouseover(function() {
                    var curLink = $(this).find('a').addClass('cur');
                    $('#aa_club_content_' + curLink.attr('id')).show();
                    $.each($(this).siblings(), function() {
                        var otherLink = $(this).find('a').removeClass('cur');
                        $('#aa_club_content_' + otherLink.attr('id')).hide();
                    });
                });
                //切换照片
                $('#photo_tabs li[tag]').mouseover(function() {
                    var curLink = $(this).find('a').addClass('cur');
                    $('#photocontent_' + curLink.attr('id')).show();
                    $.each($(this).siblings(), function() {
                        var otherLink = $(this).find('a').removeClass('cur');
                        $('#photocontent_' + otherLink.attr('id')).hide();
                    });
                    //延迟加载活动照片
                    if (curLink.attr('id') === 'photo2') {
                        if ($('#demo4').html() === '') {
                            $('#demo4').html('<?= $event_pic_html ?>').fadeIn(500);
                            setTimeout(function() {
                                sHome.toleft("demo3", "demo4", "demo5", 30, "events_pics");
                            }, 1000);
                        }
                        ;
                    }
                });
                //切换话题
                $('#bbs_tabs li').mouseover(function() {
                    var curLink = $(this).find('a').addClass('cur');
                    $('#bbscontent_' + curLink.attr('id')).show();
                    $.each($(this).siblings(), function() {
                        var otherLink = $(this).find('a').removeClass('cur');
                        $('#bbscontent_' + otherLink.attr('id')).hide();
                    });
                });

<?php if ($focus_title): ?>sHome.focusNews({titles: '<?= preg_replace('/\s*/', '', $focus_title) ?>', imgs: '<?= $focus_imgs ?>', urls: '<?= $focus_url ?>'});<?php endif; ?>
                setTimeout(function() {
                    sHome.toleft("demo", "demo1", "demo2", 30, "old_pics");
                    $("#scr1 ul").RollTitle({line: 2, speed: 500, timespan: 1500});
                    $("#marquee2 .donate_tks").RollTitle({line: 8, speed: 400, timespan: 1500});
                }, 1500);
            };
</script>
