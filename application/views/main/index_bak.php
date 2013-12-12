<!-- main/index:_body -->
<script type="text/jscript">
        function setTab(name,cursel,n){
        for(i=1;i<=n;i++){
        var menu=document.getElementById(name+i);
        var con=document.getElementById("con_"+name+"_"+i);
        menu.className=i==cursel?"cur":"";
        con.style.display=i==cursel?"block":"none";
        }
        }
</script>

<!--body -->

<div id="header">
        <div class="menu"></div>
</div>
<!--row1:bannern and login-->
<div class="row">
        <!--focus news -->
        <!--top_topic -->
        <div id="banner">
                <?php if ($fixed_img): ?>
                        <?php if ($fixed_img['redirect']): ?>
                                <a href="<?= $fixed_img['redirect'] ?>"><img src="<?= str_replace('_s', '', $fixed_img['img_path']) ?>"></a>
                        <?php else: ?>
                                <img src="<?= str_replace('_s', '', $fixed_img['img_path']) ?>">
                        <?php endif; ?>
                <?php endif; ?>
        </div>
        <?php if (count($news_pics) > 0 AND empty($fixed_img)): ?>
                <script type="text/javascript" src="/static/swfobject_source.js"></script>
                <?php
                //设置焦点新闻图片及标题
                foreach ($news_pics as $np) {
                        $focus_title = empty($focus_title) ? $np['title'] : $focus_title . '|' . $np['title'];
                        $focus_url = empty($focus_url) ? URL::site('news/view?id=' . $np['id']) : $focus_url . '|' . URL::site('news/view?id=' . $np['id']);
                        $focus_imgs = empty($focus_imgs) ? $np['img_path'] : $focus_imgs . '|' . $np['img_path'];
                }
                ?>

                <script language="javascript" type="text/javascript">
                        var titles = '<?= preg_replace('/\s*/', '', $focus_title) ?>';
                        var imgs='<?= $focus_imgs ?>';
                        var urls='<?= $focus_url ?>';
                        var pw = 663;
                        var ph = 185;
                        var sizes = 14;
                        var Times = 4000;
                        var umcolor = 0xFFFFFF;
                        var btnbg =0xFF7E00;
                        var txtcolor =0xFFFFFF;
                        var txtoutcolor = 0x000000;
                        var flash = new SWFObject('/static/focus65.swf', 'mymovie', pw, ph, '7', '');
                        flash.addParam('allowFullScreen', 'true');
                        flash.addParam('allowScriptAccess', 'always');
                        flash.addParam('quality', 'high');
                        flash.addParam('wmode', 'Transparent');
                        flash.addVariable('pw', pw);
                        flash.addVariable('ph', ph);
                        flash.addVariable('sizes', sizes);
                        flash.addVariable('umcolor', umcolor);
                        flash.addVariable('btnbg', btnbg);
                        flash.addVariable('txtcolor', txtcolor);
                        flash.addVariable('txtoutcolor', txtoutcolor);
                        flash.addVariable('urls', urls);
                        flash.addVariable('Times', Times);
                        flash.addVariable('titles', titles);
                        flash.addVariable('imgs', imgs);
                        flash.write('banner');
                </script>
                <!--//top_topic -->
        <?php endif; ?>
        <!--//focus news -->

        <?php if (!$_UID): ?>
                <!--login -->
                <?= View::factory('main/index_login') ?>
                <!--//login -->
        <?php else: ?>
                <?= View::factory('main/index_qlink') ?>
        <? endif ?>
        <div class="clear"></div>
