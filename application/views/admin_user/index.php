<!-- admin_user/index:_body -->
<style type="text/css">
    #user_filter{ text-align: right; }
    #user_filter a{ margin: 0 3px; }
    #user_filter a.cur{ font-weight: bold; }
    #user_list{  }
    #user_list td{ vertical-align: middle; padding:10px 5px }
    tr.oktr td{ background-color:#DBFCE3};
    #alumni_ul li:hover{ background-color: #f5f5f5}
    #alumni_ul li a:link,#alumni_ul li a:visited{color: #999}
    #alumni_ul li a:hover{color: #333}
</style>

<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" style="margin-top:9px ">
    <tr>
        <td height="29" class="td_title" >

            <div class="title_name"><b>快速浏览：</b></div>
            <div class="title_search">
                <form action="<?= URL::site('admin_user/index') ?>" method="get">
                    <select name="role" style="padding:1px 4px;">
                        <option value="" >所有</option>
                        <?php foreach ($roles as $key => $r): ?>
                            <option value="<?= $r ?>" <?= $role == $r ? 'selected' : ''; ?> style="color:<?= $roles_color[$key] ?>"><?= $r ?></option>
                        <?php endforeach; ?>
                        <option value="已与档案挂钩" <?= $role == '已与档案挂钩' ? 'selected' : ''; ?> style="color:#007219">已与档案挂钩</option>
                        <option value="未与档案挂钩" <?= $role == '未与档案挂钩' ? 'selected' : ''; ?> style="color:#c00">未与档案挂钩</option>
                    </select>

                    <select name="search_type" style="padding:1px 4px;">
                        <option value="realname" <?= $search_type == 'realname' ? 'selected' : ''; ?>>按姓名</option>
                        <option value="account" <?= $search_type == 'account' ? 'selected' : ''; ?>>按邮箱</option>
                        <option value="uid" <?= $search_type == 'uid' ? 'selected' : ''; ?>>按注册ID</option>
                        <option value="city" <?= $search_type == 'city' ? 'selected' : ''; ?>>按当前所在城市</option>
                        <option value="student_no" <?= $search_type == 'student_no' ? 'selected' : ''; ?>>按学号</option>
                        <option value="file_no" <?= $search_type == 'file_no' ? 'selected' : ''; ?>>按档案编号</option>
                        <option value="start_year" <?= $search_type == 'start_year' ? 'selected' : ''; ?>>按入学年份</option>
                        <option value="finish_year" <?= $search_type == 'finish_year' ? 'selected' : ''; ?>>按毕业年份</option>
                        <option value="speciality" <?= $search_type == 'speciality' ? 'selected' : ''; ?>>按专业关键字</option>z
                    </select>
                    <input name="q" type="text" style="width:200px;margin:0" class="keyinput" value="<?= $q ?>">
                    <input type="submit" value="搜索">
                </form>
            </div>
        </td>
    </tr>
    <tr>
        <td height="25" style="padding:0px 10px" >
            <a class="<?= $role ? '' : 'cur' ?>" href="<?= URL::site('admin_user/index') ?>">所有</a>
            <?php foreach ($roles as $key => $r): ?>
                &nbsp;|&nbsp;<a class="<?= $role == $r ? 'cur' : '' ?>" href="<?= URL::site('admin_user/index?role=' . urlencode($r)) ?>" style="color:<?= $roles_color[$key] ?>"><?= $r ?></a>
            <?php endforeach; ?>
            &nbsp;|&nbsp;<a class="<?= $role == '已与档案挂钩' ? 'cur' : '' ?>" href="<?= URL::site('admin_user/index?role=已与档案挂钩') ?>" style="color:#006600">已与档案挂钩</a>
            &nbsp;|&nbsp;<a class="<?= $role == '未与档案挂钩' ? 'cur' : '' ?>" href="<?= URL::site('admin_user/index?role=未与档案挂钩') ?>"  style="color:#c00">未与档案挂钩</a>
        </td>
    </tr>
</table>

<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" style="margin-top:0px ">
    <tr>
        <td colspan="8" class="td_title">注册校友</td>
    </tr>
    <tr>
        <td colspan="2">用户(找到<?= $pager->total_items ?>人)</td>
        <td style="text-align:center">注册时间</td>
        <td style="text-align:center">评论/话题</td>
        <td style="text-align:center">身份</td>
        <td style="text-align:center">已与档案挂钩</td>
        <td style="text-align:center">编辑资料</td>
        <td style="text-align:center">删除</td>
    </tr>

    <?php if (count($users) == 0): ?>
        <tr class="even_tr">
            <td colspan="8" >
                <div class="nodata" style="padding:10px;">暂无记录</div>
            </td>
        </tr>
    <?php endif; ?>

    <?php foreach ($users as $key => $u): ?>
        <tr id="user_<?= $u['id'] ?>" class="<?php
    if (($key) % 2 == 0) {
        echo'even_tr';
    }
        ?>">
            <td width="50" style="padding:5px 15px">
                <a href="javascript:userDetailAdmin(<?= $u['id'] ?>,'<?= $u['role'] ?>')" title="浏览详细信息"><img src="<?= Model_User::avatar($u['id'],48,$u['sex']) ?>" style="border-width:0"></a>
            </td>
            <td>
                <a href="<?= URL::site('user_home?id=' . $u['id']) ?>" target="_blank"><?= $u['realname'] ?></a><br />
                <span style="color:<?= $u['actived'] == true ? 'green' : 'grey' ?>"><?= $u['account'] ?>
                    <?= $u['actived']==False&&$u['is_sended_active']==False? '(未发激活)' : '' ?>
                    <?= $u['actived']==False&&$u['is_sended_active']==True? '(已发激活邮件)' : '' ?>
                </span>
                <br />
                <?= $u['mobile'] ? '' . $u['mobile'] . '<br>' : ''; ?>
                <?= $u['city'] ? $u['city'] . ',' : '' ?>
                <?= $u['start_year'] ? $u['start_year'] . '级' : ''; ?><?= $u['speciality'] ?>，
                <?= Date::span_str(strtotime($u['login_time'])) ?>前登录
            </td>
            <td class="center" title="<?= $u['reg_at'] ?>">
                <?= Date::span_str(strtotime($u['reg_at'])) ?>前
            </td>
            <td class="center">
                <a href="<?= URL::site('admin_bbs/comment?search_type=author&user_id=' . $u['id'] . '&q=' . $u['realname']) ?>"><?= $u['comment_num'] ?></a>/<a href="<?= URL::site('admin_bbs?search_type=author&user_id=' . $u['id'] . '&q=' . $u['realname']) ?>"><?= $u['bbs_unit_num'] ?></a>
            </td>
            <td class="center">
                <select name="role" onchange="userRole(<?= $u['id'] ?>, this.value)" id="role_<?= $u['id'] ?>">
                    <option value=""  style="color:#999">----</option>
                    <?php foreach ($roles as $key => $res): ?>
                        <option <?= $u['role'] == $res ? 'selected' : '' ?> value="<?= urlencode($res) ?>"  style="color:<?= $roles_color[$key] ?>"><?= $res ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td class="center">
                <?php if ($u['file_no']): ?>
                    <img src="/static/images/li_ok.gif" />
                <?php else: ?>
                    <img src="/static/images/static3.gif" />
                <?php endif ?>
            </td>
            <td class="center" width="100">
                <a href="<?= URL::site('admin_user/form?id=' . $u['id']) ?>">编辑</a>
            </td>
            <td class="center" width="100">
                <a href="javascript:del(<?= $u['id'] ?>)">删除</a>
            </td>
        </tr>
    <?php endforeach; ?>


</table>
<table class="admin_table" width="100%" border="0" cellpadding="0" cellspacing="1" style="margin-top:-1px " >
    <tr>
        <td style="height: 50px"><?= $pager ?></td>
    </tr>
</table>
<script type="text/javascript">
    function userMemo(uid){
        var box = new Facebox({
            url: '<?= URL::site('admin_user/set/memo?id=') ?>'+uid,
            ok: function(){
                new ajaxForm('user_memo', {callback:function(){
                        box.close();
                    }}).send();
            }
        });
        box.show();
    }

    var shenhefacebox;
    function userDetailAdmin(uid,role){
        var button_name=null;
        var ok=false;
        if(role=='校友(未审核)'){
            button_name='设为校友(已认证)';
            ok=function(){
                new ajaxForm('user_info', {callback:function(){
                        box.close();
                        window.location.reload();
                    }}).send();
            }
        }
        shenhefacebox=new Facebox({
            title:'查看资料',
            url: '<?= URL::site('user/userDetail?webmanager=1&role=' . $role . '&file_no=' . $file_no . '&page=' . Arr::get($_GET, 'page') . '&id=') ?>'+uid,
            width:'750px',
            cancelVal:'关闭',
            okVal:button_name,
            ok: ok
        }).show();
    }

    function userPassChange(uid){
        var box = new Facebox({
            url: '<?= URL::site('admin_user/set/password?id=') ?>'+uid,
            ok: function(){
                new ajaxForm('user_password', {callback:function(){
                        box.close();
                    }}).send();
            }
        });
        box.show();
    }

    function userRole(uid, val){
        if(val!=''){
            new Request({
                url: '<?= URL::site('admin_user/setRole') ?>?id='+uid+'&val='+val,
                success: function(){}
            }).send()
        }
    }

    function del(cid){
        var b = new Facebox({
            title: '删除确认！',
            message: '确定要删除此注册账号吗？<br>注意删除后将同时删除该用户所有关联的信息。',
            icon:'question',
            ok: function(){
                new Request({
                    url: '<?= URL::site('admin_user/del?cid=') ?>'+cid,
                    type: 'post',
                    success: function(){
                        candyDel('user_'+cid);
                    }
                }).send();
                b.close();
            }
        });
        b.show();
    }

    //设置挂钩
    function setAlumni(user_id,alumni_id) {
        new Request({
            url: '/admin_user/setHook',
            type: 'post',
            data: 'user_id='+user_id+'&alumni_id='+alumni_id,
            beforeSend:function(){
                $('#span_alumni_'+alumni_id).html('&nbsp;<img src="/static/images/loading.gif" >');
            },
            success:function(data){
                shenhefacebox.close();
                var usertr=$('#user_'+user_id);
                usertr.addClass('oktr');
                usertr.fadeOut(400);
                window.location.reload();
            }
        }).send();
    }
</script>