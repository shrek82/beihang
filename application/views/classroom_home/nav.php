<!-- 专业班级导航 -->
<?php
$links = array(
    'classroom_home/index' => '首页',
    'classroom_home/bbs' => '论坛',
    'classroom_home/guestbook' => '留言板',
    // 'classroom_home/update' => '动态',
    'classroom_home/members' => '成员',
    'classroom_home/addressbook' => '通讯录',
    'classroom_home/album' => '班级相册',
    'classroom_home/photos' => '成员照片',
);
if ($_IS_MANAGER) {
    $links['classroom_admin'] = '管理班级';
}
?>
<div id="aa_top">
    <div class="left">
        <div><span style="font-size:22px; color: #333;font-family:'Microsoft YaHei','Microsoft JhengHei'"><?= $_CLASSROOM['speciality'] ?></span>
            <span style="color:#666;padding:0 10px"><?= $_CLASSROOM['institute'] ? ' -- ' . $_CLASSROOM['institute'] . '&nbsp;&nbsp;' : '' ?><?= $_CLASSROOM['start_year'] ?>级</span>
            <?php if (!$_CLASSROOM['verify']): ?>
                <span style="color:#f60">尚未通过审核</span>
            <?php endif; ?>
        </div>

        <div id="aa_home_nav">
            <?php foreach ($links as $url => $name): ?>
                <a href="<?= URL::site($url . '?id=' . $_CLASSROOM['id']) ?>" class="<?= strstr($_URI, $url) ? 'cur' : '' ?>"><?= $name ?></a>
            <?php endforeach; ?>
            <div class="clear"></div>
        </div>

    </div>
    <div class="right">
        <?php if ($_CLASSROOM['is_member']): ?>
            <img src="<?= URL::base() . 'static/images/door.gif' ?>" />
            <a id="leave_aa" href="javascript:exit_classroom()">退出班级</a>
        <?php else: ?>
            <span><img src="<?= URL::base() . 'static/images/+.gif' ?>" class="middle" /></span>&nbsp;<a href="javascript:join_classroom()">加入到本班级</a>
        <?php endif; ?>

        <img src="<?= URL::base() . 'static/ico/03060811.gif' ?>" />
        <a href="javascript:AddFavorite('http://zuaa.zju.edu.cn/classroom_home?id=<?= $_CLASSROOM['id'] ?>','<?= $_CLASSROOM['speciality'] ?><?= $_CLASSROOM['start_year'] ?>级')">加入收藏</a>
    </div>
    <div class="clear"></div>
</div>

<script type="text/javascript">
    function join_classroom(){

        var box = new Facebox({
            title: '申请加入<?= $_CLASSROOM['start_year'] ?>级<?= $_CLASSROOM['speciality'] ?>',
            url: '<?= URL::site('classroom_home/join') . URL::query() ?>',
            icon:'question',
            ok: function(){
                new ajaxForm('apply_form', {callback:function(){
                        box.close();
                        location.reload();
                    }}).send();
            }
        });

        box.show();
    }

    function exit_classroom(){

        var b = new Facebox({
            title: '退出确认！',
            message: '确定要退出<?= $_CLASSROOM['start_year'] ?>级<?= $_CLASSROOM['speciality'] ? $_CLASSROOM['speciality'] : $_CLASSROOM['name'] ?>吗？<br>注意退出后下次加入班级需再次审核！<?= $_IS_MANAGER ? '您目前是班级管理员。' : '' ?>',
            icon:'question',
            ok: function(){
                new Request({
                    url: '<?= URL::site('classroom_home/exitclass') . URL::query() ?>',
                    type: 'post',
                    success: function(){
                        location.reload();
                    }
                }).send();
                b.close();
            }
        });
        b.show();
    }
</script>