</div>
<!--//row1 -->
<!--row2:alumni、 news... -->
<div class="row">
        <!--alumni event -->
        <div id="alumni_event">

                <div class="small_tab" >
                        <ul>
                                <li><a href="<?= URL::site('aa') ?>" id="one1"  onmouseover="setTab('one',1,3)" class="cur" >地方校友会</a></li>
                                <li><a href="#" id="one2"  onmouseover="setTab('one',2,3)"  >院系分会</a></li>
                                <li><a href="#" id="one3" onmouseover="setTab('one',3,3)" >俱乐部</a></li>
                        </ul>
                </div>


                <div id="con_one_1" class="tab_content">

                        <div id="marquee">
                                <div id="scr1">
                                        <ul>
                                                <?php foreach ($aa as $i => $aa): if ($aa['mcount'] > 0): ?>
                                                                <li><a href="<?= URL::site('aa_home?id=' . $aa['id']) ?>" target="'_blank"><?= $aa['name'] ?></a><span><? // $aa['mcount']    ?></span></li>
                                                        <?php endif;
                                                endforeach; ?>
                                        </ul>
                                </div>
                        </div>

                        <p class="more"><a href="/aa/">>>更多</a></p>
                </div>
                <script type="text/javascript">
                        function marquee(marquee_name,scr_name,height,speed,delay){
                                var scrollT;
                                var pause = false;
                                var ScrollBox = document.getElementById(marquee_name);
                                if(document.getElementById(scr_name).offsetHeight <= height) return;
                                var _tmp = ScrollBox.innerHTML.replace(scr_name, scr_name+'_2')
                                ScrollBox.innerHTML += _tmp;
                                ScrollBox.onmouseover = function(){pause = true}
                                ScrollBox.onmouseout = function(){pause = false}
                                ScrollBox.scrollTop = 0;
                                function start(){
                                        scrollT = setInterval(scrolling,speed);
                                        if(!pause) ScrollBox.scrollTop += 2;
                                }
                                function scrolling(){
                                        if(ScrollBox.scrollTop % height != 0){
                                                ScrollBox.scrollTop += 2;
                                                if(ScrollBox.scrollTop >= ScrollBox.scrollHeight/2) ScrollBox.scrollTop = 0;
                                        }
                                        else{
                                                clearInterval(scrollT);
                                                setTimeout(start,delay);
                                        }
                                }
                                setTimeout(start,delay);
                        }
                        marquee('marquee','scr1',72,30,3000);
                </script>
                <div id="con_one_2" class="tab_content" style="display:none">
                        <ul style="margin:4px 8px">
                                <?php foreach ($institution as $i => $aa): ?>
                                        <li><a href="<?= URL::site('aa_home?id=' . $aa['id']) ?>" target="'_blank"><?= $aa['name'] ?></a><span><? // $aa['mcount']    ?></span></li>
                                <?php endforeach; ?>
                        </ul>
                        <p class="more"><a href="/aa/">>>更多</a></p>
                </div>
                <div id="con_one_3" class="tab_content" style="display:none">
                        <ul>
                                <li><a href="http://info.hi-chance.org/html/index.html" target="_blank">北航海创俱乐部</a></li>
                                <li><a href="http://www.zuef.zju.edu.cn/hotelClub/" target="_blank">北航校友酒店俱乐部</a></li>
                                <li><a href="<?= URL::site('club_home?id=0&club_id=19') ?>"  target="_blank">北航校友不动产俱乐部</a></li>
                        </ul>
                        <p class="more"><a href="/aa/">>>更多</a></p>
                </div>


                <p class="sidebar_title" style="border-width:1px 0">推荐活动</p>
                <?php if (!$events): ?>
                        <p class="nodata" style="padding:15px">暂无推荐活动</p>
                <?php endif; ?>
                <div style=" padding:10px 13px">
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
                                                <span style="color:#666"><?= date('n月d日', strtotime($e['start'])); ?> - <?= $e['aa_name'] ?>校友会</span>

                                        </div>
                                </div>
                        <? endforeach; ?>
                        <p class="more"><a href="/event">>>更多</a></p>
                </div>


        </div>
        <!--//alumni event-->

        <?php if ($news_special): ?>
                <?=
                View::factory('main/sinaweibo', array(
                    'weibolist' => $weibolist,
                    'weibo_comments'=>$weibo_comments,
                    'top_news'=>$top_news,
                    'news_special'=>$news_special,
                    'special_album'=>$special_album,
                ));
                ?>
        <?php else: ?>
                <?=
                View::factory('main/home_news', array(
                    'top_news' => $top_news,
                    'news_list' => $news_list,
                ));
                ?>
        <?php endif; ?>


