<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <TITLE>管理中心 - <?= $_CONFIG->base['orgname'] ?></TITLE>
        <link href="/static/css/admin_frame.css" rel="stylesheet" type="text/css" />
        <style type="text/css">
            .main_left {table-layout:auto; background:url(/static/images/admin/left_bg.gif)}
            .main_left_top{ background:url(/static/images/admin/left_menu_bg.gif); padding-top:2px !important; padding-top:5px;}
            .main_left_title{text-align:left; padding-left:15px; font-size:14px; font-weight:bold; color:#fff;}
            .left_iframe{height: 92%; VISIBILITY: inherit;width: 180px; background:transparent;}
            .main_iframe{height: 92%; VISIBILITY: inherit; width:100%; Z-INDEX: 1}
            table { font-size:12px;  }
            td { font-size:12px; }
        </style>
    <body style="margin: 0px">
        <table class="admin_table" border=0 cellpadding=0 cellspacing=0 height="100%" width="100%" style="background:#C3DAF9;">
            <tbody>
                <tr>
                    <td height="52" colspan="3">
                        <iframe frameborder="0" id="frmtop" name="top" scrolling="no" src="<?= URL::site('admin/topbar') ?>" style="height: 100%; visibility: inherit;width: 100%;"></iframe>
                    </td>
                </tr>
                <tr>
                    <td height="30" colspan="3">
                        <table class="admin_table" width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr height="32">
                                <td background="/static/images/admin/bg2.gif" width="28" style="padding-left:30px;"><img src="/static/images/admin/arrow.gif" alt="" align="absmiddle" /></td>
                                <td background="/static/images/admin/bg2.gif"><span style="float:left"><?= date('Y年m月d日') ?></span><span style="color:#c00; font-weight:bold; float:left;width:300px;" id="dvbbsannounce"></span></td>
                                <td background="/static/images/admin/bg2.gif" style="text-align:right; color: #135294; padding-right:20px">管理员：<?= $_SESS->get('realname') ?>&nbsp;&nbsp;| <a href="<?= URL::site('main') ?>"  >网站首页</a>  </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td align="middle" id="frmTitle" valign="top" name="fmtitle" style="background:#c9defa">
                        <iframe frameborder="0" id="frmleft" name="frmleft" src="<?= URL::site('admin/leftbar') ?>" style="height: 100%; visibility: inherit;width: 185px;" allowtransparency="true"></iframe>
                    </td>
                    <td style="width:0px;" valign="middle">
                        <div>
                            <span class="navpoint" id="switchPoint" title="关闭/打开左栏"><img src="/static/images/admin/right.gif" alt="" /></span>
                        </div>
                    </td>
                    <td style="width: 85%" valign="top">
                        <iframe frameborder="0" id="frmright" name="frmright" scrolling="yes" src="<?= URL::site('admin/count') ?>" style="height: 100%; visibility: inherit; width:100%; z-index: 1"></iframe>
                    </td>
                </tr>
                <tr>
                    <td height="30" colspan="3">
                        <div style="color:#507EBF; text-align: center">Copyright © 2009-2011 友笑网络 版权所有</div>
                    </td>
                </tr>
            </tbody>
        </table>
        <div id="dvbbsannounce_true" style="display:none;">
        </div>
    </body>
</html>