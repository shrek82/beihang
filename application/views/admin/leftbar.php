<!doctype html public "-/w3c/dtd html 4.0 transitional/en">
<html><head><title>myspace</title>
        <meta http-equiv=content-type content="text/html; charset=utf-8">
        <style type="text/css">
            body {
                margin: 0px; overflow: hidden
            }
            .left_color {
                text-align: right
            }
            .left_color ul { margin:0px; float:right; margin-right:2px}
            .left_color li{ list-style:none; text-align: right; margin-bottom:1px}
            .left_color li a{ display:block;font-size: 12px; background: url(/static/images/admin/menubg.gif) no-repeat 0px 0; width:100px; color: #083772; line-height: 23px; height: 23px; text-decoration: none; text-align:left; border:0px solid #f60; padding-left:30px; padding-right:20px; text-align:center
            }
            .left_color li a.ahover2{background: url(/static/images/admin/menubg_hover.gif) no-repeat 0 0; color:#7b2e00}

            img {
                float: none; vertical-align: middle
            }
            #on {
                font-weight: bold; background: #fff   url(/static/images/admin/menubg_on.gif) no-repeat right 50%; color: #f20
            }
            hr {
                border-top: #46a0c8 1px solid; width: 90%; height: 0px; text-align: left; size: 0
            }
        </style>

        <script type="text/javascript">
        <!--
            function disp(id) {
                var $menubar = $("#menubar div");
                $menubar.css('display', 'none');
                $("#" + id).css('display', 'block');
                $("#" + id + " a").removeClass('ahover2');
                $("#" + id + " a:first").addClass('ahover2');
            }
        //-->
        </script>
        <script type="text/javascript" src="/static/js/jquery-1.4.2.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {

                var $a = $("a");
                $a.click(function() {
                    $("li a").removeClass('ahover2');
                    $(this).addClass('ahover2');
                });

            });

            function selectMenux(parentobj, menuobj) {
                $parent = $('#left' + parentobj);
                $("li a", $parent).removeClass('ahover2');
                $("#left" + parentobj + '_' + menuobj, $parent).addClass('ahover2');
            }
        </script>

        <meta content="mshtml 6.00.6000.20815" name=generator></head>
    <body>
        <table class="admin_table" height="100%" cellspacing=0 cellpadding=0 width="100%" border=0>
            <tbody>
                <tr>
                    <td class="left_color" id="menubar" style="padding-top: 10px" valign=top>
                        <div id="nav_home">
                            <ul>
                                <li><a href="<?= URL::site('admin/count') ?>" target="frmright" id="left0_1" >网站统计</a></li>
                                <li><a href="<?= URL::site('admin_config') ?>" target="frmright" id="left0_1" >网站设置</a></li>
                                <li><a href="<?= URL::site('admin/filter') ?>" target="frmright" id="left0_7" >词语过滤</a></li>
                                <li><a href="<?= URL::site('admin_log') ?>" target="frmright" id="left0_9" >管理日志</a></li>
                            </ul>
                        </div>

                        <div id="nav_admin_news" style="display: none">
                            <ul>
                                <li><a  href="<?= URL::site('admin_news/index') ?>"  target="frmright" >新闻管理</a></li>
                                <li><a  href="<?= URL::site('admin_news/form') ?>"  target="frmright" >添加新闻</a></li>
                                <li><a  href="<?= URL::site('admin_news/category') ?>"  target="frmright" >新闻分类</a></li>
                                <li><a  href="<?= URL::site('admin_news/special') ?>"  target="frmright" >新闻专题</a></li>
                                <li><a  href="<?= URL::site('admin_content/index?type=17') ?>"  target="frmright" >首页静态图片</a></li>
                            </ul>
                        </div>

                        <div id="nav_admin_user" style="display: none">
                            <ul>
                                <li><a  href="<?= URL::site('admin_user/index') ?>"  target="frmright" >注册校友</a></li>
                                <li><a  href="<?= URL::site('admin_aa/applyManager') ?>"  target="frmright" >加入校友会申请</a></li>
                                <li><a  href="<?= URL::site('admin_classroom/applyManager') ?>"  target="frmright" >加入班级申请</a></li>
                                <li><a  href="<?= URL::site('admin_user/alumni') ?>"  target="frmright" >档案管理</a></li>
                                <li><a href="<?= URL::site('admin_log/index?loginfo=user_id') ?>" target="frmright" id="left0_4" >管理日志</a></li>
                            </ul>
                        </div>

                        <div id="nav_admin_event" style="display: none">
                            <ul>
                                <li><a  href="<?= URL::site('admin_event/index') ?>"  target="frmright" >活动列表</a></li>
                                <li><a  href="<?= URL::site('admin_event/static') ?>"  target="frmright" >专题活动</a></li>
                            </ul>
                        </div>

                        <div id="nav_admin_mainaa" style="display: none">
                            <ul>
                                <li><a  href="<?= URL::site('admin_mainaa/main') ?>"  target="frmright" >基本信息</a></li>
                                <li><a  href="<?= URL::site('admin_mainaa/index') ?>"  target="frmright" >详细介绍</a></li>
                                <li><a  href="<?= URL::site('admin_mainaa/form') ?>"  target="frmright" >添加介绍</a></li>
                            </ul>
                        </div>

                        <div id="nav_admin_aa" style="display: none">
                            <ul>
                                <li><a  href="<?= URL::site('admin_aa/index') ?>"  target="frmright" >地方校友会</a></li>
                                <li><a  href="<?= URL::site('admin_aa/index?class=学院') ?>"  target="frmright" >学院校友会</a></li>
                                <li><a  href="<?= URL::site('admin_aa/form') ?>"  target="frmright" >添加校友会</a></li>
                                <li><a  href="<?= URL::site('admin_aa/applyManager') ?>"  target="frmright" >加入申请</a></li>
                                <li><a  href="<?= URL::site('admin_club/index') ?>"  target="frmright" >俱乐部</a></li>
                            </ul>
                        </div>

                        <div id="nav_admin_classroom" style="display: none">
                            <ul>
                                <li><a  href="<?= URL::site('admin_classroom/index') ?>"  target="frmright" >班级录</a></li>
                                <li><a  href="<?= URL::site('admin_classroom/applyManager') ?>"  target="frmright" >加入申请</a></li>
                                <li><a  href="<?= URL::site('admin_classroom/bbs') ?>"  target="frmright" >班级话题</a></li>
                            </ul>
                        </div>

                        <div id="nav_admin_bbs" style="display: none">
                            <ul>
                                <li><a  href="<?= URL::site('admin_bbs/index') ?>"  target="frmright" >所有话题</a></li>
                                <li><a  href="<?= URL::site('admin_bbs/comment') ?>"  target="frmright" >所有评论</a></li>
                                <li><a  href="<?= URL::site('admin_bbs/category') ?>"  target="frmright" >总会板块</a></li>
                                <li><a  href="<?= URL::site('admin_bbs/focus') ?>"  target="frmright" >幻灯片</a></li>
                            </ul>
                        </div>

                        <div id="nav_admin_album" style="display: none">
                            <ul>
                                <li><a  href="<?= URL::site('admin_album/index') ?>"  target="frmright" >校友相册</a></li>
                                <li><a  href="<?= URL::site('admin_album/old_photos') ?>"  target="frmright" >岁月流金</a></li>
                            </ul>
                        </div>

                        <div id="nav_admin_publication" style="display: none">
                            <ul>
                                <li><a  href="<?= URL::site('admin_publication/index') ?>"  target="frmright" >期刊管理</a></li>
                                <li><a  href="<?= URL::site('admin_publication/pubForm') ?>"  target="frmright" >新增期刊</a></li>
                                <li><a  href="<?= URL::site('admin_publication/article') ?>"  target="frmright" >文章管理</a></li>
                            </ul>
                        </div>


                        <div id="nav_admin_donate" style="display: none">
                            <ul>
                                <li><a  href="<?= URL::site('admin_donate/annual') ?>"  target="frmright" >捐赠统计</a></li>
                                <li><a  href="<?= URL::site('admin_donate/annualForm') ?>"  target="frmright" >添加统计</a></li>
                                <li><a  href="<?= URL::site('admin_donate/importAnnual') ?>"  target="frmright" >导入统计</a></li>
                                <li><a  href="<?= URL::site('admin_content/index?type=1') ?>"  target="frmright" >捐赠报道</a></li>
                                <li><a  href="<?= URL::site('admin_content/index?type=2') ?>"  target="frmright" >捐赠感言</a></li>
                                <li><a  href="<?= URL::site('admin_content/index?type=3') ?>"  target="frmright" >捐赠展示</a></li>
                                <li><a  href="<?= URL::site('admin_content/index?type=4') ?>"  target="frmright" >捐赠途径</a></li>

                            </ul>
                        </div>

                        <div id="nav_admin_content" style="display: none">
                            <ul>
                                <li><a  href="<?= URL::site('admin_content/index') ?>"  target="frmright" >内容管理</a></li>
                                <li><a  href="<?= URL::site('admin_content/form') ?>"  target="frmright" >添加内容</a></li>
                                <li><a  href="<?= URL::site('admin_content/category') ?>"  target="frmright" >分类管理</a></li>
                            </ul>
                        </div>

                        <div id="nav_admin_links" style="display: none">
                            <ul>
                                <li><a  href="<?= URL::site('admin_links/index') ?>"  target="frmright" >链接管理</a></li>
                                <li><a  href="<?= URL::site('admin_links/add') ?>"  target="frmright" >添加链接</a></li>
                            </ul>
                        </div>

                        <div id="nav_admin_people" style="display: none">
                            <ul>
                                <li><a  href="<?= URL::site('admin_people/index') ?>"  target="frmright" >院士风采</a></li>
                                <li><a  href="<?= URL::site('admin_people/president') ?>"  target="frmright" >历任校长</a></li>
                                <li><a  href="<?= URL::site('admin_people/presidentForm') ?>"  target="frmright" >新增校长</a></li>
                                <li><a  href="<?= URL::site('admin_people/newsForm') ?>"  target="frmright" >添加新闻</a></li>
                            </ul>
                        </div>

                        <div id="nav_admin_vote" style="display: none">
                            <ul>
                                <li><a  href="<?= URL::site('admin_vote/index') ?>"  target="frmright" >所有投票</a></li>
                                <li><a  href="<?= URL::site('admin_vote/form') ?>"  target="frmright" >增加投票</a></li>
                            </ul>
                        </div>


                    </td>
                </tr>
            </tbody>
        </table>
    </body>
</html>