<? //View::factory('main/home_news', array(            'top_news' => $top_news,            'news_list' => $news_list, ));   ?>

        <!--Professor and alumni-->
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

                <p class="sidebar_title" style="border-width:1px 0">主题活动</p>

                <div style="padding:10px">
                        <?php if (!$static): ?>
                                <p class="nodata" >暂无活动</p>
                        <?php endif; ?>
                                <?php foreach ($static as $e): ?>
                                        <?php if (empty($e['redirect'])): ?>
                                        <a href="<?= URL::site('event/static?id=' . $e['id']) ?>" target="_blank"><?php else: ?><a href="<?= $e['redirect'] ?>" target="_blank"><?php endif; ?><img src="<?= $e['img_path'] ?>" width="220" height="70" style="margin-bottom:10px"/></a>
<?php endforeach; ?>

                                <p class="more" style="margin:5px 0"><a href="<?= URL::site('event') ?>">>>更多</a></p>
                </div>

        </div>
        <!--//Professor alumni-->
        <div class="clear"></div>
</div>
<!--//row2 -->

<!--//row3-->
<div class="big_tab_title">
        <ul>
                <li><a href="javascript:void(0)" id="two1"  onmouseover="setTab('two',1,2);setMorePhoto('morephoto1')" class="cur">岁月流金</a></li>
                <li><a href="javascript:void(0)" id="two2" onmouseover="setTab('two',2,2);setMorePhoto('morephoto2')">活动照片</a></li>
                <li style="color: #487EB2;line-height: 25px;padding:0px 50px 0 20px;margin-right: 50px;font-size:10px"><marquee style="width:400px;" scrollamount=2 >一个镜头记录一个瞬间，一张照片留存一段记忆。让我们再次重温求是园里久违的片段，分享那些过往的故事…</marquee></li>
        </ul>
        <p style=" text-align: right; padding: 15px 20px 0 0; color: #5F9BD6" id="morephoto1"><a href="<?= URL::site('album/uploadPic?id=59&enc=' . base64_encode(date('d')) . '=') ?>" target="_blank">上传照片</a> | <a href="<?= URL::site('album/picIndex?id=59') ?>" target="_blank">更多...</a></p>
        <p style=" text-align: right; padding: 15px 20px 0 0; color: #5F9BD6; display: none" id="morephoto2"></p>
        <script type="text/javascript">
                function setMorePhoto(obj){
                        document.getElementById('morephoto1').style.display='none';
                        document.getElementById('morephoto2').style.display='none';
                        document.getElementById(obj).style.display='';
                }
        </script>
</div>
<div class="tab_content_photo" id="con_two_1">
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
                                                                        <td width="130" height="110"><p class="a_pic"><a href="<?= URL::site('album/picView?id=' . $p['Pic']['id']) ?>" target="_blank"><img src="<?= URL::base() . $p['Pic']['img_path'] ?>" width="105" height="79" /></a></p></td>
        <?php endforeach; ?>
                                                        </tr>
                                                </table></td>
                                        <td id="demo2" valign="top"></td>
                                </tr>
                        </table>
                </div>
<?php endif; ?>
</div>

<div class="tab_content_photo" id="con_two_2" style="display:none">
        <?php if (!$event_pic): ?>
                <div class="nodata">暂时还没有活动照片。</div>
<?php else: ?>
                <div id="demo3" style="overflow: hidden; width: 895px; height: 110px">
                        <table cellpadding="0" align="left" border="0" cellspacing="0">
                                <tr>
                                        <td id="demo4" valign="top">
                                                <table cellspacing="0" cellpadding="0" width="322"  border="0">
                                                        <tr align="center">
                                                                <?php foreach ($event_pic as $p): ?>
                                                                        <td width="130" height="110"><p class="a_pic"><a href="<?= URL::site('album/picView?id=' . $p['Pic']['id']) ?>" target="_blank"><img src="<?= URL::base() . $p['Pic']['img_path'] ?>" width="105" height="79" /></a></p></td>
        <?php endforeach; ?>
                                                        </tr>
                                                </table></td>
                                        <td id="demo5" valign="top"></td>
                                </tr>
                        </table>
                </div>
