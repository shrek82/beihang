<?php
$aa_links = array(
    'aa_home/index' => '首页',
    'aa_home/info' => '相关信息',
    'aa_home/news' => '新闻',
    'aa_home/event' => '活动',
    'aa_home/album' => '相册',
    'aa_home/club' => '俱乐部',
    'aa_home/member' => '成员',
    'aa_home/bbs' => '论坛',
);

if($_MEMBER && $_MEMBER['permissions']){
    $aa_links['aa_admin/index'] = '管理';
}

?>
<div id="aa_top">
    <div class="left">
	<div><span style="font-size:22px; color: #333"><?= $aa['name'] ?></span>
	<span style="color:#666;padding:0 10px"><?= $aa['found_at']?'成立于:'.$aa['found_at']:'' ?></span>
	</div>

	<div id="aa_home_nav">
		<?php foreach($aa_links as $uri=>$name): ?>
		<a href="<?= URL::site($uri.'?id='.$aa['id']) ?>" <?= $_C.'/'.$_A == $uri ? 'class="cur"':'' ?>><?= $name ?></a>
		<?php endforeach; ?>
		<div class="clear"></div>
	</div>

    </div>
    <div class="right">
                <?php if($_MEMBER && ! $_MEMBER['chairman']): ?>
	<img src="<?= URL::base().'static/images/door.gif' ?>" />
                <a id="leave_aa" href="javascript:leave_aa()">退会</a>
                <?php endif; ?>

                <?php if($_UID && !$_MEMBER): ?>
                <img src="<?= URL::base().'static/images/+.gif' ?>" />
                <a id="join_aa" href="javascript:join_aa()">申请加入</a>
                <?php endif; ?>
    </div>
    <div class="clear"></div>
</div>

<script type="text/javascript">

function join_aa(){

    var box = new Facebox({
        title: '申请加入校友会',
        url: '<?= URL::site('aa_home/join').URL::query() ?>',
        ok: function(){
            new ajaxForm('apply_form', {callback:function(){
                box.close();
            }}).send();
        }
    });

    box.show();
}

function leave_aa(){
    var box = new Facebox({
        title: '确定要退出本校友会？',
        message: '这个操作将会抹除你在本校友会的积分，确认要退出吗？',
        submitValue: '确认离开',
        ok: function(){
            $('#leave_aa').load('<?= URL::site('aa_home/leave').URL::query() ?>');
            history.go(0)
        }
    });

    box.show();
}
</script>