<?php endif; ?>
</div>
<script type="text/javascript">
        toleft("demo","demo1","demo2",30,"old_pics");
        toleft("demo3","demo4","demo5",30,"events_pics");

        function toleft(demo,demo1,demo2,speed,flag){
                demo=$(demo);demo1=$(demo1);demo2=$(demo2)
                demo2.innerHTML=demo1.innerHTML
                function Marquee(){
                        if(demo2.offsetWidth-demo.scrollLeft<=0){
                                demo.scrollLeft-=demo1.offsetWidth
                        }
                        else{
                                demo.scrollLeft++
                        }
                }
                flag=setInterval(Marquee,speed)
                demo.onmouseover=function(){clearInterval(flag);}
                demo.onmouseout=function(){flag=setInterval(Marquee,speed);}
        }
</script>

<!--//row3-->

<!--row4 -->
<div id="row4" style="margin-top:10px">
        <div class="left">
                <!--new alumni-->

                <p class="sidebar_title" style="border-width:1px">最新加入校友</p>
                <div id="new_alumni">
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
                                <li><a href="javascript:void(0)" id="bbstab1"  onmouseover="setTab('bbstab',1,2)" class="cur">最新话题</a></li>
                                <li>/</li>
                                <li><a href="javascript:void(0)" id="bbstab2" onmouseover="setTab('bbstab',2,2)">热门话题</a></li>
                        </ul>
                </div>
                <div id="con_bbstab_1" style="height: 330px">
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
                <div id="con_bbstab_2" style="display:none;height: 330px">
                        <table border="0" id="home_bbs" width="100%" cellspacing="0" cellpadding="0">
                                <tbody>
<?php foreach ($hot_topic as $key => $un): ?>
                                                <tr <?= ($key + 1) % 2 == 0 ? 'class="two"' : '' ?>>
                                                        <td><span class="aa_bbs">[<?php if ($un['aa_id']): ?><a href="<?= URL::site('bbs/list?aid=' . $un['aa_id']) ?>"><?= $un['aa_name'] ?></a><?php else: ?><a href="<?= URL::site('bbs/list') ?>">公共</a><?php endif; ?>]</span><a href="<?= URL::site('bbs/view' . $un['type'] . '?id=' . $un['id']) ?>" target="_blank" title="<?= $un['title'] ?>" class="unit_title"><?= Text::limit_chars($un['title'], 17, '...') ?></a></td>
                                                        <td class="center"><span style="color:#009900"><?= $un['reply_num'] ?></span>/<?= $un['hit'] ?></td>
                                                </tr>
<?php endforeach; ?>
                                </tbody>
                        </table>	
                </div>
                <p class="more" style="margin:10px"><a href="<?= URL::site('bbs') ?>">>>更多</a></p>
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
                <script type="text/javascript">
                        var $j = jQuery.noConflict();  
                        //滚动插件
                        (function($){
                                $j.fn.extend({
                                        Scroll:function(opt,callback){
                                                //参数初始化
                                                if(!opt) var opt={};
                                                var _this=this.eq(0).find("ul:first");
                                                var  lineH=_this.find("li:first").height(), //获取行高
                                                line=opt.line?parseInt(opt.line,10):parseInt(this.height()/lineH,10), //每次滚动的行数，默认为一屏，即父容器高度
                                                speed=opt.speed?parseInt(opt.speed,10):500, //卷动速度，数值越大，速度越慢（毫秒）
                                                timer=opt.timer?parseInt(opt.timer,10):3000; //滚动的时间间隔（毫秒）
                                                if(line==0) line=1;
                                                var upHeight=0-line*lineH;
                                                //滚动函数
                                                scrollUp=function(){
                                                        _this.animate({
                                                                marginTop:upHeight
                                                        },speed,function(){
                                                                for(i=1;i<=line;i++){
                                                                        _this.find("li:first").appendTo(_this);
                                                                }
                                                                _this.css({marginTop:0});
                                                        });
                                                }
                                                //鼠标事件绑定
                                                _this.hover(function(){
                                                        clearInterval(timerID);
                                                },function(){
                                                        timerID=setInterval("scrollUp()",timer);
                                                }).mouseout();
                                        }
                                })
                        })(jQuery);
                        $j(document).ready(function(){
                                jQuery("#marquee2").Scroll({line:5,speed:1000,timer:3000});
                        });
                </script>
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